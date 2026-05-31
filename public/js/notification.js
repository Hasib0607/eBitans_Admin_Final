// $(document).ready(function () {
//
//     $('.modal123').hide();
//     $('#myModal').hide();
//     $('#myModal').show();
//
//     // playaudio();
//
//     function playaudio() {
//         var audio = new Audio('https://admin.ebitans.com/img/message-ringtone-magic.ogg');
//         audio.muted = false;
//         audio.play();
//         setTimeout(playaudio, 4000);
//     }
//
//     // update();
//
//     function update() {
//         let id = '1';
//         $.get('/getnoti', {id: id}, function (data) {
//
//             if (data[0]) {
//                 playaudio();
//                 $('#modalshow').load(location.href + ' .modalshow');
//                 open();
//             } else if (data[2]) {
//                 playaudio();
//                 $('#myModal').show();
//                 open();
//             } else {
//                 close();
//             }
//         });
//
//     }
//
//     function open() {
//         $('.modal123').show();
//         $("#exampleModal123").show();
//         playaudio();
//         setTimeout(close, 100000);
//     }
//
//     function close() {
//         $('.modal123').hide();
//         setTimeout(update, 100000);
//     }
//
//
// });
//
// $(document).ready(function () {
//
//     $('.modal1234').hide();
//
//     function playaudios() {
//         var audio = new Audio('https://admin.ebitans.com/img/message-ringtone-magic.ogg');
//         audio.muted = false;
//         audio.play();
//         setTimeout(playaudios, 4000);
//     }
//
//     // updates();
//
//     function updates() {
//         let id = '1';
//         $.get('/getnotiorder', {id: id}, function (data) {
//             if (data[0]) {
//                 $('#modalshow1').load(location.href + ' .modalshow1');
//                 opens();
//             } else {
//                 closes();
//             }
//         });
//
//     }
//
//     function opens() {
//
//         $('.modal1234').show();
//         $("#exampleModal1234").show();
//         playaudios();
//         setTimeout(closes, 100000);
//     }
//
//     function closes() {
//         $('.modal1234').hide();
//         setTimeout(updates, 100000);
//     }
//
//
// });
