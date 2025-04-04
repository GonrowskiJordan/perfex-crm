/**
 * elFinder command prototype
 *
 * @type  elFinder.command
 * @author  Dmitry (dio) Levashov
 */
elFinder.prototype.command = function(fm) {
	"use strict";
	/**
	 * elFinder instance
	 *
	 * @type  elFinder
	 */
	this.fm = fm;
	
	/**
	 * Command name, same as class name
	 *
	 * @type  String
	 */
	this.name = '';
	
	/**
	 * Dialog class name
	 *
	 * @type  String
	 */
	this.dialogClass = '';

	/**
	 * Command icon class name with out 'elfinder-button-icon-'
	 * Use this.name if it is empty
	 *
	 * @type  String
	 */
	this.className = '';

	/**
	 * Short command description
	 *
	 * @type  String
	 */
	this.title = '';
	
	/**
	 * Linked(Child) commands name
	 * They are loaded together when tthis command is loaded.
	 * 
	 * @type  Array
	 */
	this.linkedCmds = [];
	
	/**
	 * Current command state
	 *
	 * @example
	 * this.state = -1; // command disabled
	 * this.state = 0;  // command enabled
	 * this.state = 1;  // command active (for example "fullscreen" command while elfinder in fullscreen mode)
	 * @default -1
	 * @type  Number
	 */
	this.state = -1;
	
	/**
	 * If true, command can not be disabled by connector.
	 * @see this.update()
	 *
	 * @type  Boolen
	 */
	this.alwaysEnabled = false;
	
	/**
	 * Do not change dirctory on removed current work directory
	 * 
	 * @type  Boolen
	 */
	this.noChangeDirOnRemovedCwd = false;
	
	/**
	 * If true, this means command was disabled by connector.
	 * @see this.update()
	 *
	 * @type  Boolen
	 */
	this._disabled = false;
	
	/**
	 * If true, this command is disabled on serach results
	 * 
	 * @type  Boolean
	 */
	this.disableOnSearch = false;
	
	/**
	 * Call update() when event select fired
	 * 
	 * @type  Boolean
	 */
	this.updateOnSelect = true;
	
	/**
	 * Sync toolbar button title on change
	 * 
	 * @type  Boolean
	 */
	this.syncTitleOnChange = false;

	/**
	 * Keep display of the context menu when command execution
	 * 
	 * @type  Boolean
	 */
	this.keepContextmenu = false;
	
	/**
	 * elFinder events defaults handlers.
	 * Inside handlers "this" is current command object
	 *
	 * @type  Object
	 */
	this._handlers = {
		enable  : function() { this.update(void(0), this.value); },
		disable : function() { this.update(-1, this.value); },
		'open reload load sync'    : function() { 
			this._disabled = !(this.alwaysEnabled || this.fm.isCommandEnabled(this.name));
			this.update(void(0), this.value);
			this.change(); 
		}
	};
	
	/**
	 * elFinder events handlers.
	 * Inside handlers "this" is current command object
	 *
	 * @type  Object
	 */
	this.handlers = {};
	
	/**
	 * Shortcuts
	 *
	 * @type  Array
	 */
	this.shortcuts = [];
	
	/**
	 * Command options
	 *
	 * @type  Object
	 */
	this.options = {ui : 'button'};
	
	/**
	 * Callback functions on `change` event
	 * 
	 * @type  Array
	 */
	this.listeners = [];

	/**
	 * Prepare object -
	 * bind events and shortcuts
	 *
	 * @return void
	 */
	this.setup = function(name, opts) {
		var self = this,
			fm   = this.fm,
			setCallback = function(s) {
				var cb = s.callback || function(e) {
							fm.exec(self.name, void(0), {
							_userAction: true,
							_currentType: 'shortcut'
						});
					};
				s.callback = function(e) {
					var enabled, checks = {};
					if (self.enabled()) {
						if (fm.searchStatus.state < 2) {
							enabled = fm.isCommandEnabled(self.name);
						} else {
							$.each(fm.selected(), function(i, h) {
								if (fm.optionsByHashes[h]) {
									checks[h] = true;
								} else {
									$.each(fm.volOptions, function(id) {
										if (!checks[id] && h.indexOf(id) === 0) {
											checks[id] = true;
											return false;
										}
									});
								}
							});
							$.each(checks, function(h) {
								enabled = fm.isCommandEnabled(self.name, h);
								if (! enabled) {
									return false;
								}
							});
						}
						if (enabled) {
							self.event = e;
							cb.call(self);
							delete self.event;
						}
					}
				};
			},
			i, s, sc;

		this.name      = name;
		this.title     = fm.messages['cmd'+name] ? fm.i18n('cmd'+name)
		               : ((this.extendsCmd && fm.messages['cmd'+this.extendsCmd]) ? fm.i18n('cmd'+this.extendsCmd) : name);
		this.options   = Object.assign({}, this.options, opts);
		this.listeners = [];
		this.dialogClass = 'elfinder-dialog-' + name;

		if (opts.shortcuts) {
			if (typeof opts.shortcuts === 'function') {
				sc = opts.shortcuts(this.fm, this.shortcuts);
			} else if (Array.isArray(opts.shortcuts)) {
				sc = opts.shortcuts;
			}
			this.shortcuts = sc || [];
		}

		if (this.updateOnSelect) {
			this._handlers.select = function() { this.update(void(0), this.value); };
		}

		$.each(Object.assign({}, self._handlers, self.handlers), function(cmd, handler) {
			fm.bind(cmd, $.proxy(handler, self));
		});

		for (i = 0; i < this.shortcuts.length; i++) {
			s = this.shortcuts[i];
			setCallback(s);
			!s.description && (s.description = this.title);
			fm.shortcut(s);
		}

		if (this.disableOnSearch) {
			fm.bind('search searchend', function() {
				self._disabled = this.type === 'search'? true : ! (this.alwaysEnabled || fm.isCommandEnabled(name));
				self.update(void(0), self.value);
			});
		}

		this.init();
	};

	/**
	 * Command specific init stuffs
	 *
	 * @return void
	 */
	this.init = function() {};

	/**
	 * Exec command
	 *
	 * @param  Array         target files hashes
	 * @param  Array|Object  command value
	 * @return $.Deferred
	 */
	this.exec = function(files, opts) { 
		return $.Deferred().reject(); 
	};
	
	this.getUndo = function(opts, resData) {
		return false;
	};
	
	/**
	 * Return true if command disabled.
	 *
	 * @return Boolen
	 */
	this.disabled = function() {
		return this.state < 0;
	};
	
	/**
	 * Return true if command enabled.
	 *
	 * @return Boolen
	 */
	this.enabled = function() {
		return this.state > -1;
	};
	
	/**
	 * Return true if command active.
	 *
	 * @return Boolen
	 */
	this.active = function() {
		return this.state > 0;
	};
	
	/**
	 * Return current command state.
	 * Must be overloaded in most commands
	 *
	 * @return Number
	 */
	this.getstate = function() {
		return -1;
	};
	
	/**
	 * Update command state/value
	 * and rize 'change' event if smth changed
	 *
	 * @param  Number  new state or undefined to auto update state
	 * @param  mixed   new value
	 * @return void
	 */
	this.update = function(s, v) {
		var state = this.state,
			value = this.value;

		if (this._disabled && this.fm.searchStatus === 0) {
			this.state = -1;
		} else {
			this.state = s !== void(0) ? s : this.getstate();
		}

		this.value = v;
		
		if (state != this.state || value != this.value) {
			this.change();
		}
	};
	
	/**
	 * Bind handler / fire 'change' event.
	 *
	 * @param  Function|undefined  event callback
	 * @return void
	 */
	this.change = function(c) {
		var cmd, i;
		
		if (typeof(c) === 'function') {
			this.listeners.push(c);			
		} else {
			for (i = 0; i < this.listeners.length; i++) {
				cmd = this.listeners[i];
				try {
					cmd(this.state, this.value);
				} catch (e) {
					this.fm.debug('error', e);
				}
			}
		}
		return this;
	};
	

	/**
	 * With argument check given files hashes and return list of existed files hashes.
	 * Without argument return selected files hashes.
	 *
	 * @param  Array|String|void  hashes
	 * @return Array
	 */
	this.hashes = function(hashes) {
		return hashes
			? $.grep(Array.isArray(hashes) ? hashes : [hashes], function(hash) { return fm.file(hash) ? true : false; })
			: fm.selected();
	};
	
	/**
	 * Return only existed files from given fils hashes | selected files
	 *
	 * @param  Array|String|void  hashes
	 * @return Array
	 */
	this.files = function(hashes) {
		var fm = this.fm;
		
		return hashes
			? $.map(Array.isArray(hashes) ? hashes : [hashes], function(hash) { return fm.file(hash) || null; })
			: fm.selectedFiles();
	};

	/**
	 * Wrapper to fm.dialog()
	 *
	 * @param  String|DOMElement  content
	 * @param  Object             options
	 * @return Object             jQuery element object
	 */
	this.fmDialog = function(content, options) {
		if (options.cssClass) {
			options.cssClass += ' ' + this.dialogClass;
		} else {
			options.cssClass = this.dialogClass;
		}
		return this.fm.dialog(content, options);
	};
};
