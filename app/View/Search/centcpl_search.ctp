<!-- File: /app/View/Search/centcpl_search.ctp -->
<script type="text/javascript">
	$('body').css({
		"background-image": "url(http://www.centersplan.com/wp-content/themes/cp/img/bg.png)",
		"font-family": 'Open Sans,Lucida Grande,sans-serif',
		"line-height": 1.6,
		"margin": 0,
		"padding": 0
    });
   
   </script>


    <div class="container theme-showcase">

      <!-- Main jumbotron for a primary marketing message or call to action -->
     


      <div class="page-header">
        <h1 style="border: medium none;
color: #359CDA;
font-family: Open Sans,Helvetica Neue,Arial,Sans Serif;
font-size: 24px;
letter-spacing: 0.02em;
margin-bottom: 15px;
margin-top: 0;
padding-bottom: 0;
text-shadow: 0 1px 1px #CCCCCC;">Search Form</h1>
      </div>
   
   
   
   
 <form class="form-horizontal" action="<?php echo $client_name?>/result" method="post">
<fieldset>

<!-- Form Name -->
<legend  style="border: medium none;
color: #359CDA;
font-family: Open Sans,Helvetica Neue,Arial,Sans Serif;
font-size: 24px;
letter-spacing: 0.02em;
margin-bottom: 15px;
margin-top: 0;
padding-bottom: 0;
text-shadow: 0 1px 1px #CCCCCC;">Search Our Provider Directories</legend>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="specialty">Specialty</label>
  <div class="col-md-5">
    <select id="specialty" name="specialtie_id" class="form-specialty">
    	
  <option value="0">- All specialties -</option>
	
<?php foreach($specialties as $specialty){ ?>
	<option value="<?php echo $specialty['Specialtie']['id'];?>"><?php echo $specialty['Specialtie']['name'];?></option>
<?php } ?>
    </select>
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('specialtie_id') ; ?></span>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Zip Code</label>  
  <div class="col-md-2">
  <input id="textinput" name="zipcode" placeholder="Zip Code" class="form-control input-md" type="text">
  
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('zipcode') ; ?></span>
</div>

<div class="form-group">
  <label class="col-md-4 control-label" for="textinput">Distance</label>  
  <div class="col-md-2">
  <select id="distance" name="distance" class="form-control">
  	<option value="1">1 Miles</option>
  	 <option value="5">5 Miles</option>
      <option value="10">10 Miles</option>
      <option value="20">20 Miles</option>
    </select>
  
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('distance') ; ?></span>
</div>

<hr>
<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="gender">Gender</label>
  <div class="col-md-2">
    <select id="gender" name="gender" class="form-gender">
      <option value="0">No Preference</option>
      <option value="M">Male</option>
      <option value="F">Female</option>
    </select>
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('gender') ; ?></span>
</div>


<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="insurance">Insurance</label>
  <div class="col-md-4">
    <select id="insurance" name="insurance_id" class="form-insurance">
      <option value="0">No Preference</option>
      
      <?php foreach($insurances as $insurance){ ?>
	<option value="<?php echo $insurance['Insurance']['id'];?>"><?php echo $insurance['Insurance']['name'];?></option>
<?php } ?>
    </select>
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('insurance_id') ; ?></span>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="hospital">Hospital Affiliation</label>
  <div class="col-md-4">
    <select id="hospital" name="location_id" class="form-hospital">
      <option value="0">No Preference</option>
      

      <?php foreach($locations as $location){ ?>
	<option value="<?php echo $location['Location']['id'];?>"><?php echo $location['Location']['name'];?></option>
<?php } ?>
    </select>
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('location_id') ; ?></span>
</div>

<!-- Select Basic -->
<div class="form-group">
  <label class="col-md-4 control-label" for="language">Language</label>
  <div class="col-md-4">
    <select id="language" name="language_id" class="form-language">
      <option value="0" selected="selected">No Preference</option>
      
	 <?php foreach($languages as $language){ ?>
		<option value="<?php echo $language['Language']['id'];?>"><?php echo $language['Language']['name'];?></option>
	<?php } ?>
    </select>
  </div><span class="field-error" style="color:red;"><?php echo $this->Session->flash('language_id') ; ?></span>
</div>



<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="singlebutton"></label>
  <div class="col-md-6">
    <button id="singlebutton" class="btn btn-primary">Search Provider</button>
  </div>
</div>

</fieldset>
</form>

   
   
    </div> <!-- /container -->