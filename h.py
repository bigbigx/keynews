# -*- coding: utf-8 -*-
#
# author: oldj
#
import os
import BaseHTTPServer
LOCAL_FOLDERS = [
    "./web"
]
BASE_URL = "http://www.baidu.com"
class WebRequestHandler(BaseHTTPServer.BaseHTTPRequestHandler):
    def do_GET(self):
        print "Request for '%s' received." % self.path
        for folder in LOCAL_FOLDERS:
            fn = os.path.join(folder, self.path.replace("/", os.sep)[1:])
            if os.path.isfile(fn):
                self.send_response(200)
                self.end_headers()
                self.wfile.write(open(fn, "rb").read())
                break
        else:
            self.send_response(302)
            self.send_header("Location", "%s%s" % (BASE_URL, self.path))

server = BaseHTTPServer.HTTPServer(("0.0.0.0", 8011), WebRequestHandler)
server.serve_forever()
