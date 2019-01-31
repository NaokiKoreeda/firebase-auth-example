<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>firebase auth test</title>
    <script src="https://www.gstatic.com/firebasejs/5.8.0/firebase.js"></script>
    <script>
    // Initialize Firebase
    var config = {
        apiKey: "******************************",
        authDomain: "******************************",
        databaseURL: "******************************",
        projectId: "******************************",
        storageBucket: "******************************",
        messagingSenderId: "******************************"
    };
    firebase.initializeApp(config);
    </script>
    <script src="https://cdn.firebase.com/libs/firebaseui/3.1.1/firebaseui.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdn.firebase.com/libs/firebaseui/3.1.1/firebaseui.css" />
</head>
<body>

<h1>Firebase Auth Test App.</h1>
<div id="firebaseui-auth-container"></div>
<div id="loader">Loading...</div>

<div id="provider"></div>
<div id="displayName"></div>
<div id="email"></div>

<script>
var runLogin = function () {
    // 初期化
    var ui = new firebaseui.auth.AuthUI(firebase.auth());
    // 設定
    var uiConfig = {
        callbacks: {
            signInSuccessWithAuthResult: function(authResult, redirectUrl) {
            return true;
            },
            uiShown: function() {
            document.getElementById('loader').style.display = 'none';
            }
        },
        signInFlow: 'popup',
        signInSuccessUrl: 'http://******************************.com',
        signInOptions: [
            firebase.auth.EmailAuthProvider.PROVIDER_ID,
            firebase.auth.TwitterAuthProvider.PROVIDER_ID,
        ],
    };
    ui.start('#firebaseui-auth-container', uiConfig);

    // Twitterログインの実装。公式ページのコードをコピペしただけ
    // 力尽きたので全くリファクタリングしていない、、、
    var provider = new firebase.auth.TwitterAuthProvider();
    firebase.auth().signInWithPopup(provider).then(function(result) {
        // This gives you a the Twitter OAuth 1.0 Access Token and Secret.
        // You can use these server side with your app's credentials to access the Twitter API.
        var token = result.credential.accessToken;
        var secret = result.credential.secret;
        // The signed-in user info.
        var user = result.user;
        // ...
    }).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;
        // The email of the user's account used.
        var email = error.email;
        // The firebase.auth.AuthCredential type that was used.
        var credential = error.credential;
        // ...
    });
};

firebase.auth().onAuthStateChanged(function (user) {
    if (user) {
        // User is signed in.
        document.getElementById('loader').style.display = 'none';
        // このへん適当
        var user = firebase.auth().currentUser;
        if (user != null) {
            user.providerData.forEach(function (profile) {
                document.getElementById('provider').innerText = 'Sign-in provider: ' + profile.providerId;
                document.getElementById('displayName').innerText = 'displayName: ' + profile.displayName;
                document.getElementById('email').innerText = 'email: ' + profile.email;
            });
        }
    } else {
        // No user is signed in.
        this.runLogin();
    }
}.bind(this));
</script>

</body>
</html>