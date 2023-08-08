<style>
.profile-wrapper * {
	box-sizing: border-box;
}
.profile-wrapper img {
max-width: 100%;
height: auto;	
}
	.profile-header {
	text-align: center;	
	font-size: 32px;
	}
	.profile-body {
	display: flex;	
	justify-content:space-between;
	width: 100%;
	padding-right: 15px;
	}
	.left-panel {
	flex: 0 0 33%;
	max-width:33%;	
	}
	.right-panel {
	flex: 0 0 65%;
	max-width:65%;	
	}
.left-panel, .right-panel {
	background: white;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
	box-shadow: 0 0 10px rgba(0,0,0,0.1);
	padding: 15px;
}
.left-panel {
text-align: center;	
}
</style>
<div class="profile-wrapper">
<div class="profile-header">
	<h2 >My Profile</h2>
</div>
 <div class="profile-body">
 	<div class="left-panel">
    	<img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="">
    </div>
    <div class="right-panel"></div>
 </div>   
</div>