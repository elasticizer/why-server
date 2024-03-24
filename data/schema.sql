PRAGMA FOREIGN_KEYS=OFF;
BEGIN TRANSACTION;

-- [x] Article
DROP TABLE IF EXISTS `Article`;
CREATE TABLE `Article` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` NVARCHAR(128) NOT NULL UNIQUE /* 識別碼 */,
	`Title` NVARCHAR(128) NOT NULL /* 標題 */,
	`Intro` TEXT NULL /* 簡介 */,
	`Content` TEXT NOT NULL /* 內容 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`WhenPublished` DATETIME NULL /* 發布時間 */,
	`AuthorSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 作者序號 */,
	`SeriesSN` INTEGER NULL REFERENCES `Series` (`SN`) /* 系列序號 */
) /* 文章 */;

-- [x] Category
DROP TABLE IF EXISTS `Category`;
CREATE TABLE `Category` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` VARCHAR(64) NOT NULL UNIQUE /* 識別碼 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Intro` TEXT NOT NULL /* 簡介 */,
	`Implicit` BOOLEAN NOT NULL DEFAULT 0 /* 隱含的 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`ParentSN` INTEGER NULL REFERENCES `Category` (`SN`) /* 父項序號 */,
	`CreatorSN` INTEGER NOT NULL REFERENCES `Staff` (`SN`) /* 建立者序號 */
) /* 分類 */;

-- [x] ArticleCategory
DROP TABLE IF EXISTS `ArticleCategory`;
CREATE TABLE `ArticleCategory` (
	`ArticleSN` INTEGER NOT NULL REFERENCES `Article` (`SN`) /* 文章序號  */,
	`CategorySN` INTEGER NOT NULL REFERENCES `Category` (`SN`) /* 分類序號 */,

	PRIMARY KEY (`ArticleSN`, `CategorySN`)
) /* 文章分類 */;

-- [x] Tag
DROP TABLE IF EXISTS `Tag`;
CREATE TABLE `Tag` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */
) /* 標籤 */;

-- [x] ArticleTag
DROP TABLE IF EXISTS `ArticleTag`;
CREATE TABLE `ArticleTag` (
	`ArticleSN` INTEGER NOT NULL REFERENCES `Article` (`SN`) /* 文章序號  */,
	`TagSN` INTEGER NOT NULL REFERENCES `Tag` (`SN`) /* 標籤序號 */,

	PRIMARY KEY (`ArticleSN`, `TagSN`)
) /* 文章標籤 */;

-- [x] Configuration
DROP TABLE IF EXISTS `Configuration`;
CREATE TABLE `Configuration` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Code` VARCHAR(64) NOT NULL UNIQUE /* 代號 */,
	`Value` TEXT NULL /* 值 */,
	`Name` TEXT NOT NULL /* 名稱 */,
	`Description` TEXT NULL /* 說明 */,
	`WidgetSN` INTEGER NULL REFERENCES `Widget` (`SN`) /* 控制項序號 */,
	`SetSN` INTEGER NULL REFERENCES `Set` (`SN`) /* 集合序號 */,
	`TabSN` INTEGER NULL REFERENCES `Tab` (`SN`) /* 頁籤序號 */
) /* 組態 */;

-- [x] Widget
DROP TABLE IF EXISTS `Widget`;
CREATE TABLE `Widget` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Type` CHAR(16) NOT NULL /* 類型 */
) /* 控制項類型 */;

-- [x] Set
DROP TABLE IF EXISTS `Set`;
CREATE TABLE `Set` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Name` TEXT NOT NULL /* 名稱 */,
	`Description` TEXT NULL /* 說明 */
) /* 集合 */;

-- [x] Tab
DROP TABLE IF EXISTS `Tab`;
CREATE TABLE `Tab` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Name` TEXT NOT NULL /* 名稱 */,
	`Description` TEXT NULL /* 說明 */
) /* 頁籤 */;

