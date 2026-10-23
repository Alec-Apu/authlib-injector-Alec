# Alec 皮肤站当前部署源码

本分支保存从服务器导出的 Blessing Skin 6.0.2 定制部署文件，包括邀请注册、邀请码后台、自定义界面以及 Yggdrasil API 插件。原仓库的 authlib-injector Java 代码保留在 develop 分支。

## 文件范围

包含 PHP 应用、模板、已编译的前端静态资源和插件。此导出来自运行中的发布版，并非完整的前端开发仓库，没有原始前端工程和构建依赖清单。

不包含生产 .env、数据库、用户和皮肤数据、会话、缓存、日志、TLS 私钥及 vendor 依赖。此仓库不是服务器完整备份。

## 部署说明

1. 使用官方 Blessing Skin 6.0.2 发布包作为基础，保留其 vendor 依赖和运行目录。
2. 将本分支文件覆盖到发布包，保留 plugins 内的定制插件。
3. 根据 .env.example 创建自己的 .env，生成新 APP_KEY，配置数据库及 Web 服务。不要将生产凭据提交到 Git。
4. 邀请码管理依赖当前定制代码及对应数据表；迁移前检查 database/migrations 和邀请相关控制器，先在测试环境验证。
5. 当前运行环境为 PHP 8.3；生产 vendor 中曾对 Carbon 的 setLastErrors(false) 兼容做过调整，vendor 未包含在本导出中，部署时需验证该兼容性。

## 上游与许可证

- Blessing Skin: https://github.com/bs-community/blessing-skin-server
- Yggdrasil API 插件保留其包元数据及源码声明。
- 上游 README 和 LICENSE 保留在仓库中，分发时遵守对应许可证。
