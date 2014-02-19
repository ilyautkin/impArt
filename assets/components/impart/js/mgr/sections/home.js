impArt.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'impart-panel-home'
			,renderTo: 'impart-panel-home-div'
		}]
	}); 
	impArt.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(impArt.page.Home,MODx.Component);
Ext.reg('impart-page-home',impArt.page.Home);