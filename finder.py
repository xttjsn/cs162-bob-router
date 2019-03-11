import string

FILE = 'log.txt'
GAPSIZE = 15

template = """
HTTPServerRequest(protocol='http', host='18.217.54.18:8888', method='POST', uri='/', version='HTTP/1.1', remote_ip='35.186.170.218') pinnum={}&loginresult=
<html>
    <head>
        <link href='https://fonts.googleapis.com/css?family=Jura:400,500,600,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="index.css">
    </head>

    <body>
        <div id="loginDiv">
            <img src="http://hmapr.com/wp-content/uploads/2011/07/secure-lock.png"/>
            <h1>CISCO Administration Portal</h1>
            <h2>Authentication Required</h2>
        </div>

        <div id="loginForm">
                            <p class="error">Only the admin account is currently enabled</p>
"""

template2 = """
HTTPServerRequest(protocol='http', host='18.217.54.18:8888', method='POST', uri='/', version='HTTP/1.1', remote_ip='35.186.170.218') pinnum={}&loginresult=
"""

text = ''
with open(FILE, 'r') as f:
    text = f.read()

for i in range(10000):
    pinnum = str(i).rjust(4, '0')
    print('Searching {}...'.format(pinnum))
    idx = text.find(template.format(pinnum))
    if idx == -1:
        idx = text.find(template2.format(pinnum))
        if idx == -1:
            print('Not done this one! {}'.format(pinnum))
        else:
            print('Found it! {}'.format(pinnum))
    # else:
        # text = text[idx:idx + len(template) + 2]
