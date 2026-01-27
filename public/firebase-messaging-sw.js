// Scripts for firebase messaging
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// Initialize the Firebase app in the service worker by passing in the messagingSenderId.
firebase.initializeApp({
    apiKey: "AIzaSyA0jXdEMSnmv4SQvxop17SqLKt4m8ab_UE",
    authDomain: "sistem-perizinan-santri-ec4de.firebaseapp.com",
    projectId: "sistem-perizinan-santri-ec4de",
    storageBucket: "sistem-perizinan-santri-ec4de.firebasestorage.app",
    messagingSenderId: "1065097278114",
    appId: "1:1065097278114:web:c22400c714f0d805ffddfb"
});

// Retrieve an instance of Firebase Messaging so that it can handle background messages.
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here if needed
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/logo.png' // Pastikan ada logo.png atau sesuaikan
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
