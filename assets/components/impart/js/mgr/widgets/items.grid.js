impArt.grid.Items = function(config) {
	config = config || {};
    this.sm = new Ext.grid.CheckboxSelectionModel();
	Ext.applyIf(config,{
		id: 'impart-grid-items'
		,url: impArt.config.connector_url
		,baseParams: {
			action: 'mgr/item/getlist'
		}
		,fields: ['id', 'pagetitle', 'longtitle', 'content', 'alias', 'parent', 'alias_dublicate', 'imported']
		,autoHeight: true
		,paging: true
		,remoteSort: true
        ,sm: this.sm
		,columns: [
			{header: _('id'),sortable: true,dataIndex: 'id',width: 50}
			,{header: _('pagetitle'),sortable: true,dataIndex: 'pagetitle',width: 150}
			,{header: _('impart_longtitle'),sortable: true,dataIndex: 'longtitle',width: 150}
			,{header: _('alias'),sortable: true,dataIndex: 'alias',width: 150}
			,{header: _('impart_parent'),sortable: true,dataIndex: 'parent',width: 80}
			,{header: _('impart_alias_dublicate'),sortable: true,dataIndex: 'alias_dublicate',width: 80,renderer: this.renderBooleanReverce}
			,{header: _('impart_imported'),sortable: true,dataIndex: 'imported',width: 80,renderer: this.renderBoolean}
		]
		,tbar: [{
    		text: _('impart_item_create')
			,handler: this.createItem
			,scope: this
		},{
    		text: 'Обработать 10 статей'
			,handler: this.iterateItems
			,scope: this
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		}
	});
	impArt.grid.Items.superclass.constructor.call(this,config);
};
Ext.extend(impArt.grid.Items,MODx.grid.Grid,{
	windows: {}
    ,renderBoolean: function(val,cell,row) {
		return val == '' || val == 0
			? '<span style="color:red">' + _('no') + '<span>'
			: '<span style="color:green">' + _('yes') + '<span>';
	}
    
    ,renderBooleanReverce: function(val,cell,row) {
		return val == '' || val == 0
			? '<span style="color:green">' + _('no') + '<span>'
			: '<span style="color:red">' + _('yes') + '<span>';
	}

	,renderImage: function(val,cell,row) {
		return val != ''
			? '<img src="' + val + '" alt="" height="50" />'
			: '';
	}
	,getMenu: function() {
        var cs = this.getSelectedAsList();
        var m = [];
        if (cs.split(',').length > 1) {
        	m.push({
    			text: _('impart_items_remove')
    			,handler: this.removeSelected
    		});
        } else {
    		m.push({
    			text: _('impart_item_update')
    			,handler: this.updateItem
    		});
    		m.push('-');
    		m.push({
    			text: _('impart_item_remove')
    			,handler: this.removeItem
    		});
        }
		this.addContextMenuItem(m);
    }
    
    ,getSelectedAsList: function() {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;

        var cs = '';
        for (var i=0;i<sels.length;i++) {
            cs += ','+sels[i].data.id;
        }
        cs = cs.substr(1);
        return cs;
    }

    ,removeSelected: function(act,btn,e) {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;
    	MODx.msg.confirm({
			title: _('impart_items_remove_confirm')
			,text: _('impart_items_remove_confirm_msg')
            ,url: this.config.url
			,params: {
                action: 'mgr/item/multiremove'
                ,resources: cs
			}
            ,listeners: {
                'success': {fn:function(r) {
                    this.getSelectionModel().clearSelections(true);
                    this.refresh();
                       var t = Ext.getCmp('modx-resource-tree');
                       if (t) { t.refresh(); }
                },scope:this}
            }
		});
        return true;
    }

	,createItem: function(btn,e) {
		if (!this.windows.createItem) {
			this.windows.createItem = MODx.load({
				xtype: 'impart-window-item-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.createItem.fp.getForm().reset();
		this.windows.createItem.show(e.target);
	}
    
    ,iterateItems: function(btn,e) {
        Ext.MessageBox.wait(_('loading'), _('please_wait'));
    	MODx.Ajax.request({
			url: impArt.config.connector_url
			,params: {
				action: 'mgr/item/iterate'
			}
			,listeners: {
				'success': {fn:function() { Ext.MessageBox.hide(); this.refresh(); },scope:this}
			}
		});
	}

	,updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: impArt.config.connector_url
			,params: {
				action: 'mgr/item/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					if (!this.windows.updateItem) {
						this.windows.updateItem = MODx.load({
							xtype: 'impart-window-item-update'
							,record: r
							,listeners: {
								'success': {fn:function() { this.refresh(); },scope:this}
							}
						});
					}
					this.windows.updateItem.fp.getForm().reset();
					this.windows.updateItem.fp.getForm().setValues(r.object);
					this.windows.updateItem.show(e.target);
				},scope:this}
			}
		});
	}

	,removeItem: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('impart_item_remove')
			,text: _('impart_item_remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/item/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}
});
Ext.reg('impart-grid-items',impArt.grid.Items);




impArt.window.CreateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('impart_item_create')
		,id: this.ident
		,height: 500
		,width: 475
		,url: impArt.config.connector_url
		,action: 'mgr/item/create'
		,fields: [
            {xtype: 'textfield',fieldLabel: _('impart_parent_res'),name: 'parent',id: 'impart-'+this.ident+'-parent',anchor: '40%'}
			,{xtype: 'textarea',fieldLabel: _('content'),name: 'content',id: 'impart-'+this.ident+'-content',height: 450,anchor: '99%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	impArt.window.CreateItem.superclass.constructor.call(this,config);
};
Ext.extend(impArt.window.CreateItem,MODx.Window);
Ext.reg('impart-window-item-create',impArt.window.CreateItem);


impArt.window.UpdateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'meuitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('impart_item_update')
		,id: this.ident
		,height: 200
		,width: 475
		,url: impArt.config.connector_url
		,action: 'mgr/item/update'
		,fields: [
			{xtype: 'hidden',name: 'id',id: 'impart-'+this.ident+'-id'}
			,{xtype: 'textfield',fieldLabel: _('pagetitle'),name: 'pagetitle',id: 'impart-'+this.ident+'-pagetitle',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('impart_longtitle'),name: 'longtitle',id: 'impart-'+this.ident+'-longtitle',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('alias'),name: 'alias',id: 'impart-'+this.ident+'-alias',anchor: '99%'}
			,{xtype: 'textarea',fieldLabel: _('content'),name: 'content',id: 'impart-'+this.ident+'-content',height: 150,anchor: '99%'}
            ,{xtype: 'hidden',name: 'parent',id: 'impart-'+this.ident+'-parent'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	impArt.window.UpdateItem.superclass.constructor.call(this,config);
};
Ext.extend(impArt.window.UpdateItem,MODx.Window);
Ext.reg('impart-window-item-update',impArt.window.UpdateItem);