<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8"/>
		<title>data</title>
		<style>
			h1 {text-align:center;}
			p { text-align:justify;}
			body {background-color:lightgrey}
		</style>
	</head>

	<body>

		<h1>Data Design Project </h1>

		<iframe width="540" height="290" src="https://www.youtube.com/embed/WPvGqX-TXP0" frameborder="0" allowfullscreen></iframe

		<br/>

		<h2>Persona and Demographics.</h2>

		<p>The major demographic of users who watch youtube tutorials on Java coding, are either learning how to code in java, or are using the video as reference material, while they are coding. Micah is one such user, he is 25, has a moderate understanding of coding, and is watching the video on his laptop using Chrome on a wifi connection. Micah would expect the type of youtube video he was looking for could be found using some type of search function. Micah would also need some type of rating system on youtube to ensure that he is watching a high quality video, and the information is correct. Finally, the most important feature Micah would want, is the ability to pause and jump around in the video to ensure he got the most information possible from the video.</p>

		<h3>User Case</h3>
		<h4>Micahs goal is to find a video on Java coding.</h4>
		<ol>
			<li>Micah goes to youtube.</li>
			<li>browser loads youtube home page.</li>
			<li>Micah searches for videos about Java.</li>
			<li>Youtube searches database for videos that are relevant to Java.</li>
			<li>Micah looks for a video relavent to what he was looking for, and clicks on the video.</li>
			<li>Youtube loads video in a new page. </li>
			<li>Micah checks comments to make sure video is accurate and presses play.</li>
			<li>Youtube begins streaming video and checks Micahs connection to decide what quality of video to stream. </li>
			<li>Micah pauses or fast forewords as needed.</li>
			<li>Youtubes video player registers Micahs actions and manipulates the video as needed.</li>
			<li>Micah finishes video and comments, upvotes the video because it was useful.</li>
			<li>Youtube adds comment to the comment thread and increases the number the upvotes on the video, which in theory would make the video easier to find for other users.</li>
		</ol>

		<h3>Control Schema</h3>

		<h4>Entity: Account.</h4>
		<h5> Atributes</h5>
		<ul>
			<li>Account id</li>
			<li>Profile name</li>
			<li>email</li>
			<li>Videos this person has uploaded.</li>
			<li>Profile Information</li>
		</ul>
		<h5>Relationship</h5>
		<p>The relationship between acount and video would be one to many. )</p>

		<h4>Entity: Video.</h4>
		<h5>Attributes</h5>
		<ul>
			<li>title</li>
			<li>watch count</li>
			<li>acount id</li>
			<li>resolution</li>
			<li>file type</li>
		</ul>
		<h5>Relationship</h5>
		<p>The relationship would be many to one. Each video can only be associated with one account. </p>
		<br/>
		<img src="img/youtubeDataDesign5.svg" alt="relationshipdata5"/>

		<h3>Code sample</h3>

		<pre>

			<code>DROP TABLE IF EXISTS youtubeVideo;
				DROP TABLE IF EXISTS youtubeAccount;

				CREATE TABLE youtubeAccount (
				accountId   INT UNSIGNED AUTO_INCREMENT NOT NULL,
				email       VARCHAR(128)                NOT NULL,
				accountName VARCHAR(32)                 NOT NULL,
				userInfo    VARCHAR(100),
				salt CHAR (64) NOT NULL,
				hash CHAR (128) NOT NULL,
				UNIQUE (email),
				UNIQUE (accountName),
				PRIMARY KEY (accountId)
				);

				CREATE TABLE youtubeVideo (
				youtubeVideoId INT UNSIGNED AUTO_INCREMENT NOT NULL,
				accountId      INT UNSIGNED                NOT NULL,
				videoTitle     VARCHAR(28)                 NOT NULL,
				fileFormatType VARCHAR(8)                  NOT NULL,
				resolution     CHAR(5)                     NOT NULL,
				videoPublishDate DATETIME                  NOT NULL,
				INDEX (accountId),
				FOREIGN KEY (accountId) REFERENCES youtubeAccount(accountId),
				PRIMARY KEY (youtubeVideoId)
				);
			</code>

		</pre>

	</body>

</html>