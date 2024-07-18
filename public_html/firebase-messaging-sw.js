 // Scripts for firebase and firebase messaging
 importScripts("https://cdnjs.cloudflare.com/ajax/libs/firebase/10.4.0/firebase-app-compat.min.js");
 importScripts("https://cdnjs.cloudflare.com/ajax/libs/firebase/10.4.0/firebase-messaging-compat.min.js");

 // Initialize the Firebase app in the service worker by passing the generated config
 const firebaseConfig = {
  apiKey: "AIzaSyAMyRsB9u4w1IbWNl0n0gGQd0LPE1jjU4c",
  authDomain: "njeek-eeca7.firebaseapp.com",
  projectId: "njeek-eeca7",
  storageBucket: "njeek-eeca7.appspot.com",
  messagingSenderId: "150919195413",
  appId: "1:150919195413:web:ee3178e4e7dd600462971c",
  measurementId: "G-PT06263J26"
 };

 firebase.initializeApp(firebaseConfig);

 // Retrieve firebase messaging
 const messaging = firebase.messaging();



 messaging.onBackgroundMessage(function(payload) {
   //console.log("Received background message ", payload);

   const notificationTitle = payload.notification.title;
   const notificationOptions = {
     body: payload.notification.body,
   };

   self.registration.showNotification(notificationTitle, notificationOptions);
   //window.open("https://njeek.shop/order/browse");
 });

