<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Response;

# Models
use App\Models\Chat;
use App\Models\ChatAttachment;

# Broadcast events
use App\Events\NewMessage;
use App\Events\DeleteMessage;
use App\Events\EditMessage;
use App\Events\SeenMessage;

class MessengerController extends Controller
{
    public function sendMessage (Request $request) {
        # Create new message
        $message = Chat::create([
            'from_id' => $request->from_id,
            'to_id' => $request->to_id,
            'text' => $request->text,
        ]);

        # Broadcast new message event
        broadcast(new NewMessage($message));

        # Response with message id
        return response()->json($message['id']);
    }

    public function editMessage (Request $request) {
        # Edit message text
        Chat::where('id', $request->message_id)->update(['text' => $request->text]);

        # Fetch message data
        $message = Chat::where('id', $request->message_id)->first();

        # Broadcast message edit event
        broadcast(new EditMessage($message));

        # Detach all images from the message
        $this->detachImages($request->message_id);
    }

    public function deleteMessage (Request $request) {
        # Fetch message data
        $message = Chat::where('id', $request->message_id)->first();

        # Broadcast message delete event
        broadcast(new DeleteMessage($message));

        # Detach all images from the message
        $this->detachImages($request->message_id);

        # Delete message
        Chat::where('id', $request->message_id)->delete();
    }

    public function markAsRead (Request $request) {
        # Mark one-way sent messages as read
        Chat::where('from_id', $request->from_id)->where('to_id', $request->to_id)->update(['status' => 1]);

        # Fetch last data
        $message = Chat::where('from_id', $request->from_id)->where('to_id', $request->to_id)->orderBy('id', 'DESC')->first();

        # Broadcast message seen event
        broadcast(new SeenMessage($message));
    }

    public function detachImages ($message_id) {
        # Make an attempt to fetch attachments for the message
        $attachments = ChatAttachment::where('message_id', $message_id)->get();

        # If there are attachments for the message
        if (isset($attachments[0])) {
            for ($i = 0; $i < count($attachments); $i++) { 
                # Delete attachment from database
                ChatAttachment::where('id', $attachments[$i]['id'])->delete();

                # Define attachment path
                $path = public_path().'/../../web/src/assets/img/uploads/'.$attachments[$i]['file'];

                # If attachment file exists
                if (File::exists($path)) {
                    # Delete attachment
                    unlink($path);
                }
            }
        }
    }

    public function attachImage (Request $request) {
        # Processing base64
        $exploded = explode(',', $request->image_data);
        $decoded = base64_decode($exploded[1]);

        # Configuring filename
        $file_id = count(ChatAttachment::all()) + 1;
        $extension = strtok(explode('/', $exploded[0])[1], ';');
        $file_name = 'attachment_'.$file_id.'.'.$extension;

        # Defining the path
        $path = public_path().'/../../web/src/assets/img/uploads/'.$file_name;

        # Uploading file to the storage
        file_put_contents($path, $decoded);

        $attachment = ChatAttachment::create([
            'file' => $file_name,
            'message_id' => $request->message_id
        ]);
    }
}