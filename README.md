# TwitchSubEmojiJsonSimpler
This script uses data from https://twitchemotes.com/

# How to use
1. Download JSONParser php from https://github.com/kuma-giyomu/JSONParser/tree/master/package (all 4 php file)
1. Place to same directory with PHP files(sub_cache.php)
1. Make folder name `json` and give write permission to webserver(apache/nginx/php/etc)
1. Download subscriber emote json by executing(by web or cli) `sub_refresh.php`
1. Make simple json cache by executing `sub_cache.php`
1. json file `sub.json` will created inside `json` directory.