-- [x] Coupon
DROP TABLE IF EXISTS `Coupon`;
CREATE TABLE `Coupon` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` VARCHAR(64) NOT NULL UNIQUE /* 識別碼 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Description` TEXT NOT NULL /* 說明 */,
	`DiscountRate` INTEGER NOT NULL CHECK (`DiscountRate` BETWEEN 0 AND 100) /* 折扣比率 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenIssued` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 發行時間 */,
	`WhenStarted` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 開始時間 */,
	`WhenEnded` DATETIME NOT NULL /* 結束時間 */,
	`CreatorSN` INTEGER NOT NULL REFERENCES `Staff` (`SN`) /* 建立者序號 */
) /* 優惠券 */;

-- [x] Course
DROP TABLE IF EXISTS `Course`;
CREATE TABLE `Course` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` VARCHAR(64) NOT NULL UNIQUE /* 識別碼 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Intro` TEXT NOT NULL /* 簡介 */,
	`Syllabus` TEXT NOT NULL /* 課綱 */,
	`Price` INTEGER NOT NULL CHECK (`Price` >= 0) /* 價格 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenApproved` DATETIME NULL /* 核准時間 */,
	`WhenLaunched` DATETIME NULL /* 上市時間 */,
	`TeacherSN` INTEGER NULL REFERENCES `User` (`SN`) /* 教師序號 */,
	`DomainSN` INTEGER NOT NULL DEFAULT 0 REFERENCES `Domain` (`SN`) /* 領域序號 */,
	`ThumbnailSN` INTEGER NOT NULL REFERENCES `File` (`SN`) /* 縮圖序號 */,
	`DailySN` INTEGER NOT NULL REFERENCES `File` (`SN`) /* 樣片序號 */,
	`ApproverSN` INTEGER NULL REFERENCES `Staff` (`SN`) /* 核准者序號 */
) /* 課程 */;

-- [x] Chapter
DROP TABLE IF EXISTS `Chapter`;
CREATE TABLE `Chapter` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Title` NVARCHAR(128) NOT NULL /* 標題 */,
	`Intro` TEXT NOT NULL /* 簡介 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */
) /* 章節 */;

-- [x] Lesson
DROP TABLE IF EXISTS `Lesson`;
CREATE TABLE `Lesson` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Title` NVARCHAR(128) NOT NULL /* 標題 */,
	`Intro` TEXT NOT NULL /* 簡介 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`ChapterSN` INTEGER NOT NULL REFERENCES `Chapter` (`SN`) /* 章節序號 */,
	`VideoSN` INTEGER NOT NULL REFERENCES `File` (`SN`) /* 影片序號 */,

	UNIQUE (`ChapterSN`, `VideoSN`)
) /* 單元 */;

-- [x] Review
DROP TABLE IF EXISTS `Review`;
CREATE TABLE `Review` (
	`Rate` INTEGER NOT NULL CHECK (`Rate` BETWEEN 1 AND 5) /* 評分 */,
	`Summary` TEXT NULL /* 摘要 */,
	`Content` TEXT NOT NULL /* 內容 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,
	`LearnerSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 學員序號 */,

	PRIMARY KEY (`CourseSN`, `LearnerSN`)
) /* 評論 */;

-- [ ] Discussion
DROP TABLE IF EXISTS `Discussion`;
CREATE TABLE `Discussion` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Title` NVARCHAR(128) NOT NULL /* 標題 */,
	`Content` TEXT NOT NULL /* 內容 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`WhenDeleted` DATETIME NULL /* 刪除時間 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,
	`AuthorSN` INTEGER NULL REFERENCES `User` (`SN`) /* 作者序號 */,
	`SolutionSN` INTEGER NULL REFERENCES `DiscussionComment` (`SN`) /* 解決方案序號 */
) /* 討論 */;

-- [ ] Comment
DROP TABLE IF EXISTS `Comment`;
CREATE TABLE `Comment` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Content` TEXT NOT NULL /* 內容 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`WhenDeleted` DATETIME NULL /* 刪除時間 */,
	`DiscussionSN` INTEGER NOT NULL REFERENCES `Discussion` (`SN`) /* 討論序號 */,
	`AuthorSN` INTEGER NULL REFERENCES `User` (`SN`) /* 作者序號 */,
	`ParentSN` INTEGER NULL REFERENCES `Comment` (`SN`) /* 父項序號 */
) /* 留言 */;

