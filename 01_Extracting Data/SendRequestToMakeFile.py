import requests
import time
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0'}
for x in xrange(0,4300,500):
	url = 'http://yout.000webhostapp.com/maker.php?s='+str(x) + '&e='+str(x+500)
	print url
	try: requests.get(url, headers=headers)
	except Exception as e:pass
	# time.sleep(1)