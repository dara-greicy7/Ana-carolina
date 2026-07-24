/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

declare interface GlobalAPI {
  cid: number;
  options: Object;
  config: Config;
  util: Object;

  extend: (options: Object) => Function;
  set: (obj: Object, key: string, value: any) => void;
  delete: (obj: Object, key: string) => void;
  nextTick: (fn: Function, context?: Object) => void;
  use: (plugin: Function | Object) => void;
  mixin: (mixin: Object) => void;
  compile: (template: string) => { render: Function, staticRenderFns: Array<Function> };

  directive: (id: string, def?: Function | Object) => Function | Object | void;
  component: (id: string, def?: Class<Component> | Object) => Class<Component>;
  filter: (id: string, def?: Function) => Function | void;

  // allow dynamic method registration
  [key: string]: any
}
