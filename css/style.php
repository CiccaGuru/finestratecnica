<?php
    header("Content-type: text/css; charset: UTF-8");
    include "../include/funzioni.php";
    if($db=database_connect()){
      $primaryColor = getProp("primaryColor");
      $primaryColorDark = getProp("primaryColorDark");
      $primaryColorDarkest = getProp("primaryColorDarkest");
      $primaryColorLight = getProp("primaryColorLight");
      $accentColor = getProp("accentColor");
      $accentColorLight = getProp("accentColorLight");
      $primaryText = getProp("primaryText");
      $primaryDarkText = getProp("primaryDarkText");
      $primaryLightText = getProp("primaryLightText");
      $accentText = getProp("accentText");
    }
    else{
      $primaryColor = "#2196f3";
      $primaryColorDark = "#1976d2";
      $primaryColorDarkest = "#1565c0";
      $primaryColorLight = "#64b5f6";
      $accentColor = "#f44336";
      $accentColorLight = "#e57373";
      $primaryText = "#fafafa";
      $primaryDarkText = "#fafafa";
      $primaryLightText = "#fafafa";
      $accentText = "#fafafa";
    }
?>

@.icon-block {
    padding: 0 15px;
}

@font-face {
  font-family: "Roboto-Condensed";
  src: url("../fonts/roboto/RobotoCondensed-Light.ttf");
  font-weight: 300;
}

@font-face {
  font-family: "Roboto-Condensed";
  src: url("../fonts/roboto/RobotoCondensed-Regular.ttf");
  font-weight: 400;
}


.primary-light{
  background-color: <?php echo $primaryColorLight; ?> !important;
  color: <?php echo $primaryLightText; ?> !important;
}

.primary{
  background-color: <?php echo $primaryColor; ?> !important;
  color: <?php echo $primaryText; ?> !important;
}

.primary-dark{
  background-color: <?php echo $primaryColorDark; ?>  !important;
  color: <?php echo $primaryDarkText; ?> !important;
}

.accent{
  background-color: <?php echo $accentColor; ?> !important;
  color: <?php echo $accentText; ?> !important;
}

.primary-text{
  color: <?php echo $primaryColor; ?> !important;
}

.primary-dark-text{
  color: <?php echo $primaryColorDark; ?> !important;
}

.primary-darkest-text{
  color: <?php echo $primaryColorDarkest; ?> !important;
}

.accent-text{
  color: <?php echo $accentColor; ?> !important;
}

.spinner-primary-only {
  border-color: <?php echo $primaryColor;?>;
}

.spinner-accent-only {
  border-color: <?php echo $accentColor;?>;
}


.switch label input[type=checkbox]:checked + .lever {
  background-color: <?php echo $accentColorLight; ?>;
}

.switch label input[type=checkbox]:checked + .lever:after {
  background-color: <?php echo $accentColor; ?>;
}

[type="checkbox"]:checked + label:before {
  border-right: 2px solid <?php echo $accentColor;?>;
  border-bottom: 2px solid <?php echo $accentColor;?>;
}

.multiple-select-dropdown li.disabled label{
  display:none;
}
.icon-block .material-icons {
    font-size: inherit;
}

.input-field label {
    color: #000;
}

.cont-dett [type="checkbox"]:checked + label:before {
	  border-right: 2px solid <?php echo $primaryColor;?>;
	  border-bottom: 2px solid <?php echo $primaryColor;?>;
}

.picker__button--today:hover,
.picker__button--clear:hover,
.picker__button--close:hover {
  color: <?php echo $primaryLightText;?>;
  background: <?php echo $primaryColorLight;?>;
  border-bottom-color: <?php echo $primaryColorLight;?>;
}

.picker__date-display {
  color: <?php echo $primaryText;?>;
  background-color: <?php echo $primaryColor;?>;
}

.picker__nav--prev:hover,
.picker__nav--next:hover,
  button.picker__today:focus, button.picker__clear:focus, button.picker__close:focus,
  .picker__button--today:focus,
  .picker__button--clear:focus,
  .picker__button--close:focus {
  background: <?php echo $primaryColorLight;?>;
}

.picker__weekday-display {
  color: <?php echo $primaryDarkText;?>;
  background-color: <?php echo $primaryColorDark;?>;
}

.picker__day.picker__day--today,
  .picker__close, .picker__today {
  color: <?php echo $primaryColorDark;?>;
}

.picker__day--selected,
.picker__day--selected:hover,
.picker--focused .picker__day--selected {
    background-color: <?php echo $primaryColor;?>;
  }

.pagination li.active {
  background-color: <?php echo $accentColor?> !important;
}