-- [ ] Domain
DROP TABLE IF EXISTS `Domain`;
CREATE TABLE `Domain` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` VARCHAR(64) NOT NULL UNIQUE /* 識別碼 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Intro` TEXT NOT NULL /* 簡介 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`ParentSN` INTEGER NULL REFERENCES `Domain` (`SN`) /* 父項序號 */,
	`CreatorSN` INTEGER NOT NULL REFERENCES `Staff` (`SN`) /* 建立者序號 */
) /* 領域 */;

-- [ ] File
DROP TABLE IF EXISTS `File`;
CREATE TABLE `File` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Filename` NVARCHAR(128) NOT NULL /* 檔名 */,
	`Extension` VARCHAR(64) NOT NULL /* 副檔名 */,
	`ContentType` VARCHAR(64) NOT NULL /* 內容類型 */,
	`ContentHash` CHAR(64) NOT NULL UNIQUE /* 內容雜湊值 */,
	`WhenUploaded` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 上傳時間 */,
	`WhenDeleted` DATETIME NULL /* 刪除時間 */,
	`UploaderSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 上傳者序號 */
) /* 檔案 */;

-- [ ] Group
DROP TABLE IF EXISTS `Group`;
CREATE TABLE `Group` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` VARCHAR(64) NOT NULL UNIQUE /* 識別碼 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Description` TEXT NULL /* 說明 */,
	`Implicit` BOOLEAN NOT NULL DEFAULT 0 /* 隱含的 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`CreatorSN` INTEGER NOT NULL REFERENCES `Staff` (`SN`) /* 建立者序號 */
) /* 群組 */;

-- [ ] GroupPermission
DROP TABLE IF EXISTS `GroupPermission`;
CREATE TABLE `GroupPermission` (
	`GroupSN` INTEGER NOT NULL REFERENCES `Group` (`SN`) /* 群組序號 */,
	`PermissionSN` INTEGER NOT NULL REFERENCES `Permission` (`SN`) /* 許可權序號 */,

	PRIMARY KEY (`GroupSN`, `PermissionSN`)
) /* 群組許可權 */;

-- [ ] Homework
DROP TABLE IF EXISTS `Homework`;
CREATE TABLE `Homework` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Content` TEXT NULL /* 內容 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`WhenAssigned` DATETIME NULL /* 指派時間 */,
	`WhenDeleted` DATETIME NULL /* 刪除時間 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,
	`TeacherSN` INTEGER NULL REFERENCES `User` (`SN`) /* 教師序號 */
) /* 作業 */;

-- [ ] UserCoupon
DROP TABLE IF EXISTS `UserCoupon`;
CREATE TABLE `UserCoupon` (
	`UserSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 學員序號 */,
	`CouponSN` INTEGER NOT NULL REFERENCES `Coupon` (`SN`) /* 優惠券序號 */,

	PRIMARY KEY (`UserSN`, `CouponSN`)
) /* 學員優惠券 */;

-- [ ] UserCourse
DROP TABLE IF EXISTS `UserCourse`;
CREATE TABLE `UserCourse` (
	`UserSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 學員序號 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,

	PRIMARY KEY (`UserSN`, `CourseSN`)
) /* 學員課程 */;

-- [ ] LearnerHomework
DROP TABLE IF EXISTS `LearnerHomework`;
CREATE TABLE `LearnerHomework` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Content` TEXT NOT NULL /* 內容 */,
	`Pinned` BOOLEAN NOT NULL DEFAULT '0' /* 釘選的 */,
	`Featured` BOOLEAN NOT NULL DEFAULT '0' /* 精選的 */,
	`Feedback` TEXT NULL /* 評語 */,
	`WhenTurnedIn` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 繳交時間 */,
	`WhenFedBack` DATETIME NULL /* 繳交時間 */,
	`LearnerSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 學員序號 */,
	`HomeworkSN` INTEGER NOT NULL REFERENCES `Homework` (`SN`) /* 作業序號 */,
	`TeacherSN` INTEGER NULL REFERENCES `User` (`SN`) /* 教師序號 */,

	UNIQUE (`LearnerSN`, `HomeworkSN`)
) /* 學員作業 */;

-- [ ] LearnerHomeworkAttchment
DROP TABLE IF EXISTS `LearnerHomeworkAttchment`;
CREATE TABLE `LearnerHomeworkAttchment` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Content` TEXT NOT NULL /* 內容 */,
	`LearnerHomeworkSN` INTEGER NOT NULL REFERENCES `LearnerHomework` (`SN`) /* 學員作業序號 */,
	`AttchmentSN` INTEGER NOT NULL REFERENCES `File` (`SN`) /* 附件序號 */
) /* 學員作業附件 */;

