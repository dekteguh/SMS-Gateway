var mysql  = require('mysql');

var db = {},
    config;

module.exports = db;

db.setConfig = function (conf) {
    config = conf;
};

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