/* label focus color */
.input-field input[type=text]:focus + label,
.input-field input[type=password]:focus + label {
    color: <?php echo $primaryColorDark ?>;
}
/* label underline focus color */
.input-field input[type=text]:focus,
.input-field input[type=password]:focus {
    border-bottom: 1px solid <?php echo $primaryColorDark ?>;
    box-shadow: 0 1px 0 0 <?php echo $primaryColorDark ?>;
}
/* valid color */
.input-field input[type=text].valid
.input-field input[type=password].valid {
    border-bottom: 1px solid <?php echo $primaryColorDark ?>;
    box-shadow: 0 1px 0 0 <?php echo $primaryColorDark ?>;
}
/* invalid color */
.input-field input[type=text].invalid
.input-field input[type=password].invalid  {
    border-bottom: 1px solid <?php echo $primaryColorDark ?>;
    box-shadow: 0 1px 0 0 <?php echo $primaryColorDark ?>;
}
/* icon prefix focus color */
.input-field .prefix.active {
    color: <?php echo $primaryColorDark; ?>;
}

a, .dropdown-content li > span {
  color: <?php echo $primaryColorDark;?>;
}

body{
    background: #f5f5f5;
    position:relative;
    z-index:0;
    overflow-x: hidden;
  	overflow-y: auto;
}

html{
    height:100%;
}

.bold{
	font-weight:600;
}

table{
	table-layout:fixed;
	background-color: white;
}

.select-dropdown, a{
	z-index:0 !important;
}

.dropdown-content{
	z-index:999 !important;
}

input[type="radio"]:checked+label:after{
	border-color: <?php echo $accentColor; ?>;
	background-color: <?php echo $accentColor; ?>;
}

tr td:first-child,
thead th:first-child{
	width:2.5em;
}

th,td{
  border-radius: 0px;
}

.italic{
  font-style:italic;
}

.underline{
   text-decoration: underline;
}

.h-5em{
  height:5em;
}


.nomargin{
  margin:0px;
}

.modal.bottom-sheet{
  max-height: 80%;
}
.fake-button{
  width:95%;
  text-align:center;
  height:auto;
  padding:1em;
  border-radius: 2px; !important;
}

.row-margin{
  margin-bottom:1em;
}

.fake-button i{
  margin-bottom:0.5em;
  font-size:36px;
}

.littlemargin{
  margin:5px;
}

.fake-button .row{
  margin:0px !important;
}

[type="checkbox"].filled-in:checked + label:after {
  top: 0;
  width: 20px;
  height: 20px;
  border: 2px solid <?php echo $accentColor ?>;
  background-color: <?php echo $accentColor ?>;
  z-index: 0;
}

[type="checkbox"].filled-in.tabbed:checked:focus + label:after {
  border-radius: 2px;
  background-color: <?php echo $accentColor ?>;
  border-color: <?php echo $accentColor ?>;
}


/*-----------------------------LOGIN-----------------------*/

#login, #infos{
  margin-top:21%;
  height:50%;
  display:block;
    animation:background, 0.4s;
}

#titoloLogin{
  width:100%;
  padding:1em;
  font-size:200%;
}

#login .card-content{
	width:95%;
}

#login .card-title{
    font-size:2em;
}

#help{
    margin-top:-2em;
    position:absolute;
    left:80%;
}

#back,
.modal-primoAccesso-trigger,
#infos,
hide
{
    display:none;
}

#login.animateLogin{
    animation: round 0.65s linear;
    -webkit-animation-fill-mode: forwards;
}

#login{
  padding-bottom:1.5em;
}


.small{
  font-size:75%;
}
#ok{
    display:none;
    z-index:100;
    position:absolute;
}

footer{
  padding:0.1em;
  position:fixed;
  bottom: 0px;
  left:0;
  width:100%;
}

.cont-dett{
	margin:2em;
}

.cont-dett .row{
	margin-bottom:0.5em;
}

.collapsible-header{
    transition: all 0.4s ease-out;
    border-bottom:0px;
}

#wait{
	z-index:10000000;
	background: rgba(0,0,0,0.75);
	width:100%;
	height:100%;
	position:fixed;
	left:0;
	top:0;
	display:none;
}

#index{
	overflow: hidden;
  height:100%;
}

#install{
  height:100%;
}

#contenitore-cerchio{
	margin-top:20%;
}

#contenitore-cerchio-admin{
	margin-top:5%;
}

#errore-login, #messaggio{
	font-weight:300;
	font-size:200%;
}

#messaggio{
  margin-top:15%;
}

#card-orario-gen{
	padding-top:1em;
	padding-bottom:1em;
  position:fixed;
}

.fixed-action-btn{
  bottom: 45px;
  right: 24px;
}

.modal-content h1{
  margin-bottom:0.3em;
}

