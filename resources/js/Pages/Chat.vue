<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import moment from 'moment';
// import Welcome from '@/Components/Welcome.vue';

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex"
                    style="min-height: 400px; max-height: 400px;">
                    <!-- <List Users /> -->
                    <div class="w-3/12 bg-gray-200 bg-opacity-25 border-r border-gray-200 overflow-y-scroll">
                        <ul>
                            <li v-for="user in users" :key="user.id" @click="() => { loadMessages(user.id) }"
                                :class="userActive && userActive.id == user.id ? 'bg-gray-200 bg-opacity-50' : ''"
                                class="p-6 text-lg text-gray-600 leading-7 font-semibold border-b border-gray-200 hover:bg-gray-200 hover:bg-opacity-50 hover:cursor-pointer">
                                <p class="flex items-center">{{ user.name }}
                                    <span class="ml-2 w-2 h-2 bg-blue-500 rounded-full"></span>
                                </p>
                            </li>
                        </ul>
                    </div>
                    <!-- box message -->
                    <div class="w-9/12 flex flex-col justify-between">
                        <!-- Mensagens -->
                        <div class="w-full p-6 flex flex-col overflow-y-scroll">
                            <div v-for="message in messages" :key="message.id"
                                :class="message.from == 1 ? 'text-right' : ''" class="w-full mb-3 message">
                                <p :class="message.from == 1 ? 'messageFromMe' : 'messageToMe'"
                                    class="inline-block p-2 rounded-md bg-gray-200 messageFromMe"
                                    style="max-height: 75%;">{{
                                        message.context }}
                                </p>
                                <span class="block mt-1 text-xs text-gray-500">{{ message.created_at }}</span>
                            </div>
                        </div>
                        <!-- form -->
                        <div v-if="userActive" class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-200">
                            <form v-on:submit.prevent="sendMessage()">
                                <div class="flex rounded-md overflow-hidden border border-gray-300">
                                    <input v-model="message" class="flex-1 px-4 py-2 text-sm focus:outline-none"
                                        type="text" name="" id="">
                                    <button type="submit"
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">Enviar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
export default {
    data () {
        return {
            users: [],
            messages: [],
            userActive: null,
            message: ''
        }
    },
    methods: {

        scrollToBotton: function() {
            if (this.messages.length) {
                document.querySelectorAll('.message:last-child')[0].scrollIntoView({ behavior: 'smooth' });
            }
        },

        loadMessages: async function(user) {

            axios.get('/api/users/' + user).then(response => {
                this.userActive = response.data.user
            })

            await axios.get('/api/messages/' + user).then(response => {
                this.messages = response.data.messages
            })

            this.scrollToBotton()

        },
        sendMessage: async function() {
            await axios.post('/api/messages/store', {
                'context': this.message,
                'to': this.userActive.id
            }).then(response => {

                this.messages.push({
                    'context': this.message,
                    'from': 1,
                    'to': this.userActive.id,
                    'created_at': moment().format('DD/MM/YYYY HH:mm:ss'),
                    'updated_at': moment().format('DD/MM/YYYY HH:mm:ss')
                })

                this.message = ''
            })

            this.scrollToBotton()
        }

    },
    mounted () {
        axios.get('/api/users').then(response => {
            this.users = response.data.users
        })
    }
}
</script>

<style>
.messageFromMe {
    @apply bg-indigo-300 bg-opacity-25
}

.messageToMe {
    @apply bg-gray-300 bg-opacity-25
}

.messageToMe p {
    background-color: #fff;
    border-radius: 20px 20px 20px 0;
}
</style>
