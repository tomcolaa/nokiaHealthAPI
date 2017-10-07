# nokiahealth


Getting started
Get a quick overview of the Nokia Health API using OAuth2, including info about creating your app, authenticating users, making requests, responses.

App development
In order to use our API, you’ll need to create an app. Once you’ve created an app, you’ll be able to use our API Reference.

Create your app
You’ll need to be logged in to your Nokia Health account to create an app. If you don’t have a Nokia Health account, create one now. To create a new app:

Go to Create an App.
Agree to our terms and policies and click Create app.
Choose a name and description for your app. Click Create.
You’ll be taken to a page where you can add and manage your app’s info.

Authentication
Nokia uses OAuth 2.0 to authenticate requests between your app and your users. With OAuth, users can give you access to their Nokia data without giving up their passwords. Here's how it works:

Get authorization from your user. Your app will redirect your user to Nokia and ask for their permission to read data from their account.

Get an authorization code. If your user approves your request, you'll receive temporary credentials (known as an authorization code) for your user's Nokia Health account.

Exchange for an access token. Your app will call the API to exchange the authorization code for an access token. You'll use the access token to perform actions on Nokia on your user's behalf.

Getting your authorization code
To get your access code, direct your user to https://account.health.nokia.com/oauth2_user/authorize2 with the following parameters.

Parameter	Description
response_type	Must take the value code.
client_id	Your app ID. You can get this ID from your app page.
state	A value you define. This can be used to make sure that the redirect back to your site or app wasn’t spoofed.
scope	A comma-separated list of permission scopes you want to ask your user for (e.g., user.info,user.metrics,user.activity).
redirect_uri	The URI we should redirect your user to after they authorize (or choose not to authorize) your app. This URI must be added to your list of redirect URIs in your app configuration.
An example request might look like:

https://account.health.nokia.com/oauth2_user/authorize2?
                    response_type=code&
                    redirect_uri=https://mywebsite.com/connect/nokia/&
                    client_id=2aeb567cfba345&
                    scope=user.info,user.metrics,user.activity&
                    state=768uyFys
                
Getting your access token
After you get your access code, you can request an access token. Make a POST request to https://account.health.nokia.com/oauth2/token with the following parameters.

Parameter	Description
grant_type	Must take the value authorization_code.
client_id	Your app ID. You can get this ID from your app page.
client_secret	Your app secret. You can get this from your app page.
code	The access code you received from your redirect URI.
redirect_uri	The URI you use in the first call.
An example request might look like:

https://account.health.nokia.com/oauth2/token?
                    grant_type=authorization_code&
                    client_id=12345&
                    client_secret=6789abcd&
                    code=xyz1010
                
Refresh your access token
After you get your access code, you can request an access token. Make a POST request to https://account.health.nokia.com/oauth2/token with the following parameters as form-data body params.

Parameter	Description
grant_type	Must take the value refresh_token.
client_id	Your app ID. You can get this ID from your app page.
client_secret	Your app secret. You can get this from your app page.
refresh_token	The refresh token.
An example request might look like:

https://account.health.nokia.com/oauth2/token?
                    grant_type=refresh_token&
                    client_id=12345&
                    client_secret=6789abcd&
                    refresh_token=a1eghb56cea
                
After you get an access token, you’re ready to make requests on the user's behalf.


Permission scopes
Use these scopes to authenticate your users. Most endpoints need authentication from both you and your user, while others only need your authentication. These endpoints let you look up any public item that you know the identifier for.

Type	Description
user.info	Give you access to user info.
user.metrics	Give you access to metrics data : weight, heigthn temperature and heart rate.
user.activity	Give you access to activity data : steps, calories, distance, elevation, sleep.
Migrate from OAuth 1.0 to OAuth 2.0
Please follow the instructions here

Making requests
API requests start with the base:

https://api.health.nokia.com/
                
with the specific parameters of the request passed in after. If you are requesting info about an authenticated user, you must put in the access_token for the user. Important: All requests must be made over https. We will not redirect requests from http to https.

