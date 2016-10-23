<?php


class CalendarPlugin extends Omeka_Plugin_AbstractPlugin
{
	protected $_hooks = array(
    		 'install',
			 'uninstall',
			 'admin_head',
			 'public_head'
			
			 
        );
        
    protected $_filters = array(
    		'admin_items_form_tabs'
    
    	);
		
	public function setUp()
    {
        if(plugin_is_active('Contribution')) {
            $this->_hooks[] = 'contribution_type_form';
        }
		 parent::setUp();
	}
     
	   
	 public function hookInstall()
    {
		//insert the plugin options into the db
		set_option('calendar_default_date', '');
    }
	
	
	 public function hookUninstall()
    {
		//delete the plugin options from the db
		delete_option('calendar_default_date');
    }
	
	 
	 protected function _getDateID()
	 {
		$dateElement = $this->_db->getTable('Element');
		$dateID = $dateElement->findByElementSetNameAndElementName('Dublin Core', 'Date')->id;
		return $dateID;
	 }
	 
	
	public function hookAdminHead($args)
    {
			$view = $args['view'];
            queue_css_file('calendar');
            queue_js_url("http://code.jquery.com/jquery-1.9.1.js");
            queue_js_url('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
        
	}
	
	public function hookPublicHead($args)
    {
			$view = $args['view'];
            queue_css_file('calendar');
            queue_js_url('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
        
	}
	
	    public function filterAdminItemsFormTabs($tabs, $args)
    {
        // insert the calendar tab before the Miscellaneous tab
        $item = $args['item'];
        $tabs[__('Calendar')] = $this->_calendarAdminForm($item);
        
        return $tabs;     
    }
	

	 public function hookContributionTypeForm($args)
    {
       $contributionType = $args['type'];
       echo $this->_calendarForm(null);
        
    }
	
	protected function _calendarForm($item)
	{
		$dateID = $this->_getDateID();
		$html = '<script>


	  				$(function() {	
						
						
    				$( "#datepicker" ).datepicker(
						{ altField: "#Elements-'. $dateID .'-0-text",
						  defaultDate: new Date(),
						  dateFormat: "yy-mm-dd",
						  changeMonth: true,
      					  changeYear: true,
      					  yearRange: "1900:c"
      					
						  
						});
					 
 				});
				
				$(".dateButtons a.noDate").click(function(event){
					$("#datepicker").datepicker("setDate", null);
					$(".ui-datepicker-current-day .ui-state-active").css({"background":"#FFF8CC", "color": "rgb(0, 69, 140)","border": "1px solid #ffdd00"});
					});
				
				$(".dateButtons a.today").click(function(event){
					$("#datepicker").datepicker("setDate", new Date());	
				});
				
				
  				</script>
				
				
				
					<div id="datepicker"></div>
					<div class="dateButtons">
						<a href="#" class="noDate">No Date</a>
						<a href="#" class="today">Today</a>
					</div>';
			return $html;
			
	}
	
protected function _calendarAdminForm($item)
	{
		$dateID = $this->_getDateID();
		$html = '<script>
						
  				$(document).ready(function(){
						$(function() {	
							
						$( "#datepicker" ).datepicker(
							{ altField: ".calendarDate",
							  dateFormat: "yy-mm-dd",
							});
							$("#datepicker").datepicker("setDate", null);
					});
					
					$("#save-changes").click(function() {
						var i = 0
						while(true)
						{
							console.log($("#Elements-40-" + i + "-text"));
							console.log($("#Elements-40-" + i + "-text").val());
							if($("#Elements-40-" + i + "-text").val()){
								i++;
							}
							else{
								var dateNumber = i + 1;
								console.log(dateNumber);
								break;
							}
							
						}
						
						$(".calendarDate").attr("name", "Elements[40][" + dateNumber + "][text]");
						
					});
				});
				
  				</script>
				
				
				
					<div id="datepicker"></div>
					<textarea style="display:none;" name="calendarDate" class="calendarDate"></textarea>';
			return $html;
			
	}

}






?>