-- [ ] Order
DROP TABLE IF EXISTS `Order`;
CREATE TABLE `Order` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`WhenCheckedOut` DATETIME NULL DEFAULT CURRENT_TIMESTAMP /* 結帳時間 */,
	`WhenPaid` DATETIME NULL DEFAULT CURRENT_TIMESTAMP /* 付款時間 */,
	`LearnerSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 學員序號 */,
	`CouponSN` INTEGER NOT NULL REFERENCES `Coupon` (`SN`) /* 優惠券序號 */
) /* 訂單 */;

-- [ ] OrderDetail
DROP TABLE IF EXISTS `OrderDetail`;
CREATE TABLE `OrderDetail` (
	`Subtotal` INTEGER NOT NULL CHECK (`Subtotal` >= 0) /* 小計 */,
	`OrderSN` INTEGER NOT NULL REFERENCES `Order` (`SN`) /* 訂單序號 */,
	`LearnerSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 學員序號 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,

	PRIMARY KEY (`OrderSN`, `LearnerSN`, `CourseSN`)
) /* 訂單細項 */;

-- [ ] Permission
DROP TABLE IF EXISTS `Permission`;
CREATE TABLE `Permission` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Code` VARCHAR(64) NOT NULL UNIQUE /* 代號 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Description` TEXT NULL /* 說明 */
) /* 許可權 */;

-- [ ] Promotion
DROP TABLE IF EXISTS `Promotion`;
CREATE TABLE `Promotion` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Description` TEXT NOT NULL /* 說明 */,
	`DiscountRate` INTEGER NOT NULL CHECK (`DiscountRate` BETWEEN 0 AND 100) /* 折扣比率 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenIssued` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 發行時間 */,
	`WhenStarted` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 開始時間 */,
	`WhenEnded` DATETIME NOT NULL /* 結束時間 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,
	`CreatorSN` INTEGER NOT NULL REFERENCES `Staff` (`SN`) /* 建立者序號 */
) /* 促銷 */;

