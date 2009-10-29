<!DOCTYPE html>

<!--
/*
**  Admin
**
**  Created by Arthur Chafonov on 2009-05-07.
**  Copyright (c) 2009 Arthur Chafonov. All rights reserved.
*/
-->

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Language" content="" />
		
		<meta name="Robots" content="all" />
		<meta http-equiv="Content-Script-Type" content="text/javascript" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<meta name="Author" lang="en" content="Arthur Chafonov" />
		<meta name="Copyright" lang="en" content="&copy; 2009 Webstein" />
		<meta name="Date" content="2009-05-07" />
		
		<meta name="Keywords" lang="" content="" />
		<meta name="Description" lang="" content="" />
		
		<!-- Safe for kids -->
		<meta http-equiv="PICS-Label" content='(PICS-1.1 
		"http://www.weburbia.com/safe/ratings.htm" l r (s 0))' />
		
		<!-- Apple meta tags -->
		<meta name="Author-Corporate" content="Webstein" />
		<meta name="Author-Personal" content="Arthur Chafonov" />
		<meta name="Bookmark" content="untitled" />
		
		<!-- iPhone features -->
		<meta name="format-detection" content="telephone=yes" />
		<meta name="apple-mobile-web-app-capable" content="no" />
		
		
		<link rel="home" href="/" />
		<link rel="next" href="/la/" />
		<link rel="prev" href="/lo/" />
		<link rel="author" href="http://actuosus.ru/" />
		<link rel="copyright" href="http://webstein.ru/" />
		<link rel="help" href="/questions/" />
		
		<title>Админь</title>
		<style type="text/css" media="screen">
			body.login input {
				font-family: Helvetica;
				background: #fff url('/my/auth/auth_assets/en/rza/images/login.png') repeat-x scroll 0 -430px;
				border-top: 1px solid #707070;
				border-bottom: 1px solid #cecece;
				border-left: 1px solid #ababab;
				border-right: 1px solid #ababab;
			 	font-size: 13px;
			 	padding: 4px 3px 1px;
				-webkit-box-shadow: rgba(255,255,255,.65) 0px 1px 1px;

			}

			body.login input.title {
				font-size: 17px;
			    padding: 5px 3px 2px;
			}

			body.login input.small {
			/*	font-size: 11px;*/
				padding: 3px 3px 1px;
			}

			body.login.firefox input{
				margin-top: -2px;
				padding: 4px 3px 2px;
				border-bottom-width: 2px;
				border-right-width: 2px;
				border-left-width: 2px;
				border-top-width: 2px;
				-moz-border-top-colors: #f9f9f9 #707070 ;
				-moz-border-bottom-colors: #f9f9f9 #cecece ;
				-moz-border-left-colors: #f9f9f9 #ababab ;
				-moz-border-right-colors: #f9f9f9 #ababab ;
			}

			body.login input[disabled=disabled], body.login input.disabled {
			  color: #666 ! important;
				background-color: #f9f9f9;
				border-top: 1px solid #b1b1b1;
				border-bottom: 1px solid #e0e0e0;
				border-left: 1px solid #cecece;
				border-right: 1px solid #cecece;
				background-image: none;

			}

			body.login.firefox input[disabled=disabled], body.login.firefox input.disabled{
			  color: #bababa ! important;
				border-bottom-width: 2px;
				border-right-width: 2px;
				border-left-width: 2px;
				border-top-width: 2px;
				-moz-border-top-colors: #f9f9f9 #b1b1b1 ;
				-moz-border-bottom-colors: #f9f9f9 #e0e0e0 ;
				-moz-border-left-colors: #f9f9f9 #cecece ;
				-moz-border-right-colors: #f9f9f9 #cecece ;
			}

			body.login input:focus, body.login textarea:focus, body.login input.focus, body.login textarea.focus
			/*,
			body.contacts textarea:focus, body.contacts textarea.focus,
			body.calendar input:focus, body.calendar textarea:focus, body.calendar input.focus, body.calendar textarea.focus*/ {
				background-color: #fefeee;
				outline-color: #87929e;	
			}

			body.login.firefox input:focus, body.login.firefox textarea:focus
			{
			  outline-color: #ced2d5;
				outline-style: solid;
				outline-width: 1px;
				-moz-outline-radius: 2px;
				-moz-outline-offset: 0px;
				-moz-border-top-colors: #9ca5ae #7c8288 ;
				-moz-border-bottom-colors: #b8bec4 #a8aeb4 ;
				-moz-border-left-colors: #b8bec4 #989ea4;
				-moz-border-right-colors: #b8bec4 #989ea4 ;
			}

			body.login input.invalid, body.login input.invalid:focus, body.login input.invalid:focus{
				background-color: #fee5e5;	
			}

			body.login textarea{
					font-family: Helvetica;
					border-top: 1px solid #707070;
					border-bottom: 1px solid #cecece;
					border-left: 1px solid #ababab;
					border-right: 1px solid #ababab;

			/*		font-size: 13px;*/
					line-height: 18px;
					padding: 0px 0px 0px 1px;
					background:white url('') repeat-x scroll top left;
					-webkit-box-shadow: rgba(255,255,255,.65) 0px 1px 1px;

			}

			body.login textarea.small{
			/*	font-size: 11px;*/
				line-height: 15px;
			}

			body.login.firefox textarea {
				padding: 0px 0px 0px 3px ! important;
				border-bottom-width: 2px;
				border-right-width: 2px;
				border-left-width: 2px;
				border-top-width: 2px;
				-moz-border-top-colors: #f9f9f9 #707070 ;
				-moz-border-bottom-colors: #f9f9f9 #cecece ;
				-moz-border-left-colors: #f9f9f9 #ababab ;
				-moz-border-right-colors: #f9f9f9 #ababab ;
			}

			body.login textarea.disabled {
				color: #666;
				background-color: #f9f9f9;
				border-top: 1px solid #b1b1b1;
				border-bottom: 1px solid #e0e0e0;
				border-left: 1px solid #cecece;
				border-right: 1px solid #cecece;
				background-image: none;
			}

			body.login.firefox textarea.disabled{
					color: #bababa;
					border-bottom-width: 2px;
					border-right-width: 2px;
					border-left-width: 2px;
					border-top-width: 2px;
					-moz-border-top-colors: #f9f9f9 #b1b1b1 ;
					-moz-border-bottom-colors: #f9f9f9 #e0e0e0 ;
					-moz-border-left-colors: #f9f9f9 #cecece ;
					-moz-border-right-colors: #f9f9f9 #cecece ;
			}

			body.login .inline_editor_field  {
				-webkit-box-shadow: none;
			}

			/* =V2 */
			body.login .v2 input {
			  background: #fff url('/my/auth/loginForm/en/s4e/images/login-v2.png') 0 -296px repeat-x;
			}



			/* End ------------------------------------------------------- form_fields.css*/

			/* Start ----------------------------------------------------- login.css*/

			/* =INITIALIZE */
			html, body, form, fieldset {
			  margin: 0;
			  padding: 0;
			  font: 100%/120% Helvetica, Arial, sans-serif;
			  border: none;
			}
			h1, h2, h3, h4, h5, h6, p, pre,
			blockquote, ul, ol, dl, address {
			  margin: 0;
			  padding: 0;
			}

			/* =GENERAL */
			html {
				height: 100%;
			}
			body {
				background-color: rgb(73, 80, 85);
				font: 11px Helvetica, Arial, Sans-Serif;
				height: 100%;
				margin: 0;
			}
			#wrapper {
			  width: 100%;
			  height: 100%;
			}
			a:link,
			a:visited {
			  text-decoration: none;
			}
			a:focus,
			a:hover,
			a:active {
			  text-decoration: underline;
			}

			/* =CORNERS */
			#mmlogo,
			#applelogo,
			#copyright,
			#footer {
			  position: absolute;
			  z-index: 99;
			}
			#mmlogo {
			  top: 27px;
			  left: 29px;
			  width: 140px;
			  height: 30px;
			  background: url('/my/auth/auth_assets/en/rza/images/login.png') 0 0 no-repeat;
			  text-indent: -9999px;
			}
			#footer {
			  right: 35px;
			  bottom: 23px;
			  list-style: none;
			}
			#footer li {
			  display: block;
			  float: left;
			  margin-left: 17px;
			}
			#footer a:link,
			#footer a:visited {
			  color: #eee;
			  text-shadow: #000 0 1px 0;
			  text-shadow: rgba(0,0,0,.4) 0 1px 0;
			}
			#footer a:focus,
			#footer a:hover,
			#footer a:active {
			  color: #fff;
			}
			#copyright {
			  bottom: 23px;
			  left: 35px;
			  color: #95989b;
			  text-shadow: #000 0 1px 0;
			  text-shadow: rgba(0,0,0,.4) 0 1px 0;
			}

			#loading {
			/*  background: url('/my/auth/auth_assets/en/rza/images/spinner_16px_ffffff_50pct.gif') 0 0 no-repeat;*/
			  float: right;
			  height: 16px;
			  margin: 12px 0 0;
			  overflow: hidden;
			  text-indent: 32px;
			  width: 16px;
			}
			h1 .version {
				color:#797979;
				font-size:0.6em;
/*				text-shadow:none;*/
			}
			/* =DEFAULT */
			#wrapper {
			  background: transparent url('/admin/media/images/login/background.jpg') 50% 50% repeat-x scroll;
			  display: block;
			  height: 100%;
			  margin: 0px;
			  min-height: 592px;
			  min-width: 914px;
			  padding: 0px;
			  position: absolute;
			  width: 100%;
			}
			.container {
			  font-size: 13px;
			  display: none;
			  height: 100%;
			  position: absolute;
			  width: 100%;
			}
			.container div.inner {
			  left: 50%;
			  position: absolute;
			  top: 50%;
			}
			.container a:link,
			.container a:visited {
			  color: rgb(28, 79, 173);

			}
			.container input.text {
			  font-size: 13px;
			  position: relative;
			  width: 217px;
			  z-index: 2;
			}
			fieldset.upper {
			  padding-left: 65px;
			  position: relative;
			}

			/* =OWNER */
			#owner {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/all_in_one.jpg') 50% 50% no-repeat scroll;
			  display: block;
			  min-height: 592px;
			  min-width: 914px;
			  position: absolute;
			}
			#owner div.inner {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/center.png') 0 0 no-repeat;
			  height: 387px;
			  margin: -198px 0 0 -150px;
			  padding: 37px 40px;
			  width: 223px;

			  -webkit-transition: margin-left 0.05s ease-out;
			}
			#owner h2 {
			  font-size: 15px;
			  font-weight: bold;
			  line-height: 18px;
			  margin: 0;
			}
			#LoginForm div {
			  margin-top: 8px;
			}

			/* =LABELS */
			div.row {
			  position: relative;
			}
			label.placeholder span {
			  color: #808080;
			  font-size: 13px;
			  line-height: 16px;
			  opacity: 1;
			  padding: 5px;
			  position: absolute;
			  z-index: 5;
			  -webkit-transition: opacity 0.2s ease-out;
			}
			.firefox label.placeholder {
			  line-height: 12px;
			}
			label.placeholder.hidden span {
			  opacity: 0;
			  z-index: 0;
			}
			label.placeholder.partial span {
			  opacity: 0.42;
			}
			.ie label.placeholder.partial span {
			  color: #cacaca;
			  opacity: 1;
			}
			.ie label.placeholder.hidden {
			  visibility: hidden;
			}
			/* =CHECKBOX */
			.keeploggedin {
			  padding-top: 9px;
			  position: relative;
			}
			#keepLoggedIn {
			  position: absolute;
			  left: -9999px;
			}
			#keepLoggedInLabel {
			  padding: 1px 0 0 20px;
			  position: relative;
			}
			.firefox #keepLoggedInLabel {
			  padding: 2px 0 0 20px;
			}
			#keepLoggedInLabel span {
			  background: url('/my/auth/auth_assets/en/rza/images/login.png') -28px -36px no-repeat;
			  height: 16px;
			  left: -1px;
			  position: absolute;
			  top: 0;
			  width: 16px;
			}
			#keepLoggedInLabel.checked span { background-position: -44px -36px; }
			/* Focus */
			#keepLoggedInLabel.focus span { background-position: -67px -36px; }
			#keepLoggedInLabel.checked.focus span { background-position: -83px -36px; }
			/* Disabled */
			#keepLoggedInLabel.disabled span { background-position: -106px -36px; }
			#keepLoggedInLabel.checked.disabled span { background-position: -122px -36px; }


			/* =SMALLPANEL */
			.smallpanel form {
			  background: url('/my/auth/auth_assets/en/rza/images/login_multipanel.png') 0 0 no-repeat;
			  padding: 24px 17px 0;
			}
			.smallpanel div.buttons {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/login.png') 0 -480px repeat-x;
			  float: left;
			  margin-top: 30px;
			  padding-top: 5px;
			  width:100%;
			}
			.smallpanel h2 {
			  font-size: 15px;
			  line-height: 16px;
			  margin-bottom: 2px;
			}
			.smallpanel input.text {
			  width: 290px;
			}


			/* =REAUTHORIZE */
			.Reauthorize #owner { display: none; }
			.Reauthorize #owner_reauth {
			  background: url('/my/auth/auth_assets/en/rza/images/background_radial.jpg') 50% 50% no-repeat;
			  display: block;
			}
			.Reauthorize div.inner {
			  height: 200px;
			  margin: -128px 0 0 -209px;
			  width: 421px;
			}
			.Reauthorize div.inner p {
			  font-size: 13px;
			  line-height: 18px;
			  margin: 0 0 2px;
			}
			.Reauthorize div.bottom {
			  background: url('/my/auth/auth_assets/en/rza/images/login_multipanel.png') 0 100% no-repeat;
			  color: #9a9c9f;
			  padding: 18px 10px 75px;
			  text-align: center;
			}
			.Reauthorize div.bottom p {
			  font-size: 11px;
			  line-height: 15px;
			  text-shadow: rgba(0,0,0,.5) 0px 1px 0px;
			}
			.Reauthorize fieldset.upper {
			  background: url('/my/auth/auth_assets/en/rza/images/account_login_icon.png') 0 0 no-repeat;
			}

			/* =PUBLIC */
			.Public #owner { display: none; }
			.Public #owner_public {
			  background: url('/my/auth/auth_assets/en/rza/images/background_radial.jpg') 50% 50% no-repeat;
			  display: block;
			}
			.Public div.inner {
			  height: 200px;
			  margin: -120px 0 0 -210px;
			  width: 421px;
			}
			.Public div.bottom {
			  background: url('/my/auth/auth_assets/en/rza/images/login_multipanel.png') 0 100% no-repeat;
			  padding-top: 120px;
			}
			.Public fieldset.upper {
			  background: url('') 0 0 no-repeat;
			}



			/* =ERROR */
			#error {
				color: rgb(173, 0, 0);
				font-size: 11px;
				font-weight: bold;
				margin-left: -5px;
				position: absolute;
				top: 26px;
				width: 217px;
			}
			#errorString {
			  color: #ba0000;
			  font-size: 11px;
			  font-weight: bold;
			  opacity: 0;
			  position: absolute;
			  top: -8px;
			  -webkit-transition: opacity .42s ease-out;
			}
			#errorString.show {
			  opacity: 1;
			}

			/* =BUTTONS */
			a.button,
			a.button:link,
			a.button:visited {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/login.png') -121px -224px no-repeat scroll;
			  color: #000;
			  cursor: default;
			  float: right;
			  height: 28px;
			  line-height: 32px;
			  margin: 4px 4px 0;
			  padding-left: 19px;
			  text-shadow: rgb(206, 214, 221) 0 1px 0;
			  text-shadow: rgba(206, 214, 221, 0.65) 0 1px 0;
			  white-space: nowrap;
			}
			a.button strong,
			a.button span {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/login.png') right -56px no-repeat;
			  display: block;
			  font-weight: normal;
			  height: 28px;
			  min-width: 48px;
			  padding-right: 19px;
			  text-align: center;
			}
			a.button:hover,
			a.button:focus {
			  text-decoration: none;
			  outline: none;
			}
			a.button.disabled {
			  text-shadow: rgb(99, 102, 104) 0 1px 0;
			  text-shadow: rgba(206, 214, 221, 0.2) 0 1px 0;
			}
			a.button:active {
			  text-decoration: none;
			}



			/* =SUBMIT */
			#row-continue {
			  line-height: 33px;
			  position: relative;
			}
			a.submit,
			a.submit:link,
			a.submit:visited {
			  background-position: -121px -280px;
			  margin-top: 3px;
			}
			a.submit strong {
			  background-position: right -112px;
			}
			a.submit:focus {
			  background-position: -121px -364px;
			}
			a.submit:active {
			  background-position: -121px -308px;
			}
			a.submit:focus strong {
			  background-position: right -196px;
			}
			a.submit:active strong {
			  background-position: right -140px;
			}
			a.submit.disabled {
			  background-position: -121px -336px;
			}
			a.submit.disabled strong {
			  background-position: right -168px;
			}


			#notamember {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/login.png') 0 -480px repeat-x;
			  clear: both;
			  line-height: 18px;
			  margin-top: 10px;
			  padding-top: 15px;
			}
			#notamember strong {
			  display: block;
			}
			#readtheblog {
			  display: none;
			}

			/* =IE7 */
			        #owner_ie7 { display: none;  }
			.iewarn #owner_ie7 { display: block; }
			.iewarn #owner,
			.iewarn #page-body  { display: none;  }
			.iewarn #wrapper,
			.iewarn #wrapper.v2 {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/background_noshelf.jpg') 50% 50% repeat-x scroll;
			}
			.iewarn .corner,
			.iewarn #wrapper.v2 .footer {
			  display: none;
			}
			.ie #keepLoggedInLabel span {
				top: 6px;
			}

			#owner_ie7 {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/ie7_login_en.jpg') scroll no-repeat 50% 50%;
			}
			#owner_ie7 .content {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/mm_warning_int.png') 9px -4px no-repeat;
			  padding: 0 0 0 92px;
			}
			#owner_ie7 .inner {
			  height: 189px;
			  width: 499px;
			  margin-left: -249px;
			  margin-top: -122px;
			}
			#owner_ie7 .ie_header {
			  color: #262626;
			  font-size: 18px;
			  margin: 0 0 11px;
			}
			#owner_ie7 .text p {
			  color: #262626;
			  margin: 7px 0 8px;
			  font-size: 13px;
			}
			#owner_ie7 .buttons {
			  background: transparent url('/my/auth/auth_assets/en/rza/images/login_hr.png') 0 0 repeat-x;
			  margin-top: 15px;
			  padding-top: 18px;
			  position: relative;
			}
			#owner_ie7 .buttons .browser {
			  float: left;
			}
			#owner_ie7 .buttons .continue {
			  position: absolute;
			  top: 18px;
			  right: 0;
			}



			/* =MAIN-V2 */
			#wrapper.v2 {
			  background: #31373c url('/my/auth/loginForm/en/s4e/images/default_bg.jpg') 0 50% repeat-x;
			  min-width: 982px;
			}
			#wrapper.v2 #mmlogo.v2 {
			  background: url('/my/auth/loginForm/en/s4e/images/login-v2.png') 0 0 no-repeat;
			  height: 36px;
			  margin: 0 0 23px -3px;
			  position: static;
			  width: 175px;
			}
			#page-body {
			  background: url('/my/auth/loginForm/en/s4e/images/tiles_left.jpg') -55px 50% no-repeat;
			  color: #f3f3f3;
			  display: block;
			  height: 100%;
			  width: 100%;
			  position: static;
			  top: 0;
			  left: 0;
			}
			#page-body div.inner {
			  height: 426px;
			  left: 50%;
			  margin-top: -213px;
			  position: absolute;
			  text-shadow: #000 1px 1px 1px;
			  text-shadow: rgba(0,0,0, .4) 1px 1px 1px;
			  top: 50%;
			  width: 480px;
			}
			#page-body div.inner .placeholder {
			  text-shadow: none;
			}
			#page-body div.inner .placeholder span {
			  padding-top: 4px;
			}
			#page-body a:link,
			#page-body a:visited,
			#page-body a:focus {
			  color: #a7bad4;
			}
			#page-body a:hover,
			#page-body a:active {
			  text-decoration: underline;
			}
			#page-body h2 {
			  font: bold 15px/20px Helvetica, sans-serif;
			  margin-bottom: 3px;
			}
			#page-body div.row {
			  float: left;
			}
			#page-body div.row.username {
			  margin-left: -1px;
			}
			#page-body .row input.text,
			body.login.firefox #page-body input.text {
			  border: 1px solid #1e1f21;
			  height: 1.35em;
			  line-height: 17px;
			  margin: 0 6px 0 0;
			  width: 185px;
			  -webkit-box-shadow: rgba(255,255,255, 0.12) 0 1px 1px;
			}
			body.login.firefox #page-body input.text:focus {
			  -moz-outline: 2px solid #7b8aac;
			  -moz-outline-radius-bottomleft: 5px;
			  -moz-outline-radius-topleft: 5px;
			  -moz-outline-radius-bottomright: 5px;
			  -moz-outline-radius-topright: 5px;
			/*  -moz-outline-top-color: #3d4347;*/
			}
			body.login.ie #page-body input.text {
			  line-height: 17px;
			  padding: 1px 3px 4px 4px;
			}
			#page-body .row input.text.disabled {
			  opacity: .5;
			}
			#page-body #error {
			  color: #C80000;
			  font-size: 11px;
			  font-weight: bold;
			  position: absolute;
			  top: -26px;
			  width: 300px;
			  margin-left: 2px;
			  text-shadow: 0px 0px 3px rgba(0,0,0,.1);
			}
			#page-body #loading {
			  visibility: hidden;
			  background: url('/my/auth/loginForm/en/s4e/images/spinner_16px_31373c.gif') 0 0 no-repeat;
			}

			#page-body #loading.visible {
			  background: url('/my/auth/loginForm/en/s4e/images/spinner_16px_31373c.gif') 0 0 no-repeat;
			  float: none;
			  margin: 7px 0 0;
			  padding: 0;
			  position: absolute;
			  right: -19px;
			  text-indent: 32px;
			}
			#page-body .iforgot {
			  position: absolute;
			}
			#page-body #notamember {
			  background: none;
			  font-size: 11px;
			  font-weight: bold;
			  margin: 0;
			  padding: 0;
			}
			#page-body #notamember strong {
			  display: inline;
			}
			#page-body .keeploggedin,
			#page-body #iforgot {
			  font-size: 11px;
			  left: -3px;
			  margin-top: 8px;
			  padding: 0;
			  top: 33px;
			  position: absolute;
			}
			#page-body #iforgot {
			  left: 198px;
			}
			#page-body fieldset {
			  position: relative;
			  height: 82px;
			}
			#page-body #row-continue {
			  margin: 0;
			  position: absolute;
			  right: 0;
			  top: 5px;
			  width: 86px;
			}
			/* v2 button */
			#page-body #row-continue a.button {
			  background: url('/my/auth/loginForm/en/s4e/images/login-v2.png') -156px -176px no-repeat;
			  color: #000;
			  margin: 0;
			  padding: 0 0 0 10px;
			}
			.firefox #row-continue a.button {

			}
			#page-body #row-continue a.button:hover {
			  text-decoration: none;
			}
			#page-body #row-continue a.button strong {
			  background: url('/my/auth/loginForm/en/s4e/images/login-v2.png') 100% -56px no-repeat;
			  padding-right: 10px;
			  width: 66px;
			}
			#page-body #row-continue a.button:focus { background-position: -156px -206px; }
			#page-body #row-continue a.button:focus strong { background-position: 100% -86px; }
			#page-body #row-continue a.button:active { background-position: -156px -236px; }
			#page-body #row-continue a.button:active strong { background-position: 100% -116px; }
			#page-body #row-continue a.button.disabled { background-position: -156px -266px; }
			#page-body #row-continue a.button.disabled strong { background-position: 100% -146px; }

			/* v2 checkbox */
			#page-body #keepLoggedInLabel {
			  display: block;
			  padding: 0 0 0 18px;
			  width: 173px;
			}
			#page-body #keepLoggedInLabel span {
			  background: url('/my/auth/loginForm/en/s4e/images/login-v2.png') -83px -36px no-repeat;
			  height: 16px;
			  position: absolute;
			  top: -1px;
			  width: 16px;
			}
			/* Unchecked */
			#page-body #keepLoggedInLabel span { background-position: -83px -36px; }
			#page-body #keepLoggedInLabel.disabled span { background-position: -99px -36px; }
			#page-body #keepLoggedInLabel.active span { background-position: -115px -36px; }
			#page-body #keepLoggedInLabel.focus span { background-position: -131px -36px; }
			/* Checked */
			#page-body #keepLoggedInLabel.checked span { background-position: -19px -36px; }
			#page-body #keepLoggedInLabel.checked.disabled span { background-position: -35px -36px; }
			#page-body #keepLoggedInLabel.checked.active span { background-position: -51px -36px; }
			#page-body #keepLoggedInLabel.checked.focus span { background-position: -67px -36px; }


			/* MOTD container */
			#motd-container {
			  margin-top: 30px;
			  padding-top: 38px;
			}
			#motd-container h2 {
			  margin-bottom: 13px;
			}
			#motd-container h3 {
			  color: #f7f7f7;
			  font-size: 13px;
			}
			#motd-container #dateline {
			  color: ##e5e5e5;
			  font-size: 11px;
			  line-height: 15px;
			  margin: 2px 0 10px;
			}
			#motd-container table {
			  margin: -2px 0 0 -2px;
			}
			#motd-container table td {
			  padding: 0;
			  vertical-align: middle;
			}
			#motd-container .visitor img {
			  padding: 2px 9px 0 0;
			  vertical-align: bottom;
			}

			#motd-container .user {
			}
			#motd-container .user img {
			  float: left;
			  margin: 2px 14px 5em -2px;
			}
			#motd-container .user p {
			  line-height: 18px;
			}

			#wrapper.v2 .footer #applelogo {
			  width: 19px;
			  height: 18px;
			  background: url('/my/auth/loginForm/en/s4e/images/login-v2.png') 0 -36px no-repeat;
			}
			#wrapper.v2 .footer #applelogo a {
			  height: 18px;
			  width: 19px;
			}
			#wrapper.v2 .footer {
			  bottom: 20px;
			  height: 20px;
			  left: 20px;
			  line-height: 22px;
			  position: absolute;
			  vertical-align: baseline;
			  width: 80%;
			}
			
			body.login form.error input.text {
			  background-color: #fee5e5;
			}
		</style>
	</head>
	<body class="login">
		<div id="wrapper">
			<div id="page-body">
				<div class="inner">
					<h1><?php print Kohana::lang('admin.admin'); ?></h1>
					<form method="post">
						<div class="row">
							<!-- <label for="username">Пользуйся:</label> -->
							<input type="text" name="username" id="username" placeholder="Пользуйся" />
							<div class="clear"></div>
						</div>
						<div class="row">
							<!-- <label for="password">Пароль:</label> -->
							<?php if (!empty($error)): ?>
								<strong id="error"><?= $error ?></strong>
							<?php endif ?>
							<input type="password" name="password" id="password" placeholder="Пароль" />
							<div class="clear"></div>
						</div>
						<div class="row">
							<input type="submit" name="submit" id="submit" value="Входи" />
							<div class="clear"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>