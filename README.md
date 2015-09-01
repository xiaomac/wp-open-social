open-social
===========

OAuth Login plugin for WordPress

Allow to Login or Share with social networks (mainly in china) like QQ, Sina WeiBo, Baidu, Google, Live, DouBan, RenRen, KaiXin, XiaoMi. No API! NO Registration! NO 3rd-party! 

Blog: [xiaomac.com](http://www.xiaomac.com/201311150.html)
More: [wordpress.org](https://wordpress.org/plugins/open-social/)

新增：支持多网站绑定！！！

可用国内社交网站：腾讯QQ、新浪微博、百度、豆瓣、人人网、开心网、小米、CSDN、OSChina 绑定登录或分享的一个插件。
无第三方平台、无接口文件冗余、无任何多余脚本加载、带昵称网址头像等、可设置右侧小工具；设置简单，绿色低碳。
————国外的目前支持：谷歌、微软LIVE、脸书、推特、GitHub，会考虑陆续添加完善。

适合人群：**不喜第三方平台接入、不喜任何一个多余脚本、不喜任何一行多余代码、有一定手动折腾能力**。

流程说明：游客点击登陆按钮（如QQ），登陆QQ并确认授权后，系统会自动在后台新建一个用户并以注册用户的身份自动登陆网站：

* 帐号：QQ+OpenID（如：QQ123123123，用户唯一而且不会改变）
* 密码：系统自动随机生成
* 昵称：QQ昵称（不限）
* 角色：订阅者
* 邮箱：OpenID#fake.com（因接口无法取得用户真实QQ号或邮箱，此邮箱为虚假的，仅为标识或筛选用）
* 主页：t.qq.com/WeiBoID（如果有开通腾讯微博的话，否则为空）
* 头像：会自动沿用QQ的头像

我是菜鸟，欢迎提意见，欢迎参与。