// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAK1RmqlbfLH7vIZAGtPYbqoZua0kQwyNQ",
  authDomain: "misd-56691.firebaseapp.com",
  databaseURL: "https://misd-56691-default-rtdb.firebaseio.com",
  projectId: "misd-56691",
  storageBucket: "misd-56691.firebasestorage.app",
  messagingSenderId: "769022387351",
  appId: "1:769022387351:web:0904cce4b5cdd7ddca75d2",
  measurementId: "G-F0P2GGEKW7",
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

export { app, analytics };