-- [ ] Series
DROP TABLE IF EXISTS `Series`;
CREATE TABLE `Series` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Identifier` NVARCHAR(128) NOT NULL UNIQUE /* 識別碼 */,
	`Name` NVARCHAR(128) NOT NULL /* 名稱 */,
	`Intro` TEXT NULL /* 簡介 */,
	`WhenCreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 建立時間 */,
	`WhenLastEdited` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 最後編輯時間 */,
	`WhenPublished` DATETIME NULL /* 發布時間 */,
	`AuthorSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 作者序號 */
) /* 系列 */;

-- [ ] Staff
DROP TABLE IF EXISTS `Staff`;
CREATE TABLE `Staff` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Username` VARCHAR(64) NOT NULL UNIQUE /* 使用者名稱 */,
	`E-mail` TEXT NOT NULL UNIQUE /* 電子信箱地址 */,
	`PasswordHash` TEXT NULL /* 密碼雜湊值 */,
	`FirstName` NVARCHAR(128) NOT NULL /* 名字 */,
	`LastName` NVARCHAR(128) NOT NULL /* 姓氏 */,
	`Phone` VARCHAR(64) NULL /* 電話號碼 */,
	`WhenRegistered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 註冊時間 */,
	`WhenDeactivated` DATETIME NULL /* 停用時間 */,
	`CreatorSN` INTEGER NULL REFERENCES `Staff` (`SN`) /* 建立者序號 */
) /* 職員 */;

-- [ ] StaffGroup
DROP TABLE IF EXISTS `StaffGroup`;
CREATE TABLE `StaffGroup` (
	`StaffSN` INTEGER NOT NULL REFERENCES `Staff` (`SN`) /* 職員序號 */,
	`GroupSN` INTEGER NOT NULL REFERENCES `Group` (`SN`) /* 群組序號 */,

	PRIMARY KEY (`StaffSN`, `GroupSN`)
) /* 職員群組 */;

-- [ ] User
DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
	`SN` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT /* 序號 */,
	`Username` VARCHAR(64) NOT NULL UNIQUE /* 使用者名稱 */,
	`E-mail` TEXT NOT NULL UNIQUE /* 電子信箱地址 */,
	`PasswordHash` TEXT NULL /* 密碼雜湊值 */,
	`FirstName` NVARCHAR(128) NOT NULL /* 名字 */,
	`LastName` NVARCHAR(128) NOT NULL /* 姓氏 */,
	`Nickname` NVARCHAR(128) NULL /* 暱稱 */,
	`Phone` VARCHAR(64) NULL /* 電話號碼 */,
	`JobTitle` VARCHAR(64) NULL /* 職稱 */,
	`Intro` TEXT NULL /* 簡介 */,
	`Resume` TEXT NULL /* 簡歷 */,
	`Birthday` DATETIME NULL /* 出生日期 */,
	`WhenRegistered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 註冊時間 */,
	`WhenDeactivated` DATETIME NULL /* 停用時間 */,
	`WhenQualified` DATETIME NULL /* 合格時間 */,
	`PFPSN` INTEGER NULL REFERENCES `File` (`SN`) /* 個人資料圖片序號 */,
	`ApproverSN` INTEGER NULL REFERENCES `Staff` (`SN`) /* 核准者序號 */
) /* 使用者 */;

-- [ ] Cart
DROP TABLE IF EXISTS `Cart`;
CREATE TABLE `Cart` (
	`WhenAdded` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP /* 加入時間 */,
	`UserSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 使用者序號 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,

	PRIMARY KEY (`UserSN`, `CourseSN`)
) /* 購物車 */;

-- [ ] CollectedArticle
DROP TABLE IF EXISTS `CollectedArticle`;
CREATE TABLE `CollectedArticle` (
	`UserSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 使用者序號 */,
	`ArticleSN` INTEGER NOT NULL REFERENCES `Article` (`SN`) /* 文章序號 */,

	PRIMARY KEY (`UserSN`, `ArticleSN`)
) /* 收藏文章 */;

-- [ ] CollectedCourse
DROP TABLE IF EXISTS `CollectedCourse`;
CREATE TABLE `CollectedCourse` (
	`UserSN` INTEGER NOT NULL REFERENCES `User` (`SN`) /* 使用者序號 */,
	`CourseSN` INTEGER NOT NULL REFERENCES `Course` (`SN`) /* 課程序號 */,

	PRIMARY KEY (`UserSN`, `CourseSN`)
) /* 收藏課程 */;

-- [ ] StaffPermission
DROP VIEW IF EXISTS `StaffPermission`;
CREATE VIEW `StaffPermission` AS
	SELECT `S`.`SN`, `P`.`Code`
	FROM `Staff` `S`
	JOIN `StaffGroup` `SG` ON `S`.`SN` = `SG`.`StaffSN`
	JOIN `Group` `G` ON `G`.`SN` = `SG`.`GroupSN`
	JOIN `GroupPermission` `GP` ON `G`.`SN` = `GP`.`GroupSN`
	JOIN `Permission` `P` ON `P`.`SN` = `GP`.`PermissionSN`
	WHERE `S`.`WhenDeactivated` IS NULL;