In order to use Nokia Health API, please make sure that your server handle SNI (Server Name Indication). Otherwise old API endpoint will have to be used : wbsapi.withings.net

Example request
https://api.health.nokia.com/user?action=getinfo
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                    {
                      "status": 0,
                      "body": {
                        "user": {
                          "userid": 892535,
                          "mac_address": "00:24:e4:0e:91:e4",
                          "type": 1,
                          "model": 4,
                          "timezone": "Europe\\/Paris"
                        }
                      }
                    }
                
Errors
General errors
Error code	Messages
401 (Authorization)	Authentication failed.
Authorization failed.

You are not permitted to access that resource.

286	No such subscription was found
293	The callback URL is either absent or incorrect
304	The comment is either absent or incorrect
328	The user is deactivated
343	Wrong Notification Callback URL doesn't exist
353	Unknown userid
601	Too Many Requests
2554	Wrong action or wrong webservice
2555	An unknown error occurred
2556	Service is not defined
user.getinfo
With this service, you can retrieve data from your user ( scope : user.info ). Please find the parameters below

Fields	Type	Description
action	string	getinfo
https://api.health.nokia.com/user?action=getinfo
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                    {
                      "status": 0,
                      "body": {
                        "user": {
                          "id": 3537173,
                          "created": 1409841457,
                          "firstmeasure": {
                            "weight": "2013-08-27",
                            "activity": "2014-08-14"
                          }
                        }
                      }
                    }
                
user.getdevice
With this service, you can retrieve information about the devices of the user. Please find the parameters below

Fields	Type	Description
action	string	getinfo
https://api.health.nokia.com/v2/user?action=getdevice
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                   {
                        "status": 0,
                        "body": {
                            "devices": [{
                                "type": "Scale",
                                "model": "Smart Body Analyzer",
                                "battery": "medium"
                            }, {
                                "type": "Activity Tracker",
                                "model": "Activite Pop",
                                "battery": "medium"
                            }, {
                                "type": "Scale",
                                "model": "Smart Body Analyzer",
                                "battery": "medium"
                            }]
                        }
                    }
                
Measure
measure.getmeas
Get the body measurements for a user

Field	Type	Description
userid	Number	Callback, url encoded.
startdate optional	Date as unix epoch	Start date for the measures
enddate optional	Date as unix epoch	End date for the measures
lastupdate optional	Date as unix epoch	Returns measures updated or created after a certain date. Useful for data synchronization between systems. Note that you don't need to fill the startdate and enddate parameters when using this parameter.
meastype optional	Number	Measure type filter. Value is a number, which corresponds to : 

1 : Weight (kg)

4 : Height (meter)

5 : Fat Free Mass (kg)

6 : Fat Ratio (%)

8 : Fat Mass Weight (kg)

9 : Diastolic Blood Pressure (mmHg)

10 : Systolic Blood Pressure (mmHg)

11 : Heart Pulse (bpm)

54 : SP02(%)

71 : Body Temperature

73 : Skin Temperature

76 : Muscle Mass

77 : Hydration

88 : Bone Mass

91 : Pulse Wave Velocity

category optional	Number	1 for real measurements, 2 for user objectives.
limit optional	Number	Maximum number of measure groups to return. Results are always sorted from the most recent one first to the oldest one (ie limit=1 returns the latest measure group)
offset optional	Number	Offset to retrieve the data
https://api.health.nokia.com/measure?action=getmeas&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                    {
                       "status": 0,
                       "body": {
                           "updatetime": 1249409679,
                           "timezone": "Europe/Paris",
                           "measuregrps": [
                               {
                                   "grpid": 2909,
                                   "attrib": 0,
                                   "date": 1222930968,
                                   "category": 1,
                                   "measures": [
                                       {
                                           "value": 79300,
                                           "type": 1,
                                           "unit": -3
                                       },
                                       {
                                           "value": 652,
                                           "type": 5,
                                           "unit": -1
                                       },
                                       {
                                           "value": 178,
                                           "type": 6,
                                           "unit": -1
                                       },
                                       {
                                           "value": 14125,
                                           "type": 8,
                                           "unit": -3
                                       }
                                   ]
                               },
                               {
                                   "grpid": 2908,
                                   "attrib": 0,
                                   "date": 1222930968,
                                   "category": 1,
                                   "measures": [
                                       {
                                           "value": 173,
                                           "type": 4,
                                           "unit": -2
                                       }
                                   ]
                               }
                           ]
                       }
                   }
                
