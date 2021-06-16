<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use DateTime;

use Stripe\Stripe;
use Stripe\Subscription;

# Models
use App\Models\User;
use App\Models\UsersInfo;
use App\Models\Privacy;
use App\Models\Friend;

use App\Models\Chat;
use App\Models\ChatAttachment;

class GatherController extends Controller
{
    /**
     *
     * User data related functions
     *
    **/
    public function GetUserData (Request $request) {
        # Get user data
        $user = $request->user();

        if (isset($user)) {
            # Define weather or not user was signed up using oauth
            if ($user->makeVisible('password')->toArray()['password']) {
                $oauthed = false;

            } else {
                $oauthed = true;
            }

            # Response only with necessary user data
            return response()->json([
                'id' => $user->id,
                'uid' => $user->uid,

                'stripe_id' => $user->stripe_id,

                'first_name' => $user->first_name,
                'last_name' => $user->last_name,

                'email' => $user->email,

                'joined_date' => $user->joined_date,

                'oauthed' => $oauthed
            ]);
        }
    }

    public function getUserPrivacySettings (Request $request) {
        # Fetch user's privacy settings
        $privacy_settings = Privacy::where('id', $request->user()->id)->first();

        # Response with user info
        return response()->json($privacy_settings);
    }

    public function getUserById (Request $request) {
        # Fetch user data
        $user_data = User::where('id', $request->user_id)->first();

        # Fetch user info
        $user_info = UsersInfo::where('id', $user_data['id'])->first();

        # Fetch user privacy data
        $user_privacy_settings = Privacy::where('id', $user_data['id'])->first();

        # Merge all the data
        $user = array_merge(array_merge((array) json_decode($user_data), (array) json_decode($user_info)), (array) json_decode($user_privacy_settings));

        # Response with user data
        return response()->json($user);
    }

    /**
     *
     * Friendship data related functions
     *
    **/
    public function getUserFriendships (Request $request) {
        # Define user id
        $user_id = $request->user()->id;

        # Fetch all active user friendships
        $friendships = Friend::where([['user_id', $user_id], ['status', 1]])->orWhere([['friend_id', $user_id], ['status', 1]])->get();

        # Define array for friends' data
        $friends = [];

        # Process friendships
        for ($i = 0; $i < count($friendships); $i++) { 
            # Define friend id which is different from the user's one
            $friend_id = ($friendships[$i]['user_id'] == $user_id) ? $friendships[$i]['friend_id'] : $friendships[$i]['user_id'];

            # Get friend data
            $friend_data = User::where('id', $friend_id)->first();

            # Append friend's data
            if ($friend_data) {
                array_push($friends, $friend_data);
            }
        }

        # Response with friends' data
        return response()->json($friends);
    }

    public function getUsersProfileImages (Request $request) {
        // Define array for users profile images
        $profile_images = [];

        for ($i=0; $i < count($request->users); $i++) {
            # Fetch user info
            $user_info = UsersInfo::where('id', $request->users[$i]['id'])->first();

            # Save profile image info
            if ($user_info->profile_image) {
                array_push($profile_images, $user_info->profile_image);

            } else {
                array_push($profile_images, null);
            }
        }

        # Response with users profile images
        return response()->json($profile_images);
    }


    /**
     *
     * Messenger data related functions
     *
    **/
    public function getInterlocutors (Request $request) {
        # Define user id
        $user_id = $request->user()->id;

        # Fetch all messages related to the user
        $messages = Chat::where('from_id', $user_id)->orWhere('to_id', $user_id)->get();

        # Define interlocutors id
        $interlocutors_id = [];

        # Fetch only interlocutors id
        for ($i = 0; $i < count($messages); $i++) { 
            if ($messages[$i]['from_id'] == $user_id) {
                array_push($interlocutors_id, $messages[$i]['to_id']);

            } else {
                array_push($interlocutors_id, $messages[$i]['from_id']);
            }
        }

        # Define interlocutors data
        $interlocutors = [];

        # Fetch interlocutors data
        for ($j = 0; $j < count($interlocutors_id); $j++) {
            # Fetch user data
            $user_data = User::where('id', $interlocutors_id[$j])->first();

            if ($user_data) {
                # Count unread messages from interlocutor
                $user_data->unread = count(Chat::where('to_id', $user_id)->where('from_id', $interlocutors_id[$j])->where('status', 0)->get());

                # Fetch user privacy data
                $user_privacy_settings = Privacy::where('id', $interlocutors_id[$j])->first();

                # Merge all the user data
                $user = array_merge((array) json_decode($user_data), (array) json_decode($user_privacy_settings));

                # Fetch the last message in the dialogue
                $user['last_message'] = Chat::where([['from_id', $user_id], ['to_id', $interlocutors_id[$j]]])->orWhere([['from_id', $interlocutors_id[$j]], ['to_id', $user_id]])->orderBy('created_at', 'DESC')->first();

                # Add interlocutor data
                array_push($interlocutors, $user);
            }
        }

        # Remove duplicates and reset indexes
        $interlocutors = array_values(array_unique($interlocutors, SORT_REGULAR));

        # Response with interlocutors data
        return response()->json($interlocutors);
    }

    public function getMessages (Request $request) {
        # Define user id
        $user_id = $request->user()->id;

        # Fetch all the messages
        $messages = Chat::where([['from_id', $user_id], ['to_id', $request->interlocutor_id]])->orWhere([['from_id', $request->interlocutor_id], ['to_id', $user_id]])->orderBy('created_at', 'ASC')->get();

        # Fetch attached photos for the messages
        for ($i = 0; $i < count($messages); $i++) {
            $messages[$i]->attachments = ChatAttachment::where('message_id', $messages[$i]['id'])->get()->pluck('file');
        }

        # Response with messages data
        return response()->json($messages);
    }

    public function getMessageAttachments (Request $request) {
        # Fetch message attachments
        $attachments = ChatAttachment::where('message_id', $request->message_id)->get()->pluck('file');

        # Response with the attachments
        return response()->json($attachments);
    }
}