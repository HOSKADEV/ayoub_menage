// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAyUHdkWn931hj3oBfF1WTr4FstEFjN3ZQ",
  authDomain: "maurizon-63850.firebaseapp.com",
  projectId: "maurizon-63850",
  storageBucket: "maurizon-63850.appspot.com",
  messagingSenderId: "854337607271",
  appId: "1:854337607271:web:341e254ee67623ea3ca6c2",
  measurementId: "G-QX6WZBVP1X"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
