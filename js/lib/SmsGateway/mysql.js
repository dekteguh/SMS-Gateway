var mysql  = require('mysql'),
    config = require('config');

var db = {};

db.client = mysql.createClient({
        user:     config.user,
        password: config.password
    );

db.client.useDatabase(config.database);

db.sendSms = function (receiver, text, flash, time, report) {
    if (flash === null) {
        flash = -1;
    }

    if (time === null) {
        time = '';
    }

    if (report === null) {
        report = 'default';
    }

}