v2/measure.getactivity
Get the activity measures log for a user. Note that the service is limited to 60 calls per minute. 
Note : you retrieve 200 objects by calls. If there is more data, we will find the parameter "more" and the "offset" in the response.

Field	Type	Description
userid	Number	Id of the user to fetch activity for.
date optional	Date in YYYY-mm-dd format	current date for the log
startdateymd optional	Date in YYYY-mm-dd format	start date for the log.
enddateymd optional	Date in YYYY-mm-dd format	end date for the log.
offset optional	Number	skip the "offset" most recent measure groups.
Example single day

https://api.health.nokia.com/v2/measure?action=getactivity&date=2015-05-15&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                    {
                       "status": 0,
                       "body": {
                           "date":"2015-04-10",
                           "steps":6523,
                           "distance":4600,
                           "calories":408.52
                           "elevation":18.2,
                           "soft": 5880,
                           "moderate": 1080,
                           "intense": 540,
                           "timezone":"Europe/Berlin"
                        }
                    }
                
Example range

https://api.health.nokia.com/v2/measure?action=getactivity&startdateymd=2015-09-04&enddateymd=2015-09-08&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                   {
                       "status": 0,
                       "body":
                           "activities": [
                              {
                               "date": "2015-10-06",
                               "steps": 10233,
                               "distance": 7439.44,
                               "calories": 530.79,
                               "elevation": 808.24,
                               "soft": 9240,
                               "moderate": 960,
                               "intense": 0,
                               "timezone":"Europe/Berlin"
                               },
                               {
                                   "date": "2015-10-07",
                                   "steps": 6027,
                                   "distance": 5015.6,
                                   "calories": 351.71,
                                   "elevation": 153.82,
                                   "elevation": 50.78,
                                   "soft": 17580,
                                   "moderate": 1860,
                                   "timezone":"Europe/Berlin"
                               },
                               {
                                   "date": "2015-10-08",
                                   "steps": 2552,
                                   "distance": 2127.73,
                                   "calories": 164.25,
                                   "elevation": 33.68,
                                   "soft": 5880,
                                   "moderate": 1080,
                                   "intense": 540,
                                   "timezone":"Europe/Berlin"
                               }
                            ]
                    }
                
Success
Field	Type	Description
date	Date as YYYY-mm-dd	Date at which the measure was taken or entered.
timezone	String	Time zone for the date.
steps	Number (integer)	Number of steps for the day.
distance	Number (float)	Distance travelled for the day (in meters).
calories	Number (float)	Active Calories burned in the day (in kcal).
totalcalories	Number (float)	Total Calories burned in the day (in kcal).
elevation	Number (float)	Elevation climbed during the day (in meters).
soft	Number (integer)	Duration of soft activities (in seconds).
moderate	Number (integer)	Duration of moderate activities (in seconds).
intense	Number	Duration of intense activities (in seconds).
body optional	Dictionary	Response data.
more optional	Boolean	Specified if you have to retrieve more data
offset optional	Number (integer)	Offset to use to retrieve more data
v2/measure.getintradayactivity
The Get Intraday Activity API lets you reconstitute a full image of the user’s activity with the details of different measures. The data is made available when the measurements from the Nokia Activity Tracker are synchronized with the Health Mate mobile application which then synchronises with Nokia Health database. The Nokia Activity Tracker synchronizes on the user’s initiative and automatically several times during the day. Note that a specific limitation of 120 API calls per minute is in place on this webservice. 
Note : if startdate and enddate are separated by more than 24h, enddate will be overwritten with 24h past startdate.

