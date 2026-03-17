/////////////////////////////////////////////////////
// 객체 생성
/////////////////////////////////////////////////////
var container = {};

/////////////////////////////////////////////////////
// 모듈 로드
/////////////////////////////////////////////////////
const Promise = require('promise');
const bcrypt = require('bcrypt');
const fs = require('fs');
const path = require('path');

//로그 쓰기
container.writeLog = function (msg) {
    // 로그 파일 기록 비활성화
    return;

    var date = new Date();
    var logMsg = date.toISOString() + ": [서버로그]::" + msg + "\n";
    console.log("[서버로그]::" + msg);

    // 날짜별 로그 파일명 생성 (YYYY-MM-DD 형식)
    var dateStr = date.toISOString().split('T')[0]; // YYYY-MM-DD
    var logDir = path.resolve(__dirname, "../../../logs");
    var logFile = path.join(logDir, 'chat_trace_' + dateStr + '.log');

    // 날짜별 로그 파일에 저장
    fs.appendFile(logFile, logMsg, (err) => {
        if (err) console.log('로그 파일 쓰기 오류:', err);
    });
}

//로그 쓰기
container.chkLog = function (msg) {
    console.log("[임시로그]::" + msg);
}

//json return
container.jsonReturn = function (code, msg, data) {
    return {
        code: code,
        msg: msg,
        data: data,
    }
}

//비밀번호 암호화
container.passwordHash = function (passwd) {
    var hash = bcrypt.hashSync(passwd, 10);
    return hash;
}

//비밀번호 확인
container.passwordCompare = function (passwd, hash) {
    var ret = bcrypt.compareSync(passwd, hash);
    return ret;
}

container.getCurrentDate = function () {
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth();
    var today = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var milliseconds = date.getMilliseconds();
    return new Date(year, month, today, hours, minutes, seconds, milliseconds);
}

container.getYmdHis = function () {
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth();
    var today = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var milliseconds = date.getMilliseconds();
    return month + "-" + month + "-" + today + " " + hours + ":" + minutes + ":" + seconds;
}

container.getPrevHourDate = function () {
    var date = new Date();
    date.setMinutes(date.getMinutes() - 10);
    var year = date.getFullYear();
    var month = date.getMonth();
    var today = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    var milliseconds = date.getMilliseconds();
    return new Date(year, month, today, hours, minutes, seconds, milliseconds);
}

container.getFormatDate = function (date, format) {
    var year = date.getFullYear();
    var month = (1 + date.getMonth());
    var day = date.getDate();
    var hours = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();

    month = month >= 10 ? month : '0' + month;
    day = day >= 10 ? day : '0' + day;

    if (format == "ymd") {
        return year + '' + month + '' + day;
    } else if (format == "y-m-d") {
        return year + '-' + month + '-' + day;
    } else if (format == "full") {
        return year + '-' + month + '-' + day + ' ' + hours + ':' + minute + ':' + second;
    } else if (format == "hh:mm") {
        return hours + ':' + minute;
    }
}

//생성
module.exports = container;