INSERT INTO `Group` (`SN`, `Identifier`, `Name`, `Implicit`, `CreatorSN`) VALUES
	(0, 'webmaster', '網站管理員', 1, 0),
	(-1, 'developer', '開發人員', 1, 0);

INSERT INTO `Permission` (`Code`, `Name`, `Description`) VALUES
	('ARTICLE_CREATE', '建立文章', ''),
	('ARTICLE_DELETE', '刪除文章', ''),
	('ARTICLE_EDIT', '編輯文章', ''),
	('ARTICLE_VIEW', '檢視文章', ''),
	('CATEGORY_CREATE', '建立分類', ''),
	('CATEGORY_DELETE', '刪除分類', ''),
	('CATEGORY_EDIT', '編輯分類', ''),
	('CATEGORY_VIEW', '檢視分類', ''),
	('COUPON_CREATE', '建立優惠券', ''),
	('COUPON_DELETE', '刪除優惠券', ''),
	('COUPON_EDIT', '編輯優惠券', ''),
	('COUPON_VIEW', '檢視優惠券', ''),
	('COURSE_CREATE', '建立課程', ''),
	('COURSE_DELETE', '刪除課程', ''),
	('COURSE_EDIT', '編輯課程', ''),
	('COURSE_VIEW', '檢視課程', ''),
	('DOMAIN_CREATE', '建立領域', ''),
	('DOMAIN_DELETE', '刪除領域', ''),
	('DOAMIN_EDIT', '編輯領域', ''),
	('DOAMIN_VIEW', '檢視領域', ''),
	('GROUP_CREATE', '建立群組', ''),
	('GROUP_DELETE', '刪除群組', ''),
	('GROUP_EDIT', '編輯群組', ''),
	('GROUP_VIEW', '檢視群組', ''),
	('STAFF_CREATE', '建立職員', ''),
	('STAFF_DELETE', '刪除職員', ''),
	('STAFF_EDIT', '編輯職員', ''),
	('STAFF_VIEW', '檢視職員', '');

INSERT INTO `Staff` (`SN`, `FirstName`, `LastName`, `Username`, `E-mail`, `WhenDeactivated`, `CreatorSN`) VALUES
	(0, '', '', 'webmaster', 'webmaster@localhost', CURRENT_TIMESTAMP, NULL),
	(-1, 'Jackall', 'Chien', 'dev.04', '04@localhost', NULL, 0),
	(-2, 'Poyuan', 'Fang', 'dev.05', '05@localhost', NULL, 0),
	(-3, 'Peiru', 'Lin', 'dev.09', '09@localhost', NULL, 0),
	(-4, 'Tingchun', 'Lin', 'dev.10', '10@localhost', NULL, 0),
	(-5, 'Felix', 'Chang', 'dev.30', '30@localhost', NULL, 0),
	(-6, 'Yu', 'Shiao', 'dev.31', '31@localhost', NULL, 0);

INSERT INTO `Widget` (`SN`, `Type`) VALUES
	(0, 'text'),
	(NULL, 'button'),
	(NULL, 'checkbox'),
	(NULL, 'color'),
	(NULL, 'date'),
	(NULL, 'datetime-local'),
	(NULL, 'email'),
	(NULL, 'file'),
	(NULL, 'hidden'),
	(NULL, 'image'),
	(NULL, 'month'),
	(NULL, 'number'),
	(NULL, 'password'),
	(NULL, 'radio'),
	(NULL, 'range'),
	(NULL, 'reset'),
	(NULL, 'search'),
	(NULL, 'submit'),
	(NULL, 'tel'),
	(NULL, 'time'),
	(NULL, 'url'),
	(NULL, 'week');

INSERT INTO `GroupPermission` (`GroupSN`, `PermissionSN`)
	SELECT 0, `SN` FROM `Permission` UNION SELECT -1, `SN` FROM `Permission`;

INSERT INTO `StaffGroup` (`StaffSN`, `GroupSN`)
	SELECT 0, 0 UNION SELECT `SN`, -1 FROM `Staff` WHERE `WhenDeactivated` IS NULL;

COMMIT;
