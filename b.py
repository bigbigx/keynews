#!/usr/bin/python
#-*- coding:utf-8 -*-
import urllib2
import re
import string
import jieba
import json
import io
import urlparse
import time
import os
import sys

reload(sys)
sys.setdefaultencoding('utf-8')


nowtime = str(int(time.time()))
def savafile(html,url):
    urlinfo=urlparse.urlparse(url)
    filename=nowtime+'_'+urlinfo.netloc+'.html'
    with io.open('./data/'+filename, 'wb') as file:
        file.write(html)
        file.close()


def gethtmltxt(url,charset):

    request = urllib2.Request(url)
    request.add_header('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.107 Safari/537.36')
    #connect to a URL
    response = urllib2.urlopen(request)
    headers = response.info()
    html = response.read()
    if ('Content-Encoding' in headers and headers['Content-Encoding']) or \
            ('content-encoding' in headers and headers['content-encoding']):
        import gzip
        import StringIO
        data = StringIO.StringIO(html)
        gz = gzip.GzipFile(fileobj=data)
        html = gz.read()
        gz.close()

    #read html code
    if charset is None and website.info().getparam('charset') is not None:
        charset  = string.upper(website.info().getparam('charset'))
    unicodehtml = html.decode(charset,'ignore')
    utf8html = unicodehtml.encode('UTF-8')
    savafile(unicodehtml,url)
    return utf8html
    


def geturltitle(url,charset):
    ret=[]
    utf8html = gethtmltxt(url,charset)
    re_h=re.compile('</?\w+[^>]*>')
    #use re.findall to get all the links
    links = re.findall('<a[^>]*href="([^"]*)"[^>]*>(.*?)<\/a>', utf8html)
    if links == []:
        print utf8html
        sys.exit()
    for x in links:
        if re.search(r"https?://www\.", x[0]): 
            continue
        if re.search(r"\.s?html?", x[0]):
            title = re_h.sub('',x[1]).strip()
            if len(title)>1:
                ret.append({'u':x[0],'t':title})
    return ret

Portalsite=[
    ['http://news.163.com','GBK'],
    ['http://news.sina.com.cn','utf-8'],
    ['http://news.sohu.com/','gb2312'],
    ['http://news.qq.com','gb2312'],
    ['http://news.baidu.com/','gb2312'],
    ['http://news.ifeng.com/','utf-8'],
    ['http://www.xinhuanet.com/','utf-8'],
    ['http://www.huanqiu.com/','utf-8'],
    ['http://cn.msn.com/','utf-8'],
    ['http://www.cntv.cn/','utf-8'],
]   
news = []
for url in Portalsite:
    news += geturltitle(url[0],url[1])


res={}
for item in news:
    #print item['t']
    seg_list = jieba.cut(item['t'], cut_all=False)
    #print ",".join(seg_list)
    #continue
    for word in seg_list:

        if not res.has_key(word):
            res[word] = {'c':1,'l':[item]}
        else:
            res[word]['c'] += 1
            res[word]['l'].append(item)


tmpres = res.keys()
fetch = iter(tmpres)
while True:
    try:
        word = fetch.next()
    except StopIteration:
        break
    key = word
    word = word.strip()
    if len(word.decode('utf-8'))<=1:
        del res[key]

blackword = [u'...',u'详细',u'quot',u'新闻'];
for word in blackword:
    if word in res:
        del res[word]

sortres = sorted(res.iteritems(),key=lambda d:d[1]['c'], reverse = True)

jsonstr = json.dumps(sortres[0:70])
print jsonstr
sys.exit()
for word in sortres:
    print word[0],"\t",word[1]['c']
    for item in word[1]['l']:
        print item['t']



        #print word,"\t",res[word]['c']
        #for item in res[word]['l']:
        #    print item['t'],"\t",item['u']