Field	Type	Description
userid	Number	Id of the user to fetch activity for.
startdate	Date in unix epoch	start time for the log.
enddate	Date in unix epoch	end time for the log.
https://api.health.nokia.com/v2/measure?action=getintradayactivity&startdate=1368138600&enddate=1368142469&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                   {
                       "status": 0,
                       "body": {
                           "series": {
                               {
                               "1368141046": {
                                   "calories": 0,
                                   "duration": 611
                               },
                               {
                               "1368141657": {
                                   "calories": 0.87,
                                   "duration": 60,
                                   "steps": 18,
                                   "elevation": 0.03,
                                   "distance": 13.26,
                               },
                               {
                               "1368141717": {
                                   "calories" : 1.2,
                                   "duration" : 60,
                                   "strokes"    : 56,
                                   "pool_lap": 2,
                                }
                           }
                       }
                    }
                
Success
Field	Type	Description
series	Dictionary	List of activity measures. Keys for this dictionary are the times at which the activity happened.
calories optional	Number (float)	Calories burned (in kcal).
distance optional	Number (float)	Distance travelled (in meters).
duration optional	Number	Duration of the activity (in seconds).
elevation optional	Number (float)	Elevation climbed (in meters).
steps optional	Number (integer)	Number of steps performed.
stroke optional	Number (integer)	Number of strokes performed.
pool_lap optional	Number (integer)	Number of pool laps performed.
body optional	Dictionary	Response data.
Measure - Get Sleep Measures
v2/sleep.get
The Sleep Measures API lets you reconstitute a full image of the user’s night with the details of each phase of their sleep cycle. The data is made available when the measurements from the Nokia Activity Tracker are synchronized with the Nokia Health database. The Nokia Activity Tracker synchronizes on the user’s initiative and automatically several times during the day. The Nokia Sleep Monitor synchronizes automatically at the end of each morning, a few minutes after the user leaves their bed.
Note: You can retrieve 200 sleep measures by calls. 
Note : if startdate and enddate are separated by more than 24h, enddate will be overwritten with 24h past startdate.

Field	Type	Description
userid	Number	Id of the user to fetch activity for.
startdate	Date in unix epoch	start date for the sleep data (recommended value : set to Noon in the user's time zone)
enddate	Date in unix epoch	end date for the sleep data (recommended value : set to Noon in the user's time zone)
https://api.health.nokia.com/v2/sleep?action=get&startdate=1368138600&enddate=1368142469&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                {
                    "status": 0,
                    "body": {
                        "model": 32,
                        "series": [
                            {
                                "startdate": 1502062440,
                                "state": 0,
                                "enddate": 1502062560
                            },
                            {
                                "startdate": 1502062560,
                                "state": 1,
                                "enddate": 1502063040
                            },
                            {
                                "startdate": 1502063040,
                                "state": 2,
                                "enddate": 1502063700
                            }
                        ]
                    }
                }
                
Success
Field	Type	Description
series	Array	Serie of sleep data
startdate	Date as unix epoch	The starting datetime for the sleep state data
enddate	Date as unix epoch	The end datetime for the sleep data. A single call can span up to 7 days maximum. To cover a wider time range, you'll need to perform multiple calls
state	Number	The state of sleeping. Values can be :

0 : awake

1 : light sleep

2 : deep sleep

3 : REM sleep (only for devices tracking sleep activities)

model	Number	Source for the sleep data. Values can be :

16 : Activity Tracker

32 : Sleep Monitor

body	Dictionary	Response data.
Measure - Get Sleep Summary
v2/sleep.getsummary
The Sleep Summary API lets you access the basic information about a user’s night: the total time they slept, how long it took them to fall asleep, how many times they woke up, etc. The data is made available when the measurements from the Nokia Activity Tracker are synchronized with the Nokia Health database. The Nokia activity trackers synchronizes on the user’s initiative and automatically several times during the day. The Nokia activity trackers synchronizes automatically at the end of each night a few minutes after the user exists the bed.
Note: You can retrieve 7 days of data by calls.

Field	Type	Description
startdateymd	Date in unix epoch	start time for the log.
enddateymd	Date in unix epoch	end time for the log.
lastupdate	Date in unix epoch	Either the lastupdate or the stardateymd/enddateymd must be used
https://api.health.nokia.com/v2/sleep?action=getsummary&startdateymd=2014-06-20&enddateymd=2014-10-25&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                   {
                       "status":0,
                       "body": {
                         "series":
                        [
                         {
                           "id":16616514,
                           "timezone":"\"Europe/Paris\"",
                           "model":32,
                           "startdate":1410521659,
                           "enddate":1410542577,
                           "date":"2014-09-11",
                           "data":
                           {
                             "wakeupduration":1800,
                             "lightsleepduration":18540,
                             "deepsleepduration":8460,
                             "remsleepduration":10460,
                             "durationtosleep":420,
                             "durationtowakeup":360,
                             "wakeupcount":3
                            },
                            "modified":1412087110
                          }
                       ],
                       "more":false
                       }
                     }
                
Success
Field	Type	Description
body	Dictionary	Response data.
series	Array	Series of sleep data
userid	Number	Id of the user to fetch activity for.
Timezone	String	Timezone to be used to calculate startdate/enddate
model	Number	Source for the sleep data. Values can be :

51 to 55: Activity Tracker

61: Aura

startdate	Date as unix epoch	The starting datetime of the sleep night
enddate	Date as unix epoch	The endding datetime of the sleep night
date	Date Y-m-d	Date of the sleep night
data	Dictionary	Details of the sleep night

wakeupduration: total of awake states

lightsleepduration: total of light sleep states

deepsleepduration: total of deep sleep states

remsleepduration: total of rem sleep states

durationtosleep: total of awake states before the first sleep state

durationtowakeup: total of awake states after the last sleep state

wakeupcount: total number of awake periods

modified	Date as unix epoch	The timestamp of the last modification of the night sleep
Measure - Get Workouts
v2/measure.getworkouts
The Workouts API lets you retrieve the data relevant to workout sessions as measured by the Nokia activity trackers. The data is presented as aggregates for each workout session of a given day. Detailed minute-by-minute data for all workout activities is also available through the GetIntradayActivity service.
Note: You can retrieve 200 workouts by calls

Field	Type	Description
userid	Number	Id of the user to fetch activity for.
startdateymd optional	Date in YYYY-mm-dd format	start date for the log.
enddateymd optional	Date in YYYY-mm-dd format	end date for the log.
https://api.health.nokia.com/v2/measure?action=getworkout&userid=29&startdateymd=2014-10-04&enddateymd=2014-10-08&
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                   {
                        "status":0,
                        "body":
                        {
                            "series":
                            [
                                {
                                    "id":16618655,
                                    "userid":3537173,
                                    "category":7,
                                    "timezone":"Europe/Paris",
                                    "model":61,
                                    "attrib":0,
                                    "startdate":1425445911,
                                    "enddate":1425457911,
                                    "date":"2014-08-30",
                                    "data":
                                    {
                                        "nblengthpool":400,
                                        "nbmovement":800,
                                        "calories":2500,
                                        "duration":2600
                                    },
                                    "modified":1425457943
                                }
                            ]
                        }
                    }
                
Success
Field	Type	Description
body	Dictionary	Response data.
series	Array	Series of sleep data
userid	Number	Id of the user to fetch activity for.
Timezone	String	Timezone to be used to calculate startdate/enddate
model	Number	Source for the sleep data. Values can be :

51 to 55: Activity Tracker

61: Aura

startdate	Date as unix epoch	The starting datetime of the sleep night
enddate	Date as unix epoch	The endding datetime of the sleep night
date	Date Y-m-d	Date of the sleep night
data	Dictionary	Details of the workout (depending on the category)
modified	Date as unix epoch	The timestamp of the last modification of the night sleep
category	Number	Category of the workout: 
1 : Walk
2 : Run
3 : Hiking
4 : Staking
5 : BMX
6 : Bicycling
7 : Swim
8 : Surfing
9 : KiteSurfing
10 : Windsurfing
11 : Bodyboard
12 : Tennis
13 : Table Tennis
14: Squash
15 : Badminton
16 : Lift Weights
17 : Calisthenics
18 : Elliptical
19: Pilate
20 : Basketball
21 : Soccer
22 : Football
23 : Rugby
24 : VollyBall
25 : WaterPolo
26 : HorseRiding
27 : Golf
28 : Yoga
29 : Dancing
30 : Boxing
31 : Fencing
32 : Wrestling
33 : Martial Arts
34 : Skiing
35 : SnowBoarding
192 : Handball
29 : Dancing
186 : Base
187 : Rowing
188 : Zumba
191 : Baseball
192 : Handball
193 : Hockey
194 : IceHockey
195 : Climbing
196 : ICeSkating
Notification
notify.subscribe
Notification lets your system be informed every time new data is available for a device. Nokia will call a provided url every time the device syncs.

Field	Type	Description
action	string	subscribe
callbackurl	string	The URL the API notification service will call. This URL will be used as a key whenever one needs to list it or revoke it. WBS API notification are merely HTTP POST requests to this URL (such as http://www.yourdomain.net/yourCustomApplication.php ?userid=123456&startdate=1260350649 &enddate=1260350650&appli=44). Those requests contain startdate and enddate parameters (both are integers in EPOCH format) and the userid it refers to. It is up to the targeted system to issue a measure/getmeas request using both figures to retrieve updated data. This URL should be a valid URL, provided as a URL-encoded string. Please refer to w3schools URL encoding reference to learn more about URL encoding, use free online URL encoders or check the example provided. The URL length shall not be greater than 255 characters. See below the payload for each type of notification :
: userid=123545&startdate=1411002541&enddate=1411002542
appli	Number	Value for this parameter is a number, which corresponds to :
1 : Weight
4 : Heart Rate, Diastolic pressure, Systolic pressure, Oxymetry
16 : Activity Measure ( steps, calories, distance, elevation)
44 : Sleep
https://api.health.nokia.com/notify?action=subscribe&callbackurl=http://example.com&appli=45
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                    {
                        "status": 0
                    }
                
notify.list
List notification configurations for this user.

Field	Type	Description
action	string	list
https://api.health.nokia.com/notify?action=list
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                   {
                      "status": 0,
                      "body": {
                        "profiles": [
                          {
                            "appli": 1,
                            "callbackurl": "http:\\/\\/www.google.fr",
                            "comment": ""
                          },
                          {
                            "appli": 45,
                            "callbackurl": "http:\\/\\/health.nokia.com",
                            "comment": ""
                          }
                        ]
                      }
                    }
                
notify.revoke
This service allows third party applications to revoke a previously subscribed notification. This will disable the notification feature between the WBS API and the specified applications for the specified user.

Field	Type	Description
action	string	revoke
callbackurl	string	The URL the API notification service will call. This URL will be used as a key whenever one needs to list it or revoke it. WBS API notification are merely HTTP POST requests to this URL (such as http://www.yourdomain.net/yourCustomApplication.php ?userid=123456&startdate=1260350649 &enddate=1260350650&appli=44). Those requests contain startdate and enddate parameters (both are integers in EPOCH format) and the userid it refers to. It is up to the targeted system to issue a measure/getmeas request using both figures to retrieve updated data. This URL should be a valid URL, provided as a URL-encoded string. Please refer to w3schools URL encoding reference to learn more about URL encoding, use free online URL encoders or check the example provided. The URL length shall not be greater than 255 characters. See below the payload for each type of notification :
45(Environment data) : userid=123545&startdate=1411002541&enddate=1411002542
appli	Number	Only one value : 45
https://api.health.nokia.com/notify?action=revoke&callbackurl=http://example.com&appli=45/span>
                    access_token=YOUR-ACCESS-TOKEN
                
Example response

                    {
                      "status": 0
                    }
                
