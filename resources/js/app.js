import './bootstrap';
import firebase from 'firebase/app';
import 'firebase/database';

const firebaseConfig = {
    apiKey: "AIzaSyCZMpdzj4jjOEzI1xUMuIHkOvs3VobyPXs",
    authDomain: "realtime-seat-booking.firebaseapp.com",
    databaseURL: "https://realtime-seat-booking-default-rtdb.firebaseio.com",
    projectId: "realtime-seat-booking",
    storageBucket: "realtime-seat-booking.appspot.com",
    messagingSenderId: "323149257094",
    appId: "1:323149257094:web:0010b23628866463813407",
    measurementId: "G-XSJY91M6T4"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
