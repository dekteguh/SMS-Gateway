SMS Gateway for gammu-smsd
==========================

**WARNING: CURRENTLY IN DEVELOPMENT! THIS REPOSITORY CONTAINS NO PRODUCTION 
READY SOFTWARE!**

This is a webservice enabled SMS Gateway. It might be used via CLI, 
include or as webservice(see below).

Currently PHP and JavaScript for node.js are planned. Future releases might
include more supported languages.

Currently only MySQL with gammu-smsd database version 11 is supported as
backend but this could be changed if needed as the source code is supposed
to be modular and the backend drivers should be interchangeable.

Contents
========

1. Installation
    1. Requirements
    2. CLI
    3. In-App usage
    4. Webservice
2. Webservice usage
3. Optional Flags
    1. Flash SMS
    2. Reports
    3. Send date

1 Installation
===============

1.1 Requirements
----------------

To run the SMS Gateway you need gammu-smsd. Currently, the gateway is 
developed against the gammu-smsd database version 11.
