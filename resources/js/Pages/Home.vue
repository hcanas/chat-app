<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const chatBox = ref(null);
const trigger = ref(null);
const messages = ref([]);
const messagesCurrentPage = ref(1);
const inputMessage = ref('');
const endOfChatHistory = ref(false);
        
const observer = new IntersectionObserver((entries) => {
        if (messages.value.length > 0 && entries[0].isIntersecting) {
            loadMessages(messagesCurrentPage.value++);
        }
    }, 
    {
        root: chatBox.value,
        threshold: .1,
    });

function submit() {
    axios.post('/api/messages', { content: inputMessage.value })
        .then(() => inputMessage.value = '')
        .catch(error => alert(error.message));
}

function loadMessages(page) {
    axios.get(`/api/messages?page=${page}`)
        .then(response => {
            if (response.data.data.length > 0) response.data.data.forEach(x => messages.value.push(x));
            else endOfChatHistory.value = true;
        });
}

onMounted(() => {
    loadMessages(messagesCurrentPage.value++);
    window.Echo.channel('chats')
        .listen('NewMessageEvent', e => {
            messages.value.unshift(e.message);
        });

    observer.observe(trigger.value);
});
</script>

<template>
    <AppLayout title="Home">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Home
            </h2>
        </template>

        <div class="flex-grow flex flex-col bg-white rounded shadow">
            <div ref="chatBox" class="flex-grow flex flex-col-reverse space-y-4 space-y-reverse h-0 px-4 pt-8 pb-4 overflow-y-auto">
                <div v-for="message in messages" class="flex flex-col" :class="{ 'items-end': $page.props.auth.user.id === message.user.id }">
                    <span class="text-sm text-gray-600 font-medium">{{ `${message.user.name} says...` }}</span>
                    <span>{{ message.content }}</span>
                    <span class="text-xs text-gray-400">{{ new Date(message.created_at).toLocaleString() }}</span>
                </div>
                <div v-if="endOfChatHistory" class="text-center text-gray-400 text-sm">End of chat history</div>
                <div v-else ref="trigger"></div>
            </div>
            <div class="flex-shrink-0">
                <form @submit.prevent="submit">
                    <div class="w-full relative">
                        <input type="text" v-model="inputMessage" class="w-full border-0" placeholder="Say something..." />
                        <button type="submit" class="absolute right-4 top-1 text-xl hover:text-blue-600">&rarr;</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
