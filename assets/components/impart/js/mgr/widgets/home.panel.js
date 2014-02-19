impArt.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false
		,baseCls: 'modx-formpanel'
		,items: [{
			html: '<h2>'+_('impart')+'</h2>'
			,border: false
			,cls: 'modx-page-header container'
		},{
    		xtype: 'modx-tabs'
			,bodyStyle: 'padding: 10px'
			,defaults: { border: false ,autoHeight: true }
			,border: true
			,activeItem: 0
			,hideMode: 'offsets'
			,items: [{
        		title: _('impart_items')
				,items: [{
					html: _('impart_intro_msg')
					,border: false
                    ,hideMode: 'offsets'
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'impart-grid-items'
					,preventRender: true
				}]
			},{
        		title: _('impart_example')
				,items: [{
					html: _('impart_example_msg')
					,border: false
                    ,hideMode: 'offsets'
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				}]
			}]
		}]
	});
	impArt.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(impArt.panel.Home,MODx.Panel);
Ext.reg('impart-panel-home',impArt.panel.Home);
