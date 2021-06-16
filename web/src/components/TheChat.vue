<template>
    <div>
        <!-- Transparent background to close any modal window -->
        <div v-if="url.previewing_image" @click="closePreviewingImage()" class="transparent_background"></div>

        <!-- Preview images -->
        <img v-if="url.previewing_image" :src="url.previewing_image" class="previewing_image" alt="Previewing image">

        <div class="section section__margins__messenger__chat">
            <!-- Interlocutors list -->
            <div class="messenger__interlocutors" v-if="!interlocutor_id">
                <!-- Title -->
                <div class="section__title messenger__interlocutors__title"><p>Messenger</p></div>

                <!-- Users -->
                <div class="messenger__interlocutors__list" ref="contacts">
                    <div v-for="interlocutor in interlocutors" @click="openDialogue(interlocutor.id)" :key="interlocutor.id" class="messenger__interlocutors__list__user">
                        <!-- User profile image -->
                        <img class="messenger__interlocutors__list__user__profile_image" v-if="interlocutor.profile_image" :src="interlocutor.profile_image" :alt="`${interlocutor.first_name} ${interlocutor.last_name} profile image`">
                        <vue-initials-img class="messenger__interlocutors__list__user__profile_image" v-else :name="`${interlocutor.first_name} ${interlocutor.last_name}`" size="150"/>

                        <div class="nowrap_text">
                            <!-- User name -->
                            <p class="messenger__interlocutors__list__user__name nowrap_text">{{ `${interlocutor.first_name} ${interlocutor.last_name}` }}</p>

                            <!-- Last message info -->
                            <div class="messenger__interlocutors__list__user__last_message_info" v-if="interlocutor.last_message">
                                <!-- Last message text -->
                                <p v-if="interlocutor.last_message.text" class="nowrap_text">{{interlocutor.last_message.text}}</p>
                                <p v-else class="nowrap_text">Image</p>

                                <!-- Dot - separator -->
                                <div v-if="!(interlocutor.last_message.from_id == user.id && interlocutor.last_message.status == 1)"></div>

                                <!-- Last message status -->
                                <svg v-if="interlocutor.last_message.from_id == user.id && interlocutor.last_message.status == 1" viewBox="0 0 30 30"><path d="M 21.980469 7.9902344 A 1.0001 1.0001 0 0 0 21.292969 8.2929688 L 12 17.585938 L 8.7167969 14.302734 A 1 1 0 0 0 8.7070312 14.292969 A 1 1 0 0 0 8 14 A 1 1 0 0 0 7 15 A 1 1 0 0 0 7.2929688 15.707031 L 10.585938 19 L 9 20.585938 L 2.7070312 14.292969 A 1.0001 1.0001 0 1 0 1.2929688 15.707031 L 8.2929688 22.707031 A 1.0001 1.0001 0 0 0 9.7070312 22.707031 L 22.707031 9.7070312 A 1.0001 1.0001 0 0 0 21.980469 7.9902344 z M 28 8 A 1 1 0 0 0 27.292969 8.2929688 L 15 20.585938 L 14.707031 20.292969 L 13.292969 21.707031 L 14.292969 22.707031 A 1.0001 1.0001 0 0 0 15.707031 22.707031 L 28.697266 9.7167969 A 1 1 0 0 0 29 9 A 1 1 0 0 0 28 8 z"></path></svg>

                                <!-- Last message date -->
                                <p>{{ formatDate(new Date(interlocutor.last_message.created_at)) }}</p>
                            </div>
                        </div>

                        <!-- Amount of unread messages -->
                        <p v-if="interlocutor.unread > 0" class="messenger__interlocutors__list__user__unread_amount">{{ interlocutor.unread }}</p>
                    </div>
                </div>
            </div>

            <!-- Chat -->
            <div class="messenger__chat" v-else>
                <!-- Interlocutor info -->
                <div class="messenger__chat__interlocutor_info" v-if="!support_mode">
                    <!-- Back, to dialogue list -->
                    <svg @click="closeDialogue()" viewBox="0 0 64 64"><path d="m54 30h-39.892l15.272-14.552c.799-.762.83-2.028.068-2.828-.762-.798-2.027-.831-2.828-.068l-17.445 16.625c-.758.758-1.175 1.761-1.175 2.823s.417 2.063 1.21 2.858l17.41 16.59c.387.369.884.552 1.38.552.528 0 1.055-.208 1.448-.62.762-.8.731-2.065-.068-2.828l-15.341-14.552h39.961c1.104 0 2-.896 2-2s-.896-2-2-2z"/></svg>

                    <!-- Interlocutor's info -->
                    <router-link :to="`/user/@${interlocutor_data.uid}`" v-if="interlocutor_data">
                        <!-- Interlocutor's profile image -->
                        <img class="messenger__chat__interlocutor_info__profile_image" v-if="interlocutor_data.profile_image" :src="interlocutor_data.profile_image" :alt="`${interlocutor_data.first_name} ${interlocutor_data.last_name} profile image as interlocutor`">
                        <vue-initials-img class="messenger__chat__interlocutor_info__profile_image" v-else :name="`${interlocutor_data.first_name} ${interlocutor_data.last_name}`" size="150"/>

                        <!-- Interlocutor's full name -->
                        <p class="messenger__chat__interlocutor_info__name nowrap_text">{{ `${interlocutor_data.first_name} ${interlocutor_data.last_name}` }}</p>
                    </router-link>
                </div>

                <!-- Support info -->
                <div class="messenger__chat__interlocutor_info" v-else>
                    <!-- Back, to dialogue list -->
                    <svg v-if="window_width && window_width < 920" @click="$router.go(-1)" viewBox="0 0 64 64"><path d="m54 30h-39.892l15.272-14.552c.799-.762.83-2.028.068-2.828-.762-.798-2.027-.831-2.828-.068l-17.445 16.625c-.758.758-1.175 1.761-1.175 2.823s.417 2.063 1.21 2.858l17.41 16.59c.387.369.884.552 1.38.552.528 0 1.055-.208 1.448-.62.762-.8.731-2.065-.068-2.828l-15.341-14.552h39.961c1.104 0 2-.896 2-2s-.896-2-2-2z"/></svg>

                    <!-- Support logo -->
                    <div class="messenger__chat__interlocutor_info__support_logo">
                        <svg viewBox="0 0 512 512"><path d="M490.667,153.993V96c0-41.167-33.5-74.667-74.667-74.667h-57.992C326.723,7.68,292.259,0,256,0 s-70.723,7.68-102.008,21.333H96c-41.167,0-74.667,33.5-74.667,74.667v57.993C7.68,185.279,0,219.742,0,256 s7.68,70.721,21.333,102.007V416c0,41.167,33.5,74.667,74.667,74.667h57.992C185.277,504.32,219.741,512,256,512 s70.723-7.68,102.008-21.333H416c41.167,0,74.667-33.5,74.667-74.667v-57.993C504.32,326.721,512,292.258,512,256 S504.32,185.279,490.667,153.993z M416,42.667c29.417,0,53.333,23.927,53.333,53.333v18.797 c-18.987-28.591-43.538-53.142-72.13-72.13H416z M315.292,29.217c81.754,21.402,146.095,85.745,167.495,167.503l-84.484,14.087 c-14.659-46.042-51.069-82.448-97.108-97.107L315.292,29.217z M384,256c0,70.583-57.417,128-128,128s-128-57.417-128-128 s57.417-128,128-128S384,185.417,384,256z M256,21.333c13.107,0,25.879,1.349,38.406,3.424l-14.03,84.078 c-7.952-1.314-16.057-2.169-24.376-2.169s-16.424,0.855-24.376,2.171l-14.03-84.079C230.121,22.682,242.893,21.333,256,21.333z  M42.667,96c0-29.406,23.917-53.333,53.333-53.333h18.797c-28.592,18.988-53.143,43.539-72.13,72.13V96z M196.708,29.217 l14.098,84.483c-46.039,14.659-82.449,51.065-97.108,97.107L29.214,196.72C50.613,114.962,114.954,50.62,196.708,29.217z  M21.333,256c0-13.104,1.348-25.872,3.423-38.397l84.081,14.018c-1.315,7.953-2.171,16.06-2.171,24.379s0.855,16.426,2.171,24.379 l-84.079,14.022C22.682,281.875,21.333,269.105,21.333,256z M96,469.333c-29.417,0-53.333-23.927-53.333-53.333v-18.797 c18.987,28.591,43.538,53.142,72.13,72.13H96z M196.708,482.783c-81.754-21.402-146.094-85.745-167.493-167.5l84.483-14.089 c14.659,46.042,51.069,82.447,97.108,97.105L196.708,482.783z M256,490.667c-13.107,0-25.879-1.349-38.406-3.424l14.03-84.079 c7.952,1.315,16.057,2.171,24.376,2.171s16.424-0.855,24.376-2.169l14.03,84.078C281.879,489.318,269.107,490.667,256,490.667z  M469.333,416c0,29.406-23.917,53.333-53.333,53.333h-18.797c28.592-18.988,53.143-43.539,72.13-72.13V416z M315.292,482.783 l-14.098-84.483c46.039-14.659,82.449-51.064,97.108-97.105l84.483,14.089C461.385,397.038,397.046,461.38,315.292,482.783z  M487.242,294.401l-84.078-14.022c1.315-7.953,2.169-16.06,2.169-24.379s-0.854-16.426-2.169-24.379l84.079-14.018 c2.076,12.525,3.423,25.293,3.423,38.397C490.667,269.105,489.318,281.875,487.242,294.401z"/></svg>
                    </div>

                    <!-- Support name -->
                    <p class="messenger__chat__interlocutor_info__name nowrap_text">Ehhoo Support</p>
                </div>

                <!-- Context menu -->
                <div class="messenger__chat__feed__message_options" v-if="chat.selected_message">
                    <div class="messenger__chat__feed__message_options__content">
                        <!-- Delete (message owner) -->
                        <svg v-if="!chat.editing && chat.selected_message.from_id == user.id" @click="deleteMessage()" viewBox="0 0 407.521 407.521"><path d="M335.94,114.944H71.581c-2.86-0.243-5.694,0.702-7.837,2.612c-2.107,2.024-3.083,4.954-2.612,7.837l27.167,236.669 c3.186,26.093,25.436,45.647,51.722,45.453h131.657c27.026,0.385,49.791-20.104,52.245-47.02l22.465-236.147 c0.139-2.533-0.811-5.005-2.612-6.792C341.634,115.646,338.8,114.701,335.94,114.944z M303.026,359.45 c-1.642,15.909-15.366,27.803-31.347,27.167H140.022c-15.694,0.637-29.184-11.024-30.825-26.645L83.075,135.842h241.371 L303.026,359.45z"/><path d="M374.079,47.026H266.454V30.307c0.58-16.148-12.04-29.708-28.188-30.288c-0.53-0.019-1.061-0.024-1.591-0.014h-65.829 c-16.156-0.299-29.494,12.555-29.793,28.711c-0.01,0.53-0.005,1.061,0.014,1.591v16.718H33.442 c-5.771,0-10.449,4.678-10.449,10.449s4.678,10.449,10.449,10.449h340.637c5.771,0,10.449-4.678,10.449-10.449 S379.849,47.026,374.079,47.026z M245.556,30.307v16.718h-83.592V30.307c-0.589-4.579,2.646-8.768,7.225-9.357 c0.549-0.071,1.104-0.086,1.656-0.047h65.829c4.605-0.326,8.603,3.142,8.929,7.748C245.643,29.203,245.627,29.758,245.556,30.307 z"/></svg>

                        <!-- Edit (message owner) -->
                        <svg v-if="!chat.editing && chat.selected_message.from_id == user.id" @click="editMessage()" viewBox="0 0 386.615 386.615"><path d="M36.573,309.292h2.09l100.833-21.943c1.964-0.327,3.784-1.237,5.224-2.612L315.56,113.896 c12.48-12.453,19.443-29.391,19.331-47.02c0.023-17.766-6.917-34.833-19.331-47.543C303.108,6.853,286.17-0.11,268.54,0.003 c-17.742-0.157-34.76,7.028-47.02,19.853L51.201,190.696c-1.502,1.209-2.597,2.85-3.135,4.702L26.124,296.231 c-0.599,3.62,0.565,7.308,3.135,9.927C31.188,308.126,33.817,309.253,36.573,309.292z M268.54,20.901 c25.103-0.002,45.454,20.347,45.456,45.45c0,0.175-0.001,0.35-0.003,0.525c0.171,11.959-4.547,23.47-13.061,31.869 l-64.261-64.784C245.137,25.548,256.604,20.848,268.54,20.901z M222.042,49.113l64.261,64.261L137.405,261.749l-64.261-63.739 L222.042,49.113z M64.785,218.909l51.722,51.722L50.156,285.26L64.785,218.909z"/><path d="M368.328,365.717H18.287c-5.771,0-10.449,4.678-10.449,10.449s4.678,10.449,10.449,10.449h350.041 c5.771,0,10.449-4.678,10.449-10.449S374.099,365.717,368.328,365.717z"/></svg>

                        <!-- Copy -->
                        <svg v-if="!chat.editing && chat.selected_message.text && !chat.selected_message.copied" @click="copyText(chat.selected_message.text)" viewBox="0 0 477.867 477.867"><path d="M341.333,85.333H51.2c-28.277,0-51.2,22.923-51.2,51.2v290.133c0,28.277,22.923,51.2,51.2,51.2h290.133 c28.277,0,51.2-22.923,51.2-51.2V136.533C392.533,108.256,369.61,85.333,341.333,85.333z M358.4,426.667 c0,9.426-7.641,17.067-17.067,17.067H51.2c-9.426,0-17.067-7.641-17.067-17.067V136.533c0-9.426,7.641-17.067,17.067-17.067 h290.133c9.426,0,17.067,7.641,17.067,17.067V426.667z"/><path d="M426.667,0h-307.2c-28.277,0-51.2,22.923-51.2,51.2c0,9.426,7.641,17.067,17.067,17.067S102.4,60.626,102.4,51.2 s7.641-17.067,17.067-17.067h307.2c9.426,0,17.067,7.641,17.067,17.067v307.2c0,9.426-7.641,17.067-17.067,17.067 s-17.067,7.641-17.067,17.067s7.641,17.067,17.067,17.067c28.277,0,51.2-22.923,51.2-51.2V51.2 C477.867,22.923,454.944,0,426.667,0z"/></svg>

                        <!-- Close contect menu -->
                        <svg v-if="!chat.editing" @click="closeMessageContextMenu()" viewBox="0 0 311 311.07733" style="width: 18px; height: 18px; margin-top: auto; margin-bottom: auto"><path d="m16.035156 311.078125c-4.097656 0-8.195312-1.558594-11.308594-4.695313-6.25-6.25-6.25-16.382812 0-22.632812l279.0625-279.0625c6.25-6.25 16.382813-6.25 22.632813 0s6.25 16.382812 0 22.636719l-279.058594 279.058593c-3.136719 3.117188-7.234375 4.695313-11.328125 4.695313zm0 0"/><path d="m295.117188 311.078125c-4.097657 0-8.191407-1.558594-11.308594-4.695313l-279.082032-279.058593c-6.25-6.253907-6.25-16.386719 0-22.636719s16.382813-6.25 22.636719 0l279.058594 279.0625c6.25 6.25 6.25 16.382812 0 22.632812-3.136719 3.117188-7.230469 4.695313-11.304687 4.695313zm0 0"/></svg>

                        <!-- Editing options -->
                        <div v-if="chat.editing" @click="cancelEditingMessage()" class="messenger__chat__feed__message_options__content__editing">
                            <svg viewBox="0 0 64 64"><path d="m54 30h-39.892l15.272-14.552c.799-.762.83-2.028.068-2.828-.762-.798-2.027-.831-2.828-.068l-17.445 16.625c-.758.758-1.175 1.761-1.175 2.823s.417 2.063 1.21 2.858l17.41 16.59c.387.369.884.552 1.38.552.528 0 1.055-.208 1.448-.62.762-.8.731-2.065-.068-2.828l-15.341-14.552h39.961c1.104 0 2-.896 2-2s-.896-2-2-2z"/></svg>
                            <p>Cancel Editing</p>
                        </div>
                    </div>
                </div>

                <!-- Chat feed -->
                <div :class="`messenger__chat__feed scrollbar_fade ${chat.emoji && chat.attached_files[0] ? 'messenger__chat__feed__height__full' : chat.emoji && !chat.attached_files[0] ? 'messenger__chat__feed__height__emoji' : !chat.emoji && chat.attached_files[0] ? 'messenger__chat__feed__height__files' : 'messenger__chat__feed__height__default'}`">
                    <div v-for="message in chat.messages" @contextmenu.prevent="!chat.selected_message && !message.new_messages_title ? openMessageContextMenu(message) : chat.selected_message && !chat.editing ? closeMessageContextMenu() : ''" :key="message.id" :class="`messenger__chat__feed__message ${message == chat.selected_message ? 'messenger__chat__feed__message__selected' : ''} ${message.attachments[0] ? 'messenger__chat__feed__message__with_files' : ''} ${message.to_id == interlocutor_id && message.from_id == user.id ? 'messenger__chat__feed__message__sent' : message.to_id == user.id && message.from_id == interlocutor_id ? 'messenger__chat__feed__message__received' : ' messenger__chat__feed__message__centered'}`">
                        <!-- Message contains attached images -->
                        <div class="messenger__chat__feed__message__attached" v-if="message.attachments[0]">
                            <!-- Attachments -->
                            <div class="messenger__chat__feed__message__attached__files">
                                <img v-for="(attachment, index) in message.attachments" :src="attachment" @click="openPreviewingImage(attachment)" :class="!message.attachments[1] && isEmptyOrSpaces(message.text) ? 'messenger__chat__feed__message__attached__files__manual' : !message.attachments[1] && !isEmptyOrSpaces(message.text) ? 'messenger__chat__feed__message__attached__files__manual__text' : message.attachments[1] && isEmptyOrSpaces(message.text) ? 'messenger__chat__feed__message__attached__files__multiple' : message.attachments[1] && !isEmptyOrSpaces(message.text) ? 'messenger__chat__feed__message__attached__files__multiple__text' : ''" :key="index" :alt="`Attached file no. ${index}`">

                                <!-- Message info -->
                                <div v-if="isEmptyOrSpaces(message.text)" class="messenger__chat__feed__message__attached__info__no_caption_date">
                                    <!-- Seen -->
                                    <svg v-if="message.from_id == user.id && message.status == 1" viewBox="0 0 30 30"><path d="M 21.980469 7.9902344 A 1.0001 1.0001 0 0 0 21.292969 8.2929688 L 12 17.585938 L 8.7167969 14.302734 A 1 1 0 0 0 8.7070312 14.292969 A 1 1 0 0 0 8 14 A 1 1 0 0 0 7 15 A 1 1 0 0 0 7.2929688 15.707031 L 10.585938 19 L 9 20.585938 L 2.7070312 14.292969 A 1.0001 1.0001 0 1 0 1.2929688 15.707031 L 8.2929688 22.707031 A 1.0001 1.0001 0 0 0 9.7070312 22.707031 L 22.707031 9.7070312 A 1.0001 1.0001 0 0 0 21.980469 7.9902344 z M 28 8 A 1 1 0 0 0 27.292969 8.2929688 L 15 20.585938 L 14.707031 20.292969 L 13.292969 21.707031 L 14.292969 22.707031 A 1.0001 1.0001 0 0 0 15.707031 22.707031 L 28.697266 9.7167969 A 1 1 0 0 0 29 9 A 1 1 0 0 0 28 8 z"></path></svg>

                                    <!-- Date -->
                                    <p>{{ formatDate(new Date(message.created_at)) }}</p>
                                </div>
                            </div>

                            <!-- Caption -->
                            <p v-if="!isEmptyOrSpaces(message.text)" v-html="message.text" class="messenger__chat__feed__message__attached__caption_text">{{ message.text }}</p>

                            <!-- Message info -->
                            <div v-if="!isEmptyOrSpaces(message.text)" class="messenger__chat__feed__message__attached__info__caption_date">
                                <!-- Seen -->
                                <svg v-if="message.from_id == user.id && message.status == 1" viewBox="0 0 30 30"><path d="M 21.980469 7.9902344 A 1.0001 1.0001 0 0 0 21.292969 8.2929688 L 12 17.585938 L 8.7167969 14.302734 A 1 1 0 0 0 8.7070312 14.292969 A 1 1 0 0 0 8 14 A 1 1 0 0 0 7 15 A 1 1 0 0 0 7.2929688 15.707031 L 10.585938 19 L 9 20.585938 L 2.7070312 14.292969 A 1.0001 1.0001 0 1 0 1.2929688 15.707031 L 8.2929688 22.707031 A 1.0001 1.0001 0 0 0 9.7070312 22.707031 L 22.707031 9.7070312 A 1.0001 1.0001 0 0 0 21.980469 7.9902344 z M 28 8 A 1 1 0 0 0 27.292969 8.2929688 L 15 20.585938 L 14.707031 20.292969 L 13.292969 21.707031 L 14.292969 22.707031 A 1.0001 1.0001 0 0 0 15.707031 22.707031 L 28.697266 9.7167969 A 1 1 0 0 0 29 9 A 1 1 0 0 0 28 8 z"></path></svg>

                                <!-- Date -->
                                <p>{{ formatDate(new Date(message.created_at)) }}</p>
                            </div>
                        </div>

                        <!-- Message contains only text -->
                        <p v-html="message.text" v-if="!message.attachments[0]">{{ message.text }}</p>

                        <!-- Message info -->
                        <div v-if="!message.attachments[0] && message.to_id != message.from_id" class="messenger__chat__feed__message__info">
                            <!-- Seen -->
                            <svg v-if="message.from_id == user.id && message.status == 1" viewBox="0 0 30 30"><path d="M 21.980469 7.9902344 A 1.0001 1.0001 0 0 0 21.292969 8.2929688 L 12 17.585938 L 8.7167969 14.302734 A 1 1 0 0 0 8.7070312 14.292969 A 1 1 0 0 0 8 14 A 1 1 0 0 0 7 15 A 1 1 0 0 0 7.2929688 15.707031 L 10.585938 19 L 9 20.585938 L 2.7070312 14.292969 A 1.0001 1.0001 0 1 0 1.2929688 15.707031 L 8.2929688 22.707031 A 1.0001 1.0001 0 0 0 9.7070312 22.707031 L 22.707031 9.7070312 A 1.0001 1.0001 0 0 0 21.980469 7.9902344 z M 28 8 A 1 1 0 0 0 27.292969 8.2929688 L 15 20.585938 L 14.707031 20.292969 L 13.292969 21.707031 L 14.292969 22.707031 A 1.0001 1.0001 0 0 0 15.707031 22.707031 L 28.697266 9.7167969 A 1 1 0 0 0 29 9 A 1 1 0 0 0 28 8 z"></path></svg>

                            <!-- Date -->
                            <p class="messenger__chat__feed__message__info__date">{{ formatDate(new Date(message.created_at)) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Attached files -->
                <div :class="`messenger__chat__attached_files ${chat.emoji ? 'messenger__chat__attached_files__margin_bottom' : ''}`" v-if="chat.attached_files[0]">
                    <div v-for="(attachment, index) in chat.attached_files" :key="attachment.id" class="messenger__chat__attached_files__file_card">
                        <!-- Image itself -->
                        <img @click="openPreviewingImage(attachment.image)" :src="attachment.image" :alt="`Attachment no. ${index}`">

                        <!-- Remove button -->
                        <div class="messenger__chat__attached_files__file_card__remove_button" @click="detachFile(index)">
                            <svg viewBox="0 0 311 311.07733"><path d="m16.035156 311.078125c-4.097656 0-8.195312-1.558594-11.308594-4.695313-6.25-6.25-6.25-16.382812 0-22.632812l279.0625-279.0625c6.25-6.25 16.382813-6.25 22.632813 0s6.25 16.382812 0 22.636719l-279.058594 279.058593c-3.136719 3.117188-7.234375 4.695313-11.328125 4.695313zm0 0"/><path d="m295.117188 311.078125c-4.097657 0-8.191407-1.558594-11.308594-4.695313l-279.082032-279.058593c-6.25-6.253907-6.25-16.386719 0-22.636719s16.382813-6.25 22.636719 0l279.058594 279.0625c6.25 6.25 6.25 16.382812 0 22.632812-3.136719 3.117188-7.230469 4.695313-11.304687 4.695313zm0 0"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Emoji -->
                <chat-emoji v-if="chat.emoji" @addEmoji="addEmoji($event)"/>

                <!-- Chat actions -->
                <form @submit.prevent="submitMessage()" class="messenger__chat__footer" v-if="checkAbilityToChat()">
                    <!-- Message input -->
                    <input id="message_input" type="text" class="messenger__chat__footer__message_input" placeholder="Type message" v-model="chat.new_message" @input="checkAbilityToSubmitMessage()">

                    <!-- Emoji -->
                    <div class="messenger__chat__footer__icon" id="chat_emoji_icon" @click="chat.emoji = !chat.emoji">
                        <svg viewBox="0 0 24 24"><path d="m2.149 18.169c-.264 0-.52-.139-.657-.386-.975-1.76-1.492-3.76-1.492-5.783 0-6.617 5.383-12 12-12 1.929 0 3.842.47 5.533 1.358.367.193.508.646.315 1.013-.191.367-.644.509-1.013.315-1.477-.776-3.148-1.186-4.835-1.186-5.79 0-10.5 4.71-10.5 10.5 0 1.769.451 3.517 1.305 5.056.2.362.069.819-.292 1.02-.116.063-.241.093-.364.093z"/><path d="m12 24c-2.223 0-4.392-.614-6.272-1.775-.353-.217-.462-.68-.245-1.032.218-.351.681-.462 1.032-.244 1.644 1.014 3.541 1.551 5.485 1.551 5.79 0 10.5-4.71 10.5-10.5 0-1.947-.536-3.84-1.551-5.474-.218-.352-.109-.814.242-1.033.352-.22.814-.11 1.032.242 1.163 1.871 1.777 4.038 1.777 6.265 0 6.617-5.383 12-12 12z"/><path d="m9.25 11.25c-.414 0-.75-.336-.75-.75 0-.551-.448-1-1-1s-1 .449-1 1c0 .414-.336.75-.75.75s-.75-.336-.75-.75c0-1.378 1.121-2.5 2.5-2.5s2.5 1.122 2.5 2.5c0 .414-.336.75-.75.75z"/><path d="m18.25 11.25c-.414 0-.75-.336-.75-.75 0-.551-.448-1-1-1s-1 .449-1 1c0 .414-.336.75-.75.75s-.75-.336-.75-.75c0-1.378 1.121-2.5 2.5-2.5s2.5 1.122 2.5 2.5c0 .414-.336.75-.75.75z"/><path d="m12 18c-2.184 0-4.236-.85-5.78-2.395-.293-.293-.293-.768 0-1.061s.768-.293 1.061 0c1.26 1.262 2.936 1.956 4.719 1.956s3.459-.694 4.72-1.955c.293-.293.768-.293 1.061 0s.293.768 0 1.061c-1.545 1.544-3.597 2.394-5.781 2.394z"/><path d="m20 8.036c-.15 0-.3-.045-.43-.135-.595-.417-3.57-2.594-3.57-4.601 0-1.288 1.097-2.335 2.445-2.335.579 0 1.125.195 1.555.531.43-.336.976-.531 1.555-.531 1.348-.001 2.445 1.047 2.445 2.335 0 2.007-2.975 4.185-3.57 4.601-.13.09-.28.135-.43.135zm-1.555-5.572c-.521 0-.945.375-.945.835 0 .8 1.367 2.177 2.5 3.055 1.153-.89 2.5-2.246 2.5-3.055 0-.46-.424-.835-.945-.835-.386 0-.729.203-.874.517-.246.53-1.115.53-1.361 0-.146-.314-.489-.517-.875-.517z"/><path d="m4 24.036c-.15 0-.3-.045-.43-.135-.595-.417-3.57-2.594-3.57-4.601 0-1.288 1.097-2.335 2.445-2.335.579 0 1.125.195 1.555.531.43-.336.976-.531 1.555-.531 1.348-.001 2.445 1.047 2.445 2.335 0 2.007-2.975 4.185-3.57 4.601-.13.09-.28.135-.43.135zm-1.555-5.572c-.521 0-.945.375-.945.835 0 .8 1.367 2.177 2.5 3.055 1.153-.89 2.5-2.246 2.5-3.055 0-.46-.424-.835-.945-.835-.386 0-.729.203-.874.517-.246.53-1.115.53-1.361 0-.146-.314-.489-.517-.875-.517z"/></svg>
                    </div>

                    <!-- Attach file -->
                    <input type="file" multiple accept="image/x-png,image/gif,image/jpeg" id="file_input" ref="file_input" @change="attachFile">
                    <div class="messenger__chat__footer__icon" @click="$refs.file_input.click()">
                        <svg viewBox="0 0 24 24"><path d="M16.5,6V17.5A4,4 0 0,1 12.5,21.5A4,4 0 0,1 8.5,17.5V5A2.5,2.5 0 0,1 11,2.5A2.5,2.5 0 0,1 13.5,5V15.5A1,1 0 0,1 12.5,16.5A1,1 0 0,1 11.5,15.5V6H10V15.5A2.5,2.5 0 0,0 12.5,18A2.5,2.5 0 0,0 15,15.5V5A4,4 0 0,0 11,1A4,4 0 0,0 7,5V17.5A5.5,5.5 0 0,0 12.5,23A5.5,5.5 0 0,0 18,17.5V6H16.5Z"></path></svg>
                    </div>

                    <!-- Send message -->
                    <div v-if="chat.can_send_message" class="messenger__chat__footer__icon" @click="submitMessage()">
                        <svg viewBox="0 0 24 24"><path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"></path></svg>
                    </div>

                    <div v-else class="messenger__chat__footer__icon">
                        <svg id="messenger__chat__footer__cannot_send" viewBox="0 0 24 24"><path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"></path></svg>
                    </div>
                </form>

                <!-- Inability to chat -->
                <div class="messenger__chat__footer" v-else>
                    <div class="messenger__chat__footer__warning">
                        <svg viewBox="0 0 512 512"><path d="M256,0C114.497,0,0,114.507,0,256c0,141.503,114.507,256,256,256c141.503,0,256-114.507,256-256 C512,114.497,397.493,0,256,0z M256,472c-119.393,0-216-96.615-216-216c0-119.393,96.615-216,216-216 c119.393,0,216,96.615,216,216C472,375.393,375.385,472,256,472z"/><path d="M256,128.877c-11.046,0-20,8.954-20,20V277.67c0,11.046,8.954,20,20,20s20-8.954,20-20V148.877 C276,137.831,267.046,128.877,256,128.877z"/><circle cx="256" cy="349.16" r="27"/></svg>
                        <p>Unfortunately, your message cannot be sent due to this user's privacy settings</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// Modules
import Vue from 'vue'
import {mapGetters} from 'vuex'
import axios from 'axios'

// Components
import ChatEmoji from '@/components/ChatEmoji'

import VueInitialsImg from 'vue-initials-img';
Vue.use(VueInitialsImg);

import * as tf from '@tensorflow/tfjs';
import * as nsfwjs from 'nsfwjs';

export default {
    components: {
        ChatEmoji,
    },

    props: {
        interlocutor_id: {type: Number},
        support_mode: {type: Boolean}
    },

    data: () => ({
        url: {
            // Variable to preview images
            previewing_image: null
        },

        // Window width
        window_width: null,

        // Modal window
        modal_window: {
            display: false,
            mode: 0,
            title: '',
            message: '',
            verification_data: null,
        },

        // User privacy settings
        user_privacy_settings: null,

        // Chat
        chat: {
            // Chat feed
            messages: [],

            // Variables for new message
            new_message: '',
            can_send_message: false,

            // Variable to preview file to be attached
            attached_files: [],

            // Variable to show/hide emoji bar
            emoji: false,

            // Message options (contect menu)
            message_options: false,
            selected_message: null,
            editing: false
        },

        // Interlocutors data (dialogues)
        interlocutors: [],
        interlocutor_data: null,

        // Friendship status variable
        friendship_status: false,
    }),

    mounted () {
        // Set window width
        this.window_width = window.innerWidth;

        // Get user privacy settings
        axios.get('/get/user/privacy-settings')
        .then((response) => {
            this.user_privacy_settings = response.data;

        }).catch((error) => {
            console.log(error);
        });

        // Fetch all the interlocutors
        this.loadInterlocutors();

        // Listen to the chat channel
        window.Echo.private(`chat.${this.user.id}`)
        .listen('NewMessage', (e) => {
            if (this.$route.name == 'Messenger' || this.$route.name == 'Support') {
                this.handleIncomingMessage(e.message);
            }
        })
        .listen('DeleteMessage', (e) => {
            if (this.$route.name == 'Messenger' || this.$route.name == 'Support') {
                // Remove the message from chat feed locally
                for (let i = 0; i < this.chat.messages.length; i++) {
                    if (this.chat.messages[i].id == e.message.id) {
                        this.chat.messages.splice(i, 1);
                    }
                }

                // Reload interlocutors data
                this.loadInterlocutors();
            }
        })
        .listen('EditMessage', (e) => {
            if (this.$route.name == 'Messenger' || this.$route.name == 'Support') {
                axios.post('/get/message-attachments', {
                    message_id: e.message.id

                }).then((response) => {
                    // Edit the message locally
                    for (let j = 0; j < this.chat.messages.length; j++) {
                        if (this.chat.messages[j].id == e.message.id) {
                            this.chat.messages[j].attachments = response.data
                            this.chat.messages[j].text = this.urlify(e.message.text);
                        }
                    }

                }).catch((error) => {
                    console.log(error);
                });
            }
        })
        .listen('SeenMessage', (e) => {
            if (this.$route.name == 'Messenger' || this.$route.name == 'Support') {
                // Change seen prop to true locally
                for (let i = 0; i < this.chat.messages.length; i++) {
                    this.chat.messages[i].status = 1;
                }

                // Change seen prop for interlocutor to true
                this.interlocutors[this.interlocutors.map(user => user.id).indexOf(e.message.to_id)].last_message.status = 1;
            }
        });
    },

    methods: {
        isEmptyOrSpaces (str) {
            return str === null || str.match(/^ *$/) !== null;
        },

        loadInterlocutors () {
            axios.get('/get/interlocutors')
            .then((response) => {
                // Define local interlocutors' data
                let interlocutors = response.data;

                // New interlocutor
                if (this.interlocutor_id && !interlocutors.filter(user => {return user.id === this.interlocutor_id})[0]) {
                    // Fetch new interlocutor's data
                    axios.post('/get/user/by-id', {
                        user_id: this.interlocutor_id

                    }).then((response) => {
                        // Add new interlocutor to the general list
                        interlocutors.push(response.data);

                        // Set interlocutors' data
                        this.fetchUsersProfileImages(interlocutors).then((response) => {
                            this.interlocutors = response;

                            // Open the dialogue
                            this.openDialogue(this.interlocutor_id);
                        });

                    }).catch((error) => {
                        console.log(error);
                    });

                // Interlocutor already exists
                } else {
                    // Set interlocutors' data
                    this.fetchUsersProfileImages(interlocutors).then((response) => {
                        this.interlocutors = response;

                        // Open the dialogue if interlocutor id was passed from another page
                        if (this.interlocutor_id) {
                            this.openDialogue(this.interlocutor_id);
                        }
                    });
                }

            }).catch((error) => {
                console.log(error);
            });
        },

        urlify (text) {
            if (text == null) {
                return "";
            }

            // Prevent XSS attack
            text = this.unurlify(text);

            let urlRegex = /(https?:\/\/[^\s]+)/g;

            return text.replace(urlRegex, function(url) {
                return '<a target="_blanket" class="messenger__chat__feed__message__link" href="' + url + '">' + url + '</a>';
            });
        },

        unurlify (text) {
            if (text == null) {
                return "";
            }

            let regex = /(<([^>]+)>)/ig;
        
            return text.replace(regex, "");
        },

        formatDate (date) {
            let hours = date.getHours();
            let minutes = date.getMinutes();
            let period = hours >= 12 ? 'pm' : 'am';

            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0'+minutes : minutes;

            let strTime = `${hours}:${minutes} ${period}`;

            return strTime;
        },

        openPreviewingImage (image_url) {
            // Disable scrolling
            document.body.style.overflow = 'hidden';

            // Set previewing image url to the argument one
            this.url.previewing_image = image_url;
        },

        closePreviewingImage () {
            // Apply closing animations
            document.getElementsByClassName('transparent_background')[0].style.animation = 'fadeOut .275s ease-in-out';
            document.getElementsByClassName('previewing_image')[0].style.animation = 'fadeOut_slideUp .275s ease-in-out';

            // When animations are done
            setTimeout(() => {
                // Hide previewing image
                this.url.previewing_image = null;

                // Enable scrollbar
                document.body.style.overflowY = 'scroll';
            }, 275);
        },

        async fetchUsersProfileImages (array) {
            let response = await axios.post('/get/users/profile-images', {
                users: array

            }).then((response) => {
                for (let i = 0; i < array.length; i++) {
                    if (response.data[i]) {
                        // Load profile picture
                        array[i].profile_image = this.checkImageExistence(response.data[i]);
                    }
                }

                return array;

            }).catch((error) => {
                console.log(error);
            });

            return response;
        },

        checkImageExistence (file) {
            try {
                if (process.env.NODE_ENV === 'development') {
                    let loaded_file = require(`../assets/img/uploads/${file}`);
                    return loaded_file;

                } else {
                    let loaded_file = `${window.location.protocol}//${window.location.host}/images/${file}`;
                    return loaded_file;
                }

            } catch (e) {
                return false;
            }
        },

        updateUnread () {
            // Fetch all the interlocutors
            axios.get('/get/interlocutors')
            .then((response) => {
                // Define unread chats amount
                let unread_chats_amount = 0;

                for (let i = 0; i < response.data.length; i++) {
                    if (response.data[i].unread > 0) {
                        unread_chats_amount++;
                    }
                }

                // Update unread chats amount
                this.$store.commit('setUnreadChatsAmount', unread_chats_amount);

            }).catch((error) => {
                console.log(error);
            });
        },

        defineFrienshipStatus (user_id) {
            // Load friends data
            axios.get('/get/user/friendships')
            .then((response) => {
                for (let i = 0; i < response.data.length; i++) {
                    if (response.data[i].id == user_id) {
                        this.friendship_status = true;
                    }
                }

            }).catch((error) => {
                console.log(error);
            });
        },

        groupMessagesByDates (messages) {
            // Define months names
            let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            // Add date separator
            for (let k = 0; k < messages.length; k++) {
                setTimeout(() => {
                    // Insert date before the first message
                    if (k == 0) {
                        // Not current year
                        if (new Date(messages[0].created_at).getFullYear() < new Date().getFullYear()) {
                            messages.unshift({from_id: this.interlocutor_id, to_id: this.interlocutor_id, attachments: [], text: `${months[new Date(messages[0].created_at).getMonth()]} ${new Date(messages[0].created_at).getDate()}, ${new Date(messages[0].created_at).getFullYear()}`});

                        // Current year
                        } else {
                            messages.unshift({from_id: this.interlocutor_id, to_id: this.interlocutor_id, attachments: [], text: `${months[new Date(messages[0].created_at).getMonth()]} ${new Date(messages[0].created_at).getDate()}`});
                        }

                    // Any other message
                    } else if (messages[k + 1]) {
                        if (new Date(messages[k].created_at).getDate() < new Date(messages[k + 1].created_at).getDate()) {
                            // Not current year
                            if (new Date(messages[k + 1].created_at).getFullYear() < new Date().getFullYear()) {
                                messages.splice(k + 1, 0, {from_id: this.interlocutor_id, to_id: this.interlocutor_id, attachments: [], text: `${months[new Date(messages[k + 1].created_at).getMonth()]} ${new Date(messages[k + 1].created_at).getDate()}, ${new Date(messages[k + 1].created_at).getFullYear()}`})

                            // Current year
                            } else {
                                messages.splice(k + 1, 0, {from_id: this.interlocutor_id, to_id: this.interlocutor_id, attachments: [], text: `${months[new Date(messages[k + 1].created_at).getMonth()]} ${new Date(messages[k + 1].created_at).getDate()}`})
                            }
                        }
                    }
                }, 0);
            }

            return messages;
        },

        checkAbilityToChat () {
            if (this.interlocutor_data) {
                // First step
                if ((this.interlocutor_data.private_profile && this.interlocutor_data.messaging == 'Only friends' && this.friendship_status) || (!this.interlocutor_data.private_profile && (this.interlocutor_data.messaging == 'All' || (this.interlocutor_data.messaging == 'Only friends' && this.friendship_status)))) {
                    // Second stop
                    if ((this.user_privacy_settings.private_profile && this.user_privacy_settings.messaging == 'Only friends' && this.friendship_status) || (!this.user_privacy_settings.private_profile && (this.user_privacy_settings.messaging == 'All' || (this.user_privacy_settings.messaging == 'Only friends' && this.friendship_status)))) {
                        return true;
                    }
                }

                // Support chat
                if (this.support_mode) {
                    return true;
                }

                return false;
            }
        },

        scrollDown () {
            let feed = document.getElementsByClassName('messenger__chat__feed')[0];

            setTimeout(() => {
                // Scroll to the bottom of the chat
                feed.scrollTop = feed.scrollHeight - feed.clientHeight;
            }, 200);
        },

        openDialogue (user_id) {
            // Define friendship status
            this.defineFrienshipStatus(user_id);

            // Set up interlocutor data
            this.interlocutor_data = this.interlocutors.find(user => {return user.id === user_id});

            // Define the time for fade in/out animation
            let timeout = 0;

            // If user has chosen interlocutor from the list
            if (document.getElementsByClassName('messenger__interlocutors')[0]) {
                // Apply interlocutors list closing animation
                document.getElementsByClassName('messenger__interlocutors')[0].style.animation = 'fadeOut .275s ease-in-out';

                // Set time for animation
                timeout = 275;
            }

            setTimeout(() => {
                // If user has chosen interlocutor from the list
                if (timeout == 275) {
                    // Change interlocutor id
                    this.$emit('changeInterlocutor', user_id);

                    // Apply chat opening animation
                    setTimeout(() => {
                        document.getElementsByClassName('messenger__chat')[0].style.animation = 'fadeIn .275s ease-in-out';
                    }, 10);
                }

                // Fetch all messages
                axios.post('/get/messages', {
                    interlocutor_id: user_id

                }).then((response) => {
                    // Require attachments
                    for (let i = 0; i < response.data.length; i++) {
                        if (response.data[i].attachments) {
                            for (let j = 0; j < response.data[i].attachments.length; j++) {
                                response.data[i].attachments[j] = this.checkImageExistence(response.data[i].attachments[j]);
                            }
                        }
                    }

                    // Define messages links
                    for (let j = 0; j < response.data.length; j++) {
                        response.data[j].text = this.urlify(response.data[j].text);
                    }

                    // Group messages by dates
                    response.data = this.groupMessagesByDates(response.data);

                    // Set chat feed
                    this.chat.messages = response.data;

                    // If there are unread messages
                    if (this.interlocutors[this.interlocutors.map(user => user.id).indexOf(this.interlocutor_id)].unread > 0) {
                        setTimeout(() => {
                            // Define the index of 
                            let new_messages_index = this.chat.messages.length - this.interlocutors[this.interlocutors.map(user => user.id).indexOf(this.interlocutor_id)].unread;

                            // Add "New Messages" separator
                            this.chat.messages.splice(new_messages_index, 0, {from_id: this.interlocutor_id, to_id: this.interlocutor_id, attachments: [], text: "New Messages", created_at: this.chat.messages[new_messages_index].created_at});
                        }, 0);

                        // Mark messages as read globally
                        axios.post('/messenger/mark-as-read', {
                            from_id: this.interlocutor_id,
                            to_id: this.user.id

                        }).then(() => {
                            // Mark messages as read locally
                            this.interlocutors.find(user => {return user.id === this.interlocutor_id}).unread = 0;

                            // Update the amount of unread messages
                            this.updateUnread();

                        }).catch((error) => {
                            console.log(error);
                        });
                    }

                    // Scroll to the bottom of the chat
                    this.scrollDown();

                }).catch((error) => {
                    console.log(error);
                });
            }, timeout);
        },

        closeDialogue () {
            // Apply chat closing animation
            document.getElementsByClassName('messenger__chat')[0].style.animation = 'fadeOut .275s ease-in-out';

            setTimeout(() => {
                // Set interlocutor id to null
                this.$emit('changeInterlocutor', null);

                // Apply interlocutors list opening animation
                setTimeout(() => {
                    document.getElementsByClassName('messenger__interlocutors')[0].style.animation = 'fadeIn .275s ease-in-out';
                }, 10);
            }, 275);
        },

        checkAbilityToSubmitMessage () {
            // If input is empty, disallow sending new message
            if (this.isEmptyOrSpaces(this.chat.new_message) && !this.chat.attached_files[0]) {
                this.chat.can_send_message = false;

            // If user has entered something, allow him to send new message
            } else {
                this.chat.can_send_message = true;
            }
        },

        removeNewMessage () {
            // Remove "new message" title from the chat
            for (let i = 0; i < this.chat.messages.length; i++) {
                if (this.chat.messages[i].from_id == this.chat.messages[i].to_id && this.chat.messages[i].text == 'New Messages') {
                    setTimeout(() => {
                        // Hide element
                        document.getElementsByClassName('messenger__chat__feed__message')[i].style.display = 'none';
                    }, 275);
                }
            }
        },

        processImageToElement (data) {
            let file_url = URL.createObjectURL(data);

            let file_element = document.createElement('img');
            file_element.src = file_url;

            return file_element;
        },

        uploadImage (image, message_id) {
            // Interlocutors are friends
            if (this.friendship_status) {
                // Processing image to base64
                let file_reader = new FileReader();
                file_reader.readAsDataURL(image); // base64

                file_reader.onload = (event) => {
                    // Upload the file
                    axios.post('/messenger/attach/image', {
                        image_data: event.target.result,
                        message_id: message_id

                    }).catch((error) => {
                        console.log(error);

                        // Error payload is too large
                        if (error.response.status == 413) {
                            // Display error modal window
                            this.modal_window.title = "The image to be sent is too large.";
                            this.modal_window.message = "Please, choose another one.";
                            this.modal_window.mode = 0;
                            this.modal_window.display = true;
                        }

                        // Delete last message
                        this.chat.selected_message = {id: message_id}
                        this.deleteMessage();

                        return;
                    });
                }

            // Interlocutors are not friends
            } else {
                // Process image
                let image_element = this.processImageToElement(image);

                // Check for nsfw content
                nsfwjs.load().then(net => {
                    net.classify(image_element).then(result => {
                        // Image is appropriate
                        if (result[0].className == 'Neutral' || result[0].className == 'Drawing') {
                            // Processing image to base64
                            let file_reader = new FileReader();
                            file_reader.readAsDataURL(image); // base64

                            file_reader.onload = (event) => {
                                // Upload the file
                                axios.post('/messenger/attach/image', {
                                    image_data: event.target.result,
                                    message_id: message_id

                                }).catch((error) => {
                                    console.log(error);

                                    // Error payload is too large
                                    if (error.response.status == 413) {
                                        // Display error modal window
                                        this.modal_window.title = "The image to be sent is too large.";
                                        this.modal_window.message = "Please, choose another one.";
                                        this.modal_window.mode = 0;
                                        this.modal_window.display = true;
                                    }

                                    // Delete last message
                                    this.chat.selected_message = {id: message_id}
                                    this.deleteMessage();

                                    return;
                                });
                            }

                        // Image is inappropriate
                        } else {
                            // Display error modal window
                            this.modal_window.title = "NSFW content is not allowed.";
                            this.modal_window.message = "Please, choose a more appropriate photo.";
                            this.modal_window.mode = 0;
                            this.modal_window.display = true;

                            // Delete last message
                            this.chat.selected_message = {id: message_id}
                            this.deleteMessage();

                            return;
                        }
                    });
                });
            }
        },

        submitMessage () {
            // Check message text for emptiness
            this.checkAbilityToSubmitMessage();
            if (!this.chat.can_send_message) {return false}

            // Remove message that separates old & new messages
            this.removeNewMessage();

            // Play chat sent sound
            let sent = new Audio(require('../assets/audio/chat/sent.mp3'));
            sent.load();
            sent.play();

            // Send new message
            if (!this.chat.editing) {
                // Add new message locally
                this.chat.messages.push({from_id: this.user.id, to_id: this.interlocutor_id, attachments: this.chat.attached_files.map(attachment => attachment.image), text: this.urlify(this.chat.new_message), created_at: new Date(), status: 0});

                // Change seen prop for interlocutor to true
                this.interlocutors[this.interlocutors.map(user => user.id).indexOf(this.interlocutor_id)].last_message = {from_id: this.user.id, to_id: this.interlocutor_id, status: 0, text: this.urlify(this.chat.new_message), created_at: new Date()};

                // Add new message globally
                axios.post('/messenger/message/send', {
                    from_id: this.user.id,
                    to_id: this.interlocutor_id,
                    text: this.chat.new_message,

                }).then((response) => {
                    // Set up new message id
                    this.chat.messages[this.chat.messages.length - 1].id = response.data;

                    for (let i = 0; i < this.chat.messages.length; i++) {
                        // Remove date separators
                        if (this.chat.messages[i].from_id == this.chat.messages[i].to_id) {
                            this.chat.messages.splice(i, 1);
                        }
                    }

                    // Group messages by dates
                    this.chat.messages = this.groupMessagesByDates(this.chat.messages);

                    // Upload attachments
                    if (this.chat.attached_files[0]) {
                        for (let i = 0; i < this.chat.attached_files.length; i++) {
                            this.uploadImage(this.chat.attached_files[i].image_data, response.data);
                        }
                    }

                    // Clear preview of attached files
                    this.chat.attached_files = [];

                }).catch((error) => {
                    console.log(error);
                });

                // Clear input
                this.chat.new_message = '';

                // Disallow sending messages in case of empty input
                this.chat.can_send_message = false;

                // Scroll to the bottom of the chat
                this.scrollDown();

                return;
            }

            // Edit already existing message
            for (let j = 0; j < this.chat.messages.length; j++) {
                // Edit message
                if (this.chat.messages[j].id == this.chat.selected_message.id) {
                    this.chat.messages[j] = {from_id: this.user.id, to_id: this.interlocutor_id, attachments: this.chat.attached_files.map(attachment => attachment.image), text: this.urlify(this.chat.new_message), created_at: this.chat.messages[j].created_at, status: this.chat.messages[j].status};
                }
            }

            // Add new message globally
            axios.post('/messenger/message/edit', {
                message_id: this.chat.selected_message.id,
                text: this.chat.new_message

            }).catch((error) => {
                console.log(error);
            });

            // Upload attachments
            if (this.chat.attached_files[0]) {
                for (let i = 0; i < this.chat.attached_files.length; i++) {
                    this.uploadImage(this.chat.attached_files[i].image_data, this.chat.selected_message.id);
                }
            }

            // Clear preview of attached files
            this.chat.attached_files = [];

            // Clear input
            this.chat.new_message = '';

            // Disallow sending messages in case of empty input
            this.chat.can_send_message = false;

            // Close context menu
            this.closeMessageContextMenu();
        },

        attachFile (event) {
            // Add new files to ones that already exist
            for (let i = 0; i < event.target.files.length; i++) {
                this.chat.attached_files.push({id: this.chat.attached_files.length, image: URL.createObjectURL(event.target.files[i]), image_data: event.target.files[i]});
            }

            // Check ability to send new message
            this.checkAbilityToSubmitMessage();
        },

        detachFile (index) {
            // Detach file by completed index
            for (let i = 0; i < this.chat.attached_files.length; i++) {
                if (i == index) {
                    this.chat.attached_files.splice(i, 1);
                }
            }

            // Check ability to send new message
            this.checkAbilityToSubmitMessage();
        },

        addEmoji (emoji) {
            // Concatenation for message text and new emoji
            this.chat.new_message = this.chat.new_message + emoji;

            // Allow to send message
            this.chat.can_send_message = true;
        },

        openMessageContextMenu (message) {
            // Save message data
            this.chat.selected_message = message;
        },

        deleteMessage () {
            axios.post('/messenger/message/delete', {
                message_id: this.chat.selected_message.id

            }).then(() => {
                // Remove selected message from chat feed locally
                for (let i = 0; i < this.chat.messages.length; i++) {
                    if (this.chat.messages[i].id == this.chat.selected_message.id) {
                        this.chat.messages.splice(i, 1);
                    }
                }

                // Remove date separators
                for (let i = 0; i < this.chat.messages.length; i++) {
                    if (this.chat.messages[i].from_id == this.chat.messages[i].to_id) {
                        this.chat.messages.splice(i, 1);
                    }
                }

                // Group messages by dates
                this.chat.messages = this.groupMessagesByDates(this.chat.messages);

                // Close message context menu
                this.closeMessageContextMenu();

            }).catch((error) => {
                console.log(error);
            })
        },

        loadXHR (url) {
            return new Promise(function(resolve, reject) {
                try {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", url);
                    xhr.responseType = "blob";
                    xhr.onerror = function() {reject("Network error.")};
                    xhr.onload = function() {
                        if (xhr.status === 200) {resolve(xhr.response)}
                        else {reject("Loading error:" + xhr.statusText)}
                    };
                    xhr.send();
                }
                catch(err) {reject(err.message)}
            });
        },

        editMessage () {
            // Change context menu icons
            this.chat.editing = true;

            // Update chat input
            this.chat.new_message = this.unurlify(this.chat.selected_message.text);
            this.chat.can_send_message = true;

            // Define attachments
            let attachments = [];

            // Set "image" prop for attachments
            for (let i = 0; i < this.chat.selected_message.attachments.length; i++) {
                let self = this;

                // Conver image to blob
                this.loadXHR(this.chat.selected_message.attachments[i]).then(function (blob) {
                    attachments.push({id: attachments.length, image: self.chat.selected_message.attachments[i], image_data: blob});
                });
            }

            // Update attachments
            this.chat.attached_files = attachments;

            // Focus on the input
            document.getElementById('message_input').focus();
        },

        cancelEditingMessage () {
            // Change context menu icons
            this.chat.editing = false;

            // Clear chat input
            this.chat.new_message = '';
            this.chat.can_send_message = false;

            // Clear attachments
            this.chat.attached_files = [];
        },

        copyText (text) {
            // Copy text to clipboard
            navigator.clipboard.writeText(text);

            // Close message context menu
            this.closeMessageContextMenu();
        },

        closeMessageContextMenu () {
            // Apply closing animation for message context menu & the message
            if (document.getElementsByClassName('messenger__chat__feed__message__selected')[0]) {
                document.getElementsByClassName('messenger__chat__feed__message__selected')[0].style.animation = "fadeIn_half .275s ease-in-out"
            }
            document.getElementsByClassName('messenger__chat__feed__message_options')[0].style.animation = "fadeOut_slideUp .275s ease-in-out";

            // When animation is done
            setTimeout(() => {
                // Hide context menu
                this.chat.selected_message = null;

                // Set editing prop to false
                this.chat.editing = false;
            }, 275);
        },

        handleIncomingMessage (message) {
            // Reload interlocutors data
            this.loadInterlocutors();

            // User is in the dialogue with the sender
            if (this.interlocutor_id && message.from_id == this.interlocutor_id) {
                // Mark messages as read globally
                axios.post('/messenger/mark-as-read', {
                    from_id: this.interlocutor_id,
                    to_id: this.user.id

                }).then(() => {
                    // Update interlocutors unread prop
                    this.interlocutors[this.interlocutors.map(user => user.id).indexOf(this.interlocutor_id)].unread = 0;

                }).catch((error) => {
                    console.log(error);
                });

                // Fetch attachments for the message
                axios.post('/get/message-attachments', {
                    message_id: message.id

                }).then((response) => {
                    // Add new message locally
                    this.chat.messages.push({from_id: message.from_id, to_id: message.to_id, attachments: response.data, text: this.urlify(message.text), created_at: new Date()});

                    // Scroll to the bottom of the chat
                    this.scrollDown();

                }).catch((error) => {
                    console.log(error);
                });
            }
        }
    },

    computed: {
        ...mapGetters({
            authenticated: 'auth/authenticated',
            user: 'auth/user'
        })
    }
}
</script>

<style scoped>
    @import '../assets/css/styles-app.css';
</style>