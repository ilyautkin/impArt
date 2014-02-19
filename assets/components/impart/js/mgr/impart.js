var impArt = function(config) {
	config = config || {};
	impArt.superclass.constructor.call(this,config);
};
Ext.extend(impArt,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('impart',impArt);

impArt = new impArt();