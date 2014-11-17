=== Open Social ===

Contributors: playes
Donate link: https://me.alipay.com/playes
Tags: china, chinese, afly, social, login, connect, qq, sina, weibo, baidu, google, live, douban, renren, kaixin001, openid, xiaomi, wechat, QQ登陆, 新浪微博, 百度, 谷歌, 豆瓣, 人人网, 开心网, 登录, 连接, 注册, 分享, 小米, 微信
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Login/Share with social networks: QQ, Sina, Baidu, Google, Live, DouBan, RenRen, KaiXin, XiaoMi, WeChat. No API, NO Register!

== Description ==

Allow to Login or Share with social networks (mainly in china) like QQ, Sina WeiBo, Baidu, Google, Live, DouBan, RenRen, KaiXin, XiaoMi. No API! NO Registration! NO 3rd-party! **Show and Post** will be the Next.

More information: [www.xiaomac.com](http://www.xiaomac.com/).

新增：微信开放平台登陆。

可用国内社交网站：腾讯QQ、新浪微博、百度、豆瓣、人人网、开心网、小米、CSDN、OSChina 绑定登录或分享的一个插件。
无第三方平台、无接口文件冗余、无任何多余脚本加载、带昵称网址头像等、可设置右侧小工具；设置简单，绿色低碳。
————国外的目前支持：谷歌、微软LIVE、脸书、推特、GitHub，会考虑陆续添加完善。

适合人群：**不喜第三方平台接入、不喜任何一个多余脚本、不喜任何一行多余代码、有一定手动折腾能力**。

流程说明：游客点击登陆按钮（如QQ），登陆QQ并确认授权后，系统会自动在后台新建一个用户并以注册用户的身份自动登陆网站：

* 帐号：QQ+OpenID（如：QQ123123123，用户唯一而且不会改变）
* 密码：系统自动随机生成
* 昵称：QQ昵称（不限）
* 角色：订阅者
* 邮箱：OpenID#t.qq.com（因接口无法取得用户真实QQ号或邮箱，此邮箱为虚假的，仅为标识或筛选用）
* 主页：t.qq.com/WeiBoID（如果有开通腾讯微博的话，否则为空）
* 头像：会自动沿用QQ的头像

更多可查看: [www.xiaomac.com](http://www.xiaomac.com/)，觉得插件好用就帮我挂个链接吧，谢：）

——[ICON来自](http://www.yootheme.com/)、[设置办法](http://www.xiaomac.com/2014081490.html)

== Installation ==

1. Upload the plugin folder to the "/wp-content/plugins/" directory of your WordPress site,
2. Activate the plugin through the 'Plugins' menu in WordPress,
3. Visit the "Settings \ Open Social Setting" administration page to setup the plugin. 

also:

1. 直接在 WordPress 后台搜索 open social 在线安装，并启用。
2. 然后在设置页面“社交网站设置”配置几个平台的 App ID、Secret KEY 即可。
3. 选择显示位置，或拖入小工具 widget。
4. 卸载也同样方便，直接删除即可，无任何数据库残留！

== Frequently Asked Questions ==

= 这个跟其他同类插件有什么不同？ =

其他大部分是适合国外的平台和接口；有国内做的，但要么都是第三方平台中转、要么是残缺陈旧老版本+收费新版本，我个人是不会用的。
所以弄了这个适合国内，免费、开放、不冗余的登陆接口。

= 绑定帐号后可以自动同步文章或评论么？ =

目前没有做这个功能，感觉不够实用。要实现也很简单，代码中提供了一个接口，有需要的朋友可以参照官方API说明自行拓展。不排除后面的版本会加强这个功能。

= 为什么登陆后未显示登陆状态？ =

通常是第三方设置的回调地址设置问题所致。注意：要跟网站域名一致，并且末尾要保留斜杆（如何可设置）。

= 为什么脸书推特等无法登陆？ =

脸书、推特需要空间网络支持，大多数国内的空间可能都不支持。目前提供了一个设置代理的功能。

= 为什么邮件通知没有效果？ =

这个也是需要空间支持邮件函数的支持的。否则可以安装邮件插件（如 wp-mail-smtp）。

＝ 有些第三方开放后台并没有 App ID 或 Secret KEY？ ＝

各有各的叫法。但一般会有两个：1个是公开的ID，1个是不公开的KEY。这样就很好分，不一定确切就是这个叫法。

＝ 为什么登陆方式是跳转而非弹窗？ ＝

弹窗容易出现一些兼容问题出现，后来才改为跳转，好像也没什么坏处。

＝ 关闭游客评论的情况下怎么单独开放游客评论？ ＝

编辑任意文章或页面，添加自定义栏目“os_guestbook”，值为 1，该文章即支持游客评论；而且跟登陆评论并不矛盾。

== Screenshots ==

1. Sidebar
2. Widgets
3. Setting1: General Setting
4. Setting2: Account Setting
5. Setting3: Profile Option
6. Comment Form

== Changelog ==

= 1.5.0 =
* 个人用户名允许修改一次
* 简化帐号及小工具的设置选项
* 增加几个非常实用的短代码
* 支持文章单独开放游客评论
* 游客评论支持反垃圾正则过滤
* 切换语言功能移到个人资料页
* 优化了一些细节和样式及翻译

= 1.4.1 =
* 增加微信开放平台登陆（未有帐号）
* 优化了分享按钮的提示问题
* 优化了一些体验小问题

= 1.4.0 =
* 优化分享接口可以自动附加文章批量图片
* 针对新版插件系统添加一个漂亮的图标

= 1.3.2 =
* 针对国内环境提供了登陆接口的代理及反向代理的功能
* 优化了推特的登陆函数和头像功能
* 优化了远程访问的接口函数

= 1.3.1 =
* 新用户默认角色指定为订阅者
* 新增转换其他同类插件用户数据
* 脚本加载方式改为可配置并后置
* 评论中的链接和外链统一为新窗
* 修正一些小问题

= 1.3.0 =
* 新增 Twitter/Github 登陆
* 优化配置保存方式防止更新丢失
* 精简大量代码和删除无关功能
* 登陆及分享按钮可以配置是否启用
* 修正头像显示的一些问题
* 增强了请求函数的兼容性

= 1.2.0 =
* 新增 CSDN/OSChina/Facebook 登陆
* 登陆方式弃用弹窗彻底改为跳转更稳定
* 新增评论回复邮件通知功能并带总开关
* 完善用户个人资料页的配置和整合度
* 增加了几个实用扩展功能和开关选项
* 添加了顶部和评论两个滚动小按钮
* 优化代码和规范修正一些翻译小错误

= 1.1.5 =
* 登陆页面以设置的callback参数为准避免混淆问题
* 修正链接带#时登陆后未自动刷新的问题
* 修正tooltip对页面非插件元素的影响

= 1.1.4 =
* 修正QQ头像的问题

= 1.1.3 =
* 修正 iOS 登陆时不会跳转的问题

= 1.1.2 =
* 增加以小米帐号登陆
* 增加短代码[os_hide]，登陆用户可见

= 1.1.1 =
* 解决绑定功能逻辑不清晰的问题

= 1.1.0 =
* 解决豆瓣回调地址要完全匹配不能带参数的问题
* 支持评论需登陆设置下的登陆按钮的默认展示
* 登陆界面下通过开放帐号登陆可智能返回登陆前的页面
* 支持在个人资料页里绑定系统已注册的用户
* 哦，等等，上面这个功能原来一早已支持了的

= 1.0.9 =
* 解决更新后帐号配置被清空的问题

= 1.0.8 =
* 默认显示较清晰头像
* 分享按钮可添加在文章后
* 合并谷歌回调文件（旧文件可删）
* 修正登陆页面登陆问题
* 样式表和脚本放到图片目录下
* 规范了一下配置的变量名

= 1.0.7 =
* 修正头像函数调试模式下会出现警告的问题

= 1.0.6 =
* 更新了一下设定

= 1.0.5 =
* 增加参数设置、优化设置页面
* 增加入口，用户更容易修改邮箱
* 修正头像BUG，细节优化

= 1.0.4 =
* 增加语言切换
* 图片归类到一个目录
* 一些小修正

= 1.0.3 =
* 增加谷歌用户的头像

= 1.0.2 =
* 全新改版

= 1.0.1 =
* 增加多LIVE、豆瓣、人人网、开心网
* 精简大量代码

= 1.0.0 =
* 第一个版本
