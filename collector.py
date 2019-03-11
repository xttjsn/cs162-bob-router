import tornado.web
import tornado.ioloop


class MainHandler(tornado.web.RequestHandler):
    def initialize(self, appRef):
        self.appRef = appRef

    def set_default_headers(self):
        print("setting headers!!!")
        self.set_header("Access-Control-Allow-Origin", "*")

    def get(self):
        print('received a GET request')
        self.write("Here's router's info {}".format(self.appRef.getRouterInfo()))

    def post(self):
        print('received a POST request')
        info = self.get_argument('router-info')
        self.appRef.setRouterInfo(info)


class LoginHandler(tornado.web.RequestHandler):
    def initialize(self, appRef):
        self.appRef = appRef

    def set_default_headers(self):
        print("setting headers!!!")
        self.set_header("Access-Control-Allow-Origin", "*")

    def post(self):
        self.write('PHPSESSID=213lj1l2kj3o1y3ioklkjbndf90 Only the admin account is currently enabled')

class Application(tornado.web.Application):
    def __init__(self):
        super(Application, self).__init__([
            (r'/', MainHandler, dict(appRef=self)),
            (r'/login.php', LoginHandler, dict(appRef=self)),
        ])
        self.routerInfo = ''

    def setRouterInfo(self, info):
        self.routerInfo = info

    def getRouterInfo(self):
        return self.routerInfo


if __name__ == '__main__':
    Application().listen(8090)
    tornado.ioloop.IOLoop.current().start()