.modal-content h4{
  margin-top:0px;
}


.all-width{
  width:100%;
}

.margin0{
  margin-bottom:0px;
}

.chip{
  z-index:1000;
}

.collapsible-body > div.center {
  width:100%;
  margin:1em;
}

.collapsible{
  margin-top:1em;
  margin-bottom:3em;
}

.pointerCursor{
  cursor:pointer;
}

.chipMio{
  font-family:"Roboto-Condensed";
  letter-spacing:1px;
  display: inline-block;
  height: 26px;
  font-size: 13px;
  font-weight: 500;
  line-height: 26px;
  padding: 0 10px;
  border-radius: 2px;
}

@media only screen and (max-width: 600px){
  .chipMio{
    padding:0px !important;
    letter-spacing:0.5px;
  }
}

.condensed{
  font-family:"Roboto-Condensed";
}

.letter-spacing-1{
  letter-spacing:0.5px;
  word-spacing:1.5px;
}


.waves-effect.waves-amber .waves-ripple {
      background-color: rgba(255, 160, 0, 0.55);
}

.waves-effect.waves-primary .waves-ripple {
         background-color: <?php echo $primaryColorLight; ?> !important;
}

.waves-effect.waves-accent .waves-ripple {
         background-color: <?php echo $accentColorLight; ?> !important;
}

.waves-mod{
    display: table-cell;
}

.more-margin-bottom{
  margin-bottom:3em;
}

.width-90{
  width:90%
}

footer{
  padding:0.5em;
  font-size:115%;
  z-index:-1;
  letter-spacing:0.1px;
}

.space-below{
  margin-top:1em;
  margin-bottom:2.5em;
}

.p-no-space p{
  margin:0.1em;
}

.p-no-space p a{
  margin-top:-0.4em;
}

.iscriviOraModal{
  transition: all 0.2s ease;
}

.p-space-below .condensed{
  font-size:250% !important;
}

.small-icon i{
  font-size:180% !important;
}

.small-icon{
  font-size:120%;
}

.small-icon-corsi i{
  font-size:180% !important;
}

.small-icon-corsi{
  font-size:100%;
}

label{
  text-align: left !important;
}

.ruota{
   display: inline-block;
  transition-property: all;
 transition-duration: 1s;
}

.ruota{
  display: inline-block;
  transition-property: all;
  animation-name: rotate;
  animation-duration: 4s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}

@keyframes rotate {
    from {transform: rotate(0deg);}
    to {transform: rotate(360deg);}
}

#install-card{   /*piccolo*/
  transition: all 0.2s;
  overflow-x: hidden;
  width:95%;
  margin:auto;
  height:94%;
  display: flex;
  flex-flow: column;
}


@media only screen and (min-width: 601px) { /*medio*/
  #install-card{
    width:80%;
    height:87%;
  }
}

@media only screen and (min-width: 993px) { /*grande*/
  #install-card{
    width:65%;
    height:90%;
  }
}

#install-card .title{
  flex: 0 1 auto;
  padding:0.8em 1em;
  font-size:250%;
}

#install-card .card-content{
  transition:all 0.2s;
  flex: 1 1 auto;
  overflow-y:auto;
  padding:2.5em;
}

#install-card .card-action{
  transition:all 0.2s;
  flex: 0 1 60px;
}


.dot{
  transition:all 0.2s;
}

#install-card ul{
    display: block;
    padding-left:50px;
}

#install-card ul li{
  display: list-item;
  list-style-type: disc;
}

#install-body{
  overflow:hidden;
  height:100%;
}


#compat .row{
  margin-bottom:0.15em;
}

.lightText{
  font-weight: 300;
}


#procedi{
  transition:all 0.2s cubic-bezier(0.4, 0.0, 0.6, 1);
  z-index:10 !important;
  position:fixed;
  display:none;
}

#procedi.nascosto{
  -moz-opacity: 0;
       opacity: 0;
  -webkit-transform: scale(0.01);
     -moz-transform: scale(0.01);
      -ms-transform: scale(0.01);
       -o-transform: scale(0.01);
          transform: scale(0.01);
}

#sfondo-grigio{
  z-index:11 !important;
  position:fixed;
  width:100%;
  height:100%;
  background: rgba(0,0,0,0.7);
}

#avviso-floating{
  width:35%;
  margin:auto;
  min-height:35%;
  padding:1em;
}

#avviso-floating .titolo{
  font-size:180%;
  padding:5%;
  transition:all;
}

#avviso-floating-content{
  transition:all;
}
.contieni-spinner{
  margin:auto;
  height:100%;
}

.preloader-wrapper.biggest {
  width: 128px !important;
  height: 128px !important;
}
