<div class="uk-form uk-form-stacked" ng-controller="zlpickerCtrl as vm">

    <div ng-switch="view">
        <div ng-switch-when="widgets">
            <div class="uk-panel uk-panel-box">

                <ul class="uk-grid uk-grid-width-1-2 uk-grid-width-small-1-3 uk-grid-width-medium-1-4 uk-margin-large-top uk-margin-large-bottom" data-uk-grid-margin>
                    <li ng-repeat="wgt in widgets | toArray | filter:{core: 'true'}">

                        <a class="uk-panel uk-panel-hover uk-text-center" ng-click="vm.selectWidget(wgt)">
                            <img ng-src="{{ wgt.icon }}" width="40" height="40" alt="{{ wgt.label }}">
                            <h3 class="uk-h4 uk-margin-top uk-margin-bottom-remove">{{ wgt.label }}</h3>
                        </a>

                    </li>
                </ul>

				<div ng-show="(widgets | toArray | filter:{core: '!true'}).length">

					<h3 class="wk-heading">{{'Theme' | trans}}</h3>

					<ul class="uk-grid uk-grid-width-1-2 uk-grid-width-small-1-3 uk-grid-width-medium-1-4 uk-margin-large-top uk-margin-large-bottom" data-uk-grid-margin>
						<li ng-repeat="wgt in widgets | toArray | filter:{core: '!true'}" ng-class="{'uk-active':(content.data._widget.name == wgt.name)}">

							<a class="uk-panel uk-panel-hover uk-text-center" ng-click="vm.selectWidget(wgt)">
								<img ng-src="{{ wgt.icon }}" width="40" height="40" alt="{{ wgt.label }}">
								<h3 class="uk-h4 uk-margin-top uk-margin-bottom-remove">{{ wgt.label }}</h3>
							</a>

						</li>
					</ul>
				</div>

            </div>
        </div>

        <div ng-switch-default>
            <div class="uk-modal-header uk-flex uk-flex-middle">
                <div class="uk-margin-small-right">
                    <img ng-src="{{ widget.icon }}" alt="{{ widget.label }}" height="30" width="30">
                </div>
                <div class="uk-flex-item-1 uk-h2 uk-margin-remove ng-binding">{{ widget.label }}</div>
                <a class="uk-button" ng-click="vm.listWidgets()">Change Widget</a>
            </div>
            <div ng-include="widget.name + '.edit'"></div>
        </div>
    </div>

    <div class="uk-modal-footer">
        <button class="uk-button" type="button" ng-click="vm.cancel()">{{'Cancel' | trans}}</button>
        <button class="uk-button uk-button-primary" ng-if="!view" ng-click="vm.save(widget)">{{'Save' | trans}}</button>
    </div>

</div>