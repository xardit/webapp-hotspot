## Hotspot Web Login with Mikrotik Setup

#### Requirements
* Linux server with docker installed
* Mikrotik device

## Instructions
### 1. Create virtual AP
Wireless > + > Virtual
Set at General tab a Name and a Mac Address
Wireless Tab: `Mode:Ap Bridge`, `Security: Default` for no password and `SSID: MyWifi`

### 2. Setup hostpot
IP > Hotspot > Hotspot Setup
1. Specify interface: wlan2 (the virtual one we created)
2. Leave default: Local Address, Address Pool, Certificate, SMTP Server
3. Set DNS: 1.1.1.1, 1.0.0.1
4. Leave default: DNS Name
5. User and password: user1, password1
6. Finish

#### Go to Hotspot > Servers > hotspot1
- To configure timeouts set your timeouts for: keepalife and login
- for Idle Timeout set yours for longer duration on idle connections on the hotspot
- Addresses per Mac, tap the arrow down to make it unlimited

#### Hotspot > Server Profiles
`hsproof1` which is related to our `hotspot1` that we created, doubleclick and at Login tab at `Login by` keep checked only `HTTP PAP`, we need this for http login with GET request by our app.
You could also specify rate limits or proxies at general for this profle

### 3. Whitelist server for the login page
Add your server and another host to allow connections before hotspot login
Go to Hotspot > Walled Garden: set `Action Allow` and `Dst. Host` to your server, example: `192.168.1.200` or with name matching `*.google.com`

Note: To setup more users go to Hotspot > Users

### 4. Upload login.html
Edit login.html and replace with current server IP Address
Upload it with winbox to Files > "hotspot" folder by replacing current login.html file


### Export to CSV with hidden url
http://SERVER_IP/?export4128093
