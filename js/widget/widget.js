/**
* @vue/shared v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function gf(e) {
  const n = /* @__PURE__ */ Object.create(null);
  for (const i of e.split(","))
    n[i] = 1;
  return (i) => i in n;
}
const Gt = {}, Ai = [], Un = () => {
}, pw = () => !1, tl = (e) => e.charCodeAt(0) === 111 && e.charCodeAt(1) === 110 && // uppercase letter
(e.charCodeAt(2) > 122 || e.charCodeAt(2) < 97), vf = (e) => e.startsWith("onUpdate:"), Te = Object.assign, _f = (e, n) => {
  const i = e.indexOf(n);
  i > -1 && e.splice(i, 1);
}, gw = Object.prototype.hasOwnProperty, Ft = (e, n) => gw.call(e, n), gt = Array.isArray, Ci = (e) => el(e) === "[object Map]", ad = (e) => el(e) === "[object Set]", pt = (e) => typeof e == "function", ce = (e) => typeof e == "string", Dr = (e) => typeof e == "symbol", Jt = (e) => e !== null && typeof e == "object", cd = (e) => (Jt(e) || pt(e)) && pt(e.then) && pt(e.catch), hd = Object.prototype.toString, el = (e) => hd.call(e), vw = (e) => el(e).slice(8, -1), dd = (e) => el(e) === "[object Object]", mf = (e) => ce(e) && e !== "NaN" && e[0] !== "-" && "" + parseInt(e, 10) === e, fo = /* @__PURE__ */ gf(
  // the leading comma is intentional so empty string "" is also included
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), nl = (e) => {
  const n = /* @__PURE__ */ Object.create(null);
  return (i) => n[i] || (n[i] = e(i));
}, _w = /-(\w)/g, jr = nl(
  (e) => e.replace(_w, (n, i) => i ? i.toUpperCase() : "")
), mw = /\B([A-Z])/g, Rr = nl(
  (e) => e.replace(mw, "-$1").toLowerCase()
), pd = nl((e) => e.charAt(0).toUpperCase() + e.slice(1)), Tu = nl(
  (e) => e ? `on${pd(e)}` : ""
), Or = (e, n) => !Object.is(e, n), Au = (e, ...n) => {
  for (let i = 0; i < e.length; i++)
    e[i](...n);
}, gd = (e, n, i, o = !1) => {
  Object.defineProperty(e, n, {
    configurable: !0,
    enumerable: !1,
    writable: o,
    value: i
  });
}, bw = (e) => {
  const n = parseFloat(e);
  return isNaN(n) ? e : n;
};
let _h;
const vd = () => _h || (_h = typeof globalThis < "u" ? globalThis : typeof self < "u" ? self : typeof window < "u" ? window : typeof global < "u" ? global : {});
function bf(e) {
  if (gt(e)) {
    const n = {};
    for (let i = 0; i < e.length; i++) {
      const o = e[i], l = ce(o) ? xw(o) : bf(o);
      if (l)
        for (const u in l)
          n[u] = l[u];
    }
    return n;
  } else if (ce(e) || Jt(e))
    return e;
}
const yw = /;(?![^(]*\))/g, ww = /:([^]+)/, Ew = /\/\*[^]*?\*\//g;
function xw(e) {
  const n = {};
  return e.replace(Ew, "").split(yw).forEach((i) => {
    if (i) {
      const o = i.split(ww);
      o.length > 1 && (n[o[0].trim()] = o[1].trim());
    }
  }), n;
}
function rl(e) {
  let n = "";
  if (ce(e))
    n = e;
  else if (gt(e))
    for (let i = 0; i < e.length; i++) {
      const o = rl(e[i]);
      o && (n += o + " ");
    }
  else if (Jt(e))
    for (const i in e)
      e[i] && (n += i + " ");
  return n.trim();
}
const Sw = "itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly", Tw = /* @__PURE__ */ gf(Sw);
function _d(e) {
  return !!e || e === "";
}
const md = (e) => !!(e && e.__v_isRef === !0), Ju = (e) => ce(e) ? e : e == null ? "" : gt(e) || Jt(e) && (e.toString === hd || !pt(e.toString)) ? md(e) ? Ju(e.value) : JSON.stringify(e, bd, 2) : String(e), bd = (e, n) => md(n) ? bd(e, n.value) : Ci(n) ? {
  [`Map(${n.size})`]: [...n.entries()].reduce(
    (i, [o, l], u) => (i[Cu(o, u) + " =>"] = l, i),
    {}
  )
} : ad(n) ? {
  [`Set(${n.size})`]: [...n.values()].map((i) => Cu(i))
} : Dr(n) ? Cu(n) : Jt(n) && !gt(n) && !dd(n) ? String(n) : n, Cu = (e, n = "") => {
  var i;
  return (
    // Symbol.description in es2019+ so we need to cast here to pass
    // the lib: es2016 check
    Dr(e) ? `Symbol(${(i = e.description) != null ? i : n})` : e
  );
};
/**
* @vue/reactivity v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let He;
class Aw {
  constructor(n = !1) {
    this.detached = n, this._active = !0, this.effects = [], this.cleanups = [], this._isPaused = !1, this.parent = He, !n && He && (this.index = (He.scopes || (He.scopes = [])).push(
      this
    ) - 1);
  }
  get active() {
    return this._active;
  }
  pause() {
    if (this._active) {
      this._isPaused = !0;
      let n, i;
      if (this.scopes)
        for (n = 0, i = this.scopes.length; n < i; n++)
          this.scopes[n].pause();
      for (n = 0, i = this.effects.length; n < i; n++)
        this.effects[n].pause();
    }
  }
  /**
   * Resumes the effect scope, including all child scopes and effects.
   */
  resume() {
    if (this._active && this._isPaused) {
      this._isPaused = !1;
      let n, i;
      if (this.scopes)
        for (n = 0, i = this.scopes.length; n < i; n++)
          this.scopes[n].resume();
      for (n = 0, i = this.effects.length; n < i; n++)
        this.effects[n].resume();
    }
  }
  run(n) {
    if (this._active) {
      const i = He;
      try {
        return He = this, n();
      } finally {
        He = i;
      }
    }
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  on() {
    He = this;
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  off() {
    He = this.parent;
  }
  stop(n) {
    if (this._active) {
      let i, o;
      for (i = 0, o = this.effects.length; i < o; i++)
        this.effects[i].stop();
      for (i = 0, o = this.cleanups.length; i < o; i++)
        this.cleanups[i]();
      if (this.scopes)
        for (i = 0, o = this.scopes.length; i < o; i++)
          this.scopes[i].stop(!0);
      if (!this.detached && this.parent && !n) {
        const l = this.parent.scopes.pop();
        l && l !== this && (this.parent.scopes[this.index] = l, l.index = this.index);
      }
      this.parent = void 0, this._active = !1;
    }
  }
}
function yd() {
  return He;
}
function Cw(e, n = !1) {
  He && He.cleanups.push(e);
}
let Ht;
const Ou = /* @__PURE__ */ new WeakSet();
class wd {
  constructor(n) {
    this.fn = n, this.deps = void 0, this.depsTail = void 0, this.flags = 5, this.next = void 0, this.cleanup = void 0, this.scheduler = void 0, He && He.active && He.effects.push(this);
  }
  pause() {
    this.flags |= 64;
  }
  resume() {
    this.flags & 64 && (this.flags &= -65, Ou.has(this) && (Ou.delete(this), this.trigger()));
  }
  /**
   * @internal
   */
  notify() {
    this.flags & 2 && !(this.flags & 32) || this.flags & 8 || xd(this);
  }
  run() {
    if (!(this.flags & 1))
      return this.fn();
    this.flags |= 2, mh(this), Sd(this);
    const n = Ht, i = In;
    Ht = this, In = !0;
    try {
      return this.fn();
    } finally {
      Td(this), Ht = n, In = i, this.flags &= -3;
    }
  }
  stop() {
    if (this.flags & 1) {
      for (let n = this.deps; n; n = n.nextDep)
        Ef(n);
      this.deps = this.depsTail = void 0, mh(this), this.onStop && this.onStop(), this.flags &= -2;
    }
  }
  trigger() {
    this.flags & 64 ? Ou.add(this) : this.scheduler ? this.scheduler() : this.runIfDirty();
  }
  /**
   * @internal
   */
  runIfDirty() {
    Zu(this) && this.run();
  }
  get dirty() {
    return Zu(this);
  }
}
let Ed = 0, ao;
function xd(e) {
  e.flags |= 8, e.next = ao, ao = e;
}
function yf() {
  Ed++;
}
function wf() {
  if (--Ed > 0)
    return;
  let e;
  for (; ao; ) {
    let n = ao;
    for (ao = void 0; n; ) {
      const i = n.next;
      if (n.next = void 0, n.flags &= -9, n.flags & 1)
        try {
          n.trigger();
        } catch (o) {
          e || (e = o);
        }
      n = i;
    }
  }
  if (e)
    throw e;
}
function Sd(e) {
  for (let n = e.deps; n; n = n.nextDep)
    n.version = -1, n.prevActiveLink = n.dep.activeLink, n.dep.activeLink = n;
}
function Td(e) {
  let n, i = e.depsTail, o = i;
  for (; o; ) {
    const l = o.prevDep;
    o.version === -1 ? (o === i && (i = l), Ef(o), Ow(o)) : n = o, o.dep.activeLink = o.prevActiveLink, o.prevActiveLink = void 0, o = l;
  }
  e.deps = n, e.depsTail = i;
}
function Zu(e) {
  for (let n = e.deps; n; n = n.nextDep)
    if (n.dep.version !== n.version || n.dep.computed && (Ad(n.dep.computed) || n.dep.version !== n.version))
      return !0;
  return !!e._dirty;
}
function Ad(e) {
  if (e.flags & 4 && !(e.flags & 16) || (e.flags &= -17, e.globalVersion === wo))
    return;
  e.globalVersion = wo;
  const n = e.dep;
  if (e.flags |= 2, n.version > 0 && !e.isSSR && e.deps && !Zu(e)) {
    e.flags &= -3;
    return;
  }
  const i = Ht, o = In;
  Ht = e, In = !0;
  try {
    Sd(e);
    const l = e.fn(e._value);
    (n.version === 0 || Or(l, e._value)) && (e._value = l, n.version++);
  } catch (l) {
    throw n.version++, l;
  } finally {
    Ht = i, In = o, Td(e), e.flags &= -3;
  }
}
function Ef(e) {
  const { dep: n, prevSub: i, nextSub: o } = e;
  if (i && (i.nextSub = o, e.prevSub = void 0), o && (o.prevSub = i, e.nextSub = void 0), n.subs === e && (n.subs = i), !n.subs && n.computed) {
    n.computed.flags &= -5;
    for (let l = n.computed.deps; l; l = l.nextDep)
      Ef(l);
  }
}
function Ow(e) {
  const { prevDep: n, nextDep: i } = e;
  n && (n.nextDep = i, e.prevDep = void 0), i && (i.prevDep = n, e.nextDep = void 0);
}
let In = !0;
const Cd = [];
function Pr() {
  Cd.push(In), In = !1;
}
function Mr() {
  const e = Cd.pop();
  In = e === void 0 ? !0 : e;
}
function mh(e) {
  const { cleanup: n } = e;
  if (e.cleanup = void 0, n) {
    const i = Ht;
    Ht = void 0;
    try {
      n();
    } finally {
      Ht = i;
    }
  }
}
let wo = 0;
class Iw {
  constructor(n, i) {
    this.sub = n, this.dep = i, this.version = i.version, this.nextDep = this.prevDep = this.nextSub = this.prevSub = this.prevActiveLink = void 0;
  }
}
class il {
  constructor(n) {
    this.computed = n, this.version = 0, this.activeLink = void 0, this.subs = void 0;
  }
  track(n) {
    if (!Ht || !In || Ht === this.computed)
      return;
    let i = this.activeLink;
    if (i === void 0 || i.sub !== Ht)
      i = this.activeLink = new Iw(Ht, this), Ht.deps ? (i.prevDep = Ht.depsTail, Ht.depsTail.nextDep = i, Ht.depsTail = i) : Ht.deps = Ht.depsTail = i, Ht.flags & 4 && Od(i);
    else if (i.version === -1 && (i.version = this.version, i.nextDep)) {
      const o = i.nextDep;
      o.prevDep = i.prevDep, i.prevDep && (i.prevDep.nextDep = o), i.prevDep = Ht.depsTail, i.nextDep = void 0, Ht.depsTail.nextDep = i, Ht.depsTail = i, Ht.deps === i && (Ht.deps = o);
    }
    return i;
  }
  trigger(n) {
    this.version++, wo++, this.notify(n);
  }
  notify(n) {
    yf();
    try {
      for (let i = this.subs; i; i = i.prevSub)
        i.sub.notify() && i.sub.dep.notify();
    } finally {
      wf();
    }
  }
}
function Od(e) {
  const n = e.dep.computed;
  if (n && !e.dep.subs) {
    n.flags |= 20;
    for (let o = n.deps; o; o = o.nextDep)
      Od(o);
  }
  const i = e.dep.subs;
  i !== e && (e.prevSub = i, i && (i.nextSub = e)), e.dep.subs = e;
}
const Ks = /* @__PURE__ */ new WeakMap(), kr = Symbol(
  ""
), Qu = Symbol(
  ""
), Eo = Symbol(
  ""
);
function Me(e, n, i) {
  if (In && Ht) {
    let o = Ks.get(e);
    o || Ks.set(e, o = /* @__PURE__ */ new Map());
    let l = o.get(i);
    l || o.set(i, l = new il()), l.track();
  }
}
function rr(e, n, i, o, l, u) {
  const a = Ks.get(e);
  if (!a) {
    wo++;
    return;
  }
  const h = (g) => {
    g && g.trigger();
  };
  if (yf(), n === "clear")
    a.forEach(h);
  else {
    const g = gt(e), v = g && mf(i);
    if (g && i === "length") {
      const _ = Number(o);
      a.forEach((m, x) => {
        (x === "length" || x === Eo || !Dr(x) && x >= _) && h(m);
      });
    } else
      switch (i !== void 0 && h(a.get(i)), v && h(a.get(Eo)), n) {
        case "add":
          g ? v && h(a.get("length")) : (h(a.get(kr)), Ci(e) && h(a.get(Qu)));
          break;
        case "delete":
          g || (h(a.get(kr)), Ci(e) && h(a.get(Qu)));
          break;
        case "set":
          Ci(e) && h(a.get(kr));
          break;
      }
  }
  wf();
}
function Dw(e, n) {
  var i;
  return (i = Ks.get(e)) == null ? void 0 : i.get(n);
}
function _i(e) {
  const n = Pt(e);
  return n === e ? n : (Me(n, "iterate", Eo), wn(e) ? n : n.map(De));
}
function ol(e) {
  return Me(e = Pt(e), "iterate", Eo), e;
}
const Rw = {
  __proto__: null,
  [Symbol.iterator]() {
    return Iu(this, Symbol.iterator, De);
  },
  concat(...e) {
    return _i(this).concat(
      ...e.map((n) => gt(n) ? _i(n) : n)
    );
  },
  entries() {
    return Iu(this, "entries", (e) => (e[1] = De(e[1]), e));
  },
  every(e, n) {
    return er(this, "every", e, n, void 0, arguments);
  },
  filter(e, n) {
    return er(this, "filter", e, n, (i) => i.map(De), arguments);
  },
  find(e, n) {
    return er(this, "find", e, n, De, arguments);
  },
  findIndex(e, n) {
    return er(this, "findIndex", e, n, void 0, arguments);
  },
  findLast(e, n) {
    return er(this, "findLast", e, n, De, arguments);
  },
  findLastIndex(e, n) {
    return er(this, "findLastIndex", e, n, void 0, arguments);
  },
  // flat, flatMap could benefit from ARRAY_ITERATE but are not straight-forward to implement
  forEach(e, n) {
    return er(this, "forEach", e, n, void 0, arguments);
  },
  includes(...e) {
    return Du(this, "includes", e);
  },
  indexOf(...e) {
    return Du(this, "indexOf", e);
  },
  join(e) {
    return _i(this).join(e);
  },
  // keys() iterator only reads `length`, no optimisation required
  lastIndexOf(...e) {
    return Du(this, "lastIndexOf", e);
  },
  map(e, n) {
    return er(this, "map", e, n, void 0, arguments);
  },
  pop() {
    return Qi(this, "pop");
  },
  push(...e) {
    return Qi(this, "push", e);
  },
  reduce(e, ...n) {
    return bh(this, "reduce", e, n);
  },
  reduceRight(e, ...n) {
    return bh(this, "reduceRight", e, n);
  },
  shift() {
    return Qi(this, "shift");
  },
  // slice could use ARRAY_ITERATE but also seems to beg for range tracking
  some(e, n) {
    return er(this, "some", e, n, void 0, arguments);
  },
  splice(...e) {
    return Qi(this, "splice", e);
  },
  toReversed() {
    return _i(this).toReversed();
  },
  toSorted(e) {
    return _i(this).toSorted(e);
  },
  toSpliced(...e) {
    return _i(this).toSpliced(...e);
  },
  unshift(...e) {
    return Qi(this, "unshift", e);
  },
  values() {
    return Iu(this, "values", De);
  }
};
function Iu(e, n, i) {
  const o = ol(e), l = o[n]();
  return o !== e && !wn(e) && (l._next = l.next, l.next = () => {
    const u = l._next();
    return u.value && (u.value = i(u.value)), u;
  }), l;
}
const Pw = Array.prototype;
function er(e, n, i, o, l, u) {
  const a = ol(e), h = a !== e && !wn(e), g = a[n];
  if (g !== Pw[n]) {
    const m = g.apply(e, u);
    return h ? De(m) : m;
  }
  let v = i;
  a !== e && (h ? v = function(m, x) {
    return i.call(this, De(m), x, e);
  } : i.length > 2 && (v = function(m, x) {
    return i.call(this, m, x, e);
  }));
  const _ = g.call(a, v, o);
  return h && l ? l(_) : _;
}
function bh(e, n, i, o) {
  const l = ol(e);
  let u = i;
  return l !== e && (wn(e) ? i.length > 3 && (u = function(a, h, g) {
    return i.call(this, a, h, g, e);
  }) : u = function(a, h, g) {
    return i.call(this, a, De(h), g, e);
  }), l[n](u, ...o);
}
function Du(e, n, i) {
  const o = Pt(e);
  Me(o, "iterate", Eo);
  const l = o[n](...i);
  return (l === -1 || l === !1) && Cf(i[0]) ? (i[0] = Pt(i[0]), o[n](...i)) : l;
}
function Qi(e, n, i = []) {
  Pr(), yf();
  const o = Pt(e)[n].apply(e, i);
  return wf(), Mr(), o;
}
const Mw = /* @__PURE__ */ gf("__proto__,__v_isRef,__isVue"), Id = new Set(
  /* @__PURE__ */ Object.getOwnPropertyNames(Symbol).filter((e) => e !== "arguments" && e !== "caller").map((e) => Symbol[e]).filter(Dr)
);
function Fw(e) {
  Dr(e) || (e = String(e));
  const n = Pt(this);
  return Me(n, "has", e), n.hasOwnProperty(e);
}
class Dd {
  constructor(n = !1, i = !1) {
    this._isReadonly = n, this._isShallow = i;
  }
  get(n, i, o) {
    const l = this._isReadonly, u = this._isShallow;
    if (i === "__v_isReactive")
      return !l;
    if (i === "__v_isReadonly")
      return l;
    if (i === "__v_isShallow")
      return u;
    if (i === "__v_raw")
      return o === (l ? u ? qw : Fd : u ? Md : Pd).get(n) || // receiver is not the reactive proxy, but has the same prototype
      // this means the receiver is a user proxy of the reactive proxy
      Object.getPrototypeOf(n) === Object.getPrototypeOf(o) ? n : void 0;
    const a = gt(n);
    if (!l) {
      let g;
      if (a && (g = Rw[i]))
        return g;
      if (i === "hasOwnProperty")
        return Fw;
    }
    const h = Reflect.get(
      n,
      i,
      // if this is a proxy wrapping a ref, return methods using the raw ref
      // as receiver so that we don't have to call `toRaw` on the ref in all
      // its class methods
      ie(n) ? n : o
    );
    return (Dr(i) ? Id.has(i) : Mw(i)) || (l || Me(n, "get", i), u) ? h : ie(h) ? a && mf(i) ? h : h.value : Jt(h) ? l ? Pi(h) : Tf(h) : h;
  }
}
class Rd extends Dd {
  constructor(n = !1) {
    super(!1, n);
  }
  set(n, i, o, l) {
    let u = n[i];
    if (!this._isShallow) {
      const g = ti(u);
      if (!wn(o) && !ti(o) && (u = Pt(u), o = Pt(o)), !gt(n) && ie(u) && !ie(o))
        return g ? !1 : (u.value = o, !0);
    }
    const a = gt(n) && mf(i) ? Number(i) < n.length : Ft(n, i), h = Reflect.set(
      n,
      i,
      o,
      ie(n) ? n : l
    );
    return n === Pt(l) && (a ? Or(o, u) && rr(n, "set", i, o) : rr(n, "add", i, o)), h;
  }
  deleteProperty(n, i) {
    const o = Ft(n, i);
    n[i];
    const l = Reflect.deleteProperty(n, i);
    return l && o && rr(n, "delete", i, void 0), l;
  }
  has(n, i) {
    const o = Reflect.has(n, i);
    return (!Dr(i) || !Id.has(i)) && Me(n, "has", i), o;
  }
  ownKeys(n) {
    return Me(
      n,
      "iterate",
      gt(n) ? "length" : kr
    ), Reflect.ownKeys(n);
  }
}
class Nw extends Dd {
  constructor(n = !1) {
    super(!0, n);
  }
  set(n, i) {
    return !0;
  }
  deleteProperty(n, i) {
    return !0;
  }
}
const Lw = /* @__PURE__ */ new Rd(), Bw = /* @__PURE__ */ new Nw(), Ww = /* @__PURE__ */ new Rd(!0);
const xf = (e) => e, sl = (e) => Reflect.getPrototypeOf(e);
function bs(e, n, i = !1, o = !1) {
  e = e.__v_raw;
  const l = Pt(e), u = Pt(n);
  i || (Or(n, u) && Me(l, "get", n), Me(l, "get", u));
  const { has: a } = sl(l), h = o ? xf : i ? Of : De;
  if (a.call(l, n))
    return h(e.get(n));
  if (a.call(l, u))
    return h(e.get(u));
  e !== l && e.get(n);
}
function ys(e, n = !1) {
  const i = this.__v_raw, o = Pt(i), l = Pt(e);
  return n || (Or(e, l) && Me(o, "has", e), Me(o, "has", l)), e === l ? i.has(e) : i.has(e) || i.has(l);
}
function ws(e, n = !1) {
  return e = e.__v_raw, !n && Me(Pt(e), "iterate", kr), Reflect.get(e, "size", e);
}
function yh(e, n = !1) {
  !n && !wn(e) && !ti(e) && (e = Pt(e));
  const i = Pt(this);
  return sl(i).has.call(i, e) || (i.add(e), rr(i, "add", e, e)), this;
}
function wh(e, n, i = !1) {
  !i && !wn(n) && !ti(n) && (n = Pt(n));
  const o = Pt(this), { has: l, get: u } = sl(o);
  let a = l.call(o, e);
  a || (e = Pt(e), a = l.call(o, e));
  const h = u.call(o, e);
  return o.set(e, n), a ? Or(n, h) && rr(o, "set", e, n) : rr(o, "add", e, n), this;
}
function Eh(e) {
  const n = Pt(this), { has: i, get: o } = sl(n);
  let l = i.call(n, e);
  l || (e = Pt(e), l = i.call(n, e)), o && o.call(n, e);
  const u = n.delete(e);
  return l && rr(n, "delete", e, void 0), u;
}
function xh() {
  const e = Pt(this), n = e.size !== 0, i = e.clear();
  return n && rr(e, "clear", void 0, void 0), i;
}
function Es(e, n) {
  return function(o, l) {
    const u = this, a = u.__v_raw, h = Pt(a), g = n ? xf : e ? Of : De;
    return !e && Me(h, "iterate", kr), a.forEach((v, _) => o.call(l, g(v), g(_), u));
  };
}
function xs(e, n, i) {
  return function(...o) {
    const l = this.__v_raw, u = Pt(l), a = Ci(u), h = e === "entries" || e === Symbol.iterator && a, g = e === "keys" && a, v = l[e](...o), _ = i ? xf : n ? Of : De;
    return !n && Me(
      u,
      "iterate",
      g ? Qu : kr
    ), {
      // iterator protocol
      next() {
        const { value: m, done: x } = v.next();
        return x ? { value: m, done: x } : {
          value: h ? [_(m[0]), _(m[1])] : _(m),
          done: x
        };
      },
      // iterable protocol
      [Symbol.iterator]() {
        return this;
      }
    };
  };
}
function wr(e) {
  return function(...n) {
    return e === "delete" ? !1 : e === "clear" ? void 0 : this;
  };
}
function Uw() {
  const e = {
    get(u) {
      return bs(this, u);
    },
    get size() {
      return ws(this);
    },
    has: ys,
    add: yh,
    set: wh,
    delete: Eh,
    clear: xh,
    forEach: Es(!1, !1)
  }, n = {
    get(u) {
      return bs(this, u, !1, !0);
    },
    get size() {
      return ws(this);
    },
    has: ys,
    add(u) {
      return yh.call(this, u, !0);
    },
    set(u, a) {
      return wh.call(this, u, a, !0);
    },
    delete: Eh,
    clear: xh,
    forEach: Es(!1, !0)
  }, i = {
    get(u) {
      return bs(this, u, !0);
    },
    get size() {
      return ws(this, !0);
    },
    has(u) {
      return ys.call(this, u, !0);
    },
    add: wr("add"),
    set: wr("set"),
    delete: wr("delete"),
    clear: wr("clear"),
    forEach: Es(!0, !1)
  }, o = {
    get(u) {
      return bs(this, u, !0, !0);
    },
    get size() {
      return ws(this, !0);
    },
    has(u) {
      return ys.call(this, u, !0);
    },
    add: wr("add"),
    set: wr("set"),
    delete: wr("delete"),
    clear: wr("clear"),
    forEach: Es(!0, !0)
  };
  return [
    "keys",
    "values",
    "entries",
    Symbol.iterator
  ].forEach((u) => {
    e[u] = xs(u, !1, !1), i[u] = xs(u, !0, !1), n[u] = xs(u, !1, !0), o[u] = xs(
      u,
      !0,
      !0
    );
  }), [
    e,
    i,
    n,
    o
  ];
}
const [
  Hw,
  $w,
  Kw,
  Gw
] = /* @__PURE__ */ Uw();
function Sf(e, n) {
  const i = n ? e ? Gw : Kw : e ? $w : Hw;
  return (o, l, u) => l === "__v_isReactive" ? !e : l === "__v_isReadonly" ? e : l === "__v_raw" ? o : Reflect.get(
    Ft(i, l) && l in o ? i : o,
    l,
    u
  );
}
const Yw = {
  get: /* @__PURE__ */ Sf(!1, !1)
}, Xw = {
  get: /* @__PURE__ */ Sf(!1, !0)
}, zw = {
  get: /* @__PURE__ */ Sf(!0, !1)
};
const Pd = /* @__PURE__ */ new WeakMap(), Md = /* @__PURE__ */ new WeakMap(), Fd = /* @__PURE__ */ new WeakMap(), qw = /* @__PURE__ */ new WeakMap();
function Vw(e) {
  switch (e) {
    case "Object":
    case "Array":
      return 1;
    case "Map":
    case "Set":
    case "WeakMap":
    case "WeakSet":
      return 2;
    default:
      return 0;
  }
}
function kw(e) {
  return e.__v_skip || !Object.isExtensible(e) ? 0 : Vw(vw(e));
}
function Tf(e) {
  return ti(e) ? e : Af(
    e,
    !1,
    Lw,
    Yw,
    Pd
  );
}
function Jw(e) {
  return Af(
    e,
    !1,
    Ww,
    Xw,
    Md
  );
}
function Pi(e) {
  return Af(
    e,
    !0,
    Bw,
    zw,
    Fd
  );
}
function Af(e, n, i, o, l) {
  if (!Jt(e) || e.__v_raw && !(n && e.__v_isReactive))
    return e;
  const u = l.get(e);
  if (u)
    return u;
  const a = kw(e);
  if (a === 0)
    return e;
  const h = new Proxy(
    e,
    a === 2 ? o : i
  );
  return l.set(e, h), h;
}
function Oi(e) {
  return ti(e) ? Oi(e.__v_raw) : !!(e && e.__v_isReactive);
}
function ti(e) {
  return !!(e && e.__v_isReadonly);
}
function wn(e) {
  return !!(e && e.__v_isShallow);
}
function Cf(e) {
  return e ? !!e.__v_raw : !1;
}
function Pt(e) {
  const n = e && e.__v_raw;
  return n ? Pt(n) : e;
}
function Zw(e) {
  return !Ft(e, "__v_skip") && Object.isExtensible(e) && gd(e, "__v_skip", !0), e;
}
const De = (e) => Jt(e) ? Tf(e) : e, Of = (e) => Jt(e) ? Pi(e) : e;
function ie(e) {
  return e ? e.__v_isRef === !0 : !1;
}
function Pe(e) {
  return Nd(e, !1);
}
function Ru(e) {
  return Nd(e, !0);
}
function Nd(e, n) {
  return ie(e) ? e : new Qw(e, n);
}
class Qw {
  constructor(n, i) {
    this.dep = new il(), this.__v_isRef = !0, this.__v_isShallow = !1, this._rawValue = i ? n : Pt(n), this._value = i ? n : De(n), this.__v_isShallow = i;
  }
  get value() {
    return this.dep.track(), this._value;
  }
  set value(n) {
    const i = this._rawValue, o = this.__v_isShallow || wn(n) || ti(n);
    n = o ? n : Pt(n), Or(n, i) && (this._rawValue = n, this._value = o ? n : De(n), this.dep.trigger());
  }
}
function re(e) {
  return ie(e) ? e.value : e;
}
function jw(e) {
  return pt(e) ? e() : re(e);
}
const t1 = {
  get: (e, n, i) => n === "__v_raw" ? e : re(Reflect.get(e, n, i)),
  set: (e, n, i, o) => {
    const l = e[n];
    return ie(l) && !ie(i) ? (l.value = i, !0) : Reflect.set(e, n, i, o);
  }
};
function Ld(e) {
  return Oi(e) ? e : new Proxy(e, t1);
}
class e1 {
  constructor(n) {
    this.__v_isRef = !0, this._value = void 0;
    const i = this.dep = new il(), { get: o, set: l } = n(i.track.bind(i), i.trigger.bind(i));
    this._get = o, this._set = l;
  }
  get value() {
    return this._value = this._get();
  }
  set value(n) {
    this._set(n);
  }
}
function n1(e) {
  return new e1(e);
}
class r1 {
  constructor(n, i, o) {
    this._object = n, this._key = i, this._defaultValue = o, this.__v_isRef = !0, this._value = void 0;
  }
  get value() {
    const n = this._object[this._key];
    return this._value = n === void 0 ? this._defaultValue : n;
  }
  set value(n) {
    this._object[this._key] = n;
  }
  get dep() {
    return Dw(Pt(this._object), this._key);
  }
}
class i1 {
  constructor(n) {
    this._getter = n, this.__v_isRef = !0, this.__v_isReadonly = !0, this._value = void 0;
  }
  get value() {
    return this._value = this._getter();
  }
}
function o1(e, n, i) {
  return ie(e) ? e : pt(e) ? new i1(e) : Jt(e) && arguments.length > 1 ? s1(e, n, i) : Pe(e);
}
function s1(e, n, i) {
  const o = e[n];
  return ie(o) ? o : new r1(e, n, i);
}
class l1 {
  constructor(n, i, o) {
    this.fn = n, this.setter = i, this._value = void 0, this.dep = new il(this), this.__v_isRef = !0, this.deps = void 0, this.depsTail = void 0, this.flags = 16, this.globalVersion = wo - 1, this.effect = this, this.__v_isReadonly = !i, this.isSSR = o;
  }
  /**
   * @internal
   */
  notify() {
    if (this.flags |= 16, !(this.flags & 8) && // avoid infinite self recursion
    Ht !== this)
      return xd(this), !0;
  }
  get value() {
    const n = this.dep.track();
    return Ad(this), n && (n.version = this.dep.version), this._value;
  }
  set value(n) {
    this.setter && this.setter(n);
  }
}
function u1(e, n, i = !1) {
  let o, l;
  return pt(e) ? o = e : (o = e.get, l = e.set), new l1(o, l, i);
}
const Ss = {}, Gs = /* @__PURE__ */ new WeakMap();
let qr;
function f1(e, n = !1, i = qr) {
  if (i) {
    let o = Gs.get(i);
    o || Gs.set(i, o = []), o.push(e);
  }
}
function a1(e, n, i = Gt) {
  const { immediate: o, deep: l, once: u, scheduler: a, augmentJob: h, call: g } = i, v = (L) => l ? L : wn(L) || l === !1 || l === 0 ? Tr(L, 1) : Tr(L);
  let _, m, x, A, O = !1, R = !1;
  if (ie(e) ? (m = () => e.value, O = wn(e)) : Oi(e) ? (m = () => v(e), O = !0) : gt(e) ? (R = !0, O = e.some((L) => Oi(L) || wn(L)), m = () => e.map((L) => {
    if (ie(L))
      return L.value;
    if (Oi(L))
      return v(L);
    if (pt(L))
      return g ? g(L, 2) : L();
  })) : pt(e) ? n ? m = g ? () => g(e, 2) : e : m = () => {
    if (x) {
      Pr();
      try {
        x();
      } finally {
        Mr();
      }
    }
    const L = qr;
    qr = _;
    try {
      return g ? g(e, 3, [A]) : e(A);
    } finally {
      qr = L;
    }
  } : m = Un, n && l) {
    const L = m, tt = l === !0 ? 1 / 0 : l;
    m = () => Tr(L(), tt);
  }
  const N = yd(), W = () => {
    _.stop(), N && _f(N.effects, _);
  };
  if (u && n) {
    const L = n;
    n = (...tt) => {
      L(...tt), W();
    };
  }
  let V = R ? new Array(e.length).fill(Ss) : Ss;
  const X = (L) => {
    if (!(!(_.flags & 1) || !_.dirty && !L))
      if (n) {
        const tt = _.run();
        if (l || O || (R ? tt.some((Et, St) => Or(Et, V[St])) : Or(tt, V))) {
          x && x();
          const Et = qr;
          qr = _;
          try {
            const St = [
              tt,
              // pass undefined as the old value when it's changed for the first time
              V === Ss ? void 0 : R && V[0] === Ss ? [] : V,
              A
            ];
            g ? g(n, 3, St) : (
              // @ts-expect-error
              n(...St)
            ), V = tt;
          } finally {
            qr = Et;
          }
        }
      } else
        _.run();
  };
  return h && h(X), _ = new wd(m), _.scheduler = a ? () => a(X, !1) : X, A = (L) => f1(L, !1, _), x = _.onStop = () => {
    const L = Gs.get(_);
    if (L) {
      if (g)
        g(L, 4);
      else
        for (const tt of L)
          tt();
      Gs.delete(_);
    }
  }, n ? o ? X(!0) : V = _.run() : a ? a(X.bind(null, !0), !0) : _.run(), W.pause = _.pause.bind(_), W.resume = _.resume.bind(_), W.stop = W, W;
}
function Tr(e, n = 1 / 0, i) {
  if (n <= 0 || !Jt(e) || e.__v_skip || (i = i || /* @__PURE__ */ new Set(), i.has(e)))
    return e;
  if (i.add(e), n--, ie(e))
    Tr(e.value, n, i);
  else if (gt(e))
    for (let o = 0; o < e.length; o++)
      Tr(e[o], n, i);
  else if (ad(e) || Ci(e))
    e.forEach((o) => {
      Tr(o, n, i);
    });
  else if (dd(e)) {
    for (const o in e)
      Tr(e[o], n, i);
    for (const o of Object.getOwnPropertySymbols(e))
      Object.prototype.propertyIsEnumerable.call(e, o) && Tr(e[o], n, i);
  }
  return e;
}
/**
* @vue/runtime-core v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
function Ao(e, n, i, o) {
  try {
    return o ? e(...o) : e();
  } catch (l) {
    ll(l, n, i);
  }
}
function Kn(e, n, i, o) {
  if (pt(e)) {
    const l = Ao(e, n, i, o);
    return l && cd(l) && l.catch((u) => {
      ll(u, n, i);
    }), l;
  }
  if (gt(e)) {
    const l = [];
    for (let u = 0; u < e.length; u++)
      l.push(Kn(e[u], n, i, o));
    return l;
  }
}
function ll(e, n, i, o = !0) {
  const l = n ? n.vnode : null, { errorHandler: u, throwUnhandledErrorInProduction: a } = n && n.appContext.config || Gt;
  if (n) {
    let h = n.parent;
    const g = n.proxy, v = `https://vuejs.org/error-reference/#runtime-${i}`;
    for (; h; ) {
      const _ = h.ec;
      if (_) {
        for (let m = 0; m < _.length; m++)
          if (_[m](e, g, v) === !1)
            return;
      }
      h = h.parent;
    }
    if (u) {
      Pr(), Ao(u, null, 10, [
        e,
        g,
        v
      ]), Mr();
      return;
    }
  }
  c1(e, i, l, o, a);
}
function c1(e, n, i, o = !0, l = !1) {
  if (l)
    throw e;
  console.error(e);
}
let xo = !1, ju = !1;
const $e = [];
let Ln = 0;
const Ii = [];
let Er = null, xi = 0;
const Bd = /* @__PURE__ */ Promise.resolve();
let If = null;
function Mi(e) {
  const n = If || Bd;
  return e ? n.then(this ? e.bind(this) : e) : n;
}
function h1(e) {
  let n = xo ? Ln + 1 : 0, i = $e.length;
  for (; n < i; ) {
    const o = n + i >>> 1, l = $e[o], u = So(l);
    u < e || u === e && l.flags & 2 ? n = o + 1 : i = o;
  }
  return n;
}
function Df(e) {
  if (!(e.flags & 1)) {
    const n = So(e), i = $e[$e.length - 1];
    !i || // fast path when the job id is larger than the tail
    !(e.flags & 2) && n >= So(i) ? $e.push(e) : $e.splice(h1(n), 0, e), e.flags |= 1, Wd();
  }
}
function Wd() {
  !xo && !ju && (ju = !0, If = Bd.then(Hd));
}
function d1(e) {
  gt(e) ? Ii.push(...e) : Er && e.id === -1 ? Er.splice(xi + 1, 0, e) : e.flags & 1 || (Ii.push(e), e.flags |= 1), Wd();
}
function Sh(e, n, i = xo ? Ln + 1 : 0) {
  for (; i < $e.length; i++) {
    const o = $e[i];
    if (o && o.flags & 2) {
      if (e && o.id !== e.uid)
        continue;
      $e.splice(i, 1), i--, o.flags & 4 && (o.flags &= -2), o(), o.flags &= -2;
    }
  }
}
function Ud(e) {
  if (Ii.length) {
    const n = [...new Set(Ii)].sort(
      (i, o) => So(i) - So(o)
    );
    if (Ii.length = 0, Er) {
      Er.push(...n);
      return;
    }
    for (Er = n, xi = 0; xi < Er.length; xi++) {
      const i = Er[xi];
      i.flags & 4 && (i.flags &= -2), i.flags & 8 || i(), i.flags &= -2;
    }
    Er = null, xi = 0;
  }
}
const So = (e) => e.id == null ? e.flags & 2 ? -1 : 1 / 0 : e.id;
function Hd(e) {
  ju = !1, xo = !0;
  try {
    for (Ln = 0; Ln < $e.length; Ln++) {
      const n = $e[Ln];
      n && !(n.flags & 8) && (n.flags & 4 && (n.flags &= -2), Ao(
        n,
        n.i,
        n.i ? 15 : 14
      ), n.flags &= -2);
    }
  } finally {
    for (; Ln < $e.length; Ln++) {
      const n = $e[Ln];
      n && (n.flags &= -2);
    }
    Ln = 0, $e.length = 0, Ud(), xo = !1, If = null, ($e.length || Ii.length) && Hd();
  }
}
let Ze = null, $d = null;
function Ys(e) {
  const n = Ze;
  return Ze = e, $d = e && e.type.__scopeId || null, n;
}
function Kd(e, n = Ze, i) {
  if (!n || e._n)
    return e;
  const o = (...l) => {
    o._d && Ph(-1);
    const u = Ys(n);
    let a;
    try {
      a = e(...l);
    } finally {
      Ys(u), o._d && Ph(1);
    }
    return a;
  };
  return o._n = !0, o._c = !0, o._d = !0, o;
}
function Gr(e, n, i, o) {
  const l = e.dirs, u = n && n.dirs;
  for (let a = 0; a < l.length; a++) {
    const h = l[a];
    u && (h.oldValue = u[a].value);
    let g = h.dir[o];
    g && (Pr(), Kn(g, i, 8, [
      e.el,
      h,
      e,
      n
    ]), Mr());
  }
}
const p1 = Symbol("_vte"), g1 = (e) => e.__isTeleport;
function Rf(e, n) {
  e.shapeFlag & 6 && e.component ? (e.transition = n, Rf(e.component.subTree, n)) : e.shapeFlag & 128 ? (e.ssContent.transition = n.clone(e.ssContent), e.ssFallback.transition = n.clone(e.ssFallback)) : e.transition = n;
}
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function Pf(e, n) {
  return pt(e) ? (
    // #8236: extend call and options.name access are considered side-effects
    // by Rollup, so we have to wrap it in a pure-annotated IIFE.
    Te({ name: e.name }, n, { setup: e })
  ) : e;
}
function Gd(e) {
  e.ids = [e.ids[0] + e.ids[2]++ + "-", 0, 0];
}
function tf(e, n, i, o, l = !1) {
  if (gt(e)) {
    e.forEach(
      (O, R) => tf(
        O,
        n && (gt(n) ? n[R] : n),
        i,
        o,
        l
      )
    );
    return;
  }
  if (Di(o) && !l)
    return;
  const u = o.shapeFlag & 4 ? Lf(o.component) : o.el, a = l ? null : u, { i: h, r: g } = e, v = n && n.r, _ = h.refs === Gt ? h.refs = {} : h.refs, m = h.setupState, x = Pt(m), A = m === Gt ? () => !1 : (O) => Ft(x, O);
  if (v != null && v !== g && (ce(v) ? (_[v] = null, A(v) && (m[v] = null)) : ie(v) && (v.value = null)), pt(g))
    Ao(g, h, 12, [a, _]);
  else {
    const O = ce(g), R = ie(g);
    if (O || R) {
      const N = () => {
        if (e.f) {
          const W = O ? A(g) ? m[g] : _[g] : g.value;
          l ? gt(W) && _f(W, u) : gt(W) ? W.includes(u) || W.push(u) : O ? (_[g] = [u], A(g) && (m[g] = _[g])) : (g.value = [u], e.k && (_[e.k] = g.value));
        } else
          O ? (_[g] = a, A(g) && (m[g] = a)) : R && (g.value = a, e.k && (_[e.k] = a));
      };
      a ? (N.id = -1, sn(N, i)) : N();
    }
  }
}
const Di = (e) => !!e.type.__asyncLoader, Yd = (e) => e.type.__isKeepAlive;
function v1(e, n) {
  Xd(e, "a", n);
}
function _1(e, n) {
  Xd(e, "da", n);
}
function Xd(e, n, i = Ke) {
  const o = e.__wdc || (e.__wdc = () => {
    let l = i;
    for (; l; ) {
      if (l.isDeactivated)
        return;
      l = l.parent;
    }
    return e();
  });
  if (ul(n, o, i), i) {
    let l = i.parent;
    for (; l && l.parent; )
      Yd(l.parent.vnode) && m1(o, n, i, l), l = l.parent;
  }
}
function m1(e, n, i, o) {
  const l = ul(
    n,
    e,
    o,
    !0
    /* prepend */
  );
  qd(() => {
    _f(o[n], l);
  }, i);
}
function ul(e, n, i = Ke, o = !1) {
  if (i) {
    const l = i[e] || (i[e] = []), u = n.__weh || (n.__weh = (...a) => {
      Pr();
      const h = Co(i), g = Kn(n, i, e, a);
      return h(), Mr(), g;
    });
    return o ? l.unshift(u) : l.push(u), u;
  }
}
const sr = (e) => (n, i = Ke) => {
  (!cl || e === "sp") && ul(e, (...o) => n(...o), i);
}, b1 = sr("bm"), zd = sr("m"), y1 = sr(
  "bu"
), w1 = sr("u"), E1 = sr(
  "bum"
), qd = sr("um"), x1 = sr(
  "sp"
), S1 = sr("rtg"), T1 = sr("rtc");
function A1(e, n = Ke) {
  ul("ec", e, n);
}
const C1 = Symbol.for("v-ndc");
function O1(e, n, i, o) {
  let l;
  const u = i && i[o], a = gt(e);
  if (a || ce(e)) {
    const h = a && Oi(e);
    let g = !1;
    h && (g = !wn(e), e = ol(e)), l = new Array(e.length);
    for (let v = 0, _ = e.length; v < _; v++)
      l[v] = n(
        g ? De(e[v]) : e[v],
        v,
        void 0,
        u && u[v]
      );
  } else if (typeof e == "number") {
    l = new Array(e);
    for (let h = 0; h < e; h++)
      l[h] = n(h + 1, h, void 0, u && u[h]);
  } else if (Jt(e))
    if (e[Symbol.iterator])
      l = Array.from(
        e,
        (h, g) => n(h, g, void 0, u && u[g])
      );
    else {
      const h = Object.keys(e);
      l = new Array(h.length);
      for (let g = 0, v = h.length; g < v; g++) {
        const _ = h[g];
        l[g] = n(e[_], _, g, u && u[g]);
      }
    }
  else
    l = [];
  return i && (i[o] = l), l;
}
function I1(e, n, i = {}, o, l) {
  if (Ze.ce || Ze.parent && Di(Ze.parent) && Ze.parent.ce)
    return n !== "default" && (i.name = n), Hn(), sf(
      un,
      null,
      [En("slot", i, o && o())],
      64
    );
  let u = e[n];
  u && u._c && (u._d = !1), Hn();
  const a = u && Vd(u(i)), h = sf(
    un,
    {
      key: (i.key || // slot content array of a dynamic conditional slot may have a branch
      // key attached in the `createSlots` helper, respect that
      a && a.key || `_${n}`) + // #7256 force differentiate fallback content from actual content
      (!a && o ? "_fb" : "")
    },
    a || (o ? o() : []),
    a && e._ === 1 ? 64 : -2
  );
  return !l && h.scopeId && (h.slotScopeIds = [h.scopeId + "-s"]), u && u._c && (u._d = !0), h;
}
function Vd(e) {
  return e.some((n) => pp(n) ? !(n.type === Ir || n.type === un && !Vd(n.children)) : !0) ? e : null;
}
const ef = (e) => e ? _p(e) ? Lf(e) : ef(e.parent) : null, co = (
  // Move PURE marker to new line to workaround compiler discarding it
  // due to type annotation
  /* @__PURE__ */ Te(/* @__PURE__ */ Object.create(null), {
    $: (e) => e,
    $el: (e) => e.vnode.el,
    $data: (e) => e.data,
    $props: (e) => e.props,
    $attrs: (e) => e.attrs,
    $slots: (e) => e.slots,
    $refs: (e) => e.refs,
    $parent: (e) => ef(e.parent),
    $root: (e) => ef(e.root),
    $host: (e) => e.ce,
    $emit: (e) => e.emit,
    $options: (e) => Mf(e),
    $forceUpdate: (e) => e.f || (e.f = () => {
      Df(e.update);
    }),
    $nextTick: (e) => e.n || (e.n = Mi.bind(e.proxy)),
    $watch: (e) => J1.bind(e)
  })
), Pu = (e, n) => e !== Gt && !e.__isScriptSetup && Ft(e, n), D1 = {
  get({ _: e }, n) {
    if (n === "__v_skip")
      return !0;
    const { ctx: i, setupState: o, data: l, props: u, accessCache: a, type: h, appContext: g } = e;
    let v;
    if (n[0] !== "$") {
      const A = a[n];
      if (A !== void 0)
        switch (A) {
          case 1:
            return o[n];
          case 2:
            return l[n];
          case 4:
            return i[n];
          case 3:
            return u[n];
        }
      else {
        if (Pu(o, n))
          return a[n] = 1, o[n];
        if (l !== Gt && Ft(l, n))
          return a[n] = 2, l[n];
        if (
          // only cache other properties when instance has declared (thus stable)
          // props
          (v = e.propsOptions[0]) && Ft(v, n)
        )
          return a[n] = 3, u[n];
        if (i !== Gt && Ft(i, n))
          return a[n] = 4, i[n];
        nf && (a[n] = 0);
      }
    }
    const _ = co[n];
    let m, x;
    if (_)
      return n === "$attrs" && Me(e.attrs, "get", ""), _(e);
    if (
      // css module (injected by vue-loader)
      (m = h.__cssModules) && (m = m[n])
    )
      return m;
    if (i !== Gt && Ft(i, n))
      return a[n] = 4, i[n];
    if (
      // global properties
      x = g.config.globalProperties, Ft(x, n)
    )
      return x[n];
  },
  set({ _: e }, n, i) {
    const { data: o, setupState: l, ctx: u } = e;
    return Pu(l, n) ? (l[n] = i, !0) : o !== Gt && Ft(o, n) ? (o[n] = i, !0) : Ft(e.props, n) || n[0] === "$" && n.slice(1) in e ? !1 : (u[n] = i, !0);
  },
  has({
    _: { data: e, setupState: n, accessCache: i, ctx: o, appContext: l, propsOptions: u }
  }, a) {
    let h;
    return !!i[a] || e !== Gt && Ft(e, a) || Pu(n, a) || (h = u[0]) && Ft(h, a) || Ft(o, a) || Ft(co, a) || Ft(l.config.globalProperties, a);
  },
  defineProperty(e, n, i) {
    return i.get != null ? e._.accessCache[n] = 0 : Ft(i, "value") && this.set(e, n, i.value, null), Reflect.defineProperty(e, n, i);
  }
};
function Th(e) {
  return gt(e) ? e.reduce(
    (n, i) => (n[i] = null, n),
    {}
  ) : e;
}
let nf = !0;
function R1(e) {
  const n = Mf(e), i = e.proxy, o = e.ctx;
  nf = !1, n.beforeCreate && Ah(n.beforeCreate, e, "bc");
  const {
    // state
    data: l,
    computed: u,
    methods: a,
    watch: h,
    provide: g,
    inject: v,
    // lifecycle
    created: _,
    beforeMount: m,
    mounted: x,
    beforeUpdate: A,
    updated: O,
    activated: R,
    deactivated: N,
    beforeDestroy: W,
    beforeUnmount: V,
    destroyed: X,
    unmounted: L,
    render: tt,
    renderTracked: Et,
    renderTriggered: St,
    errorCaptured: Wt,
    serverPrefetch: ft,
    // public API
    expose: Ot,
    inheritAttrs: Nt,
    // assets
    components: zt,
    directives: Lt,
    filters: pe
  } = n;
  if (v && P1(v, o, null), a)
    for (const lt in a) {
      const rt = a[lt];
      pt(rt) && (o[lt] = rt.bind(i));
    }
  if (l) {
    const lt = l.call(i, i);
    Jt(lt) && (e.data = Tf(lt));
  }
  if (nf = !0, u)
    for (const lt in u) {
      const rt = u[lt], _e = pt(rt) ? rt.bind(i, i) : pt(rt.get) ? rt.get.bind(i, i) : Un, ge = !pt(rt) && pt(rt.set) ? rt.set.bind(i) : Un, qt = Bf({
        get: _e,
        set: ge
      });
      Object.defineProperty(o, lt, {
        enumerable: !0,
        configurable: !0,
        get: () => qt.value,
        set: (oe) => qt.value = oe
      });
    }
  if (h)
    for (const lt in h)
      kd(h[lt], o, i, lt);
  if (g) {
    const lt = pt(g) ? g.call(i) : g;
    Reflect.ownKeys(lt).forEach((rt) => {
      Zd(rt, lt[rt]);
    });
  }
  _ && Ah(_, e, "c");
  function vt(lt, rt) {
    gt(rt) ? rt.forEach((_e) => lt(_e.bind(i))) : rt && lt(rt.bind(i));
  }
  if (vt(b1, m), vt(zd, x), vt(y1, A), vt(w1, O), vt(v1, R), vt(_1, N), vt(A1, Wt), vt(T1, Et), vt(S1, St), vt(E1, V), vt(qd, L), vt(x1, ft), gt(Ot))
    if (Ot.length) {
      const lt = e.exposed || (e.exposed = {});
      Ot.forEach((rt) => {
        Object.defineProperty(lt, rt, {
          get: () => i[rt],
          set: (_e) => i[rt] = _e
        });
      });
    } else
      e.exposed || (e.exposed = {});
  tt && e.render === Un && (e.render = tt), Nt != null && (e.inheritAttrs = Nt), zt && (e.components = zt), Lt && (e.directives = Lt), ft && Gd(e);
}
function P1(e, n, i = Un) {
  gt(e) && (e = rf(e));
  for (const o in e) {
    const l = e[o];
    let u;
    Jt(l) ? "default" in l ? u = ir(
      l.from || o,
      l.default,
      !0
    ) : u = ir(l.from || o) : u = ir(l), ie(u) ? Object.defineProperty(n, o, {
      enumerable: !0,
      configurable: !0,
      get: () => u.value,
      set: (a) => u.value = a
    }) : n[o] = u;
  }
}
function Ah(e, n, i) {
  Kn(
    gt(e) ? e.map((o) => o.bind(n.proxy)) : e.bind(n.proxy),
    n,
    i
  );
}
function kd(e, n, i, o) {
  let l = o.includes(".") ? ap(i, o) : () => i[o];
  if (ce(e)) {
    const u = n[e];
    pt(u) && Ge(l, u);
  } else if (pt(e))
    Ge(l, e.bind(i));
  else if (Jt(e))
    if (gt(e))
      e.forEach((u) => kd(u, n, i, o));
    else {
      const u = pt(e.handler) ? e.handler.bind(i) : n[e.handler];
      pt(u) && Ge(l, u, e);
    }
}
function Mf(e) {
  const n = e.type, { mixins: i, extends: o } = n, {
    mixins: l,
    optionsCache: u,
    config: { optionMergeStrategies: a }
  } = e.appContext, h = u.get(n);
  let g;
  return h ? g = h : !l.length && !i && !o ? g = n : (g = {}, l.length && l.forEach(
    (v) => Xs(g, v, a, !0)
  ), Xs(g, n, a)), Jt(n) && u.set(n, g), g;
}
function Xs(e, n, i, o = !1) {
  const { mixins: l, extends: u } = n;
  u && Xs(e, u, i, !0), l && l.forEach(
    (a) => Xs(e, a, i, !0)
  );
  for (const a in n)
    if (!(o && a === "expose")) {
      const h = M1[a] || i && i[a];
      e[a] = h ? h(e[a], n[a]) : n[a];
    }
  return e;
}
const M1 = {
  data: Ch,
  props: Oh,
  emits: Oh,
  // objects
  methods: io,
  computed: io,
  // lifecycle
  beforeCreate: We,
  created: We,
  beforeMount: We,
  mounted: We,
  beforeUpdate: We,
  updated: We,
  beforeDestroy: We,
  beforeUnmount: We,
  destroyed: We,
  unmounted: We,
  activated: We,
  deactivated: We,
  errorCaptured: We,
  serverPrefetch: We,
  // assets
  components: io,
  directives: io,
  // watch
  watch: N1,
  // provide / inject
  provide: Ch,
  inject: F1
};
function Ch(e, n) {
  return n ? e ? function() {
    return Te(
      pt(e) ? e.call(this, this) : e,
      pt(n) ? n.call(this, this) : n
    );
  } : n : e;
}
function F1(e, n) {
  return io(rf(e), rf(n));
}
function rf(e) {
  if (gt(e)) {
    const n = {};
    for (let i = 0; i < e.length; i++)
      n[e[i]] = e[i];
    return n;
  }
  return e;
}
function We(e, n) {
  return e ? [...new Set([].concat(e, n))] : n;
}
function io(e, n) {
  return e ? Te(/* @__PURE__ */ Object.create(null), e, n) : n;
}
function Oh(e, n) {
  return e ? gt(e) && gt(n) ? [.../* @__PURE__ */ new Set([...e, ...n])] : Te(
    /* @__PURE__ */ Object.create(null),
    Th(e),
    Th(n ?? {})
  ) : n;
}
function N1(e, n) {
  if (!e)
    return n;
  if (!n)
    return e;
  const i = Te(/* @__PURE__ */ Object.create(null), e);
  for (const o in n)
    i[o] = We(e[o], n[o]);
  return i;
}
function Jd() {
  return {
    app: null,
    config: {
      isNativeTag: pw,
      performance: !1,
      globalProperties: {},
      optionMergeStrategies: {},
      errorHandler: void 0,
      warnHandler: void 0,
      compilerOptions: {}
    },
    mixins: [],
    components: {},
    directives: {},
    provides: /* @__PURE__ */ Object.create(null),
    optionsCache: /* @__PURE__ */ new WeakMap(),
    propsCache: /* @__PURE__ */ new WeakMap(),
    emitsCache: /* @__PURE__ */ new WeakMap()
  };
}
let L1 = 0;
function B1(e, n) {
  return function(o, l = null) {
    pt(o) || (o = Te({}, o)), l != null && !Jt(l) && (l = null);
    const u = Jd(), a = /* @__PURE__ */ new WeakSet(), h = [];
    let g = !1;
    const v = u.app = {
      _uid: L1++,
      _component: o,
      _props: l,
      _container: null,
      _context: u,
      _instance: null,
      version: vE,
      get config() {
        return u.config;
      },
      set config(_) {
      },
      use(_, ...m) {
        return a.has(_) || (_ && pt(_.install) ? (a.add(_), _.install(v, ...m)) : pt(_) && (a.add(_), _(v, ...m))), v;
      },
      mixin(_) {
        return u.mixins.includes(_) || u.mixins.push(_), v;
      },
      component(_, m) {
        return m ? (u.components[_] = m, v) : u.components[_];
      },
      directive(_, m) {
        return m ? (u.directives[_] = m, v) : u.directives[_];
      },
      mount(_, m, x) {
        if (!g) {
          const A = v._ceVNode || En(o, l);
          return A.appContext = u, x === !0 ? x = "svg" : x === !1 && (x = void 0), m && n ? n(A, _) : e(A, _, x), g = !0, v._container = _, _.__vue_app__ = v, Lf(A.component);
        }
      },
      onUnmount(_) {
        h.push(_);
      },
      unmount() {
        g && (Kn(
          h,
          v._instance,
          16
        ), e(null, v._container), delete v._container.__vue_app__);
      },
      provide(_, m) {
        return u.provides[_] = m, v;
      },
      runWithContext(_) {
        const m = Ri;
        Ri = v;
        try {
          return _();
        } finally {
          Ri = m;
        }
      }
    };
    return v;
  };
}
let Ri = null;
function Zd(e, n) {
  if (Ke) {
    let i = Ke.provides;
    const o = Ke.parent && Ke.parent.provides;
    o === i && (i = Ke.provides = Object.create(o)), i[e] = n;
  }
}
function ir(e, n, i = !1) {
  const o = Ke || Ze;
  if (o || Ri) {
    const l = Ri ? Ri._context.provides : o ? o.parent == null ? o.vnode.appContext && o.vnode.appContext.provides : o.parent.provides : void 0;
    if (l && e in l)
      return l[e];
    if (arguments.length > 1)
      return i && pt(n) ? n.call(o && o.proxy) : n;
  }
}
const Qd = {}, jd = () => Object.create(Qd), tp = (e) => Object.getPrototypeOf(e) === Qd;
function W1(e, n, i, o = !1) {
  const l = {}, u = jd();
  e.propsDefaults = /* @__PURE__ */ Object.create(null), ep(e, n, l, u);
  for (const a in e.propsOptions[0])
    a in l || (l[a] = void 0);
  i ? e.props = o ? l : Jw(l) : e.type.props ? e.props = l : e.props = u, e.attrs = u;
}
function U1(e, n, i, o) {
  const {
    props: l,
    attrs: u,
    vnode: { patchFlag: a }
  } = e, h = Pt(l), [g] = e.propsOptions;
  let v = !1;
  if (
    // always force full diff in dev
    // - #1942 if hmr is enabled with sfc component
    // - vite#872 non-sfc component used by sfc component
    (o || a > 0) && !(a & 16)
  ) {
    if (a & 8) {
      const _ = e.vnode.dynamicProps;
      for (let m = 0; m < _.length; m++) {
        let x = _[m];
        if (fl(e.emitsOptions, x))
          continue;
        const A = n[x];
        if (g)
          if (Ft(u, x))
            A !== u[x] && (u[x] = A, v = !0);
          else {
            const O = jr(x);
            l[O] = of(
              g,
              h,
              O,
              A,
              e,
              !1
            );
          }
        else
          A !== u[x] && (u[x] = A, v = !0);
      }
    }
  } else {
    ep(e, n, l, u) && (v = !0);
    let _;
    for (const m in h)
      (!n || // for camelCase
      !Ft(n, m) && // it's possible the original props was passed in as kebab-case
      // and converted to camelCase (#955)
      ((_ = Rr(m)) === m || !Ft(n, _))) && (g ? i && // for camelCase
      (i[m] !== void 0 || // for kebab-case
      i[_] !== void 0) && (l[m] = of(
        g,
        h,
        m,
        void 0,
        e,
        !0
      )) : delete l[m]);
    if (u !== h)
      for (const m in u)
        (!n || !Ft(n, m)) && (delete u[m], v = !0);
  }
  v && rr(e.attrs, "set", "");
}
function ep(e, n, i, o) {
  const [l, u] = e.propsOptions;
  let a = !1, h;
  if (n)
    for (let g in n) {
      if (fo(g))
        continue;
      const v = n[g];
      let _;
      l && Ft(l, _ = jr(g)) ? !u || !u.includes(_) ? i[_] = v : (h || (h = {}))[_] = v : fl(e.emitsOptions, g) || (!(g in o) || v !== o[g]) && (o[g] = v, a = !0);
    }
  if (u) {
    const g = Pt(i), v = h || Gt;
    for (let _ = 0; _ < u.length; _++) {
      const m = u[_];
      i[m] = of(
        l,
        g,
        m,
        v[m],
        e,
        !Ft(v, m)
      );
    }
  }
  return a;
}
function of(e, n, i, o, l, u) {
  const a = e[i];
  if (a != null) {
    const h = Ft(a, "default");
    if (h && o === void 0) {
      const g = a.default;
      if (a.type !== Function && !a.skipFactory && pt(g)) {
        const { propsDefaults: v } = l;
        if (i in v)
          o = v[i];
        else {
          const _ = Co(l);
          o = v[i] = g.call(
            null,
            n
          ), _();
        }
      } else
        o = g;
      l.ce && l.ce._setProp(i, o);
    }
    a[
      0
      /* shouldCast */
    ] && (u && !h ? o = !1 : a[
      1
      /* shouldCastTrue */
    ] && (o === "" || o === Rr(i)) && (o = !0));
  }
  return o;
}
const H1 = /* @__PURE__ */ new WeakMap();
function np(e, n, i = !1) {
  const o = i ? H1 : n.propsCache, l = o.get(e);
  if (l)
    return l;
  const u = e.props, a = {}, h = [];
  let g = !1;
  if (!pt(e)) {
    const _ = (m) => {
      g = !0;
      const [x, A] = np(m, n, !0);
      Te(a, x), A && h.push(...A);
    };
    !i && n.mixins.length && n.mixins.forEach(_), e.extends && _(e.extends), e.mixins && e.mixins.forEach(_);
  }
  if (!u && !g)
    return Jt(e) && o.set(e, Ai), Ai;
  if (gt(u))
    for (let _ = 0; _ < u.length; _++) {
      const m = jr(u[_]);
      Ih(m) && (a[m] = Gt);
    }
  else if (u)
    for (const _ in u) {
      const m = jr(_);
      if (Ih(m)) {
        const x = u[_], A = a[m] = gt(x) || pt(x) ? { type: x } : Te({}, x), O = A.type;
        let R = !1, N = !0;
        if (gt(O))
          for (let W = 0; W < O.length; ++W) {
            const V = O[W], X = pt(V) && V.name;
            if (X === "Boolean") {
              R = !0;
              break;
            } else
              X === "String" && (N = !1);
          }
        else
          R = pt(O) && O.name === "Boolean";
        A[
          0
          /* shouldCast */
        ] = R, A[
          1
          /* shouldCastTrue */
        ] = N, (R || Ft(A, "default")) && h.push(m);
      }
    }
  const v = [a, h];
  return Jt(e) && o.set(e, v), v;
}
function Ih(e) {
  return e[0] !== "$" && !fo(e);
}
const rp = (e) => e[0] === "_" || e === "$stable", Ff = (e) => gt(e) ? e.map(Wn) : [Wn(e)], $1 = (e, n, i) => {
  if (n._n)
    return n;
  const o = Kd((...l) => Ff(n(...l)), i);
  return o._c = !1, o;
}, ip = (e, n, i) => {
  const o = e._ctx;
  for (const l in e) {
    if (rp(l))
      continue;
    const u = e[l];
    if (pt(u))
      n[l] = $1(l, u, o);
    else if (u != null) {
      const a = Ff(u);
      n[l] = () => a;
    }
  }
}, op = (e, n) => {
  const i = Ff(n);
  e.slots.default = () => i;
}, sp = (e, n, i) => {
  for (const o in n)
    (i || o !== "_") && (e[o] = n[o]);
}, K1 = (e, n, i) => {
  const o = e.slots = jd();
  if (e.vnode.shapeFlag & 32) {
    const l = n._;
    l ? (sp(o, n, i), i && gd(o, "_", l, !0)) : ip(n, o);
  } else
    n && op(e, n);
}, G1 = (e, n, i) => {
  const { vnode: o, slots: l } = e;
  let u = !0, a = Gt;
  if (o.shapeFlag & 32) {
    const h = n._;
    h ? i && h === 1 ? u = !1 : sp(l, n, i) : (u = !n.$stable, ip(n, l)), a = n;
  } else
    n && (op(e, n), a = { default: 1 });
  if (u)
    for (const h in l)
      !rp(h) && a[h] == null && delete l[h];
}, sn = rE;
function Y1(e) {
  return X1(e);
}
function X1(e, n) {
  const i = vd();
  i.__VUE__ = !0;
  const {
    insert: o,
    remove: l,
    patchProp: u,
    createElement: a,
    createText: h,
    createComment: g,
    setText: v,
    setElementText: _,
    parentNode: m,
    nextSibling: x,
    setScopeId: A = Un,
    insertStaticContent: O
  } = e, R = (y, E, I, $ = null, F = null, H = null, z = void 0, G = null, K = !!E.dynamicChildren) => {
    if (y === E)
      return;
    y && !ji(y, E) && ($ = ei(y), oe(y, F, H, !0), y = null), E.patchFlag === -2 && (K = !1, E.dynamicChildren = null);
    const { type: U, ref: et, shapeFlag: k } = E;
    switch (U) {
      case al:
        N(y, E, I, $);
        break;
      case Ir:
        W(y, E, I, $);
        break;
      case Nu:
        y == null && V(E, I, $, z);
        break;
      case un:
        zt(
          y,
          E,
          I,
          $,
          F,
          H,
          z,
          G,
          K
        );
        break;
      default:
        k & 1 ? tt(
          y,
          E,
          I,
          $,
          F,
          H,
          z,
          G,
          K
        ) : k & 6 ? Lt(
          y,
          E,
          I,
          $,
          F,
          H,
          z,
          G,
          K
        ) : (k & 64 || k & 128) && U.process(
          y,
          E,
          I,
          $,
          F,
          H,
          z,
          G,
          K,
          fr
        );
    }
    et != null && F && tf(et, y && y.ref, H, E || y, !E);
  }, N = (y, E, I, $) => {
    if (y == null)
      o(
        E.el = h(E.children),
        I,
        $
      );
    else {
      const F = E.el = y.el;
      E.children !== y.children && v(F, E.children);
    }
  }, W = (y, E, I, $) => {
    y == null ? o(
      E.el = g(E.children || ""),
      I,
      $
    ) : E.el = y.el;
  }, V = (y, E, I, $) => {
    [y.el, y.anchor] = O(
      y.children,
      E,
      I,
      $,
      y.el,
      y.anchor
    );
  }, X = ({ el: y, anchor: E }, I, $) => {
    let F;
    for (; y && y !== E; )
      F = x(y), o(y, I, $), y = F;
    o(E, I, $);
  }, L = ({ el: y, anchor: E }) => {
    let I;
    for (; y && y !== E; )
      I = x(y), l(y), y = I;
    l(E);
  }, tt = (y, E, I, $, F, H, z, G, K) => {
    E.type === "svg" ? z = "svg" : E.type === "math" && (z = "mathml"), y == null ? Et(
      E,
      I,
      $,
      F,
      H,
      z,
      G,
      K
    ) : ft(
      y,
      E,
      F,
      H,
      z,
      G,
      K
    );
  }, Et = (y, E, I, $, F, H, z, G) => {
    let K, U;
    const { props: et, shapeFlag: k, transition: Z, dirs: ut } = y;
    if (K = y.el = a(
      y.type,
      H,
      et && et.is,
      et
    ), k & 8 ? _(K, y.children) : k & 16 && Wt(
      y.children,
      K,
      null,
      $,
      F,
      Mu(y, H),
      z,
      G
    ), ut && Gr(y, null, $, "created"), St(K, y, y.scopeId, z, $), et) {
      for (const It in et)
        It !== "value" && !fo(It) && u(K, It, null, et[It], H, $);
      "value" in et && u(K, "value", null, et.value, H), (U = et.onVnodeBeforeMount) && Nn(U, $, y);
    }
    ut && Gr(y, null, $, "beforeMount");
    const mt = z1(F, Z);
    mt && Z.beforeEnter(K), o(K, E, I), ((U = et && et.onVnodeMounted) || mt || ut) && sn(() => {
      U && Nn(U, $, y), mt && Z.enter(K), ut && Gr(y, null, $, "mounted");
    }, F);
  }, St = (y, E, I, $, F) => {
    if (I && A(y, I), $)
      for (let H = 0; H < $.length; H++)
        A(y, $[H]);
    if (F) {
      let H = F.subTree;
      if (E === H || hp(H.type) && (H.ssContent === E || H.ssFallback === E)) {
        const z = F.vnode;
        St(
          y,
          z,
          z.scopeId,
          z.slotScopeIds,
          F.parent
        );
      }
    }
  }, Wt = (y, E, I, $, F, H, z, G, K = 0) => {
    for (let U = K; U < y.length; U++) {
      const et = y[U] = G ? xr(y[U]) : Wn(y[U]);
      R(
        null,
        et,
        E,
        I,
        $,
        F,
        H,
        z,
        G
      );
    }
  }, ft = (y, E, I, $, F, H, z) => {
    const G = E.el = y.el;
    let { patchFlag: K, dynamicChildren: U, dirs: et } = E;
    K |= y.patchFlag & 16;
    const k = y.props || Gt, Z = E.props || Gt;
    let ut;
    if (I && Yr(I, !1), (ut = Z.onVnodeBeforeUpdate) && Nn(ut, I, E, y), et && Gr(E, y, I, "beforeUpdate"), I && Yr(I, !0), (k.innerHTML && Z.innerHTML == null || k.textContent && Z.textContent == null) && _(G, ""), U ? Ot(
      y.dynamicChildren,
      U,
      G,
      I,
      $,
      Mu(E, F),
      H
    ) : z || rt(
      y,
      E,
      G,
      null,
      I,
      $,
      Mu(E, F),
      H,
      !1
    ), K > 0) {
      if (K & 16)
        Nt(G, k, Z, I, F);
      else if (K & 2 && k.class !== Z.class && u(G, "class", null, Z.class, F), K & 4 && u(G, "style", k.style, Z.style, F), K & 8) {
        const mt = E.dynamicProps;
        for (let It = 0; It < mt.length; It++) {
          const Dt = mt[It], be = k[Dt], le = Z[Dt];
          (le !== be || Dt === "value") && u(G, Dt, be, le, F, I);
        }
      }
      K & 1 && y.children !== E.children && _(G, E.children);
    } else
      !z && U == null && Nt(G, k, Z, I, F);
    ((ut = Z.onVnodeUpdated) || et) && sn(() => {
      ut && Nn(ut, I, E, y), et && Gr(E, y, I, "updated");
    }, $);
  }, Ot = (y, E, I, $, F, H, z) => {
    for (let G = 0; G < E.length; G++) {
      const K = y[G], U = E[G], et = (
        // oldVNode may be an errored async setup() component inside Suspense
        // which will not have a mounted element
        K.el && // - In the case of a Fragment, we need to provide the actual parent
        // of the Fragment itself so it can move its children.
        (K.type === un || // - In the case of different nodes, there is going to be a replacement
        // which also requires the correct parent container
        !ji(K, U) || // - In the case of a component, it could contain anything.
        K.shapeFlag & 70) ? m(K.el) : (
          // In other cases, the parent container is not actually used so we
          // just pass the block element here to avoid a DOM parentNode call.
          I
        )
      );
      R(
        K,
        U,
        et,
        null,
        $,
        F,
        H,
        z,
        !0
      );
    }
  }, Nt = (y, E, I, $, F) => {
    if (E !== I) {
      if (E !== Gt)
        for (const H in E)
          !fo(H) && !(H in I) && u(
            y,
            H,
            E[H],
            null,
            F,
            $
          );
      for (const H in I) {
        if (fo(H))
          continue;
        const z = I[H], G = E[H];
        z !== G && H !== "value" && u(y, H, G, z, F, $);
      }
      "value" in I && u(y, "value", E.value, I.value, F);
    }
  }, zt = (y, E, I, $, F, H, z, G, K) => {
    const U = E.el = y ? y.el : h(""), et = E.anchor = y ? y.anchor : h("");
    let { patchFlag: k, dynamicChildren: Z, slotScopeIds: ut } = E;
    ut && (G = G ? G.concat(ut) : ut), y == null ? (o(U, I, $), o(et, I, $), Wt(
      // #10007
      // such fragment like `<></>` will be compiled into
      // a fragment which doesn't have a children.
      // In this case fallback to an empty array
      E.children || [],
      I,
      et,
      F,
      H,
      z,
      G,
      K
    )) : k > 0 && k & 64 && Z && // #2715 the previous fragment could've been a BAILed one as a result
    // of renderSlot() with no valid children
    y.dynamicChildren ? (Ot(
      y.dynamicChildren,
      Z,
      I,
      F,
      H,
      z,
      G
    ), // #2080 if the stable fragment has a key, it's a <template v-for> that may
    //  get moved around. Make sure all root level vnodes inherit el.
    // #2134 or if it's a component root, it may also get moved around
    // as the component is being moved.
    (E.key != null || F && E === F.subTree) && lp(
      y,
      E,
      !0
      /* shallow */
    )) : rt(
      y,
      E,
      I,
      et,
      F,
      H,
      z,
      G,
      K
    );
  }, Lt = (y, E, I, $, F, H, z, G, K) => {
    E.slotScopeIds = G, y == null ? E.shapeFlag & 512 ? F.ctx.activate(
      E,
      I,
      $,
      z,
      K
    ) : pe(
      E,
      I,
      $,
      F,
      H,
      z,
      K
    ) : Ae(y, E, K);
  }, pe = (y, E, I, $, F, H, z) => {
    const G = y.component = aE(
      y,
      $,
      F
    );
    if (Yd(y) && (G.ctx.renderer = fr), cE(G, !1, z), G.asyncDep) {
      if (F && F.registerDep(G, vt, z), !y.el) {
        const K = G.subTree = En(Ir);
        W(null, K, E, I);
      }
    } else
      vt(
        G,
        y,
        E,
        I,
        F,
        H,
        z
      );
  }, Ae = (y, E, I) => {
    const $ = E.component = y.component;
    if (eE(y, E, I))
      if ($.asyncDep && !$.asyncResolved) {
        lt($, E, I);
        return;
      } else
        $.next = E, $.update();
    else
      E.el = y.el, $.vnode = E;
  }, vt = (y, E, I, $, F, H, z) => {
    const G = () => {
      if (y.isMounted) {
        let { next: k, bu: Z, u: ut, parent: mt, vnode: It } = y;
        {
          const ye = up(y);
          if (ye) {
            k && (k.el = It.el, lt(y, k, z)), ye.asyncDep.then(() => {
              y.isUnmounted || G();
            });
            return;
          }
        }
        let Dt = k, be;
        Yr(y, !1), k ? (k.el = It.el, lt(y, k, z)) : k = It, Z && Au(Z), (be = k.props && k.props.onVnodeBeforeUpdate) && Nn(be, mt, k, It), Yr(y, !0);
        const le = Fu(y), Fe = y.subTree;
        y.subTree = le, R(
          Fe,
          le,
          // parent may have changed if it's in a teleport
          m(Fe.el),
          // anchor may have changed if it's in a fragment
          ei(Fe),
          y,
          F,
          H
        ), k.el = le.el, Dt === null && nE(y, le.el), ut && sn(ut, F), (be = k.props && k.props.onVnodeUpdated) && sn(
          () => Nn(be, mt, k, It),
          F
        );
      } else {
        let k;
        const { el: Z, props: ut } = E, { bm: mt, m: It, parent: Dt, root: be, type: le } = y, Fe = Di(E);
        if (Yr(y, !1), mt && Au(mt), !Fe && (k = ut && ut.onVnodeBeforeMount) && Nn(k, Dt, E), Yr(y, !0), Z && cr) {
          const ye = () => {
            y.subTree = Fu(y), cr(
              Z,
              y.subTree,
              y,
              F,
              null
            );
          };
          Fe && le.__asyncHydrate ? le.__asyncHydrate(
            Z,
            y,
            ye
          ) : ye();
        } else {
          be.ce && be.ce._injectChildStyle(le);
          const ye = y.subTree = Fu(y);
          R(
            null,
            ye,
            I,
            $,
            y,
            F,
            H
          ), E.el = ye.el;
        }
        if (It && sn(It, F), !Fe && (k = ut && ut.onVnodeMounted)) {
          const ye = E;
          sn(
            () => Nn(k, Dt, ye),
            F
          );
        }
        (E.shapeFlag & 256 || Dt && Di(Dt.vnode) && Dt.vnode.shapeFlag & 256) && y.a && sn(y.a, F), y.isMounted = !0, E = I = $ = null;
      }
    };
    y.scope.on();
    const K = y.effect = new wd(G);
    y.scope.off();
    const U = y.update = K.run.bind(K), et = y.job = K.runIfDirty.bind(K);
    et.i = y, et.id = y.uid, K.scheduler = () => Df(et), Yr(y, !0), U();
  }, lt = (y, E, I) => {
    E.component = y;
    const $ = y.vnode.props;
    y.vnode = E, y.next = null, U1(y, E.props, $, I), G1(y, E.children, I), Pr(), Sh(y), Mr();
  }, rt = (y, E, I, $, F, H, z, G, K = !1) => {
    const U = y && y.children, et = y ? y.shapeFlag : 0, k = E.children, { patchFlag: Z, shapeFlag: ut } = E;
    if (Z > 0) {
      if (Z & 128) {
        ge(
          U,
          k,
          I,
          $,
          F,
          H,
          z,
          G,
          K
        );
        return;
      } else if (Z & 256) {
        _e(
          U,
          k,
          I,
          $,
          F,
          H,
          z,
          G,
          K
        );
        return;
      }
    }
    ut & 8 ? (et & 16 && Rn(U, F, H), k !== U && _(I, k)) : et & 16 ? ut & 16 ? ge(
      U,
      k,
      I,
      $,
      F,
      H,
      z,
      G,
      K
    ) : Rn(U, F, H, !0) : (et & 8 && _(I, ""), ut & 16 && Wt(
      k,
      I,
      $,
      F,
      H,
      z,
      G,
      K
    ));
  }, _e = (y, E, I, $, F, H, z, G, K) => {
    y = y || Ai, E = E || Ai;
    const U = y.length, et = E.length, k = Math.min(U, et);
    let Z;
    for (Z = 0; Z < k; Z++) {
      const ut = E[Z] = K ? xr(E[Z]) : Wn(E[Z]);
      R(
        y[Z],
        ut,
        I,
        null,
        F,
        H,
        z,
        G,
        K
      );
    }
    U > et ? Rn(
      y,
      F,
      H,
      !0,
      !1,
      k
    ) : Wt(
      E,
      I,
      $,
      F,
      H,
      z,
      G,
      K,
      k
    );
  }, ge = (y, E, I, $, F, H, z, G, K) => {
    let U = 0;
    const et = E.length;
    let k = y.length - 1, Z = et - 1;
    for (; U <= k && U <= Z; ) {
      const ut = y[U], mt = E[U] = K ? xr(E[U]) : Wn(E[U]);
      if (ji(ut, mt))
        R(
          ut,
          mt,
          I,
          null,
          F,
          H,
          z,
          G,
          K
        );
      else
        break;
      U++;
    }
    for (; U <= k && U <= Z; ) {
      const ut = y[k], mt = E[Z] = K ? xr(E[Z]) : Wn(E[Z]);
      if (ji(ut, mt))
        R(
          ut,
          mt,
          I,
          null,
          F,
          H,
          z,
          G,
          K
        );
      else
        break;
      k--, Z--;
    }
    if (U > k) {
      if (U <= Z) {
        const ut = Z + 1, mt = ut < et ? E[ut].el : $;
        for (; U <= Z; )
          R(
            null,
            E[U] = K ? xr(E[U]) : Wn(E[U]),
            I,
            mt,
            F,
            H,
            z,
            G,
            K
          ), U++;
      }
    } else if (U > Z)
      for (; U <= k; )
        oe(y[U], F, H, !0), U++;
    else {
      const ut = U, mt = U, It = /* @__PURE__ */ new Map();
      for (U = mt; U <= Z; U++) {
        const we = E[U] = K ? xr(E[U]) : Wn(E[U]);
        we.key != null && It.set(we.key, U);
      }
      let Dt, be = 0;
      const le = Z - mt + 1;
      let Fe = !1, ye = 0;
      const Xn = new Array(le);
      for (U = 0; U < le; U++)
        Xn[U] = 0;
      for (U = ut; U <= k; U++) {
        const we = y[U];
        if (be >= le) {
          oe(we, F, H, !0);
          continue;
        }
        let Ye;
        if (we.key != null)
          Ye = It.get(we.key);
        else
          for (Dt = mt; Dt <= Z; Dt++)
            if (Xn[Dt - mt] === 0 && ji(we, E[Dt])) {
              Ye = Dt;
              break;
            }
        Ye === void 0 ? oe(we, F, H, !0) : (Xn[Ye - mt] = U + 1, Ye >= ye ? ye = Ye : Fe = !0, R(
          we,
          E[Ye],
          I,
          null,
          F,
          H,
          z,
          G,
          K
        ), be++);
      }
      const ni = Fe ? q1(Xn) : Ai;
      for (Dt = ni.length - 1, U = le - 1; U >= 0; U--) {
        const we = mt + U, Ye = E[we], Do = we + 1 < et ? E[we + 1].el : $;
        Xn[U] === 0 ? R(
          null,
          Ye,
          I,
          Do,
          F,
          H,
          z,
          G,
          K
        ) : Fe && (Dt < 0 || U !== ni[Dt] ? qt(Ye, I, Do, 2) : Dt--);
      }
    }
  }, qt = (y, E, I, $, F = null) => {
    const { el: H, type: z, transition: G, children: K, shapeFlag: U } = y;
    if (U & 6) {
      qt(y.component.subTree, E, I, $);
      return;
    }
    if (U & 128) {
      y.suspense.move(E, I, $);
      return;
    }
    if (U & 64) {
      z.move(y, E, I, fr);
      return;
    }
    if (z === un) {
      o(H, E, I);
      for (let k = 0; k < K.length; k++)
        qt(K[k], E, I, $);
      o(y.anchor, E, I);
      return;
    }
    if (z === Nu) {
      X(y, E, I);
      return;
    }
    if ($ !== 2 && U & 1 && G)
      if ($ === 0)
        G.beforeEnter(H), o(H, E, I), sn(() => G.enter(H), F);
      else {
        const { leave: k, delayLeave: Z, afterLeave: ut } = G, mt = () => o(H, E, I), It = () => {
          k(H, () => {
            mt(), ut && ut();
          });
        };
        Z ? Z(H, mt, It) : It();
      }
    else
      o(H, E, I);
  }, oe = (y, E, I, $ = !1, F = !1) => {
    const {
      type: H,
      props: z,
      ref: G,
      children: K,
      dynamicChildren: U,
      shapeFlag: et,
      patchFlag: k,
      dirs: Z,
      cacheIndex: ut
    } = y;
    if (k === -2 && (F = !1), G != null && tf(G, null, I, y, !0), ut != null && (E.renderCache[ut] = void 0), et & 256) {
      E.ctx.deactivate(y);
      return;
    }
    const mt = et & 1 && Z, It = !Di(y);
    let Dt;
    if (It && (Dt = z && z.onVnodeBeforeUnmount) && Nn(Dt, E, y), et & 6)
      me(y.component, I, $);
    else {
      if (et & 128) {
        y.suspense.unmount(I, $);
        return;
      }
      mt && Gr(y, null, E, "beforeUnmount"), et & 64 ? y.type.remove(
        y,
        E,
        I,
        fr,
        $
      ) : U && // #5154
      // when v-once is used inside a block, setBlockTracking(-1) marks the
      // parent block with hasOnce: true
      // so that it doesn't take the fast path during unmount - otherwise
      // components nested in v-once are never unmounted.
      !U.hasOnce && // #1153: fast path should not be taken for non-stable (v-for) fragments
      (H !== un || k > 0 && k & 64) ? Rn(
        U,
        E,
        I,
        !1,
        !0
      ) : (H === un && k & 384 || !F && et & 16) && Rn(K, E, I), $ && cn(y);
    }
    (It && (Dt = z && z.onVnodeUnmounted) || mt) && sn(() => {
      Dt && Nn(Dt, E, y), mt && Gr(y, null, E, "unmounted");
    }, I);
  }, cn = (y) => {
    const { type: E, el: I, anchor: $, transition: F } = y;
    if (E === un) {
      se(I, $);
      return;
    }
    if (E === Nu) {
      L(y);
      return;
    }
    const H = () => {
      l(I), F && !F.persisted && F.afterLeave && F.afterLeave();
    };
    if (y.shapeFlag & 1 && F && !F.persisted) {
      const { leave: z, delayLeave: G } = F, K = () => z(I, H);
      G ? G(y.el, H, K) : K();
    } else
      H();
  }, se = (y, E) => {
    let I;
    for (; y !== E; )
      I = x(y), l(y), y = I;
    l(E);
  }, me = (y, E, I) => {
    const { bum: $, scope: F, job: H, subTree: z, um: G, m: K, a: U } = y;
    Dh(K), Dh(U), $ && Au($), F.stop(), H && (H.flags |= 8, oe(z, y, E, I)), G && sn(G, E), sn(() => {
      y.isUnmounted = !0;
    }, E), E && E.pendingBranch && !E.isUnmounted && y.asyncDep && !y.asyncResolved && y.suspenseId === E.pendingId && (E.deps--, E.deps === 0 && E.resolve());
  }, Rn = (y, E, I, $ = !1, F = !1, H = 0) => {
    for (let z = H; z < y.length; z++)
      oe(y[z], E, I, $, F);
  }, ei = (y) => {
    if (y.shapeFlag & 6)
      return ei(y.component.subTree);
    if (y.shapeFlag & 128)
      return y.suspense.next();
    const E = x(y.anchor || y.el), I = E && E[p1];
    return I ? x(I) : E;
  };
  let Yn = !1;
  const ur = (y, E, I) => {
    y == null ? E._vnode && oe(E._vnode, null, null, !0) : R(
      E._vnode || null,
      y,
      E,
      null,
      null,
      null,
      I
    ), E._vnode = y, Yn || (Yn = !0, Sh(), Ud(), Yn = !1);
  }, fr = {
    p: R,
    um: oe,
    m: qt,
    r: cn,
    mt: pe,
    mc: Wt,
    pc: rt,
    pbc: Ot,
    n: ei,
    o: e
  };
  let ar, cr;
  return n && ([ar, cr] = n(
    fr
  )), {
    render: ur,
    hydrate: ar,
    createApp: B1(ur, ar)
  };
}
function Mu({ type: e, props: n }, i) {
  return i === "svg" && e === "foreignObject" || i === "mathml" && e === "annotation-xml" && n && n.encoding && n.encoding.includes("html") ? void 0 : i;
}
function Yr({ effect: e, job: n }, i) {
  i ? (e.flags |= 32, n.flags |= 4) : (e.flags &= -33, n.flags &= -5);
}
function z1(e, n) {
  return (!e || e && !e.pendingBranch) && n && !n.persisted;
}
function lp(e, n, i = !1) {
  const o = e.children, l = n.children;
  if (gt(o) && gt(l))
    for (let u = 0; u < o.length; u++) {
      const a = o[u];
      let h = l[u];
      h.shapeFlag & 1 && !h.dynamicChildren && ((h.patchFlag <= 0 || h.patchFlag === 32) && (h = l[u] = xr(l[u]), h.el = a.el), !i && h.patchFlag !== -2 && lp(a, h)), h.type === al && (h.el = a.el);
    }
}
function q1(e) {
  const n = e.slice(), i = [0];
  let o, l, u, a, h;
  const g = e.length;
  for (o = 0; o < g; o++) {
    const v = e[o];
    if (v !== 0) {
      if (l = i[i.length - 1], e[l] < v) {
        n[o] = l, i.push(o);
        continue;
      }
      for (u = 0, a = i.length - 1; u < a; )
        h = u + a >> 1, e[i[h]] < v ? u = h + 1 : a = h;
      v < e[i[u]] && (u > 0 && (n[o] = i[u - 1]), i[u] = o);
    }
  }
  for (u = i.length, a = i[u - 1]; u-- > 0; )
    i[u] = a, a = n[a];
  return i;
}
function up(e) {
  const n = e.subTree.component;
  if (n)
    return n.asyncDep && !n.asyncResolved ? n : up(n);
}
function Dh(e) {
  if (e)
    for (let n = 0; n < e.length; n++)
      e[n].flags |= 8;
}
const V1 = Symbol.for("v-scx"), k1 = () => ir(V1);
function Ge(e, n, i) {
  return fp(e, n, i);
}
function fp(e, n, i = Gt) {
  const { immediate: o, deep: l, flush: u, once: a } = i, h = Te({}, i);
  let g;
  if (cl)
    if (u === "sync") {
      const x = k1();
      g = x.__watcherHandles || (x.__watcherHandles = []);
    } else if (!n || o)
      h.once = !0;
    else {
      const x = () => {
      };
      return x.stop = Un, x.resume = Un, x.pause = Un, x;
    }
  const v = Ke;
  h.call = (x, A, O) => Kn(x, v, A, O);
  let _ = !1;
  u === "post" ? h.scheduler = (x) => {
    sn(x, v && v.suspense);
  } : u !== "sync" && (_ = !0, h.scheduler = (x, A) => {
    A ? x() : Df(x);
  }), h.augmentJob = (x) => {
    n && (x.flags |= 4), _ && (x.flags |= 2, v && (x.id = v.uid, x.i = v));
  };
  const m = a1(e, n, h);
  return g && g.push(m), m;
}
function J1(e, n, i) {
  const o = this.proxy, l = ce(e) ? e.includes(".") ? ap(o, e) : () => o[e] : e.bind(o, o);
  let u;
  pt(n) ? u = n : (u = n.handler, i = n);
  const a = Co(this), h = fp(l, u.bind(o), i);
  return a(), h;
}
function ap(e, n) {
  const i = n.split(".");
  return () => {
    let o = e;
    for (let l = 0; l < i.length && o; l++)
      o = o[i[l]];
    return o;
  };
}
const Z1 = (e, n) => n === "modelValue" || n === "model-value" ? e.modelModifiers : e[`${n}Modifiers`] || e[`${jr(n)}Modifiers`] || e[`${Rr(n)}Modifiers`];
function Q1(e, n, ...i) {
  if (e.isUnmounted)
    return;
  const o = e.vnode.props || Gt;
  let l = i;
  const u = n.startsWith("update:"), a = u && Z1(o, n.slice(7));
  a && (a.trim && (l = i.map((_) => ce(_) ? _.trim() : _)), a.number && (l = i.map(bw)));
  let h, g = o[h = Tu(n)] || // also try camelCase event handler (#2249)
  o[h = Tu(jr(n))];
  !g && u && (g = o[h = Tu(Rr(n))]), g && Kn(
    g,
    e,
    6,
    l
  );
  const v = o[h + "Once"];
  if (v) {
    if (!e.emitted)
      e.emitted = {};
    else if (e.emitted[h])
      return;
    e.emitted[h] = !0, Kn(
      v,
      e,
      6,
      l
    );
  }
}
function cp(e, n, i = !1) {
  const o = n.emitsCache, l = o.get(e);
  if (l !== void 0)
    return l;
  const u = e.emits;
  let a = {}, h = !1;
  if (!pt(e)) {
    const g = (v) => {
      const _ = cp(v, n, !0);
      _ && (h = !0, Te(a, _));
    };
    !i && n.mixins.length && n.mixins.forEach(g), e.extends && g(e.extends), e.mixins && e.mixins.forEach(g);
  }
  return !u && !h ? (Jt(e) && o.set(e, null), null) : (gt(u) ? u.forEach((g) => a[g] = null) : Te(a, u), Jt(e) && o.set(e, a), a);
}
function fl(e, n) {
  return !e || !tl(n) ? !1 : (n = n.slice(2).replace(/Once$/, ""), Ft(e, n[0].toLowerCase() + n.slice(1)) || Ft(e, Rr(n)) || Ft(e, n));
}
function Fu(e) {
  const {
    type: n,
    vnode: i,
    proxy: o,
    withProxy: l,
    propsOptions: [u],
    slots: a,
    attrs: h,
    emit: g,
    render: v,
    renderCache: _,
    props: m,
    data: x,
    setupState: A,
    ctx: O,
    inheritAttrs: R
  } = e, N = Ys(e);
  let W, V;
  try {
    if (i.shapeFlag & 4) {
      const L = l || o, tt = L;
      W = Wn(
        v.call(
          tt,
          L,
          _,
          m,
          A,
          x,
          O
        )
      ), V = h;
    } else {
      const L = n;
      W = Wn(
        L.length > 1 ? L(
          m,
          { attrs: h, slots: a, emit: g }
        ) : L(
          m,
          null
        )
      ), V = n.props ? h : j1(h);
    }
  } catch (L) {
    ho.length = 0, ll(L, e, 1), W = En(Ir);
  }
  let X = W;
  if (V && R !== !1) {
    const L = Object.keys(V), { shapeFlag: tt } = X;
    L.length && tt & 7 && (u && L.some(vf) && (V = tE(
      V,
      u
    )), X = Fi(X, V, !1, !0));
  }
  return i.dirs && (X = Fi(X, null, !1, !0), X.dirs = X.dirs ? X.dirs.concat(i.dirs) : i.dirs), i.transition && Rf(X, i.transition), W = X, Ys(N), W;
}
const j1 = (e) => {
  let n;
  for (const i in e)
    (i === "class" || i === "style" || tl(i)) && ((n || (n = {}))[i] = e[i]);
  return n;
}, tE = (e, n) => {
  const i = {};
  for (const o in e)
    (!vf(o) || !(o.slice(9) in n)) && (i[o] = e[o]);
  return i;
};
function eE(e, n, i) {
  const { props: o, children: l, component: u } = e, { props: a, children: h, patchFlag: g } = n, v = u.emitsOptions;
  if (n.dirs || n.transition)
    return !0;
  if (i && g >= 0) {
    if (g & 1024)
      return !0;
    if (g & 16)
      return o ? Rh(o, a, v) : !!a;
    if (g & 8) {
      const _ = n.dynamicProps;
      for (let m = 0; m < _.length; m++) {
        const x = _[m];
        if (a[x] !== o[x] && !fl(v, x))
          return !0;
      }
    }
  } else
    return (l || h) && (!h || !h.$stable) ? !0 : o === a ? !1 : o ? a ? Rh(o, a, v) : !0 : !!a;
  return !1;
}
function Rh(e, n, i) {
  const o = Object.keys(n);
  if (o.length !== Object.keys(e).length)
    return !0;
  for (let l = 0; l < o.length; l++) {
    const u = o[l];
    if (n[u] !== e[u] && !fl(i, u))
      return !0;
  }
  return !1;
}
function nE({ vnode: e, parent: n }, i) {
  for (; n; ) {
    const o = n.subTree;
    if (o.suspense && o.suspense.activeBranch === e && (o.el = e.el), o === e)
      (e = n.vnode).el = i, n = n.parent;
    else
      break;
  }
}
const hp = (e) => e.__isSuspense;
function rE(e, n) {
  n && n.pendingBranch ? gt(e) ? n.effects.push(...e) : n.effects.push(e) : d1(e);
}
const un = Symbol.for("v-fgt"), al = Symbol.for("v-txt"), Ir = Symbol.for("v-cmt"), Nu = Symbol.for("v-stc"), ho = [];
let an = null;
function Hn(e = !1) {
  ho.push(an = e ? null : []);
}
function iE() {
  ho.pop(), an = ho[ho.length - 1] || null;
}
let To = 1;
function Ph(e) {
  To += e, e < 0 && an && (an.hasOnce = !0);
}
function dp(e) {
  return e.dynamicChildren = To > 0 ? an || Ai : null, iE(), To > 0 && an && an.push(e), e;
}
function Jr(e, n, i, o, l, u) {
  return dp(
    Bn(
      e,
      n,
      i,
      o,
      l,
      u,
      !0
    )
  );
}
function sf(e, n, i, o, l) {
  return dp(
    En(
      e,
      n,
      i,
      o,
      l,
      !0
    )
  );
}
function pp(e) {
  return e ? e.__v_isVNode === !0 : !1;
}
function ji(e, n) {
  return e.type === n.type && e.key === n.key;
}
const gp = ({ key: e }) => e ?? null, Fs = ({
  ref: e,
  ref_key: n,
  ref_for: i
}) => (typeof e == "number" && (e = "" + e), e != null ? ce(e) || ie(e) || pt(e) ? { i: Ze, r: e, k: n, f: !!i } : e : null);
function Bn(e, n = null, i = null, o = 0, l = null, u = e === un ? 0 : 1, a = !1, h = !1) {
  const g = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: e,
    props: n,
    key: n && gp(n),
    ref: n && Fs(n),
    scopeId: $d,
    slotScopeIds: null,
    children: i,
    component: null,
    suspense: null,
    ssContent: null,
    ssFallback: null,
    dirs: null,
    transition: null,
    el: null,
    anchor: null,
    target: null,
    targetStart: null,
    targetAnchor: null,
    staticCount: 0,
    shapeFlag: u,
    patchFlag: o,
    dynamicProps: l,
    dynamicChildren: null,
    appContext: null,
    ctx: Ze
  };
  return h ? (Nf(g, i), u & 128 && e.normalize(g)) : i && (g.shapeFlag |= ce(i) ? 8 : 16), To > 0 && // avoid a block node from tracking itself
  !a && // has current parent block
  an && // presence of a patch flag indicates this node needs patching on updates.
  // component nodes also should always be patched, because even if the
  // component doesn't need to update, it needs to persist the instance on to
  // the next vnode so that it can be properly unmounted later.
  (g.patchFlag > 0 || u & 6) && // the EVENTS flag is only for hydration and if it is the only flag, the
  // vnode should not be considered dynamic due to handler caching.
  g.patchFlag !== 32 && an.push(g), g;
}
const En = oE;
function oE(e, n = null, i = null, o = 0, l = null, u = !1) {
  if ((!e || e === C1) && (e = Ir), pp(e)) {
    const h = Fi(
      e,
      n,
      !0
      /* mergeRef: true */
    );
    return i && Nf(h, i), To > 0 && !u && an && (h.shapeFlag & 6 ? an[an.indexOf(e)] = h : an.push(h)), h.patchFlag = -2, h;
  }
  if (gE(e) && (e = e.__vccOpts), n) {
    n = sE(n);
    let { class: h, style: g } = n;
    h && !ce(h) && (n.class = rl(h)), Jt(g) && (Cf(g) && !gt(g) && (g = Te({}, g)), n.style = bf(g));
  }
  const a = ce(e) ? 1 : hp(e) ? 128 : g1(e) ? 64 : Jt(e) ? 4 : pt(e) ? 2 : 0;
  return Bn(
    e,
    n,
    i,
    o,
    l,
    a,
    u,
    !0
  );
}
function sE(e) {
  return e ? Cf(e) || tp(e) ? Te({}, e) : e : null;
}
function Fi(e, n, i = !1, o = !1) {
  const { props: l, ref: u, patchFlag: a, children: h, transition: g } = e, v = n ? lE(l || {}, n) : l, _ = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: e.type,
    props: v,
    key: v && gp(v),
    ref: n && n.ref ? (
      // #2078 in the case of <component :is="vnode" ref="extra"/>
      // if the vnode itself already has a ref, cloneVNode will need to merge
      // the refs so the single vnode can be set on multiple refs
      i && u ? gt(u) ? u.concat(Fs(n)) : [u, Fs(n)] : Fs(n)
    ) : u,
    scopeId: e.scopeId,
    slotScopeIds: e.slotScopeIds,
    children: h,
    target: e.target,
    targetStart: e.targetStart,
    targetAnchor: e.targetAnchor,
    staticCount: e.staticCount,
    shapeFlag: e.shapeFlag,
    // if the vnode is cloned with extra props, we can no longer assume its
    // existing patch flag to be reliable and need to add the FULL_PROPS flag.
    // note: preserve flag for fragments since they use the flag for children
    // fast paths only.
    patchFlag: n && e.type !== un ? a === -1 ? 16 : a | 16 : a,
    dynamicProps: e.dynamicProps,
    dynamicChildren: e.dynamicChildren,
    appContext: e.appContext,
    dirs: e.dirs,
    transition: g,
    // These should technically only be non-null on mounted VNodes. However,
    // they *should* be copied for kept-alive vnodes. So we just always copy
    // them since them being non-null during a mount doesn't affect the logic as
    // they will simply be overwritten.
    component: e.component,
    suspense: e.suspense,
    ssContent: e.ssContent && Fi(e.ssContent),
    ssFallback: e.ssFallback && Fi(e.ssFallback),
    el: e.el,
    anchor: e.anchor,
    ctx: e.ctx,
    ce: e.ce
  };
  return g && o && Rf(
    _,
    g.clone(_)
  ), _;
}
function vp(e = " ", n = 0) {
  return En(al, null, e, n);
}
function Mh(e = "", n = !1) {
  return n ? (Hn(), sf(Ir, null, e)) : En(Ir, null, e);
}
function Wn(e) {
  return e == null || typeof e == "boolean" ? En(Ir) : gt(e) ? En(
    un,
    null,
    // #3666, avoid reference pollution when reusing vnode
    e.slice()
  ) : typeof e == "object" ? xr(e) : En(al, null, String(e));
}
function xr(e) {
  return e.el === null && e.patchFlag !== -1 || e.memo ? e : Fi(e);
}
function Nf(e, n) {
  let i = 0;
  const { shapeFlag: o } = e;
  if (n == null)
    n = null;
  else if (gt(n))
    i = 16;
  else if (typeof n == "object")
    if (o & 65) {
      const l = n.default;
      l && (l._c && (l._d = !1), Nf(e, l()), l._c && (l._d = !0));
      return;
    } else {
      i = 32;
      const l = n._;
      !l && !tp(n) ? n._ctx = Ze : l === 3 && Ze && (Ze.slots._ === 1 ? n._ = 1 : (n._ = 2, e.patchFlag |= 1024));
    }
  else
    pt(n) ? (n = { default: n, _ctx: Ze }, i = 32) : (n = String(n), o & 64 ? (i = 16, n = [vp(n)]) : i = 8);
  e.children = n, e.shapeFlag |= i;
}
function lE(...e) {
  const n = {};
  for (let i = 0; i < e.length; i++) {
    const o = e[i];
    for (const l in o)
      if (l === "class")
        n.class !== o.class && (n.class = rl([n.class, o.class]));
      else if (l === "style")
        n.style = bf([n.style, o.style]);
      else if (tl(l)) {
        const u = n[l], a = o[l];
        a && u !== a && !(gt(u) && u.includes(a)) && (n[l] = u ? [].concat(u, a) : a);
      } else
        l !== "" && (n[l] = o[l]);
  }
  return n;
}
function Nn(e, n, i, o = null) {
  Kn(e, n, 7, [
    i,
    o
  ]);
}
const uE = Jd();
let fE = 0;
function aE(e, n, i) {
  const o = e.type, l = (n ? n.appContext : e.appContext) || uE, u = {
    uid: fE++,
    vnode: e,
    type: o,
    parent: n,
    appContext: l,
    root: null,
    // to be immediately set
    next: null,
    subTree: null,
    // will be set synchronously right after creation
    effect: null,
    update: null,
    // will be set synchronously right after creation
    job: null,
    scope: new Aw(
      !0
      /* detached */
    ),
    render: null,
    proxy: null,
    exposed: null,
    exposeProxy: null,
    withProxy: null,
    provides: n ? n.provides : Object.create(l.provides),
    ids: n ? n.ids : ["", 0, 0],
    accessCache: null,
    renderCache: [],
    // local resolved assets
    components: null,
    directives: null,
    // resolved props and emits options
    propsOptions: np(o, l),
    emitsOptions: cp(o, l),
    // emit
    emit: null,
    // to be set immediately
    emitted: null,
    // props default value
    propsDefaults: Gt,
    // inheritAttrs
    inheritAttrs: o.inheritAttrs,
    // state
    ctx: Gt,
    data: Gt,
    props: Gt,
    attrs: Gt,
    slots: Gt,
    refs: Gt,
    setupState: Gt,
    setupContext: null,
    // suspense related
    suspense: i,
    suspenseId: i ? i.pendingId : 0,
    asyncDep: null,
    asyncResolved: !1,
    // lifecycle hooks
    // not using enums here because it results in computed properties
    isMounted: !1,
    isUnmounted: !1,
    isDeactivated: !1,
    bc: null,
    c: null,
    bm: null,
    m: null,
    bu: null,
    u: null,
    um: null,
    bum: null,
    da: null,
    a: null,
    rtg: null,
    rtc: null,
    ec: null,
    sp: null
  };
  return u.ctx = { _: u }, u.root = n ? n.root : u, u.emit = Q1.bind(null, u), e.ce && e.ce(u), u;
}
let Ke = null, zs, lf;
{
  const e = vd(), n = (i, o) => {
    let l;
    return (l = e[i]) || (l = e[i] = []), l.push(o), (u) => {
      l.length > 1 ? l.forEach((a) => a(u)) : l[0](u);
    };
  };
  zs = n(
    "__VUE_INSTANCE_SETTERS__",
    (i) => Ke = i
  ), lf = n(
    "__VUE_SSR_SETTERS__",
    (i) => cl = i
  );
}
const Co = (e) => {
  const n = Ke;
  return zs(e), e.scope.on(), () => {
    e.scope.off(), zs(n);
  };
}, Fh = () => {
  Ke && Ke.scope.off(), zs(null);
};
function _p(e) {
  return e.vnode.shapeFlag & 4;
}
let cl = !1;
function cE(e, n = !1, i = !1) {
  n && lf(n);
  const { props: o, children: l } = e.vnode, u = _p(e);
  W1(e, o, u, n), K1(e, l, i);
  const a = u ? hE(e, n) : void 0;
  return n && lf(!1), a;
}
function hE(e, n) {
  const i = e.type;
  e.accessCache = /* @__PURE__ */ Object.create(null), e.proxy = new Proxy(e.ctx, D1);
  const { setup: o } = i;
  if (o) {
    const l = e.setupContext = o.length > 1 ? pE(e) : null, u = Co(e);
    Pr();
    const a = Ao(
      o,
      e,
      0,
      [
        e.props,
        l
      ]
    );
    if (Mr(), u(), cd(a)) {
      if (Di(e) || Gd(e), a.then(Fh, Fh), n)
        return a.then((h) => {
          Nh(e, h, n);
        }).catch((h) => {
          ll(h, e, 0);
        });
      e.asyncDep = a;
    } else
      Nh(e, a, n);
  } else
    mp(e, n);
}
function Nh(e, n, i) {
  pt(n) ? e.type.__ssrInlineRender ? e.ssrRender = n : e.render = n : Jt(n) && (e.setupState = Ld(n)), mp(e, i);
}
let Lh;
function mp(e, n, i) {
  const o = e.type;
  if (!e.render) {
    if (!n && Lh && !o.render) {
      const l = o.template || Mf(e).template;
      if (l) {
        const { isCustomElement: u, compilerOptions: a } = e.appContext.config, { delimiters: h, compilerOptions: g } = o, v = Te(
          Te(
            {
              isCustomElement: u,
              delimiters: h
            },
            a
          ),
          g
        );
        o.render = Lh(l, v);
      }
    }
    e.render = o.render || Un;
  }
  {
    const l = Co(e);
    Pr();
    try {
      R1(e);
    } finally {
      Mr(), l();
    }
  }
}
const dE = {
  get(e, n) {
    return Me(e, "get", ""), e[n];
  }
};
function pE(e) {
  const n = (i) => {
    e.exposed = i || {};
  };
  return {
    attrs: new Proxy(e.attrs, dE),
    slots: e.slots,
    emit: e.emit,
    expose: n
  };
}
function Lf(e) {
  return e.exposed ? e.exposeProxy || (e.exposeProxy = new Proxy(Ld(Zw(e.exposed)), {
    get(n, i) {
      if (i in n)
        return n[i];
      if (i in co)
        return co[i](e);
    },
    has(n, i) {
      return i in n || i in co;
    }
  })) : e.proxy;
}
function gE(e) {
  return pt(e) && "__vccOpts" in e;
}
const Bf = (e, n) => u1(e, n, cl), vE = "3.5.6";
/**
* @vue/runtime-dom v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let uf;
const Bh = typeof window < "u" && window.trustedTypes;
if (Bh)
  try {
    uf = /* @__PURE__ */ Bh.createPolicy("vue", {
      createHTML: (e) => e
    });
  } catch {
  }
const bp = uf ? (e) => uf.createHTML(e) : (e) => e, _E = "http://www.w3.org/2000/svg", mE = "http://www.w3.org/1998/Math/MathML", nr = typeof document < "u" ? document : null, Wh = nr && /* @__PURE__ */ nr.createElement("template"), bE = {
  insert: (e, n, i) => {
    n.insertBefore(e, i || null);
  },
  remove: (e) => {
    const n = e.parentNode;
    n && n.removeChild(e);
  },
  createElement: (e, n, i, o) => {
    const l = n === "svg" ? nr.createElementNS(_E, e) : n === "mathml" ? nr.createElementNS(mE, e) : i ? nr.createElement(e, { is: i }) : nr.createElement(e);
    return e === "select" && o && o.multiple != null && l.setAttribute("multiple", o.multiple), l;
  },
  createText: (e) => nr.createTextNode(e),
  createComment: (e) => nr.createComment(e),
  setText: (e, n) => {
    e.nodeValue = n;
  },
  setElementText: (e, n) => {
    e.textContent = n;
  },
  parentNode: (e) => e.parentNode,
  nextSibling: (e) => e.nextSibling,
  querySelector: (e) => nr.querySelector(e),
  setScopeId(e, n) {
    e.setAttribute(n, "");
  },
  // __UNSAFE__
  // Reason: innerHTML.
  // Static content here can only come from compiled templates.
  // As long as the user only uses trusted templates, this is safe.
  insertStaticContent(e, n, i, o, l, u) {
    const a = i ? i.previousSibling : n.lastChild;
    if (l && (l === u || l.nextSibling))
      for (; n.insertBefore(l.cloneNode(!0), i), !(l === u || !(l = l.nextSibling)); )
        ;
    else {
      Wh.innerHTML = bp(
        o === "svg" ? `<svg>${e}</svg>` : o === "mathml" ? `<math>${e}</math>` : e
      );
      const h = Wh.content;
      if (o === "svg" || o === "mathml") {
        const g = h.firstChild;
        for (; g.firstChild; )
          h.appendChild(g.firstChild);
        h.removeChild(g);
      }
      n.insertBefore(h, i);
    }
    return [
      // first
      a ? a.nextSibling : n.firstChild,
      // last
      i ? i.previousSibling : n.lastChild
    ];
  }
}, yE = Symbol("_vtc");
function wE(e, n, i) {
  const o = e[yE];
  o && (n = (n ? [n, ...o] : [...o]).join(" ")), n == null ? e.removeAttribute("class") : i ? e.setAttribute("class", n) : e.className = n;
}
const Uh = Symbol("_vod"), EE = Symbol("_vsh"), xE = Symbol(""), SE = /(^|;)\s*display\s*:/;
function TE(e, n, i) {
  const o = e.style, l = ce(i);
  let u = !1;
  if (i && !l) {
    if (n)
      if (ce(n))
        for (const a of n.split(";")) {
          const h = a.slice(0, a.indexOf(":")).trim();
          i[h] == null && Ns(o, h, "");
        }
      else
        for (const a in n)
          i[a] == null && Ns(o, a, "");
    for (const a in i)
      a === "display" && (u = !0), Ns(o, a, i[a]);
  } else if (l) {
    if (n !== i) {
      const a = o[xE];
      a && (i += ";" + a), o.cssText = i, u = SE.test(i);
    }
  } else
    n && e.removeAttribute("style");
  Uh in e && (e[Uh] = u ? o.display : "", e[EE] && (o.display = "none"));
}
const Hh = /\s*!important$/;
function Ns(e, n, i) {
  if (gt(i))
    i.forEach((o) => Ns(e, n, o));
  else if (i == null && (i = ""), n.startsWith("--"))
    e.setProperty(n, i);
  else {
    const o = AE(e, n);
    Hh.test(i) ? e.setProperty(
      Rr(o),
      i.replace(Hh, ""),
      "important"
    ) : e[o] = i;
  }
}
const $h = ["Webkit", "Moz", "ms"], Lu = {};
function AE(e, n) {
  const i = Lu[n];
  if (i)
    return i;
  let o = jr(n);
  if (o !== "filter" && o in e)
    return Lu[n] = o;
  o = pd(o);
  for (let l = 0; l < $h.length; l++) {
    const u = $h[l] + o;
    if (u in e)
      return Lu[n] = u;
  }
  return n;
}
const Kh = "http://www.w3.org/1999/xlink";
function Gh(e, n, i, o, l, u = Tw(n)) {
  o && n.startsWith("xlink:") ? i == null ? e.removeAttributeNS(Kh, n.slice(6, n.length)) : e.setAttributeNS(Kh, n, i) : i == null || u && !_d(i) ? e.removeAttribute(n) : e.setAttribute(
    n,
    u ? "" : Dr(i) ? String(i) : i
  );
}
function CE(e, n, i, o) {
  if (n === "innerHTML" || n === "textContent") {
    i != null && (e[n] = n === "innerHTML" ? bp(i) : i);
    return;
  }
  const l = e.tagName;
  if (n === "value" && l !== "PROGRESS" && // custom elements may use _value internally
  !l.includes("-")) {
    const a = l === "OPTION" ? e.getAttribute("value") || "" : e.value, h = i == null ? (
      // #11647: value should be set as empty string for null and undefined,
      // but <input type="checkbox"> should be set as 'on'.
      e.type === "checkbox" ? "on" : ""
    ) : String(i);
    (a !== h || !("_value" in e)) && (e.value = h), i == null && e.removeAttribute(n), e._value = i;
    return;
  }
  let u = !1;
  if (i === "" || i == null) {
    const a = typeof e[n];
    a === "boolean" ? i = _d(i) : i == null && a === "string" ? (i = "", u = !0) : a === "number" && (i = 0, u = !0);
  }
  try {
    e[n] = i;
  } catch {
  }
  u && e.removeAttribute(n);
}
function OE(e, n, i, o) {
  e.addEventListener(n, i, o);
}
function IE(e, n, i, o) {
  e.removeEventListener(n, i, o);
}
const Yh = Symbol("_vei");
function DE(e, n, i, o, l = null) {
  const u = e[Yh] || (e[Yh] = {}), a = u[n];
  if (o && a)
    a.value = o;
  else {
    const [h, g] = RE(n);
    if (o) {
      const v = u[n] = FE(
        o,
        l
      );
      OE(e, h, v, g);
    } else
      a && (IE(e, h, a, g), u[n] = void 0);
  }
}
const Xh = /(?:Once|Passive|Capture)$/;
function RE(e) {
  let n;
  if (Xh.test(e)) {
    n = {};
    let o;
    for (; o = e.match(Xh); )
      e = e.slice(0, e.length - o[0].length), n[o[0].toLowerCase()] = !0;
  }
  return [e[2] === ":" ? e.slice(3) : Rr(e.slice(2)), n];
}
let Bu = 0;
const PE = /* @__PURE__ */ Promise.resolve(), ME = () => Bu || (PE.then(() => Bu = 0), Bu = Date.now());
function FE(e, n) {
  const i = (o) => {
    if (!o._vts)
      o._vts = Date.now();
    else if (o._vts <= i.attached)
      return;
    Kn(
      NE(o, i.value),
      n,
      5,
      [o]
    );
  };
  return i.value = e, i.attached = ME(), i;
}
function NE(e, n) {
  if (gt(n)) {
    const i = e.stopImmediatePropagation;
    return e.stopImmediatePropagation = () => {
      i.call(e), e._stopped = !0;
    }, n.map(
      (o) => (l) => !l._stopped && o && o(l)
    );
  } else
    return n;
}
const zh = (e) => e.charCodeAt(0) === 111 && e.charCodeAt(1) === 110 && // lowercase letter
e.charCodeAt(2) > 96 && e.charCodeAt(2) < 123, LE = (e, n, i, o, l, u) => {
  const a = l === "svg";
  n === "class" ? wE(e, o, a) : n === "style" ? TE(e, i, o) : tl(n) ? vf(n) || DE(e, n, i, o, u) : (n[0] === "." ? (n = n.slice(1), !0) : n[0] === "^" ? (n = n.slice(1), !1) : BE(e, n, o, a)) ? (CE(e, n, o), !e.tagName.includes("-") && (n === "value" || n === "checked" || n === "selected") && Gh(e, n, o, a, u, n !== "value")) : (n === "true-value" ? e._trueValue = o : n === "false-value" && (e._falseValue = o), Gh(e, n, o, a));
};
function BE(e, n, i, o) {
  if (o)
    return !!(n === "innerHTML" || n === "textContent" || n in e && zh(n) && pt(i));
  if (n === "spellcheck" || n === "draggable" || n === "translate" || n === "form" || n === "list" && e.tagName === "INPUT" || n === "type" && e.tagName === "TEXTAREA")
    return !1;
  if (n === "width" || n === "height") {
    const l = e.tagName;
    if (l === "IMG" || l === "VIDEO" || l === "CANVAS" || l === "SOURCE")
      return !1;
  }
  return zh(n) && ce(i) ? !1 : !!(n in e || e._isVueCE && (/[A-Z]/.test(n) || !ce(i)));
}
const WE = {
  esc: "escape",
  space: " ",
  up: "arrow-up",
  left: "arrow-left",
  right: "arrow-right",
  down: "arrow-down",
  delete: "backspace"
}, mi = (e, n) => {
  const i = e._withKeys || (e._withKeys = {}), o = n.join(".");
  return i[o] || (i[o] = (l) => {
    if (!("key" in l))
      return;
    const u = Rr(l.key);
    if (n.some(
      (a) => a === u || WE[a] === u
    ))
      return e(l);
  });
}, UE = /* @__PURE__ */ Te({ patchProp: LE }, bE);
let qh;
function HE() {
  return qh || (qh = Y1(UE));
}
const $E = (...e) => {
  const n = HE().createApp(...e), { mount: i } = n;
  return n.mount = (o) => {
    const l = GE(o);
    if (!l)
      return;
    const u = n._component;
    !pt(u) && !u.render && !u.template && (u.template = l.innerHTML), l.nodeType === 1 && (l.textContent = "");
    const a = i(l, !1, KE(l));
    return l instanceof Element && (l.removeAttribute("v-cloak"), l.setAttribute("data-v-app", "")), a;
  }, n;
};
function KE(e) {
  if (e instanceof SVGElement)
    return "svg";
  if (typeof MathMLElement == "function" && e instanceof MathMLElement)
    return "mathml";
}
function GE(e) {
  return ce(e) ? document.querySelector(e) : e;
}
function Ni(e) {
  return yd() ? (Cw(e), !0) : !1;
}
function Wu() {
  const e = /* @__PURE__ */ new Set(), n = (l) => {
    e.delete(l);
  };
  return {
    on: (l) => {
      e.add(l);
      const u = () => n(l);
      return Ni(u), {
        off: u
      };
    },
    off: n,
    trigger: (...l) => Promise.all(Array.from(e).map((u) => u(...l)))
  };
}
function fn(e) {
  return typeof e == "function" ? e() : re(e);
}
const Zr = typeof window < "u" && typeof document < "u", YE = typeof WorkerGlobalScope < "u" && globalThis instanceof WorkerGlobalScope, XE = Object.prototype.toString, zE = (e) => XE.call(e) === "[object Object]", yp = () => {
};
function Vh(e, n = !1, i = "Timeout") {
  return new Promise((o, l) => {
    setTimeout(n ? () => l(i) : o, e);
  });
}
function qE(e, ...n) {
  return n.some((i) => i in e);
}
function Ls(...e) {
  if (e.length !== 1)
    return o1(...e);
  const n = e[0];
  return typeof n == "function" ? Pi(n1(() => ({ get: n, set: yp }))) : Pe(n);
}
function ff(e, n = !1) {
  function i(m, { flush: x = "sync", deep: A = !1, timeout: O, throwOnTimeout: R } = {}) {
    let N = null;
    const V = [new Promise((X) => {
      N = Ge(
        e,
        (L) => {
          m(L) !== n && (N ? N() : Mi(() => N == null ? void 0 : N()), X(L));
        },
        {
          flush: x,
          deep: A,
          immediate: !0
        }
      );
    })];
    return O != null && V.push(
      Vh(O, R).then(() => fn(e)).finally(() => N == null ? void 0 : N())
    ), Promise.race(V);
  }
  function o(m, x) {
    if (!ie(m))
      return i((L) => L === m, x);
    const { flush: A = "sync", deep: O = !1, timeout: R, throwOnTimeout: N } = x ?? {};
    let W = null;
    const X = [new Promise((L) => {
      W = Ge(
        [e, m],
        ([tt, Et]) => {
          n !== (tt === Et) && (W ? W() : Mi(() => W == null ? void 0 : W()), L(tt));
        },
        {
          flush: A,
          deep: O,
          immediate: !0
        }
      );
    })];
    return R != null && X.push(
      Vh(R, N).then(() => fn(e)).finally(() => (W == null || W(), fn(e)))
    ), Promise.race(X);
  }
  function l(m) {
    return i((x) => !!x, m);
  }
  function u(m) {
    return o(null, m);
  }
  function a(m) {
    return o(void 0, m);
  }
  function h(m) {
    return i(Number.isNaN, m);
  }
  function g(m, x) {
    return i((A) => {
      const O = Array.from(A);
      return O.includes(m) || O.includes(fn(m));
    }, x);
  }
  function v(m) {
    return _(1, m);
  }
  function _(m = 1, x) {
    let A = -1;
    return i(() => (A += 1, A >= m), x);
  }
  return Array.isArray(fn(e)) ? {
    toMatch: i,
    toContains: g,
    changed: v,
    changedTimes: _,
    get not() {
      return ff(e, !n);
    }
  } : {
    toMatch: i,
    toBe: o,
    toBeTruthy: l,
    toBeNull: u,
    toBeNaN: h,
    toBeUndefined: a,
    changed: v,
    changedTimes: _,
    get not() {
      return ff(e, !n);
    }
  };
}
function VE(e) {
  return ff(e);
}
function kE(e, n = 1e3, i = {}) {
  const {
    immediate: o = !0,
    immediateCallback: l = !1
  } = i;
  let u = null;
  const a = Pe(!1);
  function h() {
    u && (clearInterval(u), u = null);
  }
  function g() {
    a.value = !1, h();
  }
  function v() {
    const _ = fn(n);
    _ <= 0 || (a.value = !0, l && e(), h(), u = setInterval(e, _));
  }
  if (o && Zr && v(), ie(n) || typeof n == "function") {
    const _ = Ge(n, () => {
      a.value && Zr && v();
    });
    Ni(_);
  }
  return Ni(g), {
    isActive: a,
    pause: g,
    resume: v
  };
}
function JE(e, n, i = {}) {
  const {
    immediate: o = !0
  } = i, l = Pe(!1);
  let u = null;
  function a() {
    u && (clearTimeout(u), u = null);
  }
  function h() {
    l.value = !1, a();
  }
  function g(...v) {
    a(), l.value = !0, u = setTimeout(() => {
      l.value = !1, u = null, e(...v);
    }, fn(n));
  }
  return o && (l.value = !0, Zr && g()), Ni(h), {
    isPending: Pi(l),
    start: g,
    stop: h
  };
}
const wp = Zr ? window : void 0;
function ZE(e) {
  var n;
  const i = fn(e);
  return (n = i == null ? void 0 : i.$el) != null ? n : i;
}
function QE(...e) {
  let n, i, o, l;
  if (typeof e[0] == "string" || Array.isArray(e[0]) ? ([i, o, l] = e, n = wp) : [n, i, o, l] = e, !n)
    return yp;
  Array.isArray(i) || (i = [i]), Array.isArray(o) || (o = [o]);
  const u = [], a = () => {
    u.forEach((_) => _()), u.length = 0;
  }, h = (_, m, x, A) => (_.addEventListener(m, x, A), () => _.removeEventListener(m, x, A)), g = Ge(
    () => [ZE(n), fn(l)],
    ([_, m]) => {
      if (a(), !_)
        return;
      const x = zE(m) ? { ...m } : m;
      u.push(
        ...i.flatMap((A) => o.map((O) => h(_, A, O, x)))
      );
    },
    { immediate: !0, flush: "post" }
  ), v = () => {
    g(), a();
  };
  return Ni(v), v;
}
const jE = {
  json: "application/json",
  text: "text/plain"
};
function qs(e) {
  return e && qE(e, "immediate", "refetch", "initialData", "timeout", "beforeFetch", "afterFetch", "onFetchError", "fetch", "updateDataOnError");
}
const tx = /^(?:[a-z][a-z\d+\-.]*:)?\/\//i;
function ex(e) {
  return tx.test(e);
}
function po(e) {
  return typeof Headers < "u" && e instanceof Headers ? Object.fromEntries(e.entries()) : e;
}
function bi(e, ...n) {
  return e === "overwrite" ? async (i) => {
    const o = n[n.length - 1];
    return o ? { ...i, ...await o(i) } : i;
  } : async (i) => {
    for (const o of n)
      o && (i = { ...i, ...await o(i) });
    return i;
  };
}
function nx(e = {}) {
  const n = e.combination || "chain", i = e.options || {}, o = e.fetchOptions || {};
  function l(u, ...a) {
    const h = Bf(() => {
      const _ = fn(e.baseUrl), m = fn(u);
      return _ && !ex(m) ? ix(_, m) : m;
    });
    let g = i, v = o;
    return a.length > 0 && (qs(a[0]) ? g = {
      ...g,
      ...a[0],
      beforeFetch: bi(n, i.beforeFetch, a[0].beforeFetch),
      afterFetch: bi(n, i.afterFetch, a[0].afterFetch),
      onFetchError: bi(n, i.onFetchError, a[0].onFetchError)
    } : v = {
      ...v,
      ...a[0],
      headers: {
        ...po(v.headers) || {},
        ...po(a[0].headers) || {}
      }
    }), a.length > 1 && qs(a[1]) && (g = {
      ...g,
      ...a[1],
      beforeFetch: bi(n, i.beforeFetch, a[1].beforeFetch),
      afterFetch: bi(n, i.afterFetch, a[1].afterFetch),
      onFetchError: bi(n, i.onFetchError, a[1].onFetchError)
    }), rx(h, v, g);
  }
  return l;
}
function rx(e, ...n) {
  var i;
  const o = typeof AbortController == "function";
  let l = {}, u = {
    immediate: !0,
    refetch: !1,
    timeout: 0,
    updateDataOnError: !1
  };
  const a = {
    method: "GET",
    type: "text",
    payload: void 0
  };
  n.length > 0 && (qs(n[0]) ? u = { ...u, ...n[0] } : l = n[0]), n.length > 1 && qs(n[1]) && (u = { ...u, ...n[1] });
  const {
    fetch: h = (i = wp) == null ? void 0 : i.fetch,
    initialData: g,
    timeout: v
  } = u, _ = Wu(), m = Wu(), x = Wu(), A = Pe(!1), O = Pe(!1), R = Pe(!1), N = Pe(null), W = Ru(null), V = Ru(null), X = Ru(g || null), L = Bf(() => o && O.value);
  let tt, Et;
  const St = () => {
    o && (tt == null || tt.abort(), tt = new AbortController(), tt.signal.onabort = () => R.value = !0, l = {
      ...l,
      signal: tt.signal
    });
  }, Wt = (vt) => {
    O.value = vt, A.value = !vt;
  };
  v && (Et = JE(St, v, { immediate: !1 }));
  let ft = 0;
  const Ot = async (vt = !1) => {
    var lt, rt;
    St(), Wt(!0), V.value = null, N.value = null, R.value = !1, ft += 1;
    const _e = ft, ge = {
      method: a.method,
      headers: {}
    };
    if (a.payload) {
      const se = po(ge.headers), me = fn(a.payload);
      !a.payloadType && me && Object.getPrototypeOf(me) === Object.prototype && !(me instanceof FormData) && (a.payloadType = "json"), a.payloadType && (se["Content-Type"] = (lt = jE[a.payloadType]) != null ? lt : a.payloadType), ge.body = a.payloadType === "json" ? JSON.stringify(me) : me;
    }
    let qt = !1;
    const oe = {
      url: fn(e),
      options: {
        ...ge,
        ...l
      },
      cancel: () => {
        qt = !0;
      }
    };
    if (u.beforeFetch && Object.assign(oe, await u.beforeFetch(oe)), qt || !h)
      return Wt(!1), Promise.resolve(null);
    let cn = null;
    return Et && Et.start(), h(
      oe.url,
      {
        ...ge,
        ...oe.options,
        headers: {
          ...po(ge.headers),
          ...po((rt = oe.options) == null ? void 0 : rt.headers)
        }
      }
    ).then(async (se) => {
      if (W.value = se, N.value = se.status, cn = await se.clone()[a.type](), !se.ok)
        throw X.value = g || null, new Error(se.statusText);
      return u.afterFetch && ({ data: cn } = await u.afterFetch({
        data: cn,
        response: se
      })), X.value = cn, _.trigger(se), se;
    }).catch(async (se) => {
      let me = se.message || se.name;
      if (u.onFetchError && ({ error: me, data: cn } = await u.onFetchError({
        data: cn,
        error: se,
        response: W.value
      })), V.value = me, u.updateDataOnError && (X.value = cn), m.trigger(se), vt)
        throw se;
      return null;
    }).finally(() => {
      _e === ft && Wt(!1), Et && Et.stop(), x.trigger(null);
    });
  }, Nt = Ls(u.refetch);
  Ge(
    [
      Nt,
      Ls(e)
    ],
    ([vt]) => vt && Ot(),
    { deep: !0 }
  );
  const zt = {
    isFinished: Pi(A),
    isFetching: Pi(O),
    statusCode: N,
    response: W,
    error: V,
    data: X,
    canAbort: L,
    aborted: R,
    abort: St,
    execute: Ot,
    onFetchResponse: _.on,
    onFetchError: m.on,
    onFetchFinally: x.on,
    // method
    get: Lt("GET"),
    put: Lt("PUT"),
    post: Lt("POST"),
    delete: Lt("DELETE"),
    patch: Lt("PATCH"),
    head: Lt("HEAD"),
    options: Lt("OPTIONS"),
    // type
    json: Ae("json"),
    text: Ae("text"),
    blob: Ae("blob"),
    arrayBuffer: Ae("arrayBuffer"),
    formData: Ae("formData")
  };
  function Lt(vt) {
    return (lt, rt) => {
      if (!O.value)
        return a.method = vt, a.payload = lt, a.payloadType = rt, ie(a.payload) && Ge(
          [
            Nt,
            Ls(a.payload)
          ],
          ([_e]) => _e && Ot(),
          { deep: !0 }
        ), {
          ...zt,
          then(_e, ge) {
            return pe().then(_e, ge);
          }
        };
    };
  }
  function pe() {
    return new Promise((vt, lt) => {
      VE(A).toBe(!0).then(() => vt(zt)).catch((rt) => lt(rt));
    });
  }
  function Ae(vt) {
    return () => {
      if (!O.value)
        return a.type = vt, {
          ...zt,
          then(lt, rt) {
            return pe().then(lt, rt);
          }
        };
    };
  }
  return u.immediate && Promise.resolve().then(() => Ot()), {
    ...zt,
    then(vt, lt) {
      return pe().then(vt, lt);
    }
  };
}
function ix(e, n) {
  return !e.endsWith("/") && !n.startsWith("/") ? `${e}/${n}` : `${e}${n}`;
}
const kh = "ping";
function Uu(e) {
  return e === !0 ? {} : e;
}
function ox(e, n = {}) {
  const {
    onConnected: i,
    onDisconnected: o,
    onError: l,
    onMessage: u,
    immediate: a = !0,
    autoClose: h = !0,
    protocols: g = []
  } = n, v = Pe(null), _ = Pe("CLOSED"), m = Pe(), x = Ls(e);
  let A, O, R = !1, N = 0, W = [], V;
  const X = () => {
    if (W.length && m.value && _.value === "OPEN") {
      for (const ft of W)
        m.value.send(ft);
      W = [];
    }
  }, L = () => {
    clearTimeout(V), V = void 0;
  }, tt = (ft = 1e3, Ot) => {
    !Zr || !m.value || (R = !0, L(), A == null || A(), m.value.close(ft, Ot), m.value = void 0);
  }, Et = (ft, Ot = !0) => !m.value || _.value !== "OPEN" ? (Ot && W.push(ft), !1) : (X(), m.value.send(ft), !0), St = () => {
    if (R || typeof x.value > "u")
      return;
    const ft = new WebSocket(x.value, g);
    m.value = ft, _.value = "CONNECTING", ft.onopen = () => {
      _.value = "OPEN", N = 0, i == null || i(ft), O == null || O(), X();
    }, ft.onclose = (Ot) => {
      if (_.value = "CLOSED", o == null || o(ft, Ot), !R && n.autoReconnect && ft === m.value) {
        const {
          retries: Nt = -1,
          delay: zt = 1e3,
          onFailed: Lt
        } = Uu(n.autoReconnect);
        typeof Nt == "number" && (Nt < 0 || N < Nt) ? (N += 1, setTimeout(St, zt)) : typeof Nt == "function" && Nt() ? setTimeout(St, zt) : Lt == null || Lt();
      }
    }, ft.onerror = (Ot) => {
      l == null || l(ft, Ot);
    }, ft.onmessage = (Ot) => {
      if (n.heartbeat) {
        L();
        const {
          message: Nt = kh,
          responseMessage: zt = Nt
        } = Uu(n.heartbeat);
        if (Ot.data === zt)
          return;
      }
      v.value = Ot.data, u == null || u(ft, Ot);
    };
  };
  if (n.heartbeat) {
    const {
      message: ft = kh,
      interval: Ot = 1e3,
      pongTimeout: Nt = 1e3
    } = Uu(n.heartbeat), { pause: zt, resume: Lt } = kE(
      () => {
        Et(ft, !1), V == null && (V = setTimeout(() => {
          tt(), R = !1;
        }, Nt));
      },
      Ot,
      { immediate: !1 }
    );
    A = zt, O = Lt;
  }
  h && (Zr && QE("beforeunload", () => tt()), Ni(tt));
  const Wt = () => {
    !Zr && !YE || (tt(), R = !1, N = 0, St());
  };
  return a && Wt(), Ge(x, Wt), {
    data: v,
    status: _,
    close: tt,
    send: Et,
    open: Wt,
    ws: m
  };
}
var to = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, Vs = { exports: {} };
/**
 * @license
 * Lodash <https://lodash.com/>
 * Copyright OpenJS Foundation and other contributors <https://openjsf.org/>
 * Released under MIT license <https://lodash.com/license>
 * Based on Underscore.js 1.8.3 <http://underscorejs.org/LICENSE>
 * Copyright Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 */
Vs.exports;
(function(e, n) {
  (function() {
    var i, o = "4.17.21", l = 200, u = "Unsupported core-js use. Try https://npms.io/search?q=ponyfill.", a = "Expected a function", h = "Invalid `variable` option passed into `_.template`", g = "__lodash_hash_undefined__", v = 500, _ = "__lodash_placeholder__", m = 1, x = 2, A = 4, O = 1, R = 2, N = 1, W = 2, V = 4, X = 8, L = 16, tt = 32, Et = 64, St = 128, Wt = 256, ft = 512, Ot = 30, Nt = "...", zt = 800, Lt = 16, pe = 1, Ae = 2, vt = 3, lt = 1 / 0, rt = 9007199254740991, _e = 17976931348623157e292, ge = NaN, qt = 4294967295, oe = qt - 1, cn = qt >>> 1, se = [
      ["ary", St],
      ["bind", N],
      ["bindKey", W],
      ["curry", X],
      ["curryRight", L],
      ["flip", ft],
      ["partial", tt],
      ["partialRight", Et],
      ["rearg", Wt]
    ], me = "[object Arguments]", Rn = "[object Array]", ei = "[object AsyncFunction]", Yn = "[object Boolean]", ur = "[object Date]", fr = "[object DOMException]", ar = "[object Error]", cr = "[object Function]", y = "[object GeneratorFunction]", E = "[object Map]", I = "[object Number]", $ = "[object Null]", F = "[object Object]", H = "[object Promise]", z = "[object Proxy]", G = "[object RegExp]", K = "[object Set]", U = "[object String]", et = "[object Symbol]", k = "[object Undefined]", Z = "[object WeakMap]", ut = "[object WeakSet]", mt = "[object ArrayBuffer]", It = "[object DataView]", Dt = "[object Float32Array]", be = "[object Float64Array]", le = "[object Int8Array]", Fe = "[object Int16Array]", ye = "[object Int32Array]", Xn = "[object Uint8Array]", ni = "[object Uint8ClampedArray]", we = "[object Uint16Array]", Ye = "[object Uint32Array]", Do = /\b__p \+= '';/g, Lp = /\b(__p \+=) '' \+/g, Bp = /(__e\(.*?\)|\b__t\)) \+\n'';/g, Kf = /&(?:amp|lt|gt|quot|#39);/g, Gf = /[&<>"']/g, Wp = RegExp(Kf.source), Up = RegExp(Gf.source), Hp = /<%-([\s\S]+?)%>/g, $p = /<%([\s\S]+?)%>/g, Yf = /<%=([\s\S]+?)%>/g, Kp = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/, Gp = /^\w*$/, Yp = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g, dl = /[\\^$.*+?()[\]{}|]/g, Xp = RegExp(dl.source), pl = /^\s+/, zp = /\s/, qp = /\{(?:\n\/\* \[wrapped with .+\] \*\/)?\n?/, Vp = /\{\n\/\* \[wrapped with (.+)\] \*/, kp = /,? & /, Jp = /[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g, Zp = /[()=,{}\[\]\/\s]/, Qp = /\\(\\)?/g, jp = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g, Xf = /\w*$/, tg = /^[-+]0x[0-9a-f]+$/i, eg = /^0b[01]+$/i, ng = /^\[object .+?Constructor\]$/, rg = /^0o[0-7]+$/i, ig = /^(?:0|[1-9]\d*)$/, og = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g, Ro = /($^)/, sg = /['\n\r\u2028\u2029\\]/g, Po = "\\ud800-\\udfff", lg = "\\u0300-\\u036f", ug = "\\ufe20-\\ufe2f", fg = "\\u20d0-\\u20ff", zf = lg + ug + fg, qf = "\\u2700-\\u27bf", Vf = "a-z\\xdf-\\xf6\\xf8-\\xff", ag = "\\xac\\xb1\\xd7\\xf7", cg = "\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf", hg = "\\u2000-\\u206f", dg = " \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000", kf = "A-Z\\xc0-\\xd6\\xd8-\\xde", Jf = "\\ufe0e\\ufe0f", Zf = ag + cg + hg + dg, gl = "['’]", pg = "[" + Po + "]", Qf = "[" + Zf + "]", Mo = "[" + zf + "]", jf = "\\d+", gg = "[" + qf + "]", ta = "[" + Vf + "]", ea = "[^" + Po + Zf + jf + qf + Vf + kf + "]", vl = "\\ud83c[\\udffb-\\udfff]", vg = "(?:" + Mo + "|" + vl + ")", na = "[^" + Po + "]", _l = "(?:\\ud83c[\\udde6-\\uddff]){2}", ml = "[\\ud800-\\udbff][\\udc00-\\udfff]", ri = "[" + kf + "]", ra = "\\u200d", ia = "(?:" + ta + "|" + ea + ")", _g = "(?:" + ri + "|" + ea + ")", oa = "(?:" + gl + "(?:d|ll|m|re|s|t|ve))?", sa = "(?:" + gl + "(?:D|LL|M|RE|S|T|VE))?", la = vg + "?", ua = "[" + Jf + "]?", mg = "(?:" + ra + "(?:" + [na, _l, ml].join("|") + ")" + ua + la + ")*", bg = "\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])", yg = "\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])", fa = ua + la + mg, wg = "(?:" + [gg, _l, ml].join("|") + ")" + fa, Eg = "(?:" + [na + Mo + "?", Mo, _l, ml, pg].join("|") + ")", xg = RegExp(gl, "g"), Sg = RegExp(Mo, "g"), bl = RegExp(vl + "(?=" + vl + ")|" + Eg + fa, "g"), Tg = RegExp([
      ri + "?" + ta + "+" + oa + "(?=" + [Qf, ri, "$"].join("|") + ")",
      _g + "+" + sa + "(?=" + [Qf, ri + ia, "$"].join("|") + ")",
      ri + "?" + ia + "+" + oa,
      ri + "+" + sa,
      yg,
      bg,
      jf,
      wg
    ].join("|"), "g"), Ag = RegExp("[" + ra + Po + zf + Jf + "]"), Cg = /[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/, Og = [
      "Array",
      "Buffer",
      "DataView",
      "Date",
      "Error",
      "Float32Array",
      "Float64Array",
      "Function",
      "Int8Array",
      "Int16Array",
      "Int32Array",
      "Map",
      "Math",
      "Object",
      "Promise",
      "RegExp",
      "Set",
      "String",
      "Symbol",
      "TypeError",
      "Uint8Array",
      "Uint8ClampedArray",
      "Uint16Array",
      "Uint32Array",
      "WeakMap",
      "_",
      "clearTimeout",
      "isFinite",
      "parseInt",
      "setTimeout"
    ], Ig = -1, Yt = {};
    Yt[Dt] = Yt[be] = Yt[le] = Yt[Fe] = Yt[ye] = Yt[Xn] = Yt[ni] = Yt[we] = Yt[Ye] = !0, Yt[me] = Yt[Rn] = Yt[mt] = Yt[Yn] = Yt[It] = Yt[ur] = Yt[ar] = Yt[cr] = Yt[E] = Yt[I] = Yt[F] = Yt[G] = Yt[K] = Yt[U] = Yt[Z] = !1;
    var $t = {};
    $t[me] = $t[Rn] = $t[mt] = $t[It] = $t[Yn] = $t[ur] = $t[Dt] = $t[be] = $t[le] = $t[Fe] = $t[ye] = $t[E] = $t[I] = $t[F] = $t[G] = $t[K] = $t[U] = $t[et] = $t[Xn] = $t[ni] = $t[we] = $t[Ye] = !0, $t[ar] = $t[cr] = $t[Z] = !1;
    var Dg = {
      // Latin-1 Supplement block.
      À: "A",
      Á: "A",
      Â: "A",
      Ã: "A",
      Ä: "A",
      Å: "A",
      à: "a",
      á: "a",
      â: "a",
      ã: "a",
      ä: "a",
      å: "a",
      Ç: "C",
      ç: "c",
      Ð: "D",
      ð: "d",
      È: "E",
      É: "E",
      Ê: "E",
      Ë: "E",
      è: "e",
      é: "e",
      ê: "e",
      ë: "e",
      Ì: "I",
      Í: "I",
      Î: "I",
      Ï: "I",
      ì: "i",
      í: "i",
      î: "i",
      ï: "i",
      Ñ: "N",
      ñ: "n",
      Ò: "O",
      Ó: "O",
      Ô: "O",
      Õ: "O",
      Ö: "O",
      Ø: "O",
      ò: "o",
      ó: "o",
      ô: "o",
      õ: "o",
      ö: "o",
      ø: "o",
      Ù: "U",
      Ú: "U",
      Û: "U",
      Ü: "U",
      ù: "u",
      ú: "u",
      û: "u",
      ü: "u",
      Ý: "Y",
      ý: "y",
      ÿ: "y",
      Æ: "Ae",
      æ: "ae",
      Þ: "Th",
      þ: "th",
      ß: "ss",
      // Latin Extended-A block.
      Ā: "A",
      Ă: "A",
      Ą: "A",
      ā: "a",
      ă: "a",
      ą: "a",
      Ć: "C",
      Ĉ: "C",
      Ċ: "C",
      Č: "C",
      ć: "c",
      ĉ: "c",
      ċ: "c",
      č: "c",
      Ď: "D",
      Đ: "D",
      ď: "d",
      đ: "d",
      Ē: "E",
      Ĕ: "E",
      Ė: "E",
      Ę: "E",
      Ě: "E",
      ē: "e",
      ĕ: "e",
      ė: "e",
      ę: "e",
      ě: "e",
      Ĝ: "G",
      Ğ: "G",
      Ġ: "G",
      Ģ: "G",
      ĝ: "g",
      ğ: "g",
      ġ: "g",
      ģ: "g",
      Ĥ: "H",
      Ħ: "H",
      ĥ: "h",
      ħ: "h",
      Ĩ: "I",
      Ī: "I",
      Ĭ: "I",
      Į: "I",
      İ: "I",
      ĩ: "i",
      ī: "i",
      ĭ: "i",
      į: "i",
      ı: "i",
      Ĵ: "J",
      ĵ: "j",
      Ķ: "K",
      ķ: "k",
      ĸ: "k",
      Ĺ: "L",
      Ļ: "L",
      Ľ: "L",
      Ŀ: "L",
      Ł: "L",
      ĺ: "l",
      ļ: "l",
      ľ: "l",
      ŀ: "l",
      ł: "l",
      Ń: "N",
      Ņ: "N",
      Ň: "N",
      Ŋ: "N",
      ń: "n",
      ņ: "n",
      ň: "n",
      ŋ: "n",
      Ō: "O",
      Ŏ: "O",
      Ő: "O",
      ō: "o",
      ŏ: "o",
      ő: "o",
      Ŕ: "R",
      Ŗ: "R",
      Ř: "R",
      ŕ: "r",
      ŗ: "r",
      ř: "r",
      Ś: "S",
      Ŝ: "S",
      Ş: "S",
      Š: "S",
      ś: "s",
      ŝ: "s",
      ş: "s",
      š: "s",
      Ţ: "T",
      Ť: "T",
      Ŧ: "T",
      ţ: "t",
      ť: "t",
      ŧ: "t",
      Ũ: "U",
      Ū: "U",
      Ŭ: "U",
      Ů: "U",
      Ű: "U",
      Ų: "U",
      ũ: "u",
      ū: "u",
      ŭ: "u",
      ů: "u",
      ű: "u",
      ų: "u",
      Ŵ: "W",
      ŵ: "w",
      Ŷ: "Y",
      ŷ: "y",
      Ÿ: "Y",
      Ź: "Z",
      Ż: "Z",
      Ž: "Z",
      ź: "z",
      ż: "z",
      ž: "z",
      Ĳ: "IJ",
      ĳ: "ij",
      Œ: "Oe",
      œ: "oe",
      ŉ: "'n",
      ſ: "s"
    }, Rg = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;"
    }, Pg = {
      "&amp;": "&",
      "&lt;": "<",
      "&gt;": ">",
      "&quot;": '"',
      "&#39;": "'"
    }, Mg = {
      "\\": "\\",
      "'": "'",
      "\n": "n",
      "\r": "r",
      "\u2028": "u2028",
      "\u2029": "u2029"
    }, Fg = parseFloat, Ng = parseInt, aa = typeof to == "object" && to && to.Object === Object && to, Lg = typeof self == "object" && self && self.Object === Object && self, Ee = aa || Lg || Function("return this")(), yl = n && !n.nodeType && n, Fr = yl && !0 && e && !e.nodeType && e, ca = Fr && Fr.exports === yl, wl = ca && aa.process, hn = function() {
      try {
        var S = Fr && Fr.require && Fr.require("util").types;
        return S || wl && wl.binding && wl.binding("util");
      } catch {
      }
    }(), ha = hn && hn.isArrayBuffer, da = hn && hn.isDate, pa = hn && hn.isMap, ga = hn && hn.isRegExp, va = hn && hn.isSet, _a = hn && hn.isTypedArray;
    function Qe(S, D, C) {
      switch (C.length) {
        case 0:
          return S.call(D);
        case 1:
          return S.call(D, C[0]);
        case 2:
          return S.call(D, C[0], C[1]);
        case 3:
          return S.call(D, C[0], C[1], C[2]);
      }
      return S.apply(D, C);
    }
    function Bg(S, D, C, J) {
      for (var ct = -1, Rt = S == null ? 0 : S.length; ++ct < Rt; ) {
        var he = S[ct];
        D(J, he, C(he), S);
      }
      return J;
    }
    function dn(S, D) {
      for (var C = -1, J = S == null ? 0 : S.length; ++C < J && D(S[C], C, S) !== !1; )
        ;
      return S;
    }
    function Wg(S, D) {
      for (var C = S == null ? 0 : S.length; C-- && D(S[C], C, S) !== !1; )
        ;
      return S;
    }
    function ma(S, D) {
      for (var C = -1, J = S == null ? 0 : S.length; ++C < J; )
        if (!D(S[C], C, S))
          return !1;
      return !0;
    }
    function hr(S, D) {
      for (var C = -1, J = S == null ? 0 : S.length, ct = 0, Rt = []; ++C < J; ) {
        var he = S[C];
        D(he, C, S) && (Rt[ct++] = he);
      }
      return Rt;
    }
    function Fo(S, D) {
      var C = S == null ? 0 : S.length;
      return !!C && ii(S, D, 0) > -1;
    }
    function El(S, D, C) {
      for (var J = -1, ct = S == null ? 0 : S.length; ++J < ct; )
        if (C(D, S[J]))
          return !0;
      return !1;
    }
    function Vt(S, D) {
      for (var C = -1, J = S == null ? 0 : S.length, ct = Array(J); ++C < J; )
        ct[C] = D(S[C], C, S);
      return ct;
    }
    function dr(S, D) {
      for (var C = -1, J = D.length, ct = S.length; ++C < J; )
        S[ct + C] = D[C];
      return S;
    }
    function xl(S, D, C, J) {
      var ct = -1, Rt = S == null ? 0 : S.length;
      for (J && Rt && (C = S[++ct]); ++ct < Rt; )
        C = D(C, S[ct], ct, S);
      return C;
    }
    function Ug(S, D, C, J) {
      var ct = S == null ? 0 : S.length;
      for (J && ct && (C = S[--ct]); ct--; )
        C = D(C, S[ct], ct, S);
      return C;
    }
    function Sl(S, D) {
      for (var C = -1, J = S == null ? 0 : S.length; ++C < J; )
        if (D(S[C], C, S))
          return !0;
      return !1;
    }
    var Hg = Tl("length");
    function $g(S) {
      return S.split("");
    }
    function Kg(S) {
      return S.match(Jp) || [];
    }
    function ba(S, D, C) {
      var J;
      return C(S, function(ct, Rt, he) {
        if (D(ct, Rt, he))
          return J = Rt, !1;
      }), J;
    }
    function No(S, D, C, J) {
      for (var ct = S.length, Rt = C + (J ? 1 : -1); J ? Rt-- : ++Rt < ct; )
        if (D(S[Rt], Rt, S))
          return Rt;
      return -1;
    }
    function ii(S, D, C) {
      return D === D ? tv(S, D, C) : No(S, ya, C);
    }
    function Gg(S, D, C, J) {
      for (var ct = C - 1, Rt = S.length; ++ct < Rt; )
        if (J(S[ct], D))
          return ct;
      return -1;
    }
    function ya(S) {
      return S !== S;
    }
    function wa(S, D) {
      var C = S == null ? 0 : S.length;
      return C ? Cl(S, D) / C : ge;
    }
    function Tl(S) {
      return function(D) {
        return D == null ? i : D[S];
      };
    }
    function Al(S) {
      return function(D) {
        return S == null ? i : S[D];
      };
    }
    function Ea(S, D, C, J, ct) {
      return ct(S, function(Rt, he, Ut) {
        C = J ? (J = !1, Rt) : D(C, Rt, he, Ut);
      }), C;
    }
    function Yg(S, D) {
      var C = S.length;
      for (S.sort(D); C--; )
        S[C] = S[C].value;
      return S;
    }
    function Cl(S, D) {
      for (var C, J = -1, ct = S.length; ++J < ct; ) {
        var Rt = D(S[J]);
        Rt !== i && (C = C === i ? Rt : C + Rt);
      }
      return C;
    }
    function Ol(S, D) {
      for (var C = -1, J = Array(S); ++C < S; )
        J[C] = D(C);
      return J;
    }
    function Xg(S, D) {
      return Vt(D, function(C) {
        return [C, S[C]];
      });
    }
    function xa(S) {
      return S && S.slice(0, Ca(S) + 1).replace(pl, "");
    }
    function je(S) {
      return function(D) {
        return S(D);
      };
    }
    function Il(S, D) {
      return Vt(D, function(C) {
        return S[C];
      });
    }
    function Bi(S, D) {
      return S.has(D);
    }
    function Sa(S, D) {
      for (var C = -1, J = S.length; ++C < J && ii(D, S[C], 0) > -1; )
        ;
      return C;
    }
    function Ta(S, D) {
      for (var C = S.length; C-- && ii(D, S[C], 0) > -1; )
        ;
      return C;
    }
    function zg(S, D) {
      for (var C = S.length, J = 0; C--; )
        S[C] === D && ++J;
      return J;
    }
    var qg = Al(Dg), Vg = Al(Rg);
    function kg(S) {
      return "\\" + Mg[S];
    }
    function Jg(S, D) {
      return S == null ? i : S[D];
    }
    function oi(S) {
      return Ag.test(S);
    }
    function Zg(S) {
      return Cg.test(S);
    }
    function Qg(S) {
      for (var D, C = []; !(D = S.next()).done; )
        C.push(D.value);
      return C;
    }
    function Dl(S) {
      var D = -1, C = Array(S.size);
      return S.forEach(function(J, ct) {
        C[++D] = [ct, J];
      }), C;
    }
    function Aa(S, D) {
      return function(C) {
        return S(D(C));
      };
    }
    function pr(S, D) {
      for (var C = -1, J = S.length, ct = 0, Rt = []; ++C < J; ) {
        var he = S[C];
        (he === D || he === _) && (S[C] = _, Rt[ct++] = C);
      }
      return Rt;
    }
    function Lo(S) {
      var D = -1, C = Array(S.size);
      return S.forEach(function(J) {
        C[++D] = J;
      }), C;
    }
    function jg(S) {
      var D = -1, C = Array(S.size);
      return S.forEach(function(J) {
        C[++D] = [J, J];
      }), C;
    }
    function tv(S, D, C) {
      for (var J = C - 1, ct = S.length; ++J < ct; )
        if (S[J] === D)
          return J;
      return -1;
    }
    function ev(S, D, C) {
      for (var J = C + 1; J--; )
        if (S[J] === D)
          return J;
      return J;
    }
    function si(S) {
      return oi(S) ? rv(S) : Hg(S);
    }
    function xn(S) {
      return oi(S) ? iv(S) : $g(S);
    }
    function Ca(S) {
      for (var D = S.length; D-- && zp.test(S.charAt(D)); )
        ;
      return D;
    }
    var nv = Al(Pg);
    function rv(S) {
      for (var D = bl.lastIndex = 0; bl.test(S); )
        ++D;
      return D;
    }
    function iv(S) {
      return S.match(bl) || [];
    }
    function ov(S) {
      return S.match(Tg) || [];
    }
    var sv = function S(D) {
      D = D == null ? Ee : li.defaults(Ee.Object(), D, li.pick(Ee, Og));
      var C = D.Array, J = D.Date, ct = D.Error, Rt = D.Function, he = D.Math, Ut = D.Object, Rl = D.RegExp, lv = D.String, pn = D.TypeError, Bo = C.prototype, uv = Rt.prototype, ui = Ut.prototype, Wo = D["__core-js_shared__"], Uo = uv.toString, Bt = ui.hasOwnProperty, fv = 0, Oa = function() {
        var t = /[^.]+$/.exec(Wo && Wo.keys && Wo.keys.IE_PROTO || "");
        return t ? "Symbol(src)_1." + t : "";
      }(), Ho = ui.toString, av = Uo.call(Ut), cv = Ee._, hv = Rl(
        "^" + Uo.call(Bt).replace(dl, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"
      ), $o = ca ? D.Buffer : i, gr = D.Symbol, Ko = D.Uint8Array, Ia = $o ? $o.allocUnsafe : i, Go = Aa(Ut.getPrototypeOf, Ut), Da = Ut.create, Ra = ui.propertyIsEnumerable, Yo = Bo.splice, Pa = gr ? gr.isConcatSpreadable : i, Wi = gr ? gr.iterator : i, Nr = gr ? gr.toStringTag : i, Xo = function() {
        try {
          var t = Hr(Ut, "defineProperty");
          return t({}, "", {}), t;
        } catch {
        }
      }(), dv = D.clearTimeout !== Ee.clearTimeout && D.clearTimeout, pv = J && J.now !== Ee.Date.now && J.now, gv = D.setTimeout !== Ee.setTimeout && D.setTimeout, zo = he.ceil, qo = he.floor, Pl = Ut.getOwnPropertySymbols, vv = $o ? $o.isBuffer : i, Ma = D.isFinite, _v = Bo.join, mv = Aa(Ut.keys, Ut), de = he.max, Ce = he.min, bv = J.now, yv = D.parseInt, Fa = he.random, wv = Bo.reverse, Ml = Hr(D, "DataView"), Ui = Hr(D, "Map"), Fl = Hr(D, "Promise"), fi = Hr(D, "Set"), Hi = Hr(D, "WeakMap"), $i = Hr(Ut, "create"), Vo = Hi && new Hi(), ai = {}, Ev = $r(Ml), xv = $r(Ui), Sv = $r(Fl), Tv = $r(fi), Av = $r(Hi), ko = gr ? gr.prototype : i, Ki = ko ? ko.valueOf : i, Na = ko ? ko.toString : i;
      function d(t) {
        if (jt(t) && !dt(t) && !(t instanceof Tt)) {
          if (t instanceof gn)
            return t;
          if (Bt.call(t, "__wrapped__"))
            return Lc(t);
        }
        return new gn(t);
      }
      var ci = /* @__PURE__ */ function() {
        function t() {
        }
        return function(r) {
          if (!Zt(r))
            return {};
          if (Da)
            return Da(r);
          t.prototype = r;
          var s = new t();
          return t.prototype = i, s;
        };
      }();
      function Jo() {
      }
      function gn(t, r) {
        this.__wrapped__ = t, this.__actions__ = [], this.__chain__ = !!r, this.__index__ = 0, this.__values__ = i;
      }
      d.templateSettings = {
        /**
         * Used to detect `data` property values to be HTML-escaped.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        escape: Hp,
        /**
         * Used to detect code to be evaluated.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        evaluate: $p,
        /**
         * Used to detect `data` property values to inject.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        interpolate: Yf,
        /**
         * Used to reference the data object in the template text.
         *
         * @memberOf _.templateSettings
         * @type {string}
         */
        variable: "",
        /**
         * Used to import variables into the compiled template.
         *
         * @memberOf _.templateSettings
         * @type {Object}
         */
        imports: {
          /**
           * A reference to the `lodash` function.
           *
           * @memberOf _.templateSettings.imports
           * @type {Function}
           */
          _: d
        }
      }, d.prototype = Jo.prototype, d.prototype.constructor = d, gn.prototype = ci(Jo.prototype), gn.prototype.constructor = gn;
      function Tt(t) {
        this.__wrapped__ = t, this.__actions__ = [], this.__dir__ = 1, this.__filtered__ = !1, this.__iteratees__ = [], this.__takeCount__ = qt, this.__views__ = [];
      }
      function Cv() {
        var t = new Tt(this.__wrapped__);
        return t.__actions__ = Xe(this.__actions__), t.__dir__ = this.__dir__, t.__filtered__ = this.__filtered__, t.__iteratees__ = Xe(this.__iteratees__), t.__takeCount__ = this.__takeCount__, t.__views__ = Xe(this.__views__), t;
      }
      function Ov() {
        if (this.__filtered__) {
          var t = new Tt(this);
          t.__dir__ = -1, t.__filtered__ = !0;
        } else
          t = this.clone(), t.__dir__ *= -1;
        return t;
      }
      function Iv() {
        var t = this.__wrapped__.value(), r = this.__dir__, s = dt(t), f = r < 0, c = s ? t.length : 0, p = $_(0, c, this.__views__), b = p.start, w = p.end, T = w - b, P = f ? w : b - 1, M = this.__iteratees__, B = M.length, q = 0, j = Ce(T, this.__takeCount__);
        if (!s || !f && c == T && j == T)
          return oc(t, this.__actions__);
        var it = [];
        t:
          for (; T-- && q < j; ) {
            P += r;
            for (var bt = -1, ot = t[P]; ++bt < B; ) {
              var xt = M[bt], Ct = xt.iteratee, nn = xt.type, Be = Ct(ot);
              if (nn == Ae)
                ot = Be;
              else if (!Be) {
                if (nn == pe)
                  continue t;
                break t;
              }
            }
            it[q++] = ot;
          }
        return it;
      }
      Tt.prototype = ci(Jo.prototype), Tt.prototype.constructor = Tt;
      function Lr(t) {
        var r = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++r < s; ) {
          var f = t[r];
          this.set(f[0], f[1]);
        }
      }
      function Dv() {
        this.__data__ = $i ? $i(null) : {}, this.size = 0;
      }
      function Rv(t) {
        var r = this.has(t) && delete this.__data__[t];
        return this.size -= r ? 1 : 0, r;
      }
      function Pv(t) {
        var r = this.__data__;
        if ($i) {
          var s = r[t];
          return s === g ? i : s;
        }
        return Bt.call(r, t) ? r[t] : i;
      }
      function Mv(t) {
        var r = this.__data__;
        return $i ? r[t] !== i : Bt.call(r, t);
      }
      function Fv(t, r) {
        var s = this.__data__;
        return this.size += this.has(t) ? 0 : 1, s[t] = $i && r === i ? g : r, this;
      }
      Lr.prototype.clear = Dv, Lr.prototype.delete = Rv, Lr.prototype.get = Pv, Lr.prototype.has = Mv, Lr.prototype.set = Fv;
      function zn(t) {
        var r = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++r < s; ) {
          var f = t[r];
          this.set(f[0], f[1]);
        }
      }
      function Nv() {
        this.__data__ = [], this.size = 0;
      }
      function Lv(t) {
        var r = this.__data__, s = Zo(r, t);
        if (s < 0)
          return !1;
        var f = r.length - 1;
        return s == f ? r.pop() : Yo.call(r, s, 1), --this.size, !0;
      }
      function Bv(t) {
        var r = this.__data__, s = Zo(r, t);
        return s < 0 ? i : r[s][1];
      }
      function Wv(t) {
        return Zo(this.__data__, t) > -1;
      }
      function Uv(t, r) {
        var s = this.__data__, f = Zo(s, t);
        return f < 0 ? (++this.size, s.push([t, r])) : s[f][1] = r, this;
      }
      zn.prototype.clear = Nv, zn.prototype.delete = Lv, zn.prototype.get = Bv, zn.prototype.has = Wv, zn.prototype.set = Uv;
      function qn(t) {
        var r = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++r < s; ) {
          var f = t[r];
          this.set(f[0], f[1]);
        }
      }
      function Hv() {
        this.size = 0, this.__data__ = {
          hash: new Lr(),
          map: new (Ui || zn)(),
          string: new Lr()
        };
      }
      function $v(t) {
        var r = fs(this, t).delete(t);
        return this.size -= r ? 1 : 0, r;
      }
      function Kv(t) {
        return fs(this, t).get(t);
      }
      function Gv(t) {
        return fs(this, t).has(t);
      }
      function Yv(t, r) {
        var s = fs(this, t), f = s.size;
        return s.set(t, r), this.size += s.size == f ? 0 : 1, this;
      }
      qn.prototype.clear = Hv, qn.prototype.delete = $v, qn.prototype.get = Kv, qn.prototype.has = Gv, qn.prototype.set = Yv;
      function Br(t) {
        var r = -1, s = t == null ? 0 : t.length;
        for (this.__data__ = new qn(); ++r < s; )
          this.add(t[r]);
      }
      function Xv(t) {
        return this.__data__.set(t, g), this;
      }
      function zv(t) {
        return this.__data__.has(t);
      }
      Br.prototype.add = Br.prototype.push = Xv, Br.prototype.has = zv;
      function Sn(t) {
        var r = this.__data__ = new zn(t);
        this.size = r.size;
      }
      function qv() {
        this.__data__ = new zn(), this.size = 0;
      }
      function Vv(t) {
        var r = this.__data__, s = r.delete(t);
        return this.size = r.size, s;
      }
      function kv(t) {
        return this.__data__.get(t);
      }
      function Jv(t) {
        return this.__data__.has(t);
      }
      function Zv(t, r) {
        var s = this.__data__;
        if (s instanceof zn) {
          var f = s.__data__;
          if (!Ui || f.length < l - 1)
            return f.push([t, r]), this.size = ++s.size, this;
          s = this.__data__ = new qn(f);
        }
        return s.set(t, r), this.size = s.size, this;
      }
      Sn.prototype.clear = qv, Sn.prototype.delete = Vv, Sn.prototype.get = kv, Sn.prototype.has = Jv, Sn.prototype.set = Zv;
      function La(t, r) {
        var s = dt(t), f = !s && Kr(t), c = !s && !f && yr(t), p = !s && !f && !c && gi(t), b = s || f || c || p, w = b ? Ol(t.length, lv) : [], T = w.length;
        for (var P in t)
          (r || Bt.call(t, P)) && !(b && // Safari 9 has enumerable `arguments.length` in strict mode.
          (P == "length" || // Node.js 0.10 has enumerable non-index properties on buffers.
          c && (P == "offset" || P == "parent") || // PhantomJS 2 has enumerable non-index properties on typed arrays.
          p && (P == "buffer" || P == "byteLength" || P == "byteOffset") || // Skip index properties.
          Zn(P, T))) && w.push(P);
        return w;
      }
      function Ba(t) {
        var r = t.length;
        return r ? t[Xl(0, r - 1)] : i;
      }
      function Qv(t, r) {
        return as(Xe(t), Wr(r, 0, t.length));
      }
      function jv(t) {
        return as(Xe(t));
      }
      function Nl(t, r, s) {
        (s !== i && !Tn(t[r], s) || s === i && !(r in t)) && Vn(t, r, s);
      }
      function Gi(t, r, s) {
        var f = t[r];
        (!(Bt.call(t, r) && Tn(f, s)) || s === i && !(r in t)) && Vn(t, r, s);
      }
      function Zo(t, r) {
        for (var s = t.length; s--; )
          if (Tn(t[s][0], r))
            return s;
        return -1;
      }
      function t_(t, r, s, f) {
        return vr(t, function(c, p, b) {
          r(f, c, s(c), b);
        }), f;
      }
      function Wa(t, r) {
        return t && Mn(r, ve(r), t);
      }
      function e_(t, r) {
        return t && Mn(r, qe(r), t);
      }
      function Vn(t, r, s) {
        r == "__proto__" && Xo ? Xo(t, r, {
          configurable: !0,
          enumerable: !0,
          value: s,
          writable: !0
        }) : t[r] = s;
      }
      function Ll(t, r) {
        for (var s = -1, f = r.length, c = C(f), p = t == null; ++s < f; )
          c[s] = p ? i : vu(t, r[s]);
        return c;
      }
      function Wr(t, r, s) {
        return t === t && (s !== i && (t = t <= s ? t : s), r !== i && (t = t >= r ? t : r)), t;
      }
      function vn(t, r, s, f, c, p) {
        var b, w = r & m, T = r & x, P = r & A;
        if (s && (b = c ? s(t, f, c, p) : s(t)), b !== i)
          return b;
        if (!Zt(t))
          return t;
        var M = dt(t);
        if (M) {
          if (b = G_(t), !w)
            return Xe(t, b);
        } else {
          var B = Oe(t), q = B == cr || B == y;
          if (yr(t))
            return uc(t, w);
          if (B == F || B == me || q && !c) {
            if (b = T || q ? {} : Cc(t), !w)
              return T ? P_(t, e_(b, t)) : R_(t, Wa(b, t));
          } else {
            if (!$t[B])
              return c ? t : {};
            b = Y_(t, B, w);
          }
        }
        p || (p = new Sn());
        var j = p.get(t);
        if (j)
          return j;
        p.set(t, b), nh(t) ? t.forEach(function(ot) {
          b.add(vn(ot, r, s, ot, t, p));
        }) : th(t) && t.forEach(function(ot, xt) {
          b.set(xt, vn(ot, r, s, xt, t, p));
        });
        var it = P ? T ? nu : eu : T ? qe : ve, bt = M ? i : it(t);
        return dn(bt || t, function(ot, xt) {
          bt && (xt = ot, ot = t[xt]), Gi(b, xt, vn(ot, r, s, xt, t, p));
        }), b;
      }
      function n_(t) {
        var r = ve(t);
        return function(s) {
          return Ua(s, t, r);
        };
      }
      function Ua(t, r, s) {
        var f = s.length;
        if (t == null)
          return !f;
        for (t = Ut(t); f--; ) {
          var c = s[f], p = r[c], b = t[c];
          if (b === i && !(c in t) || !p(b))
            return !1;
        }
        return !0;
      }
      function Ha(t, r, s) {
        if (typeof t != "function")
          throw new pn(a);
        return Ji(function() {
          t.apply(i, s);
        }, r);
      }
      function Yi(t, r, s, f) {
        var c = -1, p = Fo, b = !0, w = t.length, T = [], P = r.length;
        if (!w)
          return T;
        s && (r = Vt(r, je(s))), f ? (p = El, b = !1) : r.length >= l && (p = Bi, b = !1, r = new Br(r));
        t:
          for (; ++c < w; ) {
            var M = t[c], B = s == null ? M : s(M);
            if (M = f || M !== 0 ? M : 0, b && B === B) {
              for (var q = P; q--; )
                if (r[q] === B)
                  continue t;
              T.push(M);
            } else
              p(r, B, f) || T.push(M);
          }
        return T;
      }
      var vr = dc(Pn), $a = dc(Wl, !0);
      function r_(t, r) {
        var s = !0;
        return vr(t, function(f, c, p) {
          return s = !!r(f, c, p), s;
        }), s;
      }
      function Qo(t, r, s) {
        for (var f = -1, c = t.length; ++f < c; ) {
          var p = t[f], b = r(p);
          if (b != null && (w === i ? b === b && !en(b) : s(b, w)))
            var w = b, T = p;
        }
        return T;
      }
      function i_(t, r, s, f) {
        var c = t.length;
        for (s = _t(s), s < 0 && (s = -s > c ? 0 : c + s), f = f === i || f > c ? c : _t(f), f < 0 && (f += c), f = s > f ? 0 : ih(f); s < f; )
          t[s++] = r;
        return t;
      }
      function Ka(t, r) {
        var s = [];
        return vr(t, function(f, c, p) {
          r(f, c, p) && s.push(f);
        }), s;
      }
      function xe(t, r, s, f, c) {
        var p = -1, b = t.length;
        for (s || (s = z_), c || (c = []); ++p < b; ) {
          var w = t[p];
          r > 0 && s(w) ? r > 1 ? xe(w, r - 1, s, f, c) : dr(c, w) : f || (c[c.length] = w);
        }
        return c;
      }
      var Bl = pc(), Ga = pc(!0);
      function Pn(t, r) {
        return t && Bl(t, r, ve);
      }
      function Wl(t, r) {
        return t && Ga(t, r, ve);
      }
      function jo(t, r) {
        return hr(r, function(s) {
          return Qn(t[s]);
        });
      }
      function Ur(t, r) {
        r = mr(r, t);
        for (var s = 0, f = r.length; t != null && s < f; )
          t = t[Fn(r[s++])];
        return s && s == f ? t : i;
      }
      function Ya(t, r, s) {
        var f = r(t);
        return dt(t) ? f : dr(f, s(t));
      }
      function Ne(t) {
        return t == null ? t === i ? k : $ : Nr && Nr in Ut(t) ? H_(t) : j_(t);
      }
      function Ul(t, r) {
        return t > r;
      }
      function o_(t, r) {
        return t != null && Bt.call(t, r);
      }
      function s_(t, r) {
        return t != null && r in Ut(t);
      }
      function l_(t, r, s) {
        return t >= Ce(r, s) && t < de(r, s);
      }
      function Hl(t, r, s) {
        for (var f = s ? El : Fo, c = t[0].length, p = t.length, b = p, w = C(p), T = 1 / 0, P = []; b--; ) {
          var M = t[b];
          b && r && (M = Vt(M, je(r))), T = Ce(M.length, T), w[b] = !s && (r || c >= 120 && M.length >= 120) ? new Br(b && M) : i;
        }
        M = t[0];
        var B = -1, q = w[0];
        t:
          for (; ++B < c && P.length < T; ) {
            var j = M[B], it = r ? r(j) : j;
            if (j = s || j !== 0 ? j : 0, !(q ? Bi(q, it) : f(P, it, s))) {
              for (b = p; --b; ) {
                var bt = w[b];
                if (!(bt ? Bi(bt, it) : f(t[b], it, s)))
                  continue t;
              }
              q && q.push(it), P.push(j);
            }
          }
        return P;
      }
      function u_(t, r, s, f) {
        return Pn(t, function(c, p, b) {
          r(f, s(c), p, b);
        }), f;
      }
      function Xi(t, r, s) {
        r = mr(r, t), t = Rc(t, r);
        var f = t == null ? t : t[Fn(mn(r))];
        return f == null ? i : Qe(f, t, s);
      }
      function Xa(t) {
        return jt(t) && Ne(t) == me;
      }
      function f_(t) {
        return jt(t) && Ne(t) == mt;
      }
      function a_(t) {
        return jt(t) && Ne(t) == ur;
      }
      function zi(t, r, s, f, c) {
        return t === r ? !0 : t == null || r == null || !jt(t) && !jt(r) ? t !== t && r !== r : c_(t, r, s, f, zi, c);
      }
      function c_(t, r, s, f, c, p) {
        var b = dt(t), w = dt(r), T = b ? Rn : Oe(t), P = w ? Rn : Oe(r);
        T = T == me ? F : T, P = P == me ? F : P;
        var M = T == F, B = P == F, q = T == P;
        if (q && yr(t)) {
          if (!yr(r))
            return !1;
          b = !0, M = !1;
        }
        if (q && !M)
          return p || (p = new Sn()), b || gi(t) ? Sc(t, r, s, f, c, p) : W_(t, r, T, s, f, c, p);
        if (!(s & O)) {
          var j = M && Bt.call(t, "__wrapped__"), it = B && Bt.call(r, "__wrapped__");
          if (j || it) {
            var bt = j ? t.value() : t, ot = it ? r.value() : r;
            return p || (p = new Sn()), c(bt, ot, s, f, p);
          }
        }
        return q ? (p || (p = new Sn()), U_(t, r, s, f, c, p)) : !1;
      }
      function h_(t) {
        return jt(t) && Oe(t) == E;
      }
      function $l(t, r, s, f) {
        var c = s.length, p = c, b = !f;
        if (t == null)
          return !p;
        for (t = Ut(t); c--; ) {
          var w = s[c];
          if (b && w[2] ? w[1] !== t[w[0]] : !(w[0] in t))
            return !1;
        }
        for (; ++c < p; ) {
          w = s[c];
          var T = w[0], P = t[T], M = w[1];
          if (b && w[2]) {
            if (P === i && !(T in t))
              return !1;
          } else {
            var B = new Sn();
            if (f)
              var q = f(P, M, T, t, r, B);
            if (!(q === i ? zi(M, P, O | R, f, B) : q))
              return !1;
          }
        }
        return !0;
      }
      function za(t) {
        if (!Zt(t) || V_(t))
          return !1;
        var r = Qn(t) ? hv : ng;
        return r.test($r(t));
      }
      function d_(t) {
        return jt(t) && Ne(t) == G;
      }
      function p_(t) {
        return jt(t) && Oe(t) == K;
      }
      function g_(t) {
        return jt(t) && vs(t.length) && !!Yt[Ne(t)];
      }
      function qa(t) {
        return typeof t == "function" ? t : t == null ? Ve : typeof t == "object" ? dt(t) ? Ja(t[0], t[1]) : ka(t) : gh(t);
      }
      function Kl(t) {
        if (!ki(t))
          return mv(t);
        var r = [];
        for (var s in Ut(t))
          Bt.call(t, s) && s != "constructor" && r.push(s);
        return r;
      }
      function v_(t) {
        if (!Zt(t))
          return Q_(t);
        var r = ki(t), s = [];
        for (var f in t)
          f == "constructor" && (r || !Bt.call(t, f)) || s.push(f);
        return s;
      }
      function Gl(t, r) {
        return t < r;
      }
      function Va(t, r) {
        var s = -1, f = ze(t) ? C(t.length) : [];
        return vr(t, function(c, p, b) {
          f[++s] = r(c, p, b);
        }), f;
      }
      function ka(t) {
        var r = iu(t);
        return r.length == 1 && r[0][2] ? Ic(r[0][0], r[0][1]) : function(s) {
          return s === t || $l(s, t, r);
        };
      }
      function Ja(t, r) {
        return su(t) && Oc(r) ? Ic(Fn(t), r) : function(s) {
          var f = vu(s, t);
          return f === i && f === r ? _u(s, t) : zi(r, f, O | R);
        };
      }
      function ts(t, r, s, f, c) {
        t !== r && Bl(r, function(p, b) {
          if (c || (c = new Sn()), Zt(p))
            __(t, r, b, s, ts, f, c);
          else {
            var w = f ? f(uu(t, b), p, b + "", t, r, c) : i;
            w === i && (w = p), Nl(t, b, w);
          }
        }, qe);
      }
      function __(t, r, s, f, c, p, b) {
        var w = uu(t, s), T = uu(r, s), P = b.get(T);
        if (P) {
          Nl(t, s, P);
          return;
        }
        var M = p ? p(w, T, s + "", t, r, b) : i, B = M === i;
        if (B) {
          var q = dt(T), j = !q && yr(T), it = !q && !j && gi(T);
          M = T, q || j || it ? dt(w) ? M = w : ee(w) ? M = Xe(w) : j ? (B = !1, M = uc(T, !0)) : it ? (B = !1, M = fc(T, !0)) : M = [] : Zi(T) || Kr(T) ? (M = w, Kr(w) ? M = oh(w) : (!Zt(w) || Qn(w)) && (M = Cc(T))) : B = !1;
        }
        B && (b.set(T, M), c(M, T, f, p, b), b.delete(T)), Nl(t, s, M);
      }
      function Za(t, r) {
        var s = t.length;
        if (s)
          return r += r < 0 ? s : 0, Zn(r, s) ? t[r] : i;
      }
      function Qa(t, r, s) {
        r.length ? r = Vt(r, function(p) {
          return dt(p) ? function(b) {
            return Ur(b, p.length === 1 ? p[0] : p);
          } : p;
        }) : r = [Ve];
        var f = -1;
        r = Vt(r, je(nt()));
        var c = Va(t, function(p, b, w) {
          var T = Vt(r, function(P) {
            return P(p);
          });
          return { criteria: T, index: ++f, value: p };
        });
        return Yg(c, function(p, b) {
          return D_(p, b, s);
        });
      }
      function m_(t, r) {
        return ja(t, r, function(s, f) {
          return _u(t, f);
        });
      }
      function ja(t, r, s) {
        for (var f = -1, c = r.length, p = {}; ++f < c; ) {
          var b = r[f], w = Ur(t, b);
          s(w, b) && qi(p, mr(b, t), w);
        }
        return p;
      }
      function b_(t) {
        return function(r) {
          return Ur(r, t);
        };
      }
      function Yl(t, r, s, f) {
        var c = f ? Gg : ii, p = -1, b = r.length, w = t;
        for (t === r && (r = Xe(r)), s && (w = Vt(t, je(s))); ++p < b; )
          for (var T = 0, P = r[p], M = s ? s(P) : P; (T = c(w, M, T, f)) > -1; )
            w !== t && Yo.call(w, T, 1), Yo.call(t, T, 1);
        return t;
      }
      function tc(t, r) {
        for (var s = t ? r.length : 0, f = s - 1; s--; ) {
          var c = r[s];
          if (s == f || c !== p) {
            var p = c;
            Zn(c) ? Yo.call(t, c, 1) : Vl(t, c);
          }
        }
        return t;
      }
      function Xl(t, r) {
        return t + qo(Fa() * (r - t + 1));
      }
      function y_(t, r, s, f) {
        for (var c = -1, p = de(zo((r - t) / (s || 1)), 0), b = C(p); p--; )
          b[f ? p : ++c] = t, t += s;
        return b;
      }
      function zl(t, r) {
        var s = "";
        if (!t || r < 1 || r > rt)
          return s;
        do
          r % 2 && (s += t), r = qo(r / 2), r && (t += t);
        while (r);
        return s;
      }
      function yt(t, r) {
        return fu(Dc(t, r, Ve), t + "");
      }
      function w_(t) {
        return Ba(vi(t));
      }
      function E_(t, r) {
        var s = vi(t);
        return as(s, Wr(r, 0, s.length));
      }
      function qi(t, r, s, f) {
        if (!Zt(t))
          return t;
        r = mr(r, t);
        for (var c = -1, p = r.length, b = p - 1, w = t; w != null && ++c < p; ) {
          var T = Fn(r[c]), P = s;
          if (T === "__proto__" || T === "constructor" || T === "prototype")
            return t;
          if (c != b) {
            var M = w[T];
            P = f ? f(M, T, w) : i, P === i && (P = Zt(M) ? M : Zn(r[c + 1]) ? [] : {});
          }
          Gi(w, T, P), w = w[T];
        }
        return t;
      }
      var ec = Vo ? function(t, r) {
        return Vo.set(t, r), t;
      } : Ve, x_ = Xo ? function(t, r) {
        return Xo(t, "toString", {
          configurable: !0,
          enumerable: !1,
          value: bu(r),
          writable: !0
        });
      } : Ve;
      function S_(t) {
        return as(vi(t));
      }
      function _n(t, r, s) {
        var f = -1, c = t.length;
        r < 0 && (r = -r > c ? 0 : c + r), s = s > c ? c : s, s < 0 && (s += c), c = r > s ? 0 : s - r >>> 0, r >>>= 0;
        for (var p = C(c); ++f < c; )
          p[f] = t[f + r];
        return p;
      }
      function T_(t, r) {
        var s;
        return vr(t, function(f, c, p) {
          return s = r(f, c, p), !s;
        }), !!s;
      }
      function es(t, r, s) {
        var f = 0, c = t == null ? f : t.length;
        if (typeof r == "number" && r === r && c <= cn) {
          for (; f < c; ) {
            var p = f + c >>> 1, b = t[p];
            b !== null && !en(b) && (s ? b <= r : b < r) ? f = p + 1 : c = p;
          }
          return c;
        }
        return ql(t, r, Ve, s);
      }
      function ql(t, r, s, f) {
        var c = 0, p = t == null ? 0 : t.length;
        if (p === 0)
          return 0;
        r = s(r);
        for (var b = r !== r, w = r === null, T = en(r), P = r === i; c < p; ) {
          var M = qo((c + p) / 2), B = s(t[M]), q = B !== i, j = B === null, it = B === B, bt = en(B);
          if (b)
            var ot = f || it;
          else
            P ? ot = it && (f || q) : w ? ot = it && q && (f || !j) : T ? ot = it && q && !j && (f || !bt) : j || bt ? ot = !1 : ot = f ? B <= r : B < r;
          ot ? c = M + 1 : p = M;
        }
        return Ce(p, oe);
      }
      function nc(t, r) {
        for (var s = -1, f = t.length, c = 0, p = []; ++s < f; ) {
          var b = t[s], w = r ? r(b) : b;
          if (!s || !Tn(w, T)) {
            var T = w;
            p[c++] = b === 0 ? 0 : b;
          }
        }
        return p;
      }
      function rc(t) {
        return typeof t == "number" ? t : en(t) ? ge : +t;
      }
      function tn(t) {
        if (typeof t == "string")
          return t;
        if (dt(t))
          return Vt(t, tn) + "";
        if (en(t))
          return Na ? Na.call(t) : "";
        var r = t + "";
        return r == "0" && 1 / t == -lt ? "-0" : r;
      }
      function _r(t, r, s) {
        var f = -1, c = Fo, p = t.length, b = !0, w = [], T = w;
        if (s)
          b = !1, c = El;
        else if (p >= l) {
          var P = r ? null : L_(t);
          if (P)
            return Lo(P);
          b = !1, c = Bi, T = new Br();
        } else
          T = r ? [] : w;
        t:
          for (; ++f < p; ) {
            var M = t[f], B = r ? r(M) : M;
            if (M = s || M !== 0 ? M : 0, b && B === B) {
              for (var q = T.length; q--; )
                if (T[q] === B)
                  continue t;
              r && T.push(B), w.push(M);
            } else
              c(T, B, s) || (T !== w && T.push(B), w.push(M));
          }
        return w;
      }
      function Vl(t, r) {
        return r = mr(r, t), t = Rc(t, r), t == null || delete t[Fn(mn(r))];
      }
      function ic(t, r, s, f) {
        return qi(t, r, s(Ur(t, r)), f);
      }
      function ns(t, r, s, f) {
        for (var c = t.length, p = f ? c : -1; (f ? p-- : ++p < c) && r(t[p], p, t); )
          ;
        return s ? _n(t, f ? 0 : p, f ? p + 1 : c) : _n(t, f ? p + 1 : 0, f ? c : p);
      }
      function oc(t, r) {
        var s = t;
        return s instanceof Tt && (s = s.value()), xl(r, function(f, c) {
          return c.func.apply(c.thisArg, dr([f], c.args));
        }, s);
      }
      function kl(t, r, s) {
        var f = t.length;
        if (f < 2)
          return f ? _r(t[0]) : [];
        for (var c = -1, p = C(f); ++c < f; )
          for (var b = t[c], w = -1; ++w < f; )
            w != c && (p[c] = Yi(p[c] || b, t[w], r, s));
        return _r(xe(p, 1), r, s);
      }
      function sc(t, r, s) {
        for (var f = -1, c = t.length, p = r.length, b = {}; ++f < c; ) {
          var w = f < p ? r[f] : i;
          s(b, t[f], w);
        }
        return b;
      }
      function Jl(t) {
        return ee(t) ? t : [];
      }
      function Zl(t) {
        return typeof t == "function" ? t : Ve;
      }
      function mr(t, r) {
        return dt(t) ? t : su(t, r) ? [t] : Nc(Mt(t));
      }
      var A_ = yt;
      function br(t, r, s) {
        var f = t.length;
        return s = s === i ? f : s, !r && s >= f ? t : _n(t, r, s);
      }
      var lc = dv || function(t) {
        return Ee.clearTimeout(t);
      };
      function uc(t, r) {
        if (r)
          return t.slice();
        var s = t.length, f = Ia ? Ia(s) : new t.constructor(s);
        return t.copy(f), f;
      }
      function Ql(t) {
        var r = new t.constructor(t.byteLength);
        return new Ko(r).set(new Ko(t)), r;
      }
      function C_(t, r) {
        var s = r ? Ql(t.buffer) : t.buffer;
        return new t.constructor(s, t.byteOffset, t.byteLength);
      }
      function O_(t) {
        var r = new t.constructor(t.source, Xf.exec(t));
        return r.lastIndex = t.lastIndex, r;
      }
      function I_(t) {
        return Ki ? Ut(Ki.call(t)) : {};
      }
      function fc(t, r) {
        var s = r ? Ql(t.buffer) : t.buffer;
        return new t.constructor(s, t.byteOffset, t.length);
      }
      function ac(t, r) {
        if (t !== r) {
          var s = t !== i, f = t === null, c = t === t, p = en(t), b = r !== i, w = r === null, T = r === r, P = en(r);
          if (!w && !P && !p && t > r || p && b && T && !w && !P || f && b && T || !s && T || !c)
            return 1;
          if (!f && !p && !P && t < r || P && s && c && !f && !p || w && s && c || !b && c || !T)
            return -1;
        }
        return 0;
      }
      function D_(t, r, s) {
        for (var f = -1, c = t.criteria, p = r.criteria, b = c.length, w = s.length; ++f < b; ) {
          var T = ac(c[f], p[f]);
          if (T) {
            if (f >= w)
              return T;
            var P = s[f];
            return T * (P == "desc" ? -1 : 1);
          }
        }
        return t.index - r.index;
      }
      function cc(t, r, s, f) {
        for (var c = -1, p = t.length, b = s.length, w = -1, T = r.length, P = de(p - b, 0), M = C(T + P), B = !f; ++w < T; )
          M[w] = r[w];
        for (; ++c < b; )
          (B || c < p) && (M[s[c]] = t[c]);
        for (; P--; )
          M[w++] = t[c++];
        return M;
      }
      function hc(t, r, s, f) {
        for (var c = -1, p = t.length, b = -1, w = s.length, T = -1, P = r.length, M = de(p - w, 0), B = C(M + P), q = !f; ++c < M; )
          B[c] = t[c];
        for (var j = c; ++T < P; )
          B[j + T] = r[T];
        for (; ++b < w; )
          (q || c < p) && (B[j + s[b]] = t[c++]);
        return B;
      }
      function Xe(t, r) {
        var s = -1, f = t.length;
        for (r || (r = C(f)); ++s < f; )
          r[s] = t[s];
        return r;
      }
      function Mn(t, r, s, f) {
        var c = !s;
        s || (s = {});
        for (var p = -1, b = r.length; ++p < b; ) {
          var w = r[p], T = f ? f(s[w], t[w], w, s, t) : i;
          T === i && (T = t[w]), c ? Vn(s, w, T) : Gi(s, w, T);
        }
        return s;
      }
      function R_(t, r) {
        return Mn(t, ou(t), r);
      }
      function P_(t, r) {
        return Mn(t, Tc(t), r);
      }
      function rs(t, r) {
        return function(s, f) {
          var c = dt(s) ? Bg : t_, p = r ? r() : {};
          return c(s, t, nt(f, 2), p);
        };
      }
      function hi(t) {
        return yt(function(r, s) {
          var f = -1, c = s.length, p = c > 1 ? s[c - 1] : i, b = c > 2 ? s[2] : i;
          for (p = t.length > 3 && typeof p == "function" ? (c--, p) : i, b && Le(s[0], s[1], b) && (p = c < 3 ? i : p, c = 1), r = Ut(r); ++f < c; ) {
            var w = s[f];
            w && t(r, w, f, p);
          }
          return r;
        });
      }
      function dc(t, r) {
        return function(s, f) {
          if (s == null)
            return s;
          if (!ze(s))
            return t(s, f);
          for (var c = s.length, p = r ? c : -1, b = Ut(s); (r ? p-- : ++p < c) && f(b[p], p, b) !== !1; )
            ;
          return s;
        };
      }
      function pc(t) {
        return function(r, s, f) {
          for (var c = -1, p = Ut(r), b = f(r), w = b.length; w--; ) {
            var T = b[t ? w : ++c];
            if (s(p[T], T, p) === !1)
              break;
          }
          return r;
        };
      }
      function M_(t, r, s) {
        var f = r & N, c = Vi(t);
        function p() {
          var b = this && this !== Ee && this instanceof p ? c : t;
          return b.apply(f ? s : this, arguments);
        }
        return p;
      }
      function gc(t) {
        return function(r) {
          r = Mt(r);
          var s = oi(r) ? xn(r) : i, f = s ? s[0] : r.charAt(0), c = s ? br(s, 1).join("") : r.slice(1);
          return f[t]() + c;
        };
      }
      function di(t) {
        return function(r) {
          return xl(dh(hh(r).replace(xg, "")), t, "");
        };
      }
      function Vi(t) {
        return function() {
          var r = arguments;
          switch (r.length) {
            case 0:
              return new t();
            case 1:
              return new t(r[0]);
            case 2:
              return new t(r[0], r[1]);
            case 3:
              return new t(r[0], r[1], r[2]);
            case 4:
              return new t(r[0], r[1], r[2], r[3]);
            case 5:
              return new t(r[0], r[1], r[2], r[3], r[4]);
            case 6:
              return new t(r[0], r[1], r[2], r[3], r[4], r[5]);
            case 7:
              return new t(r[0], r[1], r[2], r[3], r[4], r[5], r[6]);
          }
          var s = ci(t.prototype), f = t.apply(s, r);
          return Zt(f) ? f : s;
        };
      }
      function F_(t, r, s) {
        var f = Vi(t);
        function c() {
          for (var p = arguments.length, b = C(p), w = p, T = pi(c); w--; )
            b[w] = arguments[w];
          var P = p < 3 && b[0] !== T && b[p - 1] !== T ? [] : pr(b, T);
          if (p -= P.length, p < s)
            return yc(
              t,
              r,
              is,
              c.placeholder,
              i,
              b,
              P,
              i,
              i,
              s - p
            );
          var M = this && this !== Ee && this instanceof c ? f : t;
          return Qe(M, this, b);
        }
        return c;
      }
      function vc(t) {
        return function(r, s, f) {
          var c = Ut(r);
          if (!ze(r)) {
            var p = nt(s, 3);
            r = ve(r), s = function(w) {
              return p(c[w], w, c);
            };
          }
          var b = t(r, s, f);
          return b > -1 ? c[p ? r[b] : b] : i;
        };
      }
      function _c(t) {
        return Jn(function(r) {
          var s = r.length, f = s, c = gn.prototype.thru;
          for (t && r.reverse(); f--; ) {
            var p = r[f];
            if (typeof p != "function")
              throw new pn(a);
            if (c && !b && us(p) == "wrapper")
              var b = new gn([], !0);
          }
          for (f = b ? f : s; ++f < s; ) {
            p = r[f];
            var w = us(p), T = w == "wrapper" ? ru(p) : i;
            T && lu(T[0]) && T[1] == (St | X | tt | Wt) && !T[4].length && T[9] == 1 ? b = b[us(T[0])].apply(b, T[3]) : b = p.length == 1 && lu(p) ? b[w]() : b.thru(p);
          }
          return function() {
            var P = arguments, M = P[0];
            if (b && P.length == 1 && dt(M))
              return b.plant(M).value();
            for (var B = 0, q = s ? r[B].apply(this, P) : M; ++B < s; )
              q = r[B].call(this, q);
            return q;
          };
        });
      }
      function is(t, r, s, f, c, p, b, w, T, P) {
        var M = r & St, B = r & N, q = r & W, j = r & (X | L), it = r & ft, bt = q ? i : Vi(t);
        function ot() {
          for (var xt = arguments.length, Ct = C(xt), nn = xt; nn--; )
            Ct[nn] = arguments[nn];
          if (j)
            var Be = pi(ot), rn = zg(Ct, Be);
          if (f && (Ct = cc(Ct, f, c, j)), p && (Ct = hc(Ct, p, b, j)), xt -= rn, j && xt < P) {
            var ne = pr(Ct, Be);
            return yc(
              t,
              r,
              is,
              ot.placeholder,
              s,
              Ct,
              ne,
              w,
              T,
              P - xt
            );
          }
          var An = B ? s : this, tr = q ? An[t] : t;
          return xt = Ct.length, w ? Ct = tm(Ct, w) : it && xt > 1 && Ct.reverse(), M && T < xt && (Ct.length = T), this && this !== Ee && this instanceof ot && (tr = bt || Vi(tr)), tr.apply(An, Ct);
        }
        return ot;
      }
      function mc(t, r) {
        return function(s, f) {
          return u_(s, t, r(f), {});
        };
      }
      function os(t, r) {
        return function(s, f) {
          var c;
          if (s === i && f === i)
            return r;
          if (s !== i && (c = s), f !== i) {
            if (c === i)
              return f;
            typeof s == "string" || typeof f == "string" ? (s = tn(s), f = tn(f)) : (s = rc(s), f = rc(f)), c = t(s, f);
          }
          return c;
        };
      }
      function jl(t) {
        return Jn(function(r) {
          return r = Vt(r, je(nt())), yt(function(s) {
            var f = this;
            return t(r, function(c) {
              return Qe(c, f, s);
            });
          });
        });
      }
      function ss(t, r) {
        r = r === i ? " " : tn(r);
        var s = r.length;
        if (s < 2)
          return s ? zl(r, t) : r;
        var f = zl(r, zo(t / si(r)));
        return oi(r) ? br(xn(f), 0, t).join("") : f.slice(0, t);
      }
      function N_(t, r, s, f) {
        var c = r & N, p = Vi(t);
        function b() {
          for (var w = -1, T = arguments.length, P = -1, M = f.length, B = C(M + T), q = this && this !== Ee && this instanceof b ? p : t; ++P < M; )
            B[P] = f[P];
          for (; T--; )
            B[P++] = arguments[++w];
          return Qe(q, c ? s : this, B);
        }
        return b;
      }
      function bc(t) {
        return function(r, s, f) {
          return f && typeof f != "number" && Le(r, s, f) && (s = f = i), r = jn(r), s === i ? (s = r, r = 0) : s = jn(s), f = f === i ? r < s ? 1 : -1 : jn(f), y_(r, s, f, t);
        };
      }
      function ls(t) {
        return function(r, s) {
          return typeof r == "string" && typeof s == "string" || (r = bn(r), s = bn(s)), t(r, s);
        };
      }
      function yc(t, r, s, f, c, p, b, w, T, P) {
        var M = r & X, B = M ? b : i, q = M ? i : b, j = M ? p : i, it = M ? i : p;
        r |= M ? tt : Et, r &= ~(M ? Et : tt), r & V || (r &= ~(N | W));
        var bt = [
          t,
          r,
          c,
          j,
          B,
          it,
          q,
          w,
          T,
          P
        ], ot = s.apply(i, bt);
        return lu(t) && Pc(ot, bt), ot.placeholder = f, Mc(ot, t, r);
      }
      function tu(t) {
        var r = he[t];
        return function(s, f) {
          if (s = bn(s), f = f == null ? 0 : Ce(_t(f), 292), f && Ma(s)) {
            var c = (Mt(s) + "e").split("e"), p = r(c[0] + "e" + (+c[1] + f));
            return c = (Mt(p) + "e").split("e"), +(c[0] + "e" + (+c[1] - f));
          }
          return r(s);
        };
      }
      var L_ = fi && 1 / Lo(new fi([, -0]))[1] == lt ? function(t) {
        return new fi(t);
      } : Eu;
      function wc(t) {
        return function(r) {
          var s = Oe(r);
          return s == E ? Dl(r) : s == K ? jg(r) : Xg(r, t(r));
        };
      }
      function kn(t, r, s, f, c, p, b, w) {
        var T = r & W;
        if (!T && typeof t != "function")
          throw new pn(a);
        var P = f ? f.length : 0;
        if (P || (r &= ~(tt | Et), f = c = i), b = b === i ? b : de(_t(b), 0), w = w === i ? w : _t(w), P -= c ? c.length : 0, r & Et) {
          var M = f, B = c;
          f = c = i;
        }
        var q = T ? i : ru(t), j = [
          t,
          r,
          s,
          f,
          c,
          M,
          B,
          p,
          b,
          w
        ];
        if (q && Z_(j, q), t = j[0], r = j[1], s = j[2], f = j[3], c = j[4], w = j[9] = j[9] === i ? T ? 0 : t.length : de(j[9] - P, 0), !w && r & (X | L) && (r &= ~(X | L)), !r || r == N)
          var it = M_(t, r, s);
        else
          r == X || r == L ? it = F_(t, r, w) : (r == tt || r == (N | tt)) && !c.length ? it = N_(t, r, s, f) : it = is.apply(i, j);
        var bt = q ? ec : Pc;
        return Mc(bt(it, j), t, r);
      }
      function Ec(t, r, s, f) {
        return t === i || Tn(t, ui[s]) && !Bt.call(f, s) ? r : t;
      }
      function xc(t, r, s, f, c, p) {
        return Zt(t) && Zt(r) && (p.set(r, t), ts(t, r, i, xc, p), p.delete(r)), t;
      }
      function B_(t) {
        return Zi(t) ? i : t;
      }
      function Sc(t, r, s, f, c, p) {
        var b = s & O, w = t.length, T = r.length;
        if (w != T && !(b && T > w))
          return !1;
        var P = p.get(t), M = p.get(r);
        if (P && M)
          return P == r && M == t;
        var B = -1, q = !0, j = s & R ? new Br() : i;
        for (p.set(t, r), p.set(r, t); ++B < w; ) {
          var it = t[B], bt = r[B];
          if (f)
            var ot = b ? f(bt, it, B, r, t, p) : f(it, bt, B, t, r, p);
          if (ot !== i) {
            if (ot)
              continue;
            q = !1;
            break;
          }
          if (j) {
            if (!Sl(r, function(xt, Ct) {
              if (!Bi(j, Ct) && (it === xt || c(it, xt, s, f, p)))
                return j.push(Ct);
            })) {
              q = !1;
              break;
            }
          } else if (!(it === bt || c(it, bt, s, f, p))) {
            q = !1;
            break;
          }
        }
        return p.delete(t), p.delete(r), q;
      }
      function W_(t, r, s, f, c, p, b) {
        switch (s) {
          case It:
            if (t.byteLength != r.byteLength || t.byteOffset != r.byteOffset)
              return !1;
            t = t.buffer, r = r.buffer;
          case mt:
            return !(t.byteLength != r.byteLength || !p(new Ko(t), new Ko(r)));
          case Yn:
          case ur:
          case I:
            return Tn(+t, +r);
          case ar:
            return t.name == r.name && t.message == r.message;
          case G:
          case U:
            return t == r + "";
          case E:
            var w = Dl;
          case K:
            var T = f & O;
            if (w || (w = Lo), t.size != r.size && !T)
              return !1;
            var P = b.get(t);
            if (P)
              return P == r;
            f |= R, b.set(t, r);
            var M = Sc(w(t), w(r), f, c, p, b);
            return b.delete(t), M;
          case et:
            if (Ki)
              return Ki.call(t) == Ki.call(r);
        }
        return !1;
      }
      function U_(t, r, s, f, c, p) {
        var b = s & O, w = eu(t), T = w.length, P = eu(r), M = P.length;
        if (T != M && !b)
          return !1;
        for (var B = T; B--; ) {
          var q = w[B];
          if (!(b ? q in r : Bt.call(r, q)))
            return !1;
        }
        var j = p.get(t), it = p.get(r);
        if (j && it)
          return j == r && it == t;
        var bt = !0;
        p.set(t, r), p.set(r, t);
        for (var ot = b; ++B < T; ) {
          q = w[B];
          var xt = t[q], Ct = r[q];
          if (f)
            var nn = b ? f(Ct, xt, q, r, t, p) : f(xt, Ct, q, t, r, p);
          if (!(nn === i ? xt === Ct || c(xt, Ct, s, f, p) : nn)) {
            bt = !1;
            break;
          }
          ot || (ot = q == "constructor");
        }
        if (bt && !ot) {
          var Be = t.constructor, rn = r.constructor;
          Be != rn && "constructor" in t && "constructor" in r && !(typeof Be == "function" && Be instanceof Be && typeof rn == "function" && rn instanceof rn) && (bt = !1);
        }
        return p.delete(t), p.delete(r), bt;
      }
      function Jn(t) {
        return fu(Dc(t, i, Uc), t + "");
      }
      function eu(t) {
        return Ya(t, ve, ou);
      }
      function nu(t) {
        return Ya(t, qe, Tc);
      }
      var ru = Vo ? function(t) {
        return Vo.get(t);
      } : Eu;
      function us(t) {
        for (var r = t.name + "", s = ai[r], f = Bt.call(ai, r) ? s.length : 0; f--; ) {
          var c = s[f], p = c.func;
          if (p == null || p == t)
            return c.name;
        }
        return r;
      }
      function pi(t) {
        var r = Bt.call(d, "placeholder") ? d : t;
        return r.placeholder;
      }
      function nt() {
        var t = d.iteratee || yu;
        return t = t === yu ? qa : t, arguments.length ? t(arguments[0], arguments[1]) : t;
      }
      function fs(t, r) {
        var s = t.__data__;
        return q_(r) ? s[typeof r == "string" ? "string" : "hash"] : s.map;
      }
      function iu(t) {
        for (var r = ve(t), s = r.length; s--; ) {
          var f = r[s], c = t[f];
          r[s] = [f, c, Oc(c)];
        }
        return r;
      }
      function Hr(t, r) {
        var s = Jg(t, r);
        return za(s) ? s : i;
      }
      function H_(t) {
        var r = Bt.call(t, Nr), s = t[Nr];
        try {
          t[Nr] = i;
          var f = !0;
        } catch {
        }
        var c = Ho.call(t);
        return f && (r ? t[Nr] = s : delete t[Nr]), c;
      }
      var ou = Pl ? function(t) {
        return t == null ? [] : (t = Ut(t), hr(Pl(t), function(r) {
          return Ra.call(t, r);
        }));
      } : xu, Tc = Pl ? function(t) {
        for (var r = []; t; )
          dr(r, ou(t)), t = Go(t);
        return r;
      } : xu, Oe = Ne;
      (Ml && Oe(new Ml(new ArrayBuffer(1))) != It || Ui && Oe(new Ui()) != E || Fl && Oe(Fl.resolve()) != H || fi && Oe(new fi()) != K || Hi && Oe(new Hi()) != Z) && (Oe = function(t) {
        var r = Ne(t), s = r == F ? t.constructor : i, f = s ? $r(s) : "";
        if (f)
          switch (f) {
            case Ev:
              return It;
            case xv:
              return E;
            case Sv:
              return H;
            case Tv:
              return K;
            case Av:
              return Z;
          }
        return r;
      });
      function $_(t, r, s) {
        for (var f = -1, c = s.length; ++f < c; ) {
          var p = s[f], b = p.size;
          switch (p.type) {
            case "drop":
              t += b;
              break;
            case "dropRight":
              r -= b;
              break;
            case "take":
              r = Ce(r, t + b);
              break;
            case "takeRight":
              t = de(t, r - b);
              break;
          }
        }
        return { start: t, end: r };
      }
      function K_(t) {
        var r = t.match(Vp);
        return r ? r[1].split(kp) : [];
      }
      function Ac(t, r, s) {
        r = mr(r, t);
        for (var f = -1, c = r.length, p = !1; ++f < c; ) {
          var b = Fn(r[f]);
          if (!(p = t != null && s(t, b)))
            break;
          t = t[b];
        }
        return p || ++f != c ? p : (c = t == null ? 0 : t.length, !!c && vs(c) && Zn(b, c) && (dt(t) || Kr(t)));
      }
      function G_(t) {
        var r = t.length, s = new t.constructor(r);
        return r && typeof t[0] == "string" && Bt.call(t, "index") && (s.index = t.index, s.input = t.input), s;
      }
      function Cc(t) {
        return typeof t.constructor == "function" && !ki(t) ? ci(Go(t)) : {};
      }
      function Y_(t, r, s) {
        var f = t.constructor;
        switch (r) {
          case mt:
            return Ql(t);
          case Yn:
          case ur:
            return new f(+t);
          case It:
            return C_(t, s);
          case Dt:
          case be:
          case le:
          case Fe:
          case ye:
          case Xn:
          case ni:
          case we:
          case Ye:
            return fc(t, s);
          case E:
            return new f();
          case I:
          case U:
            return new f(t);
          case G:
            return O_(t);
          case K:
            return new f();
          case et:
            return I_(t);
        }
      }
      function X_(t, r) {
        var s = r.length;
        if (!s)
          return t;
        var f = s - 1;
        return r[f] = (s > 1 ? "& " : "") + r[f], r = r.join(s > 2 ? ", " : " "), t.replace(qp, `{
/* [wrapped with ` + r + `] */
`);
      }
      function z_(t) {
        return dt(t) || Kr(t) || !!(Pa && t && t[Pa]);
      }
      function Zn(t, r) {
        var s = typeof t;
        return r = r ?? rt, !!r && (s == "number" || s != "symbol" && ig.test(t)) && t > -1 && t % 1 == 0 && t < r;
      }
      function Le(t, r, s) {
        if (!Zt(s))
          return !1;
        var f = typeof r;
        return (f == "number" ? ze(s) && Zn(r, s.length) : f == "string" && r in s) ? Tn(s[r], t) : !1;
      }
      function su(t, r) {
        if (dt(t))
          return !1;
        var s = typeof t;
        return s == "number" || s == "symbol" || s == "boolean" || t == null || en(t) ? !0 : Gp.test(t) || !Kp.test(t) || r != null && t in Ut(r);
      }
      function q_(t) {
        var r = typeof t;
        return r == "string" || r == "number" || r == "symbol" || r == "boolean" ? t !== "__proto__" : t === null;
      }
      function lu(t) {
        var r = us(t), s = d[r];
        if (typeof s != "function" || !(r in Tt.prototype))
          return !1;
        if (t === s)
          return !0;
        var f = ru(s);
        return !!f && t === f[0];
      }
      function V_(t) {
        return !!Oa && Oa in t;
      }
      var k_ = Wo ? Qn : Su;
      function ki(t) {
        var r = t && t.constructor, s = typeof r == "function" && r.prototype || ui;
        return t === s;
      }
      function Oc(t) {
        return t === t && !Zt(t);
      }
      function Ic(t, r) {
        return function(s) {
          return s == null ? !1 : s[t] === r && (r !== i || t in Ut(s));
        };
      }
      function J_(t) {
        var r = ps(t, function(f) {
          return s.size === v && s.clear(), f;
        }), s = r.cache;
        return r;
      }
      function Z_(t, r) {
        var s = t[1], f = r[1], c = s | f, p = c < (N | W | St), b = f == St && s == X || f == St && s == Wt && t[7].length <= r[8] || f == (St | Wt) && r[7].length <= r[8] && s == X;
        if (!(p || b))
          return t;
        f & N && (t[2] = r[2], c |= s & N ? 0 : V);
        var w = r[3];
        if (w) {
          var T = t[3];
          t[3] = T ? cc(T, w, r[4]) : w, t[4] = T ? pr(t[3], _) : r[4];
        }
        return w = r[5], w && (T = t[5], t[5] = T ? hc(T, w, r[6]) : w, t[6] = T ? pr(t[5], _) : r[6]), w = r[7], w && (t[7] = w), f & St && (t[8] = t[8] == null ? r[8] : Ce(t[8], r[8])), t[9] == null && (t[9] = r[9]), t[0] = r[0], t[1] = c, t;
      }
      function Q_(t) {
        var r = [];
        if (t != null)
          for (var s in Ut(t))
            r.push(s);
        return r;
      }
      function j_(t) {
        return Ho.call(t);
      }
      function Dc(t, r, s) {
        return r = de(r === i ? t.length - 1 : r, 0), function() {
          for (var f = arguments, c = -1, p = de(f.length - r, 0), b = C(p); ++c < p; )
            b[c] = f[r + c];
          c = -1;
          for (var w = C(r + 1); ++c < r; )
            w[c] = f[c];
          return w[r] = s(b), Qe(t, this, w);
        };
      }
      function Rc(t, r) {
        return r.length < 2 ? t : Ur(t, _n(r, 0, -1));
      }
      function tm(t, r) {
        for (var s = t.length, f = Ce(r.length, s), c = Xe(t); f--; ) {
          var p = r[f];
          t[f] = Zn(p, s) ? c[p] : i;
        }
        return t;
      }
      function uu(t, r) {
        if (!(r === "constructor" && typeof t[r] == "function") && r != "__proto__")
          return t[r];
      }
      var Pc = Fc(ec), Ji = gv || function(t, r) {
        return Ee.setTimeout(t, r);
      }, fu = Fc(x_);
      function Mc(t, r, s) {
        var f = r + "";
        return fu(t, X_(f, em(K_(f), s)));
      }
      function Fc(t) {
        var r = 0, s = 0;
        return function() {
          var f = bv(), c = Lt - (f - s);
          if (s = f, c > 0) {
            if (++r >= zt)
              return arguments[0];
          } else
            r = 0;
          return t.apply(i, arguments);
        };
      }
      function as(t, r) {
        var s = -1, f = t.length, c = f - 1;
        for (r = r === i ? f : r; ++s < r; ) {
          var p = Xl(s, c), b = t[p];
          t[p] = t[s], t[s] = b;
        }
        return t.length = r, t;
      }
      var Nc = J_(function(t) {
        var r = [];
        return t.charCodeAt(0) === 46 && r.push(""), t.replace(Yp, function(s, f, c, p) {
          r.push(c ? p.replace(Qp, "$1") : f || s);
        }), r;
      });
      function Fn(t) {
        if (typeof t == "string" || en(t))
          return t;
        var r = t + "";
        return r == "0" && 1 / t == -lt ? "-0" : r;
      }
      function $r(t) {
        if (t != null) {
          try {
            return Uo.call(t);
          } catch {
          }
          try {
            return t + "";
          } catch {
          }
        }
        return "";
      }
      function em(t, r) {
        return dn(se, function(s) {
          var f = "_." + s[0];
          r & s[1] && !Fo(t, f) && t.push(f);
        }), t.sort();
      }
      function Lc(t) {
        if (t instanceof Tt)
          return t.clone();
        var r = new gn(t.__wrapped__, t.__chain__);
        return r.__actions__ = Xe(t.__actions__), r.__index__ = t.__index__, r.__values__ = t.__values__, r;
      }
      function nm(t, r, s) {
        (s ? Le(t, r, s) : r === i) ? r = 1 : r = de(_t(r), 0);
        var f = t == null ? 0 : t.length;
        if (!f || r < 1)
          return [];
        for (var c = 0, p = 0, b = C(zo(f / r)); c < f; )
          b[p++] = _n(t, c, c += r);
        return b;
      }
      function rm(t) {
        for (var r = -1, s = t == null ? 0 : t.length, f = 0, c = []; ++r < s; ) {
          var p = t[r];
          p && (c[f++] = p);
        }
        return c;
      }
      function im() {
        var t = arguments.length;
        if (!t)
          return [];
        for (var r = C(t - 1), s = arguments[0], f = t; f--; )
          r[f - 1] = arguments[f];
        return dr(dt(s) ? Xe(s) : [s], xe(r, 1));
      }
      var om = yt(function(t, r) {
        return ee(t) ? Yi(t, xe(r, 1, ee, !0)) : [];
      }), sm = yt(function(t, r) {
        var s = mn(r);
        return ee(s) && (s = i), ee(t) ? Yi(t, xe(r, 1, ee, !0), nt(s, 2)) : [];
      }), lm = yt(function(t, r) {
        var s = mn(r);
        return ee(s) && (s = i), ee(t) ? Yi(t, xe(r, 1, ee, !0), i, s) : [];
      });
      function um(t, r, s) {
        var f = t == null ? 0 : t.length;
        return f ? (r = s || r === i ? 1 : _t(r), _n(t, r < 0 ? 0 : r, f)) : [];
      }
      function fm(t, r, s) {
        var f = t == null ? 0 : t.length;
        return f ? (r = s || r === i ? 1 : _t(r), r = f - r, _n(t, 0, r < 0 ? 0 : r)) : [];
      }
      function am(t, r) {
        return t && t.length ? ns(t, nt(r, 3), !0, !0) : [];
      }
      function cm(t, r) {
        return t && t.length ? ns(t, nt(r, 3), !0) : [];
      }
      function hm(t, r, s, f) {
        var c = t == null ? 0 : t.length;
        return c ? (s && typeof s != "number" && Le(t, r, s) && (s = 0, f = c), i_(t, r, s, f)) : [];
      }
      function Bc(t, r, s) {
        var f = t == null ? 0 : t.length;
        if (!f)
          return -1;
        var c = s == null ? 0 : _t(s);
        return c < 0 && (c = de(f + c, 0)), No(t, nt(r, 3), c);
      }
      function Wc(t, r, s) {
        var f = t == null ? 0 : t.length;
        if (!f)
          return -1;
        var c = f - 1;
        return s !== i && (c = _t(s), c = s < 0 ? de(f + c, 0) : Ce(c, f - 1)), No(t, nt(r, 3), c, !0);
      }
      function Uc(t) {
        var r = t == null ? 0 : t.length;
        return r ? xe(t, 1) : [];
      }
      function dm(t) {
        var r = t == null ? 0 : t.length;
        return r ? xe(t, lt) : [];
      }
      function pm(t, r) {
        var s = t == null ? 0 : t.length;
        return s ? (r = r === i ? 1 : _t(r), xe(t, r)) : [];
      }
      function gm(t) {
        for (var r = -1, s = t == null ? 0 : t.length, f = {}; ++r < s; ) {
          var c = t[r];
          f[c[0]] = c[1];
        }
        return f;
      }
      function Hc(t) {
        return t && t.length ? t[0] : i;
      }
      function vm(t, r, s) {
        var f = t == null ? 0 : t.length;
        if (!f)
          return -1;
        var c = s == null ? 0 : _t(s);
        return c < 0 && (c = de(f + c, 0)), ii(t, r, c);
      }
      function _m(t) {
        var r = t == null ? 0 : t.length;
        return r ? _n(t, 0, -1) : [];
      }
      var mm = yt(function(t) {
        var r = Vt(t, Jl);
        return r.length && r[0] === t[0] ? Hl(r) : [];
      }), bm = yt(function(t) {
        var r = mn(t), s = Vt(t, Jl);
        return r === mn(s) ? r = i : s.pop(), s.length && s[0] === t[0] ? Hl(s, nt(r, 2)) : [];
      }), ym = yt(function(t) {
        var r = mn(t), s = Vt(t, Jl);
        return r = typeof r == "function" ? r : i, r && s.pop(), s.length && s[0] === t[0] ? Hl(s, i, r) : [];
      });
      function wm(t, r) {
        return t == null ? "" : _v.call(t, r);
      }
      function mn(t) {
        var r = t == null ? 0 : t.length;
        return r ? t[r - 1] : i;
      }
      function Em(t, r, s) {
        var f = t == null ? 0 : t.length;
        if (!f)
          return -1;
        var c = f;
        return s !== i && (c = _t(s), c = c < 0 ? de(f + c, 0) : Ce(c, f - 1)), r === r ? ev(t, r, c) : No(t, ya, c, !0);
      }
      function xm(t, r) {
        return t && t.length ? Za(t, _t(r)) : i;
      }
      var Sm = yt($c);
      function $c(t, r) {
        return t && t.length && r && r.length ? Yl(t, r) : t;
      }
      function Tm(t, r, s) {
        return t && t.length && r && r.length ? Yl(t, r, nt(s, 2)) : t;
      }
      function Am(t, r, s) {
        return t && t.length && r && r.length ? Yl(t, r, i, s) : t;
      }
      var Cm = Jn(function(t, r) {
        var s = t == null ? 0 : t.length, f = Ll(t, r);
        return tc(t, Vt(r, function(c) {
          return Zn(c, s) ? +c : c;
        }).sort(ac)), f;
      });
      function Om(t, r) {
        var s = [];
        if (!(t && t.length))
          return s;
        var f = -1, c = [], p = t.length;
        for (r = nt(r, 3); ++f < p; ) {
          var b = t[f];
          r(b, f, t) && (s.push(b), c.push(f));
        }
        return tc(t, c), s;
      }
      function au(t) {
        return t == null ? t : wv.call(t);
      }
      function Im(t, r, s) {
        var f = t == null ? 0 : t.length;
        return f ? (s && typeof s != "number" && Le(t, r, s) ? (r = 0, s = f) : (r = r == null ? 0 : _t(r), s = s === i ? f : _t(s)), _n(t, r, s)) : [];
      }
      function Dm(t, r) {
        return es(t, r);
      }
      function Rm(t, r, s) {
        return ql(t, r, nt(s, 2));
      }
      function Pm(t, r) {
        var s = t == null ? 0 : t.length;
        if (s) {
          var f = es(t, r);
          if (f < s && Tn(t[f], r))
            return f;
        }
        return -1;
      }
      function Mm(t, r) {
        return es(t, r, !0);
      }
      function Fm(t, r, s) {
        return ql(t, r, nt(s, 2), !0);
      }
      function Nm(t, r) {
        var s = t == null ? 0 : t.length;
        if (s) {
          var f = es(t, r, !0) - 1;
          if (Tn(t[f], r))
            return f;
        }
        return -1;
      }
      function Lm(t) {
        return t && t.length ? nc(t) : [];
      }
      function Bm(t, r) {
        return t && t.length ? nc(t, nt(r, 2)) : [];
      }
      function Wm(t) {
        var r = t == null ? 0 : t.length;
        return r ? _n(t, 1, r) : [];
      }
      function Um(t, r, s) {
        return t && t.length ? (r = s || r === i ? 1 : _t(r), _n(t, 0, r < 0 ? 0 : r)) : [];
      }
      function Hm(t, r, s) {
        var f = t == null ? 0 : t.length;
        return f ? (r = s || r === i ? 1 : _t(r), r = f - r, _n(t, r < 0 ? 0 : r, f)) : [];
      }
      function $m(t, r) {
        return t && t.length ? ns(t, nt(r, 3), !1, !0) : [];
      }
      function Km(t, r) {
        return t && t.length ? ns(t, nt(r, 3)) : [];
      }
      var Gm = yt(function(t) {
        return _r(xe(t, 1, ee, !0));
      }), Ym = yt(function(t) {
        var r = mn(t);
        return ee(r) && (r = i), _r(xe(t, 1, ee, !0), nt(r, 2));
      }), Xm = yt(function(t) {
        var r = mn(t);
        return r = typeof r == "function" ? r : i, _r(xe(t, 1, ee, !0), i, r);
      });
      function zm(t) {
        return t && t.length ? _r(t) : [];
      }
      function qm(t, r) {
        return t && t.length ? _r(t, nt(r, 2)) : [];
      }
      function Vm(t, r) {
        return r = typeof r == "function" ? r : i, t && t.length ? _r(t, i, r) : [];
      }
      function cu(t) {
        if (!(t && t.length))
          return [];
        var r = 0;
        return t = hr(t, function(s) {
          if (ee(s))
            return r = de(s.length, r), !0;
        }), Ol(r, function(s) {
          return Vt(t, Tl(s));
        });
      }
      function Kc(t, r) {
        if (!(t && t.length))
          return [];
        var s = cu(t);
        return r == null ? s : Vt(s, function(f) {
          return Qe(r, i, f);
        });
      }
      var km = yt(function(t, r) {
        return ee(t) ? Yi(t, r) : [];
      }), Jm = yt(function(t) {
        return kl(hr(t, ee));
      }), Zm = yt(function(t) {
        var r = mn(t);
        return ee(r) && (r = i), kl(hr(t, ee), nt(r, 2));
      }), Qm = yt(function(t) {
        var r = mn(t);
        return r = typeof r == "function" ? r : i, kl(hr(t, ee), i, r);
      }), jm = yt(cu);
      function t0(t, r) {
        return sc(t || [], r || [], Gi);
      }
      function e0(t, r) {
        return sc(t || [], r || [], qi);
      }
      var n0 = yt(function(t) {
        var r = t.length, s = r > 1 ? t[r - 1] : i;
        return s = typeof s == "function" ? (t.pop(), s) : i, Kc(t, s);
      });
      function Gc(t) {
        var r = d(t);
        return r.__chain__ = !0, r;
      }
      function r0(t, r) {
        return r(t), t;
      }
      function cs(t, r) {
        return r(t);
      }
      var i0 = Jn(function(t) {
        var r = t.length, s = r ? t[0] : 0, f = this.__wrapped__, c = function(p) {
          return Ll(p, t);
        };
        return r > 1 || this.__actions__.length || !(f instanceof Tt) || !Zn(s) ? this.thru(c) : (f = f.slice(s, +s + (r ? 1 : 0)), f.__actions__.push({
          func: cs,
          args: [c],
          thisArg: i
        }), new gn(f, this.__chain__).thru(function(p) {
          return r && !p.length && p.push(i), p;
        }));
      });
      function o0() {
        return Gc(this);
      }
      function s0() {
        return new gn(this.value(), this.__chain__);
      }
      function l0() {
        this.__values__ === i && (this.__values__ = rh(this.value()));
        var t = this.__index__ >= this.__values__.length, r = t ? i : this.__values__[this.__index__++];
        return { done: t, value: r };
      }
      function u0() {
        return this;
      }
      function f0(t) {
        for (var r, s = this; s instanceof Jo; ) {
          var f = Lc(s);
          f.__index__ = 0, f.__values__ = i, r ? c.__wrapped__ = f : r = f;
          var c = f;
          s = s.__wrapped__;
        }
        return c.__wrapped__ = t, r;
      }
      function a0() {
        var t = this.__wrapped__;
        if (t instanceof Tt) {
          var r = t;
          return this.__actions__.length && (r = new Tt(this)), r = r.reverse(), r.__actions__.push({
            func: cs,
            args: [au],
            thisArg: i
          }), new gn(r, this.__chain__);
        }
        return this.thru(au);
      }
      function c0() {
        return oc(this.__wrapped__, this.__actions__);
      }
      var h0 = rs(function(t, r, s) {
        Bt.call(t, s) ? ++t[s] : Vn(t, s, 1);
      });
      function d0(t, r, s) {
        var f = dt(t) ? ma : r_;
        return s && Le(t, r, s) && (r = i), f(t, nt(r, 3));
      }
      function p0(t, r) {
        var s = dt(t) ? hr : Ka;
        return s(t, nt(r, 3));
      }
      var g0 = vc(Bc), v0 = vc(Wc);
      function _0(t, r) {
        return xe(hs(t, r), 1);
      }
      function m0(t, r) {
        return xe(hs(t, r), lt);
      }
      function b0(t, r, s) {
        return s = s === i ? 1 : _t(s), xe(hs(t, r), s);
      }
      function Yc(t, r) {
        var s = dt(t) ? dn : vr;
        return s(t, nt(r, 3));
      }
      function Xc(t, r) {
        var s = dt(t) ? Wg : $a;
        return s(t, nt(r, 3));
      }
      var y0 = rs(function(t, r, s) {
        Bt.call(t, s) ? t[s].push(r) : Vn(t, s, [r]);
      });
      function w0(t, r, s, f) {
        t = ze(t) ? t : vi(t), s = s && !f ? _t(s) : 0;
        var c = t.length;
        return s < 0 && (s = de(c + s, 0)), _s(t) ? s <= c && t.indexOf(r, s) > -1 : !!c && ii(t, r, s) > -1;
      }
      var E0 = yt(function(t, r, s) {
        var f = -1, c = typeof r == "function", p = ze(t) ? C(t.length) : [];
        return vr(t, function(b) {
          p[++f] = c ? Qe(r, b, s) : Xi(b, r, s);
        }), p;
      }), x0 = rs(function(t, r, s) {
        Vn(t, s, r);
      });
      function hs(t, r) {
        var s = dt(t) ? Vt : Va;
        return s(t, nt(r, 3));
      }
      function S0(t, r, s, f) {
        return t == null ? [] : (dt(r) || (r = r == null ? [] : [r]), s = f ? i : s, dt(s) || (s = s == null ? [] : [s]), Qa(t, r, s));
      }
      var T0 = rs(function(t, r, s) {
        t[s ? 0 : 1].push(r);
      }, function() {
        return [[], []];
      });
      function A0(t, r, s) {
        var f = dt(t) ? xl : Ea, c = arguments.length < 3;
        return f(t, nt(r, 4), s, c, vr);
      }
      function C0(t, r, s) {
        var f = dt(t) ? Ug : Ea, c = arguments.length < 3;
        return f(t, nt(r, 4), s, c, $a);
      }
      function O0(t, r) {
        var s = dt(t) ? hr : Ka;
        return s(t, gs(nt(r, 3)));
      }
      function I0(t) {
        var r = dt(t) ? Ba : w_;
        return r(t);
      }
      function D0(t, r, s) {
        (s ? Le(t, r, s) : r === i) ? r = 1 : r = _t(r);
        var f = dt(t) ? Qv : E_;
        return f(t, r);
      }
      function R0(t) {
        var r = dt(t) ? jv : S_;
        return r(t);
      }
      function P0(t) {
        if (t == null)
          return 0;
        if (ze(t))
          return _s(t) ? si(t) : t.length;
        var r = Oe(t);
        return r == E || r == K ? t.size : Kl(t).length;
      }
      function M0(t, r, s) {
        var f = dt(t) ? Sl : T_;
        return s && Le(t, r, s) && (r = i), f(t, nt(r, 3));
      }
      var F0 = yt(function(t, r) {
        if (t == null)
          return [];
        var s = r.length;
        return s > 1 && Le(t, r[0], r[1]) ? r = [] : s > 2 && Le(r[0], r[1], r[2]) && (r = [r[0]]), Qa(t, xe(r, 1), []);
      }), ds = pv || function() {
        return Ee.Date.now();
      };
      function N0(t, r) {
        if (typeof r != "function")
          throw new pn(a);
        return t = _t(t), function() {
          if (--t < 1)
            return r.apply(this, arguments);
        };
      }
      function zc(t, r, s) {
        return r = s ? i : r, r = t && r == null ? t.length : r, kn(t, St, i, i, i, i, r);
      }
      function qc(t, r) {
        var s;
        if (typeof r != "function")
          throw new pn(a);
        return t = _t(t), function() {
          return --t > 0 && (s = r.apply(this, arguments)), t <= 1 && (r = i), s;
        };
      }
      var hu = yt(function(t, r, s) {
        var f = N;
        if (s.length) {
          var c = pr(s, pi(hu));
          f |= tt;
        }
        return kn(t, f, r, s, c);
      }), Vc = yt(function(t, r, s) {
        var f = N | W;
        if (s.length) {
          var c = pr(s, pi(Vc));
          f |= tt;
        }
        return kn(r, f, t, s, c);
      });
      function kc(t, r, s) {
        r = s ? i : r;
        var f = kn(t, X, i, i, i, i, i, r);
        return f.placeholder = kc.placeholder, f;
      }
      function Jc(t, r, s) {
        r = s ? i : r;
        var f = kn(t, L, i, i, i, i, i, r);
        return f.placeholder = Jc.placeholder, f;
      }
      function Zc(t, r, s) {
        var f, c, p, b, w, T, P = 0, M = !1, B = !1, q = !0;
        if (typeof t != "function")
          throw new pn(a);
        r = bn(r) || 0, Zt(s) && (M = !!s.leading, B = "maxWait" in s, p = B ? de(bn(s.maxWait) || 0, r) : p, q = "trailing" in s ? !!s.trailing : q);
        function j(ne) {
          var An = f, tr = c;
          return f = c = i, P = ne, b = t.apply(tr, An), b;
        }
        function it(ne) {
          return P = ne, w = Ji(xt, r), M ? j(ne) : b;
        }
        function bt(ne) {
          var An = ne - T, tr = ne - P, vh = r - An;
          return B ? Ce(vh, p - tr) : vh;
        }
        function ot(ne) {
          var An = ne - T, tr = ne - P;
          return T === i || An >= r || An < 0 || B && tr >= p;
        }
        function xt() {
          var ne = ds();
          if (ot(ne))
            return Ct(ne);
          w = Ji(xt, bt(ne));
        }
        function Ct(ne) {
          return w = i, q && f ? j(ne) : (f = c = i, b);
        }
        function nn() {
          w !== i && lc(w), P = 0, f = T = c = w = i;
        }
        function Be() {
          return w === i ? b : Ct(ds());
        }
        function rn() {
          var ne = ds(), An = ot(ne);
          if (f = arguments, c = this, T = ne, An) {
            if (w === i)
              return it(T);
            if (B)
              return lc(w), w = Ji(xt, r), j(T);
          }
          return w === i && (w = Ji(xt, r)), b;
        }
        return rn.cancel = nn, rn.flush = Be, rn;
      }
      var L0 = yt(function(t, r) {
        return Ha(t, 1, r);
      }), B0 = yt(function(t, r, s) {
        return Ha(t, bn(r) || 0, s);
      });
      function W0(t) {
        return kn(t, ft);
      }
      function ps(t, r) {
        if (typeof t != "function" || r != null && typeof r != "function")
          throw new pn(a);
        var s = function() {
          var f = arguments, c = r ? r.apply(this, f) : f[0], p = s.cache;
          if (p.has(c))
            return p.get(c);
          var b = t.apply(this, f);
          return s.cache = p.set(c, b) || p, b;
        };
        return s.cache = new (ps.Cache || qn)(), s;
      }
      ps.Cache = qn;
      function gs(t) {
        if (typeof t != "function")
          throw new pn(a);
        return function() {
          var r = arguments;
          switch (r.length) {
            case 0:
              return !t.call(this);
            case 1:
              return !t.call(this, r[0]);
            case 2:
              return !t.call(this, r[0], r[1]);
            case 3:
              return !t.call(this, r[0], r[1], r[2]);
          }
          return !t.apply(this, r);
        };
      }
      function U0(t) {
        return qc(2, t);
      }
      var H0 = A_(function(t, r) {
        r = r.length == 1 && dt(r[0]) ? Vt(r[0], je(nt())) : Vt(xe(r, 1), je(nt()));
        var s = r.length;
        return yt(function(f) {
          for (var c = -1, p = Ce(f.length, s); ++c < p; )
            f[c] = r[c].call(this, f[c]);
          return Qe(t, this, f);
        });
      }), du = yt(function(t, r) {
        var s = pr(r, pi(du));
        return kn(t, tt, i, r, s);
      }), Qc = yt(function(t, r) {
        var s = pr(r, pi(Qc));
        return kn(t, Et, i, r, s);
      }), $0 = Jn(function(t, r) {
        return kn(t, Wt, i, i, i, r);
      });
      function K0(t, r) {
        if (typeof t != "function")
          throw new pn(a);
        return r = r === i ? r : _t(r), yt(t, r);
      }
      function G0(t, r) {
        if (typeof t != "function")
          throw new pn(a);
        return r = r == null ? 0 : de(_t(r), 0), yt(function(s) {
          var f = s[r], c = br(s, 0, r);
          return f && dr(c, f), Qe(t, this, c);
        });
      }
      function Y0(t, r, s) {
        var f = !0, c = !0;
        if (typeof t != "function")
          throw new pn(a);
        return Zt(s) && (f = "leading" in s ? !!s.leading : f, c = "trailing" in s ? !!s.trailing : c), Zc(t, r, {
          leading: f,
          maxWait: r,
          trailing: c
        });
      }
      function X0(t) {
        return zc(t, 1);
      }
      function z0(t, r) {
        return du(Zl(r), t);
      }
      function q0() {
        if (!arguments.length)
          return [];
        var t = arguments[0];
        return dt(t) ? t : [t];
      }
      function V0(t) {
        return vn(t, A);
      }
      function k0(t, r) {
        return r = typeof r == "function" ? r : i, vn(t, A, r);
      }
      function J0(t) {
        return vn(t, m | A);
      }
      function Z0(t, r) {
        return r = typeof r == "function" ? r : i, vn(t, m | A, r);
      }
      function Q0(t, r) {
        return r == null || Ua(t, r, ve(r));
      }
      function Tn(t, r) {
        return t === r || t !== t && r !== r;
      }
      var j0 = ls(Ul), tb = ls(function(t, r) {
        return t >= r;
      }), Kr = Xa(/* @__PURE__ */ function() {
        return arguments;
      }()) ? Xa : function(t) {
        return jt(t) && Bt.call(t, "callee") && !Ra.call(t, "callee");
      }, dt = C.isArray, eb = ha ? je(ha) : f_;
      function ze(t) {
        return t != null && vs(t.length) && !Qn(t);
      }
      function ee(t) {
        return jt(t) && ze(t);
      }
      function nb(t) {
        return t === !0 || t === !1 || jt(t) && Ne(t) == Yn;
      }
      var yr = vv || Su, rb = da ? je(da) : a_;
      function ib(t) {
        return jt(t) && t.nodeType === 1 && !Zi(t);
      }
      function ob(t) {
        if (t == null)
          return !0;
        if (ze(t) && (dt(t) || typeof t == "string" || typeof t.splice == "function" || yr(t) || gi(t) || Kr(t)))
          return !t.length;
        var r = Oe(t);
        if (r == E || r == K)
          return !t.size;
        if (ki(t))
          return !Kl(t).length;
        for (var s in t)
          if (Bt.call(t, s))
            return !1;
        return !0;
      }
      function sb(t, r) {
        return zi(t, r);
      }
      function lb(t, r, s) {
        s = typeof s == "function" ? s : i;
        var f = s ? s(t, r) : i;
        return f === i ? zi(t, r, i, s) : !!f;
      }
      function pu(t) {
        if (!jt(t))
          return !1;
        var r = Ne(t);
        return r == ar || r == fr || typeof t.message == "string" && typeof t.name == "string" && !Zi(t);
      }
      function ub(t) {
        return typeof t == "number" && Ma(t);
      }
      function Qn(t) {
        if (!Zt(t))
          return !1;
        var r = Ne(t);
        return r == cr || r == y || r == ei || r == z;
      }
      function jc(t) {
        return typeof t == "number" && t == _t(t);
      }
      function vs(t) {
        return typeof t == "number" && t > -1 && t % 1 == 0 && t <= rt;
      }
      function Zt(t) {
        var r = typeof t;
        return t != null && (r == "object" || r == "function");
      }
      function jt(t) {
        return t != null && typeof t == "object";
      }
      var th = pa ? je(pa) : h_;
      function fb(t, r) {
        return t === r || $l(t, r, iu(r));
      }
      function ab(t, r, s) {
        return s = typeof s == "function" ? s : i, $l(t, r, iu(r), s);
      }
      function cb(t) {
        return eh(t) && t != +t;
      }
      function hb(t) {
        if (k_(t))
          throw new ct(u);
        return za(t);
      }
      function db(t) {
        return t === null;
      }
      function pb(t) {
        return t == null;
      }
      function eh(t) {
        return typeof t == "number" || jt(t) && Ne(t) == I;
      }
      function Zi(t) {
        if (!jt(t) || Ne(t) != F)
          return !1;
        var r = Go(t);
        if (r === null)
          return !0;
        var s = Bt.call(r, "constructor") && r.constructor;
        return typeof s == "function" && s instanceof s && Uo.call(s) == av;
      }
      var gu = ga ? je(ga) : d_;
      function gb(t) {
        return jc(t) && t >= -rt && t <= rt;
      }
      var nh = va ? je(va) : p_;
      function _s(t) {
        return typeof t == "string" || !dt(t) && jt(t) && Ne(t) == U;
      }
      function en(t) {
        return typeof t == "symbol" || jt(t) && Ne(t) == et;
      }
      var gi = _a ? je(_a) : g_;
      function vb(t) {
        return t === i;
      }
      function _b(t) {
        return jt(t) && Oe(t) == Z;
      }
      function mb(t) {
        return jt(t) && Ne(t) == ut;
      }
      var bb = ls(Gl), yb = ls(function(t, r) {
        return t <= r;
      });
      function rh(t) {
        if (!t)
          return [];
        if (ze(t))
          return _s(t) ? xn(t) : Xe(t);
        if (Wi && t[Wi])
          return Qg(t[Wi]());
        var r = Oe(t), s = r == E ? Dl : r == K ? Lo : vi;
        return s(t);
      }
      function jn(t) {
        if (!t)
          return t === 0 ? t : 0;
        if (t = bn(t), t === lt || t === -lt) {
          var r = t < 0 ? -1 : 1;
          return r * _e;
        }
        return t === t ? t : 0;
      }
      function _t(t) {
        var r = jn(t), s = r % 1;
        return r === r ? s ? r - s : r : 0;
      }
      function ih(t) {
        return t ? Wr(_t(t), 0, qt) : 0;
      }
      function bn(t) {
        if (typeof t == "number")
          return t;
        if (en(t))
          return ge;
        if (Zt(t)) {
          var r = typeof t.valueOf == "function" ? t.valueOf() : t;
          t = Zt(r) ? r + "" : r;
        }
        if (typeof t != "string")
          return t === 0 ? t : +t;
        t = xa(t);
        var s = eg.test(t);
        return s || rg.test(t) ? Ng(t.slice(2), s ? 2 : 8) : tg.test(t) ? ge : +t;
      }
      function oh(t) {
        return Mn(t, qe(t));
      }
      function wb(t) {
        return t ? Wr(_t(t), -rt, rt) : t === 0 ? t : 0;
      }
      function Mt(t) {
        return t == null ? "" : tn(t);
      }
      var Eb = hi(function(t, r) {
        if (ki(r) || ze(r)) {
          Mn(r, ve(r), t);
          return;
        }
        for (var s in r)
          Bt.call(r, s) && Gi(t, s, r[s]);
      }), sh = hi(function(t, r) {
        Mn(r, qe(r), t);
      }), ms = hi(function(t, r, s, f) {
        Mn(r, qe(r), t, f);
      }), xb = hi(function(t, r, s, f) {
        Mn(r, ve(r), t, f);
      }), Sb = Jn(Ll);
      function Tb(t, r) {
        var s = ci(t);
        return r == null ? s : Wa(s, r);
      }
      var Ab = yt(function(t, r) {
        t = Ut(t);
        var s = -1, f = r.length, c = f > 2 ? r[2] : i;
        for (c && Le(r[0], r[1], c) && (f = 1); ++s < f; )
          for (var p = r[s], b = qe(p), w = -1, T = b.length; ++w < T; ) {
            var P = b[w], M = t[P];
            (M === i || Tn(M, ui[P]) && !Bt.call(t, P)) && (t[P] = p[P]);
          }
        return t;
      }), Cb = yt(function(t) {
        return t.push(i, xc), Qe(lh, i, t);
      });
      function Ob(t, r) {
        return ba(t, nt(r, 3), Pn);
      }
      function Ib(t, r) {
        return ba(t, nt(r, 3), Wl);
      }
      function Db(t, r) {
        return t == null ? t : Bl(t, nt(r, 3), qe);
      }
      function Rb(t, r) {
        return t == null ? t : Ga(t, nt(r, 3), qe);
      }
      function Pb(t, r) {
        return t && Pn(t, nt(r, 3));
      }
      function Mb(t, r) {
        return t && Wl(t, nt(r, 3));
      }
      function Fb(t) {
        return t == null ? [] : jo(t, ve(t));
      }
      function Nb(t) {
        return t == null ? [] : jo(t, qe(t));
      }
      function vu(t, r, s) {
        var f = t == null ? i : Ur(t, r);
        return f === i ? s : f;
      }
      function Lb(t, r) {
        return t != null && Ac(t, r, o_);
      }
      function _u(t, r) {
        return t != null && Ac(t, r, s_);
      }
      var Bb = mc(function(t, r, s) {
        r != null && typeof r.toString != "function" && (r = Ho.call(r)), t[r] = s;
      }, bu(Ve)), Wb = mc(function(t, r, s) {
        r != null && typeof r.toString != "function" && (r = Ho.call(r)), Bt.call(t, r) ? t[r].push(s) : t[r] = [s];
      }, nt), Ub = yt(Xi);
      function ve(t) {
        return ze(t) ? La(t) : Kl(t);
      }
      function qe(t) {
        return ze(t) ? La(t, !0) : v_(t);
      }
      function Hb(t, r) {
        var s = {};
        return r = nt(r, 3), Pn(t, function(f, c, p) {
          Vn(s, r(f, c, p), f);
        }), s;
      }
      function $b(t, r) {
        var s = {};
        return r = nt(r, 3), Pn(t, function(f, c, p) {
          Vn(s, c, r(f, c, p));
        }), s;
      }
      var Kb = hi(function(t, r, s) {
        ts(t, r, s);
      }), lh = hi(function(t, r, s, f) {
        ts(t, r, s, f);
      }), Gb = Jn(function(t, r) {
        var s = {};
        if (t == null)
          return s;
        var f = !1;
        r = Vt(r, function(p) {
          return p = mr(p, t), f || (f = p.length > 1), p;
        }), Mn(t, nu(t), s), f && (s = vn(s, m | x | A, B_));
        for (var c = r.length; c--; )
          Vl(s, r[c]);
        return s;
      });
      function Yb(t, r) {
        return uh(t, gs(nt(r)));
      }
      var Xb = Jn(function(t, r) {
        return t == null ? {} : m_(t, r);
      });
      function uh(t, r) {
        if (t == null)
          return {};
        var s = Vt(nu(t), function(f) {
          return [f];
        });
        return r = nt(r), ja(t, s, function(f, c) {
          return r(f, c[0]);
        });
      }
      function zb(t, r, s) {
        r = mr(r, t);
        var f = -1, c = r.length;
        for (c || (c = 1, t = i); ++f < c; ) {
          var p = t == null ? i : t[Fn(r[f])];
          p === i && (f = c, p = s), t = Qn(p) ? p.call(t) : p;
        }
        return t;
      }
      function qb(t, r, s) {
        return t == null ? t : qi(t, r, s);
      }
      function Vb(t, r, s, f) {
        return f = typeof f == "function" ? f : i, t == null ? t : qi(t, r, s, f);
      }
      var fh = wc(ve), ah = wc(qe);
      function kb(t, r, s) {
        var f = dt(t), c = f || yr(t) || gi(t);
        if (r = nt(r, 4), s == null) {
          var p = t && t.constructor;
          c ? s = f ? new p() : [] : Zt(t) ? s = Qn(p) ? ci(Go(t)) : {} : s = {};
        }
        return (c ? dn : Pn)(t, function(b, w, T) {
          return r(s, b, w, T);
        }), s;
      }
      function Jb(t, r) {
        return t == null ? !0 : Vl(t, r);
      }
      function Zb(t, r, s) {
        return t == null ? t : ic(t, r, Zl(s));
      }
      function Qb(t, r, s, f) {
        return f = typeof f == "function" ? f : i, t == null ? t : ic(t, r, Zl(s), f);
      }
      function vi(t) {
        return t == null ? [] : Il(t, ve(t));
      }
      function jb(t) {
        return t == null ? [] : Il(t, qe(t));
      }
      function ty(t, r, s) {
        return s === i && (s = r, r = i), s !== i && (s = bn(s), s = s === s ? s : 0), r !== i && (r = bn(r), r = r === r ? r : 0), Wr(bn(t), r, s);
      }
      function ey(t, r, s) {
        return r = jn(r), s === i ? (s = r, r = 0) : s = jn(s), t = bn(t), l_(t, r, s);
      }
      function ny(t, r, s) {
        if (s && typeof s != "boolean" && Le(t, r, s) && (r = s = i), s === i && (typeof r == "boolean" ? (s = r, r = i) : typeof t == "boolean" && (s = t, t = i)), t === i && r === i ? (t = 0, r = 1) : (t = jn(t), r === i ? (r = t, t = 0) : r = jn(r)), t > r) {
          var f = t;
          t = r, r = f;
        }
        if (s || t % 1 || r % 1) {
          var c = Fa();
          return Ce(t + c * (r - t + Fg("1e-" + ((c + "").length - 1))), r);
        }
        return Xl(t, r);
      }
      var ry = di(function(t, r, s) {
        return r = r.toLowerCase(), t + (s ? ch(r) : r);
      });
      function ch(t) {
        return mu(Mt(t).toLowerCase());
      }
      function hh(t) {
        return t = Mt(t), t && t.replace(og, qg).replace(Sg, "");
      }
      function iy(t, r, s) {
        t = Mt(t), r = tn(r);
        var f = t.length;
        s = s === i ? f : Wr(_t(s), 0, f);
        var c = s;
        return s -= r.length, s >= 0 && t.slice(s, c) == r;
      }
      function oy(t) {
        return t = Mt(t), t && Up.test(t) ? t.replace(Gf, Vg) : t;
      }
      function sy(t) {
        return t = Mt(t), t && Xp.test(t) ? t.replace(dl, "\\$&") : t;
      }
      var ly = di(function(t, r, s) {
        return t + (s ? "-" : "") + r.toLowerCase();
      }), uy = di(function(t, r, s) {
        return t + (s ? " " : "") + r.toLowerCase();
      }), fy = gc("toLowerCase");
      function ay(t, r, s) {
        t = Mt(t), r = _t(r);
        var f = r ? si(t) : 0;
        if (!r || f >= r)
          return t;
        var c = (r - f) / 2;
        return ss(qo(c), s) + t + ss(zo(c), s);
      }
      function cy(t, r, s) {
        t = Mt(t), r = _t(r);
        var f = r ? si(t) : 0;
        return r && f < r ? t + ss(r - f, s) : t;
      }
      function hy(t, r, s) {
        t = Mt(t), r = _t(r);
        var f = r ? si(t) : 0;
        return r && f < r ? ss(r - f, s) + t : t;
      }
      function dy(t, r, s) {
        return s || r == null ? r = 0 : r && (r = +r), yv(Mt(t).replace(pl, ""), r || 0);
      }
      function py(t, r, s) {
        return (s ? Le(t, r, s) : r === i) ? r = 1 : r = _t(r), zl(Mt(t), r);
      }
      function gy() {
        var t = arguments, r = Mt(t[0]);
        return t.length < 3 ? r : r.replace(t[1], t[2]);
      }
      var vy = di(function(t, r, s) {
        return t + (s ? "_" : "") + r.toLowerCase();
      });
      function _y(t, r, s) {
        return s && typeof s != "number" && Le(t, r, s) && (r = s = i), s = s === i ? qt : s >>> 0, s ? (t = Mt(t), t && (typeof r == "string" || r != null && !gu(r)) && (r = tn(r), !r && oi(t)) ? br(xn(t), 0, s) : t.split(r, s)) : [];
      }
      var my = di(function(t, r, s) {
        return t + (s ? " " : "") + mu(r);
      });
      function by(t, r, s) {
        return t = Mt(t), s = s == null ? 0 : Wr(_t(s), 0, t.length), r = tn(r), t.slice(s, s + r.length) == r;
      }
      function yy(t, r, s) {
        var f = d.templateSettings;
        s && Le(t, r, s) && (r = i), t = Mt(t), r = ms({}, r, f, Ec);
        var c = ms({}, r.imports, f.imports, Ec), p = ve(c), b = Il(c, p), w, T, P = 0, M = r.interpolate || Ro, B = "__p += '", q = Rl(
          (r.escape || Ro).source + "|" + M.source + "|" + (M === Yf ? jp : Ro).source + "|" + (r.evaluate || Ro).source + "|$",
          "g"
        ), j = "//# sourceURL=" + (Bt.call(r, "sourceURL") ? (r.sourceURL + "").replace(/\s/g, " ") : "lodash.templateSources[" + ++Ig + "]") + `
`;
        t.replace(q, function(ot, xt, Ct, nn, Be, rn) {
          return Ct || (Ct = nn), B += t.slice(P, rn).replace(sg, kg), xt && (w = !0, B += `' +
__e(` + xt + `) +
'`), Be && (T = !0, B += `';
` + Be + `;
__p += '`), Ct && (B += `' +
((__t = (` + Ct + `)) == null ? '' : __t) +
'`), P = rn + ot.length, ot;
        }), B += `';
`;
        var it = Bt.call(r, "variable") && r.variable;
        if (!it)
          B = `with (obj) {
` + B + `
}
`;
        else if (Zp.test(it))
          throw new ct(h);
        B = (T ? B.replace(Do, "") : B).replace(Lp, "$1").replace(Bp, "$1;"), B = "function(" + (it || "obj") + `) {
` + (it ? "" : `obj || (obj = {});
`) + "var __t, __p = ''" + (w ? ", __e = _.escape" : "") + (T ? `, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
` : `;
`) + B + `return __p
}`;
        var bt = ph(function() {
          return Rt(p, j + "return " + B).apply(i, b);
        });
        if (bt.source = B, pu(bt))
          throw bt;
        return bt;
      }
      function wy(t) {
        return Mt(t).toLowerCase();
      }
      function Ey(t) {
        return Mt(t).toUpperCase();
      }
      function xy(t, r, s) {
        if (t = Mt(t), t && (s || r === i))
          return xa(t);
        if (!t || !(r = tn(r)))
          return t;
        var f = xn(t), c = xn(r), p = Sa(f, c), b = Ta(f, c) + 1;
        return br(f, p, b).join("");
      }
      function Sy(t, r, s) {
        if (t = Mt(t), t && (s || r === i))
          return t.slice(0, Ca(t) + 1);
        if (!t || !(r = tn(r)))
          return t;
        var f = xn(t), c = Ta(f, xn(r)) + 1;
        return br(f, 0, c).join("");
      }
      function Ty(t, r, s) {
        if (t = Mt(t), t && (s || r === i))
          return t.replace(pl, "");
        if (!t || !(r = tn(r)))
          return t;
        var f = xn(t), c = Sa(f, xn(r));
        return br(f, c).join("");
      }
      function Ay(t, r) {
        var s = Ot, f = Nt;
        if (Zt(r)) {
          var c = "separator" in r ? r.separator : c;
          s = "length" in r ? _t(r.length) : s, f = "omission" in r ? tn(r.omission) : f;
        }
        t = Mt(t);
        var p = t.length;
        if (oi(t)) {
          var b = xn(t);
          p = b.length;
        }
        if (s >= p)
          return t;
        var w = s - si(f);
        if (w < 1)
          return f;
        var T = b ? br(b, 0, w).join("") : t.slice(0, w);
        if (c === i)
          return T + f;
        if (b && (w += T.length - w), gu(c)) {
          if (t.slice(w).search(c)) {
            var P, M = T;
            for (c.global || (c = Rl(c.source, Mt(Xf.exec(c)) + "g")), c.lastIndex = 0; P = c.exec(M); )
              var B = P.index;
            T = T.slice(0, B === i ? w : B);
          }
        } else if (t.indexOf(tn(c), w) != w) {
          var q = T.lastIndexOf(c);
          q > -1 && (T = T.slice(0, q));
        }
        return T + f;
      }
      function Cy(t) {
        return t = Mt(t), t && Wp.test(t) ? t.replace(Kf, nv) : t;
      }
      var Oy = di(function(t, r, s) {
        return t + (s ? " " : "") + r.toUpperCase();
      }), mu = gc("toUpperCase");
      function dh(t, r, s) {
        return t = Mt(t), r = s ? i : r, r === i ? Zg(t) ? ov(t) : Kg(t) : t.match(r) || [];
      }
      var ph = yt(function(t, r) {
        try {
          return Qe(t, i, r);
        } catch (s) {
          return pu(s) ? s : new ct(s);
        }
      }), Iy = Jn(function(t, r) {
        return dn(r, function(s) {
          s = Fn(s), Vn(t, s, hu(t[s], t));
        }), t;
      });
      function Dy(t) {
        var r = t == null ? 0 : t.length, s = nt();
        return t = r ? Vt(t, function(f) {
          if (typeof f[1] != "function")
            throw new pn(a);
          return [s(f[0]), f[1]];
        }) : [], yt(function(f) {
          for (var c = -1; ++c < r; ) {
            var p = t[c];
            if (Qe(p[0], this, f))
              return Qe(p[1], this, f);
          }
        });
      }
      function Ry(t) {
        return n_(vn(t, m));
      }
      function bu(t) {
        return function() {
          return t;
        };
      }
      function Py(t, r) {
        return t == null || t !== t ? r : t;
      }
      var My = _c(), Fy = _c(!0);
      function Ve(t) {
        return t;
      }
      function yu(t) {
        return qa(typeof t == "function" ? t : vn(t, m));
      }
      function Ny(t) {
        return ka(vn(t, m));
      }
      function Ly(t, r) {
        return Ja(t, vn(r, m));
      }
      var By = yt(function(t, r) {
        return function(s) {
          return Xi(s, t, r);
        };
      }), Wy = yt(function(t, r) {
        return function(s) {
          return Xi(t, s, r);
        };
      });
      function wu(t, r, s) {
        var f = ve(r), c = jo(r, f);
        s == null && !(Zt(r) && (c.length || !f.length)) && (s = r, r = t, t = this, c = jo(r, ve(r)));
        var p = !(Zt(s) && "chain" in s) || !!s.chain, b = Qn(t);
        return dn(c, function(w) {
          var T = r[w];
          t[w] = T, b && (t.prototype[w] = function() {
            var P = this.__chain__;
            if (p || P) {
              var M = t(this.__wrapped__), B = M.__actions__ = Xe(this.__actions__);
              return B.push({ func: T, args: arguments, thisArg: t }), M.__chain__ = P, M;
            }
            return T.apply(t, dr([this.value()], arguments));
          });
        }), t;
      }
      function Uy() {
        return Ee._ === this && (Ee._ = cv), this;
      }
      function Eu() {
      }
      function Hy(t) {
        return t = _t(t), yt(function(r) {
          return Za(r, t);
        });
      }
      var $y = jl(Vt), Ky = jl(ma), Gy = jl(Sl);
      function gh(t) {
        return su(t) ? Tl(Fn(t)) : b_(t);
      }
      function Yy(t) {
        return function(r) {
          return t == null ? i : Ur(t, r);
        };
      }
      var Xy = bc(), zy = bc(!0);
      function xu() {
        return [];
      }
      function Su() {
        return !1;
      }
      function qy() {
        return {};
      }
      function Vy() {
        return "";
      }
      function ky() {
        return !0;
      }
      function Jy(t, r) {
        if (t = _t(t), t < 1 || t > rt)
          return [];
        var s = qt, f = Ce(t, qt);
        r = nt(r), t -= qt;
        for (var c = Ol(f, r); ++s < t; )
          r(s);
        return c;
      }
      function Zy(t) {
        return dt(t) ? Vt(t, Fn) : en(t) ? [t] : Xe(Nc(Mt(t)));
      }
      function Qy(t) {
        var r = ++fv;
        return Mt(t) + r;
      }
      var jy = os(function(t, r) {
        return t + r;
      }, 0), tw = tu("ceil"), ew = os(function(t, r) {
        return t / r;
      }, 1), nw = tu("floor");
      function rw(t) {
        return t && t.length ? Qo(t, Ve, Ul) : i;
      }
      function iw(t, r) {
        return t && t.length ? Qo(t, nt(r, 2), Ul) : i;
      }
      function ow(t) {
        return wa(t, Ve);
      }
      function sw(t, r) {
        return wa(t, nt(r, 2));
      }
      function lw(t) {
        return t && t.length ? Qo(t, Ve, Gl) : i;
      }
      function uw(t, r) {
        return t && t.length ? Qo(t, nt(r, 2), Gl) : i;
      }
      var fw = os(function(t, r) {
        return t * r;
      }, 1), aw = tu("round"), cw = os(function(t, r) {
        return t - r;
      }, 0);
      function hw(t) {
        return t && t.length ? Cl(t, Ve) : 0;
      }
      function dw(t, r) {
        return t && t.length ? Cl(t, nt(r, 2)) : 0;
      }
      return d.after = N0, d.ary = zc, d.assign = Eb, d.assignIn = sh, d.assignInWith = ms, d.assignWith = xb, d.at = Sb, d.before = qc, d.bind = hu, d.bindAll = Iy, d.bindKey = Vc, d.castArray = q0, d.chain = Gc, d.chunk = nm, d.compact = rm, d.concat = im, d.cond = Dy, d.conforms = Ry, d.constant = bu, d.countBy = h0, d.create = Tb, d.curry = kc, d.curryRight = Jc, d.debounce = Zc, d.defaults = Ab, d.defaultsDeep = Cb, d.defer = L0, d.delay = B0, d.difference = om, d.differenceBy = sm, d.differenceWith = lm, d.drop = um, d.dropRight = fm, d.dropRightWhile = am, d.dropWhile = cm, d.fill = hm, d.filter = p0, d.flatMap = _0, d.flatMapDeep = m0, d.flatMapDepth = b0, d.flatten = Uc, d.flattenDeep = dm, d.flattenDepth = pm, d.flip = W0, d.flow = My, d.flowRight = Fy, d.fromPairs = gm, d.functions = Fb, d.functionsIn = Nb, d.groupBy = y0, d.initial = _m, d.intersection = mm, d.intersectionBy = bm, d.intersectionWith = ym, d.invert = Bb, d.invertBy = Wb, d.invokeMap = E0, d.iteratee = yu, d.keyBy = x0, d.keys = ve, d.keysIn = qe, d.map = hs, d.mapKeys = Hb, d.mapValues = $b, d.matches = Ny, d.matchesProperty = Ly, d.memoize = ps, d.merge = Kb, d.mergeWith = lh, d.method = By, d.methodOf = Wy, d.mixin = wu, d.negate = gs, d.nthArg = Hy, d.omit = Gb, d.omitBy = Yb, d.once = U0, d.orderBy = S0, d.over = $y, d.overArgs = H0, d.overEvery = Ky, d.overSome = Gy, d.partial = du, d.partialRight = Qc, d.partition = T0, d.pick = Xb, d.pickBy = uh, d.property = gh, d.propertyOf = Yy, d.pull = Sm, d.pullAll = $c, d.pullAllBy = Tm, d.pullAllWith = Am, d.pullAt = Cm, d.range = Xy, d.rangeRight = zy, d.rearg = $0, d.reject = O0, d.remove = Om, d.rest = K0, d.reverse = au, d.sampleSize = D0, d.set = qb, d.setWith = Vb, d.shuffle = R0, d.slice = Im, d.sortBy = F0, d.sortedUniq = Lm, d.sortedUniqBy = Bm, d.split = _y, d.spread = G0, d.tail = Wm, d.take = Um, d.takeRight = Hm, d.takeRightWhile = $m, d.takeWhile = Km, d.tap = r0, d.throttle = Y0, d.thru = cs, d.toArray = rh, d.toPairs = fh, d.toPairsIn = ah, d.toPath = Zy, d.toPlainObject = oh, d.transform = kb, d.unary = X0, d.union = Gm, d.unionBy = Ym, d.unionWith = Xm, d.uniq = zm, d.uniqBy = qm, d.uniqWith = Vm, d.unset = Jb, d.unzip = cu, d.unzipWith = Kc, d.update = Zb, d.updateWith = Qb, d.values = vi, d.valuesIn = jb, d.without = km, d.words = dh, d.wrap = z0, d.xor = Jm, d.xorBy = Zm, d.xorWith = Qm, d.zip = jm, d.zipObject = t0, d.zipObjectDeep = e0, d.zipWith = n0, d.entries = fh, d.entriesIn = ah, d.extend = sh, d.extendWith = ms, wu(d, d), d.add = jy, d.attempt = ph, d.camelCase = ry, d.capitalize = ch, d.ceil = tw, d.clamp = ty, d.clone = V0, d.cloneDeep = J0, d.cloneDeepWith = Z0, d.cloneWith = k0, d.conformsTo = Q0, d.deburr = hh, d.defaultTo = Py, d.divide = ew, d.endsWith = iy, d.eq = Tn, d.escape = oy, d.escapeRegExp = sy, d.every = d0, d.find = g0, d.findIndex = Bc, d.findKey = Ob, d.findLast = v0, d.findLastIndex = Wc, d.findLastKey = Ib, d.floor = nw, d.forEach = Yc, d.forEachRight = Xc, d.forIn = Db, d.forInRight = Rb, d.forOwn = Pb, d.forOwnRight = Mb, d.get = vu, d.gt = j0, d.gte = tb, d.has = Lb, d.hasIn = _u, d.head = Hc, d.identity = Ve, d.includes = w0, d.indexOf = vm, d.inRange = ey, d.invoke = Ub, d.isArguments = Kr, d.isArray = dt, d.isArrayBuffer = eb, d.isArrayLike = ze, d.isArrayLikeObject = ee, d.isBoolean = nb, d.isBuffer = yr, d.isDate = rb, d.isElement = ib, d.isEmpty = ob, d.isEqual = sb, d.isEqualWith = lb, d.isError = pu, d.isFinite = ub, d.isFunction = Qn, d.isInteger = jc, d.isLength = vs, d.isMap = th, d.isMatch = fb, d.isMatchWith = ab, d.isNaN = cb, d.isNative = hb, d.isNil = pb, d.isNull = db, d.isNumber = eh, d.isObject = Zt, d.isObjectLike = jt, d.isPlainObject = Zi, d.isRegExp = gu, d.isSafeInteger = gb, d.isSet = nh, d.isString = _s, d.isSymbol = en, d.isTypedArray = gi, d.isUndefined = vb, d.isWeakMap = _b, d.isWeakSet = mb, d.join = wm, d.kebabCase = ly, d.last = mn, d.lastIndexOf = Em, d.lowerCase = uy, d.lowerFirst = fy, d.lt = bb, d.lte = yb, d.max = rw, d.maxBy = iw, d.mean = ow, d.meanBy = sw, d.min = lw, d.minBy = uw, d.stubArray = xu, d.stubFalse = Su, d.stubObject = qy, d.stubString = Vy, d.stubTrue = ky, d.multiply = fw, d.nth = xm, d.noConflict = Uy, d.noop = Eu, d.now = ds, d.pad = ay, d.padEnd = cy, d.padStart = hy, d.parseInt = dy, d.random = ny, d.reduce = A0, d.reduceRight = C0, d.repeat = py, d.replace = gy, d.result = zb, d.round = aw, d.runInContext = S, d.sample = I0, d.size = P0, d.snakeCase = vy, d.some = M0, d.sortedIndex = Dm, d.sortedIndexBy = Rm, d.sortedIndexOf = Pm, d.sortedLastIndex = Mm, d.sortedLastIndexBy = Fm, d.sortedLastIndexOf = Nm, d.startCase = my, d.startsWith = by, d.subtract = cw, d.sum = hw, d.sumBy = dw, d.template = yy, d.times = Jy, d.toFinite = jn, d.toInteger = _t, d.toLength = ih, d.toLower = wy, d.toNumber = bn, d.toSafeInteger = wb, d.toString = Mt, d.toUpper = Ey, d.trim = xy, d.trimEnd = Sy, d.trimStart = Ty, d.truncate = Ay, d.unescape = Cy, d.uniqueId = Qy, d.upperCase = Oy, d.upperFirst = mu, d.each = Yc, d.eachRight = Xc, d.first = Hc, wu(d, function() {
        var t = {};
        return Pn(d, function(r, s) {
          Bt.call(d.prototype, s) || (t[s] = r);
        }), t;
      }(), { chain: !1 }), d.VERSION = o, dn(["bind", "bindKey", "curry", "curryRight", "partial", "partialRight"], function(t) {
        d[t].placeholder = d;
      }), dn(["drop", "take"], function(t, r) {
        Tt.prototype[t] = function(s) {
          s = s === i ? 1 : de(_t(s), 0);
          var f = this.__filtered__ && !r ? new Tt(this) : this.clone();
          return f.__filtered__ ? f.__takeCount__ = Ce(s, f.__takeCount__) : f.__views__.push({
            size: Ce(s, qt),
            type: t + (f.__dir__ < 0 ? "Right" : "")
          }), f;
        }, Tt.prototype[t + "Right"] = function(s) {
          return this.reverse()[t](s).reverse();
        };
      }), dn(["filter", "map", "takeWhile"], function(t, r) {
        var s = r + 1, f = s == pe || s == vt;
        Tt.prototype[t] = function(c) {
          var p = this.clone();
          return p.__iteratees__.push({
            iteratee: nt(c, 3),
            type: s
          }), p.__filtered__ = p.__filtered__ || f, p;
        };
      }), dn(["head", "last"], function(t, r) {
        var s = "take" + (r ? "Right" : "");
        Tt.prototype[t] = function() {
          return this[s](1).value()[0];
        };
      }), dn(["initial", "tail"], function(t, r) {
        var s = "drop" + (r ? "" : "Right");
        Tt.prototype[t] = function() {
          return this.__filtered__ ? new Tt(this) : this[s](1);
        };
      }), Tt.prototype.compact = function() {
        return this.filter(Ve);
      }, Tt.prototype.find = function(t) {
        return this.filter(t).head();
      }, Tt.prototype.findLast = function(t) {
        return this.reverse().find(t);
      }, Tt.prototype.invokeMap = yt(function(t, r) {
        return typeof t == "function" ? new Tt(this) : this.map(function(s) {
          return Xi(s, t, r);
        });
      }), Tt.prototype.reject = function(t) {
        return this.filter(gs(nt(t)));
      }, Tt.prototype.slice = function(t, r) {
        t = _t(t);
        var s = this;
        return s.__filtered__ && (t > 0 || r < 0) ? new Tt(s) : (t < 0 ? s = s.takeRight(-t) : t && (s = s.drop(t)), r !== i && (r = _t(r), s = r < 0 ? s.dropRight(-r) : s.take(r - t)), s);
      }, Tt.prototype.takeRightWhile = function(t) {
        return this.reverse().takeWhile(t).reverse();
      }, Tt.prototype.toArray = function() {
        return this.take(qt);
      }, Pn(Tt.prototype, function(t, r) {
        var s = /^(?:filter|find|map|reject)|While$/.test(r), f = /^(?:head|last)$/.test(r), c = d[f ? "take" + (r == "last" ? "Right" : "") : r], p = f || /^find/.test(r);
        c && (d.prototype[r] = function() {
          var b = this.__wrapped__, w = f ? [1] : arguments, T = b instanceof Tt, P = w[0], M = T || dt(b), B = function(xt) {
            var Ct = c.apply(d, dr([xt], w));
            return f && q ? Ct[0] : Ct;
          };
          M && s && typeof P == "function" && P.length != 1 && (T = M = !1);
          var q = this.__chain__, j = !!this.__actions__.length, it = p && !q, bt = T && !j;
          if (!p && M) {
            b = bt ? b : new Tt(this);
            var ot = t.apply(b, w);
            return ot.__actions__.push({ func: cs, args: [B], thisArg: i }), new gn(ot, q);
          }
          return it && bt ? t.apply(this, w) : (ot = this.thru(B), it ? f ? ot.value()[0] : ot.value() : ot);
        });
      }), dn(["pop", "push", "shift", "sort", "splice", "unshift"], function(t) {
        var r = Bo[t], s = /^(?:push|sort|unshift)$/.test(t) ? "tap" : "thru", f = /^(?:pop|shift)$/.test(t);
        d.prototype[t] = function() {
          var c = arguments;
          if (f && !this.__chain__) {
            var p = this.value();
            return r.apply(dt(p) ? p : [], c);
          }
          return this[s](function(b) {
            return r.apply(dt(b) ? b : [], c);
          });
        };
      }), Pn(Tt.prototype, function(t, r) {
        var s = d[r];
        if (s) {
          var f = s.name + "";
          Bt.call(ai, f) || (ai[f] = []), ai[f].push({ name: r, func: s });
        }
      }), ai[is(i, W).name] = [{
        name: "wrapper",
        func: i
      }], Tt.prototype.clone = Cv, Tt.prototype.reverse = Ov, Tt.prototype.value = Iv, d.prototype.at = i0, d.prototype.chain = o0, d.prototype.commit = s0, d.prototype.next = l0, d.prototype.plant = f0, d.prototype.reverse = a0, d.prototype.toJSON = d.prototype.valueOf = d.prototype.value = c0, d.prototype.first = d.prototype.head, Wi && (d.prototype[Wi] = u0), d;
    }, li = sv();
    Fr ? ((Fr.exports = li)._ = li, yl._ = li) : Ee._ = li;
  }).call(to);
})(Vs, Vs.exports);
var Ts = Vs.exports;
function go(e) {
  const i = document.getSelection().getRangeAt(0), o = i.cloneRange();
  return o.selectNodeContents(e), o.setEnd(i.endContainer, i.endOffset), o.toString().length;
}
function Jh(e, n) {
  const i = sx(e, n), o = document.getSelection();
  o.removeAllRanges(), o.addRange(i);
}
function Zh(e) {
  const n = document.createRange(), i = document.getSelection();
  n.setStart(e, e.childNodes.length), n.collapse(!0), i.removeAllRanges(), i.addRange(n);
}
const sx = (e, n) => {
  var u;
  const i = document.createRange();
  i.selectNode(e), i.setStart(e, 0);
  let o = 0;
  const l = [e];
  for (; l.length > 0; ) {
    const a = l.pop();
    if (a && a.nodeType === Node.TEXT_NODE) {
      const h = ((u = a.textContent) == null ? void 0 : u.length) || 0;
      if (o + h >= n)
        return i.setStart(a, n - o), i.setEnd(a, n - o), i;
      o += h;
    } else if (a && a.childNodes && a.childNodes.length > 0)
      for (let h = a.childNodes.length - 1; h >= 0; h--)
        l.push(a.childNodes[h]);
  }
  return i.setStart(e, e.childNodes.length), i.setEnd(e, e.childNodes.length), i;
};
function Ep(e, n) {
  let i = [];
  Ge([e, ...n], () => {
    e.value && (i = [...e.value.querySelectorAll("[contenteditable]")]);
  }, {
    flush: "post"
  });
  const o = (A) => i.findIndex((O) => O.isEqualNode(A)), l = (A) => i[o(A) - 1], u = (A) => i[o(A) + 1], a = (A, O) => {
    if (A) {
      const R = u(A);
      if (R instanceof HTMLElement)
        return R.focus(), O && Zh(R), R;
    } else {
      const R = i[0];
      R instanceof HTMLElement && R.focus();
    }
  }, h = (A, O) => {
    if (A.target instanceof HTMLElement) {
      const R = A.target, N = go(R), W = O === "up" ? l(R) : u(R);
      W instanceof HTMLElement && (A.preventDefault(), W.focus(), Jh(W, N));
    }
  };
  return {
    getPreviousElementSibling: l,
    getNextElementSibling: u,
    getCurrentPositionInNavigationList: o,
    focusNextTask: a,
    focusTaskById: (A) => {
      const O = i.find((R) => {
        var N;
        return R instanceof HTMLElement && ((N = R.dataset) == null ? void 0 : N.id) == A;
      });
      O instanceof HTMLElement && O.focus();
    },
    onUp: (A) => {
      h(A, "up");
    },
    onDown: (A) => {
      h(A, "down");
    },
    onLeft: (A, O) => {
      if (A.target instanceof HTMLElement) {
        const R = A.target;
        if (go(R) === 0) {
          const N = l(R);
          N instanceof HTMLElement && (A.preventDefault(), O && (N.innerText += O), N.focus(), Zh(N), O && Jh(N, N.innerText.length - O.length));
        }
      }
    },
    onRight: (A) => {
      if (A.target instanceof HTMLElement) {
        const O = A.target;
        if (go(O) === O.innerText.length) {
          const R = u(O);
          R instanceof HTMLElement && (A.preventDefault(), R.focus());
        }
      }
    }
  };
}
const lx = { class: "tw-group tw-flex tw-relative tw-gap-2 tw-py-4 tw-px-6 -tw-mx-6" }, ux = {
  key: 0,
  class: "sortable-handle tw-cursor-grabbing tw-absolute -tw-translate-x-4 tw-hidden group-hover:tw-block"
}, fx = {
  class: "tw-w-4 tw-h-4 tw-flex-none",
  onClick: () => {
  }
}, ax = { class: "tw-flex-auto" }, cx = ["contenteditable", "placeholder", "data-id", "textContent"], hx = { key: 1 }, dx = /* @__PURE__ */ Pf({
  __name: "TaskListItem2",
  props: {
    task: {},
    sortable: { type: Boolean },
    editable: { type: Boolean },
    placeholder: {},
    addableProps: {}
  },
  emits: ["delete", "update", "insert", "blur"],
  setup(e, { emit: n }) {
    const i = e, o = n, l = Pe(!1);
    let u = {
      name: i.task.name,
      due_date: i.task.due_date,
      due_datetime: i.task.due_datetime,
      priority: i.task.priority,
      location_id: i.task.location_id
    };
    const a = Pe({ ...u });
    let h;
    Ge([() => i.task, () => i.addableProps], async ([N]) => {
      h && h(), u = Ts.pick(N, Object.keys(u)), a.value = { ...u }, h = Ge(a, async () => {
        const W = Ts.reduce(a.value, (V, X, L) => Ts.isEqual(X, u[L]) ? V : [...V, L], []);
        W.length && o("update", i.task, Ts.pick(a.value, W));
      }, {
        deep: !0
      });
    }, {
      immediate: !0
    });
    const g = async (N) => {
      N.target instanceof HTMLElement && (N.target.innerText = N.target.innerText.trim(), a.value.name = N.target.innerText), o("blur", i.task, a.value);
    }, v = (N) => {
      var V;
      N.preventDefault();
      const W = (V = N.clipboardData) == null ? void 0 : V.getData("text/plain");
      W && document.execCommand("insertText", !1, W);
    }, _ = (N) => {
      N.target instanceof HTMLElement && go(N.target) === 0 && (O(N, N.target.innerText.trim()), o("delete", i.task));
    }, m = async (N) => {
      if (N.preventDefault(), N.target instanceof HTMLElement) {
        const W = N.target, V = W.innerText.trim(), X = go(W), L = {};
        X !== 0 && (L.currentName = V.substring(0, X).trim(), L.newName = V.substring(X, V.length).trim(), W.innerText = L.currentName), o("insert", i.task, L);
      }
    }, {
      onUp: x,
      onDown: A,
      onLeft: O,
      onRight: R
    } = ir("listNavigation");
    return (N, W) => {
      var V;
      return Hn(), Jr("div", lx, [
        i.sortable ? (Hn(), Jr("div", ux, W[6] || (W[6] = [
          Bn("i", { class: "fas fa-grip-vertical" }, null, -1)
        ]))) : Mh("", !0),
        Bn("div", fx, [
          Bn("div", {
            class: rl(["tw-w-4 tw-h-4 tw-border tw-border-solid tw-border-gray-300 tw-rounded-full tw-cursor-pointer", !1])
          })
        ]),
        Bn("div", ax, [
          Bn("div", {
            contenteditable: i.editable || void 0,
            placeholder: i.placeholder,
            "data-id": i.task.id,
            onKeydown: [
              mi(m, ["enter"]),
              W[0] || (W[0] = mi(
                //@ts-ignore
                (...X) => re(O) && re(O)(...X),
                ["left"]
              )),
              W[1] || (W[1] = mi(
                //@ts-ignore
                (...X) => re(x) && re(x)(...X),
                ["up"]
              )),
              W[2] || (W[2] = mi(
                //@ts-ignore
                (...X) => re(R) && re(R)(...X),
                ["right"]
              )),
              W[3] || (W[3] = mi(
                //@ts-ignore
                (...X) => re(A) && re(A)(...X),
                ["down"]
              )),
              mi(_, ["delete"])
            ],
            onBlur: g,
            onFocus: W[4] || (W[4] = (X) => l.value = !0),
            onFocusout: W[5] || (W[5] = (X) => l.value = !1),
            onPaste: v,
            textContent: Ju(i.task.name)
          }, null, 40, cx)
        ]),
        (V = i.task.extended_data) != null && V.comments_count ? (Hn(), Jr("div", hx, [
          W[7] || (W[7] = Bn("i", { class: "far fa-comment" }, null, -1)),
          vp(" " + Ju(i.task.extended_data.comments_count), 1)
        ])) : Mh("", !0)
      ]);
    };
  }
});
/**!
 * Sortable 1.15.2
 * @author	RubaXa   <trash@rubaxa.org>
 * @author	owenm    <owen23355@gmail.com>
 * @license MIT
 */
function Qh(e, n) {
  var i = Object.keys(e);
  if (Object.getOwnPropertySymbols) {
    var o = Object.getOwnPropertySymbols(e);
    n && (o = o.filter(function(l) {
      return Object.getOwnPropertyDescriptor(e, l).enumerable;
    })), i.push.apply(i, o);
  }
  return i;
}
function Gn(e) {
  for (var n = 1; n < arguments.length; n++) {
    var i = arguments[n] != null ? arguments[n] : {};
    n % 2 ? Qh(Object(i), !0).forEach(function(o) {
      px(e, o, i[o]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(i)) : Qh(Object(i)).forEach(function(o) {
      Object.defineProperty(e, o, Object.getOwnPropertyDescriptor(i, o));
    });
  }
  return e;
}
function Bs(e) {
  "@babel/helpers - typeof";
  return typeof Symbol == "function" && typeof Symbol.iterator == "symbol" ? Bs = function(n) {
    return typeof n;
  } : Bs = function(n) {
    return n && typeof Symbol == "function" && n.constructor === Symbol && n !== Symbol.prototype ? "symbol" : typeof n;
  }, Bs(e);
}
function px(e, n, i) {
  return n in e ? Object.defineProperty(e, n, {
    value: i,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : e[n] = i, e;
}
function Dn() {
  return Dn = Object.assign || function(e) {
    for (var n = 1; n < arguments.length; n++) {
      var i = arguments[n];
      for (var o in i)
        Object.prototype.hasOwnProperty.call(i, o) && (e[o] = i[o]);
    }
    return e;
  }, Dn.apply(this, arguments);
}
function gx(e, n) {
  if (e == null)
    return {};
  var i = {}, o = Object.keys(e), l, u;
  for (u = 0; u < o.length; u++)
    l = o[u], !(n.indexOf(l) >= 0) && (i[l] = e[l]);
  return i;
}
function vx(e, n) {
  if (e == null)
    return {};
  var i = gx(e, n), o, l;
  if (Object.getOwnPropertySymbols) {
    var u = Object.getOwnPropertySymbols(e);
    for (l = 0; l < u.length; l++)
      o = u[l], !(n.indexOf(o) >= 0) && Object.prototype.propertyIsEnumerable.call(e, o) && (i[o] = e[o]);
  }
  return i;
}
function _x(e) {
  return mx(e) || bx(e) || yx(e) || wx();
}
function mx(e) {
  if (Array.isArray(e))
    return af(e);
}
function bx(e) {
  if (typeof Symbol < "u" && e[Symbol.iterator] != null || e["@@iterator"] != null)
    return Array.from(e);
}
function yx(e, n) {
  if (e) {
    if (typeof e == "string")
      return af(e, n);
    var i = Object.prototype.toString.call(e).slice(8, -1);
    if (i === "Object" && e.constructor && (i = e.constructor.name), i === "Map" || i === "Set")
      return Array.from(e);
    if (i === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i))
      return af(e, n);
  }
}
function af(e, n) {
  (n == null || n > e.length) && (n = e.length);
  for (var i = 0, o = new Array(n); i < n; i++)
    o[i] = e[i];
  return o;
}
function wx() {
  throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`);
}
var Ex = "1.15.2";
function or(e) {
  if (typeof window < "u" && window.navigator)
    return !!/* @__PURE__ */ navigator.userAgent.match(e);
}
var lr = or(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i), Oo = or(/Edge/i), jh = or(/firefox/i), vo = or(/safari/i) && !or(/chrome/i) && !or(/android/i), xp = or(/iP(ad|od|hone)/i), Sp = or(/chrome/i) && or(/android/i), Tp = {
  capture: !1,
  passive: !1
};
function At(e, n, i) {
  e.addEventListener(n, i, !lr && Tp);
}
function wt(e, n, i) {
  e.removeEventListener(n, i, !lr && Tp);
}
function ks(e, n) {
  if (n) {
    if (n[0] === ">" && (n = n.substring(1)), e)
      try {
        if (e.matches)
          return e.matches(n);
        if (e.msMatchesSelector)
          return e.msMatchesSelector(n);
        if (e.webkitMatchesSelector)
          return e.webkitMatchesSelector(n);
      } catch {
        return !1;
      }
    return !1;
  }
}
function xx(e) {
  return e.host && e !== document && e.host.nodeType ? e.host : e.parentNode;
}
function yn(e, n, i, o) {
  if (e) {
    i = i || document;
    do {
      if (n != null && (n[0] === ">" ? e.parentNode === i && ks(e, n) : ks(e, n)) || o && e === i)
        return e;
      if (e === i)
        break;
    } while (e = xx(e));
  }
  return null;
}
var td = /\s+/g;
function fe(e, n, i) {
  if (e && n)
    if (e.classList)
      e.classList[i ? "add" : "remove"](n);
    else {
      var o = (" " + e.className + " ").replace(td, " ").replace(" " + n + " ", " ");
      e.className = (o + (i ? " " + n : "")).replace(td, " ");
    }
}
function Q(e, n, i) {
  var o = e && e.style;
  if (o) {
    if (i === void 0)
      return document.defaultView && document.defaultView.getComputedStyle ? i = document.defaultView.getComputedStyle(e, "") : e.currentStyle && (i = e.currentStyle), n === void 0 ? i : i[n];
    !(n in o) && n.indexOf("webkit") === -1 && (n = "-webkit-" + n), o[n] = i + (typeof i == "string" ? "" : "px");
  }
}
function Qr(e, n) {
  var i = "";
  if (typeof e == "string")
    i = e;
  else
    do {
      var o = Q(e, "transform");
      o && o !== "none" && (i = o + " " + i);
    } while (!n && (e = e.parentNode));
  var l = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
  return l && new l(i);
}
function Ap(e, n, i) {
  if (e) {
    var o = e.getElementsByTagName(n), l = 0, u = o.length;
    if (i)
      for (; l < u; l++)
        i(o[l], l);
    return o;
  }
  return [];
}
function $n() {
  var e = document.scrollingElement;
  return e || document.documentElement;
}
function Xt(e, n, i, o, l) {
  if (!(!e.getBoundingClientRect && e !== window)) {
    var u, a, h, g, v, _, m;
    if (e !== window && e.parentNode && e !== $n() ? (u = e.getBoundingClientRect(), a = u.top, h = u.left, g = u.bottom, v = u.right, _ = u.height, m = u.width) : (a = 0, h = 0, g = window.innerHeight, v = window.innerWidth, _ = window.innerHeight, m = window.innerWidth), (n || i) && e !== window && (l = l || e.parentNode, !lr))
      do
        if (l && l.getBoundingClientRect && (Q(l, "transform") !== "none" || i && Q(l, "position") !== "static")) {
          var x = l.getBoundingClientRect();
          a -= x.top + parseInt(Q(l, "border-top-width")), h -= x.left + parseInt(Q(l, "border-left-width")), g = a + u.height, v = h + u.width;
          break;
        }
      while (l = l.parentNode);
    if (o && e !== window) {
      var A = Qr(l || e), O = A && A.a, R = A && A.d;
      A && (a /= R, h /= O, m /= O, _ /= R, g = a + _, v = h + m);
    }
    return {
      top: a,
      left: h,
      bottom: g,
      right: v,
      width: m,
      height: _
    };
  }
}
function ed(e, n, i) {
  for (var o = Cr(e, !0), l = Xt(e)[n]; o; ) {
    var u = Xt(o)[i], a = void 0;
    if (i === "top" || i === "left" ? a = l >= u : a = l <= u, !a)
      return o;
    if (o === $n())
      break;
    o = Cr(o, !1);
  }
  return !1;
}
function Li(e, n, i, o) {
  for (var l = 0, u = 0, a = e.children; u < a.length; ) {
    if (a[u].style.display !== "none" && a[u] !== st.ghost && (o || a[u] !== st.dragged) && yn(a[u], i.draggable, e, !1)) {
      if (l === n)
        return a[u];
      l++;
    }
    u++;
  }
  return null;
}
function Wf(e, n) {
  for (var i = e.lastElementChild; i && (i === st.ghost || Q(i, "display") === "none" || n && !ks(i, n)); )
    i = i.previousElementSibling;
  return i || null;
}
function ae(e, n) {
  var i = 0;
  if (!e || !e.parentNode)
    return -1;
  for (; e = e.previousElementSibling; )
    e.nodeName.toUpperCase() !== "TEMPLATE" && e !== st.clone && (!n || ks(e, n)) && i++;
  return i;
}
function nd(e) {
  var n = 0, i = 0, o = $n();
  if (e)
    do {
      var l = Qr(e), u = l.a, a = l.d;
      n += e.scrollLeft * u, i += e.scrollTop * a;
    } while (e !== o && (e = e.parentNode));
  return [n, i];
}
function Sx(e, n) {
  for (var i in e)
    if (e.hasOwnProperty(i)) {
      for (var o in n)
        if (n.hasOwnProperty(o) && n[o] === e[i][o])
          return Number(i);
    }
  return -1;
}
function Cr(e, n) {
  if (!e || !e.getBoundingClientRect)
    return $n();
  var i = e, o = !1;
  do
    if (i.clientWidth < i.scrollWidth || i.clientHeight < i.scrollHeight) {
      var l = Q(i);
      if (i.clientWidth < i.scrollWidth && (l.overflowX == "auto" || l.overflowX == "scroll") || i.clientHeight < i.scrollHeight && (l.overflowY == "auto" || l.overflowY == "scroll")) {
        if (!i.getBoundingClientRect || i === document.body)
          return $n();
        if (o || n)
          return i;
        o = !0;
      }
    }
  while (i = i.parentNode);
  return $n();
}
function Tx(e, n) {
  if (e && n)
    for (var i in n)
      n.hasOwnProperty(i) && (e[i] = n[i]);
  return e;
}
function Hu(e, n) {
  return Math.round(e.top) === Math.round(n.top) && Math.round(e.left) === Math.round(n.left) && Math.round(e.height) === Math.round(n.height) && Math.round(e.width) === Math.round(n.width);
}
var _o;
function Cp(e, n) {
  return function() {
    if (!_o) {
      var i = arguments, o = this;
      i.length === 1 ? e.call(o, i[0]) : e.apply(o, i), _o = setTimeout(function() {
        _o = void 0;
      }, n);
    }
  };
}
function Ax() {
  clearTimeout(_o), _o = void 0;
}
function Op(e, n, i) {
  e.scrollLeft += n, e.scrollTop += i;
}
function Uf(e) {
  var n = window.Polymer, i = window.jQuery || window.Zepto;
  return n && n.dom ? n.dom(e).cloneNode(!0) : i ? i(e).clone(!0)[0] : e.cloneNode(!0);
}
function rd(e, n) {
  Q(e, "position", "absolute"), Q(e, "top", n.top), Q(e, "left", n.left), Q(e, "width", n.width), Q(e, "height", n.height);
}
function $u(e) {
  Q(e, "position", ""), Q(e, "top", ""), Q(e, "left", ""), Q(e, "width", ""), Q(e, "height", "");
}
function Ip(e, n, i) {
  var o = {};
  return Array.from(e.children).forEach(function(l) {
    var u, a, h, g;
    if (!(!yn(l, n.draggable, e, !1) || l.animated || l === i)) {
      var v = Xt(l);
      o.left = Math.min((u = o.left) !== null && u !== void 0 ? u : 1 / 0, v.left), o.top = Math.min((a = o.top) !== null && a !== void 0 ? a : 1 / 0, v.top), o.right = Math.max((h = o.right) !== null && h !== void 0 ? h : -1 / 0, v.right), o.bottom = Math.max((g = o.bottom) !== null && g !== void 0 ? g : -1 / 0, v.bottom);
    }
  }), o.width = o.right - o.left, o.height = o.bottom - o.top, o.x = o.left, o.y = o.top, o;
}
var Re = "Sortable" + (/* @__PURE__ */ new Date()).getTime();
function Cx() {
  var e = [], n;
  return {
    captureAnimationState: function() {
      if (e = [], !!this.options.animation) {
        var o = [].slice.call(this.el.children);
        o.forEach(function(l) {
          if (!(Q(l, "display") === "none" || l === st.ghost)) {
            e.push({
              target: l,
              rect: Xt(l)
            });
            var u = Gn({}, e[e.length - 1].rect);
            if (l.thisAnimationDuration) {
              var a = Qr(l, !0);
              a && (u.top -= a.f, u.left -= a.e);
            }
            l.fromRect = u;
          }
        });
      }
    },
    addAnimationState: function(o) {
      e.push(o);
    },
    removeAnimationState: function(o) {
      e.splice(Sx(e, {
        target: o
      }), 1);
    },
    animateAll: function(o) {
      var l = this;
      if (!this.options.animation) {
        clearTimeout(n), typeof o == "function" && o();
        return;
      }
      var u = !1, a = 0;
      e.forEach(function(h) {
        var g = 0, v = h.target, _ = v.fromRect, m = Xt(v), x = v.prevFromRect, A = v.prevToRect, O = h.rect, R = Qr(v, !0);
        R && (m.top -= R.f, m.left -= R.e), v.toRect = m, v.thisAnimationDuration && Hu(x, m) && !Hu(_, m) && // Make sure animatingRect is on line between toRect & fromRect
        (O.top - m.top) / (O.left - m.left) === (_.top - m.top) / (_.left - m.left) && (g = Ix(O, x, A, l.options)), Hu(m, _) || (v.prevFromRect = _, v.prevToRect = m, g || (g = l.options.animation), l.animate(v, O, m, g)), g && (u = !0, a = Math.max(a, g), clearTimeout(v.animationResetTimer), v.animationResetTimer = setTimeout(function() {
          v.animationTime = 0, v.prevFromRect = null, v.fromRect = null, v.prevToRect = null, v.thisAnimationDuration = null;
        }, g), v.thisAnimationDuration = g);
      }), clearTimeout(n), u ? n = setTimeout(function() {
        typeof o == "function" && o();
      }, a) : typeof o == "function" && o(), e = [];
    },
    animate: function(o, l, u, a) {
      if (a) {
        Q(o, "transition", ""), Q(o, "transform", "");
        var h = Qr(this.el), g = h && h.a, v = h && h.d, _ = (l.left - u.left) / (g || 1), m = (l.top - u.top) / (v || 1);
        o.animatingX = !!_, o.animatingY = !!m, Q(o, "transform", "translate3d(" + _ + "px," + m + "px,0)"), this.forRepaintDummy = Ox(o), Q(o, "transition", "transform " + a + "ms" + (this.options.easing ? " " + this.options.easing : "")), Q(o, "transform", "translate3d(0,0,0)"), typeof o.animated == "number" && clearTimeout(o.animated), o.animated = setTimeout(function() {
          Q(o, "transition", ""), Q(o, "transform", ""), o.animated = !1, o.animatingX = !1, o.animatingY = !1;
        }, a);
      }
    }
  };
}
function Ox(e) {
  return e.offsetWidth;
}
function Ix(e, n, i, o) {
  return Math.sqrt(Math.pow(n.top - e.top, 2) + Math.pow(n.left - e.left, 2)) / Math.sqrt(Math.pow(n.top - i.top, 2) + Math.pow(n.left - i.left, 2)) * o.animation;
}
var yi = [], Ku = {
  initializeByDefault: !0
}, Io = {
  mount: function(n) {
    for (var i in Ku)
      Ku.hasOwnProperty(i) && !(i in n) && (n[i] = Ku[i]);
    yi.forEach(function(o) {
      if (o.pluginName === n.pluginName)
        throw "Sortable: Cannot mount plugin ".concat(n.pluginName, " more than once");
    }), yi.push(n);
  },
  pluginEvent: function(n, i, o) {
    var l = this;
    this.eventCanceled = !1, o.cancel = function() {
      l.eventCanceled = !0;
    };
    var u = n + "Global";
    yi.forEach(function(a) {
      i[a.pluginName] && (i[a.pluginName][u] && i[a.pluginName][u](Gn({
        sortable: i
      }, o)), i.options[a.pluginName] && i[a.pluginName][n] && i[a.pluginName][n](Gn({
        sortable: i
      }, o)));
    });
  },
  initializePlugins: function(n, i, o, l) {
    yi.forEach(function(h) {
      var g = h.pluginName;
      if (!(!n.options[g] && !h.initializeByDefault)) {
        var v = new h(n, i, n.options);
        v.sortable = n, v.options = n.options, n[g] = v, Dn(o, v.defaults);
      }
    });
    for (var u in n.options)
      if (n.options.hasOwnProperty(u)) {
        var a = this.modifyOption(n, u, n.options[u]);
        typeof a < "u" && (n.options[u] = a);
      }
  },
  getEventProperties: function(n, i) {
    var o = {};
    return yi.forEach(function(l) {
      typeof l.eventProperties == "function" && Dn(o, l.eventProperties.call(i[l.pluginName], n));
    }), o;
  },
  modifyOption: function(n, i, o) {
    var l;
    return yi.forEach(function(u) {
      n[u.pluginName] && u.optionListeners && typeof u.optionListeners[i] == "function" && (l = u.optionListeners[i].call(n[u.pluginName], o));
    }), l;
  }
};
function oo(e) {
  var n = e.sortable, i = e.rootEl, o = e.name, l = e.targetEl, u = e.cloneEl, a = e.toEl, h = e.fromEl, g = e.oldIndex, v = e.newIndex, _ = e.oldDraggableIndex, m = e.newDraggableIndex, x = e.originalEvent, A = e.putSortable, O = e.extraEventProperties;
  if (n = n || i && i[Re], !!n) {
    var R, N = n.options, W = "on" + o.charAt(0).toUpperCase() + o.substr(1);
    window.CustomEvent && !lr && !Oo ? R = new CustomEvent(o, {
      bubbles: !0,
      cancelable: !0
    }) : (R = document.createEvent("Event"), R.initEvent(o, !0, !0)), R.to = a || i, R.from = h || i, R.item = l || i, R.clone = u, R.oldIndex = g, R.newIndex = v, R.oldDraggableIndex = _, R.newDraggableIndex = m, R.originalEvent = x, R.pullMode = A ? A.lastPutMode : void 0;
    var V = Gn(Gn({}, O), Io.getEventProperties(o, n));
    for (var X in V)
      R[X] = V[X];
    i && i.dispatchEvent(R), N[W] && N[W].call(n, R);
  }
}
var Dx = ["evt"], ke = function(n, i) {
  var o = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, l = o.evt, u = vx(o, Dx);
  Io.pluginEvent.bind(st)(n, i, Gn({
    dragEl: Y,
    parentEl: te,
    ghostEl: ht,
    rootEl: kt,
    nextEl: Vr,
    lastDownEl: Ws,
    cloneEl: Qt,
    cloneHidden: Ar,
    dragStarted: so,
    putSortable: Se,
    activeSortable: st.active,
    originalEvent: l,
    oldIndex: Ti,
    oldDraggableIndex: mo,
    newIndex: ln,
    newDraggableIndex: Sr,
    hideGhostForTarget: Mp,
    unhideGhostForTarget: Fp,
    cloneNowHidden: function() {
      Ar = !0;
    },
    cloneNowShown: function() {
      Ar = !1;
    },
    dispatchSortableEvent: function(h) {
      Ue({
        sortable: i,
        name: h,
        originalEvent: l
      });
    }
  }, u));
};
function Ue(e) {
  oo(Gn({
    putSortable: Se,
    cloneEl: Qt,
    targetEl: Y,
    rootEl: kt,
    oldIndex: Ti,
    oldDraggableIndex: mo,
    newIndex: ln,
    newDraggableIndex: Sr
  }, e));
}
var Y, te, ht, kt, Vr, Ws, Qt, Ar, Ti, ln, mo, Sr, As, Se, Si = !1, Js = !1, Zs = [], Xr, Cn, Gu, Yu, id, od, so, wi, bo, yo = !1, Cs = !1, Us, Ie, Xu = [], cf = !1, Qs = [], hl = typeof document < "u", Os = xp, sd = Oo || lr ? "cssFloat" : "float", Rx = hl && !Sp && !xp && "draggable" in document.createElement("div"), Dp = function() {
  if (hl) {
    if (lr)
      return !1;
    var e = document.createElement("x");
    return e.style.cssText = "pointer-events:auto", e.style.pointerEvents === "auto";
  }
}(), Rp = function(n, i) {
  var o = Q(n), l = parseInt(o.width) - parseInt(o.paddingLeft) - parseInt(o.paddingRight) - parseInt(o.borderLeftWidth) - parseInt(o.borderRightWidth), u = Li(n, 0, i), a = Li(n, 1, i), h = u && Q(u), g = a && Q(a), v = h && parseInt(h.marginLeft) + parseInt(h.marginRight) + Xt(u).width, _ = g && parseInt(g.marginLeft) + parseInt(g.marginRight) + Xt(a).width;
  if (o.display === "flex")
    return o.flexDirection === "column" || o.flexDirection === "column-reverse" ? "vertical" : "horizontal";
  if (o.display === "grid")
    return o.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
  if (u && h.float && h.float !== "none") {
    var m = h.float === "left" ? "left" : "right";
    return a && (g.clear === "both" || g.clear === m) ? "vertical" : "horizontal";
  }
  return u && (h.display === "block" || h.display === "flex" || h.display === "table" || h.display === "grid" || v >= l && o[sd] === "none" || a && o[sd] === "none" && v + _ > l) ? "vertical" : "horizontal";
}, Px = function(n, i, o) {
  var l = o ? n.left : n.top, u = o ? n.right : n.bottom, a = o ? n.width : n.height, h = o ? i.left : i.top, g = o ? i.right : i.bottom, v = o ? i.width : i.height;
  return l === h || u === g || l + a / 2 === h + v / 2;
}, Mx = function(n, i) {
  var o;
  return Zs.some(function(l) {
    var u = l[Re].options.emptyInsertThreshold;
    if (!(!u || Wf(l))) {
      var a = Xt(l), h = n >= a.left - u && n <= a.right + u, g = i >= a.top - u && i <= a.bottom + u;
      if (h && g)
        return o = l;
    }
  }), o;
}, Pp = function(n) {
  function i(u, a) {
    return function(h, g, v, _) {
      var m = h.options.group.name && g.options.group.name && h.options.group.name === g.options.group.name;
      if (u == null && (a || m))
        return !0;
      if (u == null || u === !1)
        return !1;
      if (a && u === "clone")
        return u;
      if (typeof u == "function")
        return i(u(h, g, v, _), a)(h, g, v, _);
      var x = (a ? h : g).options.group.name;
      return u === !0 || typeof u == "string" && u === x || u.join && u.indexOf(x) > -1;
    };
  }
  var o = {}, l = n.group;
  (!l || Bs(l) != "object") && (l = {
    name: l
  }), o.name = l.name, o.checkPull = i(l.pull, !0), o.checkPut = i(l.put), o.revertClone = l.revertClone, n.group = o;
}, Mp = function() {
  !Dp && ht && Q(ht, "display", "none");
}, Fp = function() {
  !Dp && ht && Q(ht, "display", "");
};
hl && !Sp && document.addEventListener("click", function(e) {
  if (Js)
    return e.preventDefault(), e.stopPropagation && e.stopPropagation(), e.stopImmediatePropagation && e.stopImmediatePropagation(), Js = !1, !1;
}, !0);
var zr = function(n) {
  if (Y) {
    n = n.touches ? n.touches[0] : n;
    var i = Mx(n.clientX, n.clientY);
    if (i) {
      var o = {};
      for (var l in n)
        n.hasOwnProperty(l) && (o[l] = n[l]);
      o.target = o.rootEl = i, o.preventDefault = void 0, o.stopPropagation = void 0, i[Re]._onDragOver(o);
    }
  }
}, Fx = function(n) {
  Y && Y.parentNode[Re]._isOutsideThisEl(n.target);
};
function st(e, n) {
  if (!(e && e.nodeType && e.nodeType === 1))
    throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(e));
  this.el = e, this.options = n = Dn({}, n), e[Re] = this;
  var i = {
    group: null,
    sort: !0,
    disabled: !1,
    store: null,
    handle: null,
    draggable: /^[uo]l$/i.test(e.nodeName) ? ">li" : ">*",
    swapThreshold: 1,
    // percentage; 0 <= x <= 1
    invertSwap: !1,
    // invert always
    invertedSwapThreshold: null,
    // will be set to same as swapThreshold if default
    removeCloneOnHide: !0,
    direction: function() {
      return Rp(e, this.options);
    },
    ghostClass: "sortable-ghost",
    chosenClass: "sortable-chosen",
    dragClass: "sortable-drag",
    ignore: "a, img",
    filter: null,
    preventOnFilter: !0,
    animation: 0,
    easing: null,
    setData: function(a, h) {
      a.setData("Text", h.textContent);
    },
    dropBubble: !1,
    dragoverBubble: !1,
    dataIdAttr: "data-id",
    delay: 0,
    delayOnTouchOnly: !1,
    touchStartThreshold: (Number.parseInt ? Number : window).parseInt(window.devicePixelRatio, 10) || 1,
    forceFallback: !1,
    fallbackClass: "sortable-fallback",
    fallbackOnBody: !1,
    fallbackTolerance: 0,
    fallbackOffset: {
      x: 0,
      y: 0
    },
    supportPointer: st.supportPointer !== !1 && "PointerEvent" in window && !vo,
    emptyInsertThreshold: 5
  };
  Io.initializePlugins(this, e, i);
  for (var o in i)
    !(o in n) && (n[o] = i[o]);
  Pp(n);
  for (var l in this)
    l.charAt(0) === "_" && typeof this[l] == "function" && (this[l] = this[l].bind(this));
  this.nativeDraggable = n.forceFallback ? !1 : Rx, this.nativeDraggable && (this.options.touchStartThreshold = 1), n.supportPointer ? At(e, "pointerdown", this._onTapStart) : (At(e, "mousedown", this._onTapStart), At(e, "touchstart", this._onTapStart)), this.nativeDraggable && (At(e, "dragover", this), At(e, "dragenter", this)), Zs.push(this.el), n.store && n.store.get && this.sort(n.store.get(this) || []), Dn(this, Cx());
}
st.prototype = /** @lends Sortable.prototype */
{
  constructor: st,
  _isOutsideThisEl: function(n) {
    !this.el.contains(n) && n !== this.el && (wi = null);
  },
  _getDirection: function(n, i) {
    return typeof this.options.direction == "function" ? this.options.direction.call(this, n, i, Y) : this.options.direction;
  },
  _onTapStart: function(n) {
    if (n.cancelable) {
      var i = this, o = this.el, l = this.options, u = l.preventOnFilter, a = n.type, h = n.touches && n.touches[0] || n.pointerType && n.pointerType === "touch" && n, g = (h || n).target, v = n.target.shadowRoot && (n.path && n.path[0] || n.composedPath && n.composedPath()[0]) || g, _ = l.filter;
      if (Kx(o), !Y && !(/mousedown|pointerdown/.test(a) && n.button !== 0 || l.disabled) && !v.isContentEditable && !(!this.nativeDraggable && vo && g && g.tagName.toUpperCase() === "SELECT") && (g = yn(g, l.draggable, o, !1), !(g && g.animated) && Ws !== g)) {
        if (Ti = ae(g), mo = ae(g, l.draggable), typeof _ == "function") {
          if (_.call(this, n, g, this)) {
            Ue({
              sortable: i,
              rootEl: v,
              name: "filter",
              targetEl: g,
              toEl: o,
              fromEl: o
            }), ke("filter", i, {
              evt: n
            }), u && n.cancelable && n.preventDefault();
            return;
          }
        } else if (_ && (_ = _.split(",").some(function(m) {
          if (m = yn(v, m.trim(), o, !1), m)
            return Ue({
              sortable: i,
              rootEl: m,
              name: "filter",
              targetEl: g,
              fromEl: o,
              toEl: o
            }), ke("filter", i, {
              evt: n
            }), !0;
        }), _)) {
          u && n.cancelable && n.preventDefault();
          return;
        }
        l.handle && !yn(v, l.handle, o, !1) || this._prepareDragStart(n, h, g);
      }
    }
  },
  _prepareDragStart: function(n, i, o) {
    var l = this, u = l.el, a = l.options, h = u.ownerDocument, g;
    if (o && !Y && o.parentNode === u) {
      var v = Xt(o);
      if (kt = u, Y = o, te = Y.parentNode, Vr = Y.nextSibling, Ws = o, As = a.group, st.dragged = Y, Xr = {
        target: Y,
        clientX: (i || n).clientX,
        clientY: (i || n).clientY
      }, id = Xr.clientX - v.left, od = Xr.clientY - v.top, this._lastX = (i || n).clientX, this._lastY = (i || n).clientY, Y.style["will-change"] = "all", g = function() {
        if (ke("delayEnded", l, {
          evt: n
        }), st.eventCanceled) {
          l._onDrop();
          return;
        }
        l._disableDelayedDragEvents(), !jh && l.nativeDraggable && (Y.draggable = !0), l._triggerDragStart(n, i), Ue({
          sortable: l,
          name: "choose",
          originalEvent: n
        }), fe(Y, a.chosenClass, !0);
      }, a.ignore.split(",").forEach(function(_) {
        Ap(Y, _.trim(), zu);
      }), At(h, "dragover", zr), At(h, "mousemove", zr), At(h, "touchmove", zr), At(h, "mouseup", l._onDrop), At(h, "touchend", l._onDrop), At(h, "touchcancel", l._onDrop), jh && this.nativeDraggable && (this.options.touchStartThreshold = 4, Y.draggable = !0), ke("delayStart", this, {
        evt: n
      }), a.delay && (!a.delayOnTouchOnly || i) && (!this.nativeDraggable || !(Oo || lr))) {
        if (st.eventCanceled) {
          this._onDrop();
          return;
        }
        At(h, "mouseup", l._disableDelayedDrag), At(h, "touchend", l._disableDelayedDrag), At(h, "touchcancel", l._disableDelayedDrag), At(h, "mousemove", l._delayedDragTouchMoveHandler), At(h, "touchmove", l._delayedDragTouchMoveHandler), a.supportPointer && At(h, "pointermove", l._delayedDragTouchMoveHandler), l._dragStartTimer = setTimeout(g, a.delay);
      } else
        g();
    }
  },
  _delayedDragTouchMoveHandler: function(n) {
    var i = n.touches ? n.touches[0] : n;
    Math.max(Math.abs(i.clientX - this._lastX), Math.abs(i.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1)) && this._disableDelayedDrag();
  },
  _disableDelayedDrag: function() {
    Y && zu(Y), clearTimeout(this._dragStartTimer), this._disableDelayedDragEvents();
  },
  _disableDelayedDragEvents: function() {
    var n = this.el.ownerDocument;
    wt(n, "mouseup", this._disableDelayedDrag), wt(n, "touchend", this._disableDelayedDrag), wt(n, "touchcancel", this._disableDelayedDrag), wt(n, "mousemove", this._delayedDragTouchMoveHandler), wt(n, "touchmove", this._delayedDragTouchMoveHandler), wt(n, "pointermove", this._delayedDragTouchMoveHandler);
  },
  _triggerDragStart: function(n, i) {
    i = i || n.pointerType == "touch" && n, !this.nativeDraggable || i ? this.options.supportPointer ? At(document, "pointermove", this._onTouchMove) : i ? At(document, "touchmove", this._onTouchMove) : At(document, "mousemove", this._onTouchMove) : (At(Y, "dragend", this), At(kt, "dragstart", this._onDragStart));
    try {
      document.selection ? Hs(function() {
        document.selection.empty();
      }) : window.getSelection().removeAllRanges();
    } catch {
    }
  },
  _dragStarted: function(n, i) {
    if (Si = !1, kt && Y) {
      ke("dragStarted", this, {
        evt: i
      }), this.nativeDraggable && At(document, "dragover", Fx);
      var o = this.options;
      !n && fe(Y, o.dragClass, !1), fe(Y, o.ghostClass, !0), st.active = this, n && this._appendGhost(), Ue({
        sortable: this,
        name: "start",
        originalEvent: i
      });
    } else
      this._nulling();
  },
  _emulateDragOver: function() {
    if (Cn) {
      this._lastX = Cn.clientX, this._lastY = Cn.clientY, Mp();
      for (var n = document.elementFromPoint(Cn.clientX, Cn.clientY), i = n; n && n.shadowRoot && (n = n.shadowRoot.elementFromPoint(Cn.clientX, Cn.clientY), n !== i); )
        i = n;
      if (Y.parentNode[Re]._isOutsideThisEl(n), i)
        do {
          if (i[Re]) {
            var o = void 0;
            if (o = i[Re]._onDragOver({
              clientX: Cn.clientX,
              clientY: Cn.clientY,
              target: n,
              rootEl: i
            }), o && !this.options.dragoverBubble)
              break;
          }
          n = i;
        } while (i = i.parentNode);
      Fp();
    }
  },
  _onTouchMove: function(n) {
    if (Xr) {
      var i = this.options, o = i.fallbackTolerance, l = i.fallbackOffset, u = n.touches ? n.touches[0] : n, a = ht && Qr(ht, !0), h = ht && a && a.a, g = ht && a && a.d, v = Os && Ie && nd(Ie), _ = (u.clientX - Xr.clientX + l.x) / (h || 1) + (v ? v[0] - Xu[0] : 0) / (h || 1), m = (u.clientY - Xr.clientY + l.y) / (g || 1) + (v ? v[1] - Xu[1] : 0) / (g || 1);
      if (!st.active && !Si) {
        if (o && Math.max(Math.abs(u.clientX - this._lastX), Math.abs(u.clientY - this._lastY)) < o)
          return;
        this._onDragStart(n, !0);
      }
      if (ht) {
        a ? (a.e += _ - (Gu || 0), a.f += m - (Yu || 0)) : a = {
          a: 1,
          b: 0,
          c: 0,
          d: 1,
          e: _,
          f: m
        };
        var x = "matrix(".concat(a.a, ",").concat(a.b, ",").concat(a.c, ",").concat(a.d, ",").concat(a.e, ",").concat(a.f, ")");
        Q(ht, "webkitTransform", x), Q(ht, "mozTransform", x), Q(ht, "msTransform", x), Q(ht, "transform", x), Gu = _, Yu = m, Cn = u;
      }
      n.cancelable && n.preventDefault();
    }
  },
  _appendGhost: function() {
    if (!ht) {
      var n = this.options.fallbackOnBody ? document.body : kt, i = Xt(Y, !0, Os, !0, n), o = this.options;
      if (Os) {
        for (Ie = n; Q(Ie, "position") === "static" && Q(Ie, "transform") === "none" && Ie !== document; )
          Ie = Ie.parentNode;
        Ie !== document.body && Ie !== document.documentElement ? (Ie === document && (Ie = $n()), i.top += Ie.scrollTop, i.left += Ie.scrollLeft) : Ie = $n(), Xu = nd(Ie);
      }
      ht = Y.cloneNode(!0), fe(ht, o.ghostClass, !1), fe(ht, o.fallbackClass, !0), fe(ht, o.dragClass, !0), Q(ht, "transition", ""), Q(ht, "transform", ""), Q(ht, "box-sizing", "border-box"), Q(ht, "margin", 0), Q(ht, "top", i.top), Q(ht, "left", i.left), Q(ht, "width", i.width), Q(ht, "height", i.height), Q(ht, "opacity", "0.8"), Q(ht, "position", Os ? "absolute" : "fixed"), Q(ht, "zIndex", "100000"), Q(ht, "pointerEvents", "none"), st.ghost = ht, n.appendChild(ht), Q(ht, "transform-origin", id / parseInt(ht.style.width) * 100 + "% " + od / parseInt(ht.style.height) * 100 + "%");
    }
  },
  _onDragStart: function(n, i) {
    var o = this, l = n.dataTransfer, u = o.options;
    if (ke("dragStart", this, {
      evt: n
    }), st.eventCanceled) {
      this._onDrop();
      return;
    }
    ke("setupClone", this), st.eventCanceled || (Qt = Uf(Y), Qt.removeAttribute("id"), Qt.draggable = !1, Qt.style["will-change"] = "", this._hideClone(), fe(Qt, this.options.chosenClass, !1), st.clone = Qt), o.cloneId = Hs(function() {
      ke("clone", o), !st.eventCanceled && (o.options.removeCloneOnHide || kt.insertBefore(Qt, Y), o._hideClone(), Ue({
        sortable: o,
        name: "clone"
      }));
    }), !i && fe(Y, u.dragClass, !0), i ? (Js = !0, o._loopId = setInterval(o._emulateDragOver, 50)) : (wt(document, "mouseup", o._onDrop), wt(document, "touchend", o._onDrop), wt(document, "touchcancel", o._onDrop), l && (l.effectAllowed = "move", u.setData && u.setData.call(o, l, Y)), At(document, "drop", o), Q(Y, "transform", "translateZ(0)")), Si = !0, o._dragStartId = Hs(o._dragStarted.bind(o, i, n)), At(document, "selectstart", o), so = !0, vo && Q(document.body, "user-select", "none");
  },
  // Returns true - if no further action is needed (either inserted or another condition)
  _onDragOver: function(n) {
    var i = this.el, o = n.target, l, u, a, h = this.options, g = h.group, v = st.active, _ = As === g, m = h.sort, x = Se || v, A, O = this, R = !1;
    if (cf)
      return;
    function N(rt, _e) {
      ke(rt, O, Gn({
        evt: n,
        isOwner: _,
        axis: A ? "vertical" : "horizontal",
        revert: a,
        dragRect: l,
        targetRect: u,
        canSort: m,
        fromSortable: x,
        target: o,
        completed: V,
        onMove: function(qt, oe) {
          return Is(kt, i, Y, l, qt, Xt(qt), n, oe);
        },
        changed: X
      }, _e));
    }
    function W() {
      N("dragOverAnimationCapture"), O.captureAnimationState(), O !== x && x.captureAnimationState();
    }
    function V(rt) {
      return N("dragOverCompleted", {
        insertion: rt
      }), rt && (_ ? v._hideClone() : v._showClone(O), O !== x && (fe(Y, Se ? Se.options.ghostClass : v.options.ghostClass, !1), fe(Y, h.ghostClass, !0)), Se !== O && O !== st.active ? Se = O : O === st.active && Se && (Se = null), x === O && (O._ignoreWhileAnimating = o), O.animateAll(function() {
        N("dragOverAnimationComplete"), O._ignoreWhileAnimating = null;
      }), O !== x && (x.animateAll(), x._ignoreWhileAnimating = null)), (o === Y && !Y.animated || o === i && !o.animated) && (wi = null), !h.dragoverBubble && !n.rootEl && o !== document && (Y.parentNode[Re]._isOutsideThisEl(n.target), !rt && zr(n)), !h.dragoverBubble && n.stopPropagation && n.stopPropagation(), R = !0;
    }
    function X() {
      ln = ae(Y), Sr = ae(Y, h.draggable), Ue({
        sortable: O,
        name: "change",
        toEl: i,
        newIndex: ln,
        newDraggableIndex: Sr,
        originalEvent: n
      });
    }
    if (n.preventDefault !== void 0 && n.cancelable && n.preventDefault(), o = yn(o, h.draggable, i, !0), N("dragOver"), st.eventCanceled)
      return R;
    if (Y.contains(n.target) || o.animated && o.animatingX && o.animatingY || O._ignoreWhileAnimating === o)
      return V(!1);
    if (Js = !1, v && !h.disabled && (_ ? m || (a = te !== kt) : Se === this || (this.lastPutMode = As.checkPull(this, v, Y, n)) && g.checkPut(this, v, Y, n))) {
      if (A = this._getDirection(n, o) === "vertical", l = Xt(Y), N("dragOverValid"), st.eventCanceled)
        return R;
      if (a)
        return te = kt, W(), this._hideClone(), N("revert"), st.eventCanceled || (Vr ? kt.insertBefore(Y, Vr) : kt.appendChild(Y)), V(!0);
      var L = Wf(i, h.draggable);
      if (!L || Wx(n, A, this) && !L.animated) {
        if (L === Y)
          return V(!1);
        if (L && i === n.target && (o = L), o && (u = Xt(o)), Is(kt, i, Y, l, o, u, n, !!o) !== !1)
          return W(), L && L.nextSibling ? i.insertBefore(Y, L.nextSibling) : i.appendChild(Y), te = i, X(), V(!0);
      } else if (L && Bx(n, A, this)) {
        var tt = Li(i, 0, h, !0);
        if (tt === Y)
          return V(!1);
        if (o = tt, u = Xt(o), Is(kt, i, Y, l, o, u, n, !1) !== !1)
          return W(), i.insertBefore(Y, tt), te = i, X(), V(!0);
      } else if (o.parentNode === i) {
        u = Xt(o);
        var Et = 0, St, Wt = Y.parentNode !== i, ft = !Px(Y.animated && Y.toRect || l, o.animated && o.toRect || u, A), Ot = A ? "top" : "left", Nt = ed(o, "top", "top") || ed(Y, "top", "top"), zt = Nt ? Nt.scrollTop : void 0;
        wi !== o && (St = u[Ot], yo = !1, Cs = !ft && h.invertSwap || Wt), Et = Ux(n, o, u, A, ft ? 1 : h.swapThreshold, h.invertedSwapThreshold == null ? h.swapThreshold : h.invertedSwapThreshold, Cs, wi === o);
        var Lt;
        if (Et !== 0) {
          var pe = ae(Y);
          do
            pe -= Et, Lt = te.children[pe];
          while (Lt && (Q(Lt, "display") === "none" || Lt === ht));
        }
        if (Et === 0 || Lt === o)
          return V(!1);
        wi = o, bo = Et;
        var Ae = o.nextElementSibling, vt = !1;
        vt = Et === 1;
        var lt = Is(kt, i, Y, l, o, u, n, vt);
        if (lt !== !1)
          return (lt === 1 || lt === -1) && (vt = lt === 1), cf = !0, setTimeout(Lx, 30), W(), vt && !Ae ? i.appendChild(Y) : o.parentNode.insertBefore(Y, vt ? Ae : o), Nt && Op(Nt, 0, zt - Nt.scrollTop), te = Y.parentNode, St !== void 0 && !Cs && (Us = Math.abs(St - Xt(o)[Ot])), X(), V(!0);
      }
      if (i.contains(Y))
        return V(!1);
    }
    return !1;
  },
  _ignoreWhileAnimating: null,
  _offMoveEvents: function() {
    wt(document, "mousemove", this._onTouchMove), wt(document, "touchmove", this._onTouchMove), wt(document, "pointermove", this._onTouchMove), wt(document, "dragover", zr), wt(document, "mousemove", zr), wt(document, "touchmove", zr);
  },
  _offUpEvents: function() {
    var n = this.el.ownerDocument;
    wt(n, "mouseup", this._onDrop), wt(n, "touchend", this._onDrop), wt(n, "pointerup", this._onDrop), wt(n, "touchcancel", this._onDrop), wt(document, "selectstart", this);
  },
  _onDrop: function(n) {
    var i = this.el, o = this.options;
    if (ln = ae(Y), Sr = ae(Y, o.draggable), ke("drop", this, {
      evt: n
    }), te = Y && Y.parentNode, ln = ae(Y), Sr = ae(Y, o.draggable), st.eventCanceled) {
      this._nulling();
      return;
    }
    Si = !1, Cs = !1, yo = !1, clearInterval(this._loopId), clearTimeout(this._dragStartTimer), hf(this.cloneId), hf(this._dragStartId), this.nativeDraggable && (wt(document, "drop", this), wt(i, "dragstart", this._onDragStart)), this._offMoveEvents(), this._offUpEvents(), vo && Q(document.body, "user-select", ""), Q(Y, "transform", ""), n && (so && (n.cancelable && n.preventDefault(), !o.dropBubble && n.stopPropagation()), ht && ht.parentNode && ht.parentNode.removeChild(ht), (kt === te || Se && Se.lastPutMode !== "clone") && Qt && Qt.parentNode && Qt.parentNode.removeChild(Qt), Y && (this.nativeDraggable && wt(Y, "dragend", this), zu(Y), Y.style["will-change"] = "", so && !Si && fe(Y, Se ? Se.options.ghostClass : this.options.ghostClass, !1), fe(Y, this.options.chosenClass, !1), Ue({
      sortable: this,
      name: "unchoose",
      toEl: te,
      newIndex: null,
      newDraggableIndex: null,
      originalEvent: n
    }), kt !== te ? (ln >= 0 && (Ue({
      rootEl: te,
      name: "add",
      toEl: te,
      fromEl: kt,
      originalEvent: n
    }), Ue({
      sortable: this,
      name: "remove",
      toEl: te,
      originalEvent: n
    }), Ue({
      rootEl: te,
      name: "sort",
      toEl: te,
      fromEl: kt,
      originalEvent: n
    }), Ue({
      sortable: this,
      name: "sort",
      toEl: te,
      originalEvent: n
    })), Se && Se.save()) : ln !== Ti && ln >= 0 && (Ue({
      sortable: this,
      name: "update",
      toEl: te,
      originalEvent: n
    }), Ue({
      sortable: this,
      name: "sort",
      toEl: te,
      originalEvent: n
    })), st.active && ((ln == null || ln === -1) && (ln = Ti, Sr = mo), Ue({
      sortable: this,
      name: "end",
      toEl: te,
      originalEvent: n
    }), this.save()))), this._nulling();
  },
  _nulling: function() {
    ke("nulling", this), kt = Y = te = ht = Vr = Qt = Ws = Ar = Xr = Cn = so = ln = Sr = Ti = mo = wi = bo = Se = As = st.dragged = st.ghost = st.clone = st.active = null, Qs.forEach(function(n) {
      n.checked = !0;
    }), Qs.length = Gu = Yu = 0;
  },
  handleEvent: function(n) {
    switch (n.type) {
      case "drop":
      case "dragend":
        this._onDrop(n);
        break;
      case "dragenter":
      case "dragover":
        Y && (this._onDragOver(n), Nx(n));
        break;
      case "selectstart":
        n.preventDefault();
        break;
    }
  },
  /**
   * Serializes the item into an array of string.
   * @returns {String[]}
   */
  toArray: function() {
    for (var n = [], i, o = this.el.children, l = 0, u = o.length, a = this.options; l < u; l++)
      i = o[l], yn(i, a.draggable, this.el, !1) && n.push(i.getAttribute(a.dataIdAttr) || $x(i));
    return n;
  },
  /**
   * Sorts the elements according to the array.
   * @param  {String[]}  order  order of the items
   */
  sort: function(n, i) {
    var o = {}, l = this.el;
    this.toArray().forEach(function(u, a) {
      var h = l.children[a];
      yn(h, this.options.draggable, l, !1) && (o[u] = h);
    }, this), i && this.captureAnimationState(), n.forEach(function(u) {
      o[u] && (l.removeChild(o[u]), l.appendChild(o[u]));
    }), i && this.animateAll();
  },
  /**
   * Save the current sorting
   */
  save: function() {
    var n = this.options.store;
    n && n.set && n.set(this);
  },
  /**
   * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
   * @param   {HTMLElement}  el
   * @param   {String}       [selector]  default: `options.draggable`
   * @returns {HTMLElement|null}
   */
  closest: function(n, i) {
    return yn(n, i || this.options.draggable, this.el, !1);
  },
  /**
   * Set/get option
   * @param   {string} name
   * @param   {*}      [value]
   * @returns {*}
   */
  option: function(n, i) {
    var o = this.options;
    if (i === void 0)
      return o[n];
    var l = Io.modifyOption(this, n, i);
    typeof l < "u" ? o[n] = l : o[n] = i, n === "group" && Pp(o);
  },
  /**
   * Destroy
   */
  destroy: function() {
    ke("destroy", this);
    var n = this.el;
    n[Re] = null, wt(n, "mousedown", this._onTapStart), wt(n, "touchstart", this._onTapStart), wt(n, "pointerdown", this._onTapStart), this.nativeDraggable && (wt(n, "dragover", this), wt(n, "dragenter", this)), Array.prototype.forEach.call(n.querySelectorAll("[draggable]"), function(i) {
      i.removeAttribute("draggable");
    }), this._onDrop(), this._disableDelayedDragEvents(), Zs.splice(Zs.indexOf(this.el), 1), this.el = n = null;
  },
  _hideClone: function() {
    if (!Ar) {
      if (ke("hideClone", this), st.eventCanceled)
        return;
      Q(Qt, "display", "none"), this.options.removeCloneOnHide && Qt.parentNode && Qt.parentNode.removeChild(Qt), Ar = !0;
    }
  },
  _showClone: function(n) {
    if (n.lastPutMode !== "clone") {
      this._hideClone();
      return;
    }
    if (Ar) {
      if (ke("showClone", this), st.eventCanceled)
        return;
      Y.parentNode == kt && !this.options.group.revertClone ? kt.insertBefore(Qt, Y) : Vr ? kt.insertBefore(Qt, Vr) : kt.appendChild(Qt), this.options.group.revertClone && this.animate(Y, Qt), Q(Qt, "display", ""), Ar = !1;
    }
  }
};
function Nx(e) {
  e.dataTransfer && (e.dataTransfer.dropEffect = "move"), e.cancelable && e.preventDefault();
}
function Is(e, n, i, o, l, u, a, h) {
  var g, v = e[Re], _ = v.options.onMove, m;
  return window.CustomEvent && !lr && !Oo ? g = new CustomEvent("move", {
    bubbles: !0,
    cancelable: !0
  }) : (g = document.createEvent("Event"), g.initEvent("move", !0, !0)), g.to = n, g.from = e, g.dragged = i, g.draggedRect = o, g.related = l || n, g.relatedRect = u || Xt(n), g.willInsertAfter = h, g.originalEvent = a, e.dispatchEvent(g), _ && (m = _.call(v, g, a)), m;
}
function zu(e) {
  e.draggable = !1;
}
function Lx() {
  cf = !1;
}
function Bx(e, n, i) {
  var o = Xt(Li(i.el, 0, i.options, !0)), l = Ip(i.el, i.options, ht), u = 10;
  return n ? e.clientX < l.left - u || e.clientY < o.top && e.clientX < o.right : e.clientY < l.top - u || e.clientY < o.bottom && e.clientX < o.left;
}
function Wx(e, n, i) {
  var o = Xt(Wf(i.el, i.options.draggable)), l = Ip(i.el, i.options, ht), u = 10;
  return n ? e.clientX > l.right + u || e.clientY > o.bottom && e.clientX > o.left : e.clientY > l.bottom + u || e.clientX > o.right && e.clientY > o.top;
}
function Ux(e, n, i, o, l, u, a, h) {
  var g = o ? e.clientY : e.clientX, v = o ? i.height : i.width, _ = o ? i.top : i.left, m = o ? i.bottom : i.right, x = !1;
  if (!a) {
    if (h && Us < v * l) {
      if (!yo && (bo === 1 ? g > _ + v * u / 2 : g < m - v * u / 2) && (yo = !0), yo)
        x = !0;
      else if (bo === 1 ? g < _ + Us : g > m - Us)
        return -bo;
    } else if (g > _ + v * (1 - l) / 2 && g < m - v * (1 - l) / 2)
      return Hx(n);
  }
  return x = x || a, x && (g < _ + v * u / 2 || g > m - v * u / 2) ? g > _ + v / 2 ? 1 : -1 : 0;
}
function Hx(e) {
  return ae(Y) < ae(e) ? 1 : -1;
}
function $x(e) {
  for (var n = e.tagName + e.className + e.src + e.href + e.textContent, i = n.length, o = 0; i--; )
    o += n.charCodeAt(i);
  return o.toString(36);
}
function Kx(e) {
  Qs.length = 0;
  for (var n = e.getElementsByTagName("input"), i = n.length; i--; ) {
    var o = n[i];
    o.checked && Qs.push(o);
  }
}
function Hs(e) {
  return setTimeout(e, 0);
}
function hf(e) {
  return clearTimeout(e);
}
hl && At(document, "touchmove", function(e) {
  (st.active || Si) && e.cancelable && e.preventDefault();
});
st.utils = {
  on: At,
  off: wt,
  css: Q,
  find: Ap,
  is: function(n, i) {
    return !!yn(n, i, n, !1);
  },
  extend: Tx,
  throttle: Cp,
  closest: yn,
  toggleClass: fe,
  clone: Uf,
  index: ae,
  nextTick: Hs,
  cancelNextTick: hf,
  detectDirection: Rp,
  getChild: Li
};
st.get = function(e) {
  return e[Re];
};
st.mount = function() {
  for (var e = arguments.length, n = new Array(e), i = 0; i < e; i++)
    n[i] = arguments[i];
  n[0].constructor === Array && (n = n[0]), n.forEach(function(o) {
    if (!o.prototype || !o.prototype.constructor)
      throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(o));
    o.utils && (st.utils = Gn(Gn({}, st.utils), o.utils)), Io.mount(o);
  });
};
st.create = function(e, n) {
  return new st(e, n);
};
st.version = Ex;
var ue = [], lo, df, pf = !1, qu, Vu, js, uo;
function Gx() {
  function e() {
    this.defaults = {
      scroll: !0,
      forceAutoScrollFallback: !1,
      scrollSensitivity: 30,
      scrollSpeed: 10,
      bubbleScroll: !0
    };
    for (var n in this)
      n.charAt(0) === "_" && typeof this[n] == "function" && (this[n] = this[n].bind(this));
  }
  return e.prototype = {
    dragStarted: function(i) {
      var o = i.originalEvent;
      this.sortable.nativeDraggable ? At(document, "dragover", this._handleAutoScroll) : this.options.supportPointer ? At(document, "pointermove", this._handleFallbackAutoScroll) : o.touches ? At(document, "touchmove", this._handleFallbackAutoScroll) : At(document, "mousemove", this._handleFallbackAutoScroll);
    },
    dragOverCompleted: function(i) {
      var o = i.originalEvent;
      !this.options.dragOverBubble && !o.rootEl && this._handleAutoScroll(o);
    },
    drop: function() {
      this.sortable.nativeDraggable ? wt(document, "dragover", this._handleAutoScroll) : (wt(document, "pointermove", this._handleFallbackAutoScroll), wt(document, "touchmove", this._handleFallbackAutoScroll), wt(document, "mousemove", this._handleFallbackAutoScroll)), ld(), $s(), Ax();
    },
    nulling: function() {
      js = df = lo = pf = uo = qu = Vu = null, ue.length = 0;
    },
    _handleFallbackAutoScroll: function(i) {
      this._handleAutoScroll(i, !0);
    },
    _handleAutoScroll: function(i, o) {
      var l = this, u = (i.touches ? i.touches[0] : i).clientX, a = (i.touches ? i.touches[0] : i).clientY, h = document.elementFromPoint(u, a);
      if (js = i, o || this.options.forceAutoScrollFallback || Oo || lr || vo) {
        ku(i, this.options, h, o);
        var g = Cr(h, !0);
        pf && (!uo || u !== qu || a !== Vu) && (uo && ld(), uo = setInterval(function() {
          var v = Cr(document.elementFromPoint(u, a), !0);
          v !== g && (g = v, $s()), ku(i, l.options, v, o);
        }, 10), qu = u, Vu = a);
      } else {
        if (!this.options.bubbleScroll || Cr(h, !0) === $n()) {
          $s();
          return;
        }
        ku(i, this.options, Cr(h, !1), !1);
      }
    }
  }, Dn(e, {
    pluginName: "scroll",
    initializeByDefault: !0
  });
}
function $s() {
  ue.forEach(function(e) {
    clearInterval(e.pid);
  }), ue = [];
}
function ld() {
  clearInterval(uo);
}
var ku = Cp(function(e, n, i, o) {
  if (n.scroll) {
    var l = (e.touches ? e.touches[0] : e).clientX, u = (e.touches ? e.touches[0] : e).clientY, a = n.scrollSensitivity, h = n.scrollSpeed, g = $n(), v = !1, _;
    df !== i && (df = i, $s(), lo = n.scroll, _ = n.scrollFn, lo === !0 && (lo = Cr(i, !0)));
    var m = 0, x = lo;
    do {
      var A = x, O = Xt(A), R = O.top, N = O.bottom, W = O.left, V = O.right, X = O.width, L = O.height, tt = void 0, Et = void 0, St = A.scrollWidth, Wt = A.scrollHeight, ft = Q(A), Ot = A.scrollLeft, Nt = A.scrollTop;
      A === g ? (tt = X < St && (ft.overflowX === "auto" || ft.overflowX === "scroll" || ft.overflowX === "visible"), Et = L < Wt && (ft.overflowY === "auto" || ft.overflowY === "scroll" || ft.overflowY === "visible")) : (tt = X < St && (ft.overflowX === "auto" || ft.overflowX === "scroll"), Et = L < Wt && (ft.overflowY === "auto" || ft.overflowY === "scroll"));
      var zt = tt && (Math.abs(V - l) <= a && Ot + X < St) - (Math.abs(W - l) <= a && !!Ot), Lt = Et && (Math.abs(N - u) <= a && Nt + L < Wt) - (Math.abs(R - u) <= a && !!Nt);
      if (!ue[m])
        for (var pe = 0; pe <= m; pe++)
          ue[pe] || (ue[pe] = {});
      (ue[m].vx != zt || ue[m].vy != Lt || ue[m].el !== A) && (ue[m].el = A, ue[m].vx = zt, ue[m].vy = Lt, clearInterval(ue[m].pid), (zt != 0 || Lt != 0) && (v = !0, ue[m].pid = setInterval((function() {
        o && this.layer === 0 && st.active._onTouchMove(js);
        var Ae = ue[this.layer].vy ? ue[this.layer].vy * h : 0, vt = ue[this.layer].vx ? ue[this.layer].vx * h : 0;
        typeof _ == "function" && _.call(st.dragged.parentNode[Re], vt, Ae, e, js, ue[this.layer].el) !== "continue" || Op(ue[this.layer].el, vt, Ae);
      }).bind({
        layer: m
      }), 24))), m++;
    } while (n.bubbleScroll && x !== g && (x = Cr(x, !1)));
    pf = v;
  }
}, 30), Np = function(n) {
  var i = n.originalEvent, o = n.putSortable, l = n.dragEl, u = n.activeSortable, a = n.dispatchSortableEvent, h = n.hideGhostForTarget, g = n.unhideGhostForTarget;
  if (i) {
    var v = o || u;
    h();
    var _ = i.changedTouches && i.changedTouches.length ? i.changedTouches[0] : i, m = document.elementFromPoint(_.clientX, _.clientY);
    g(), v && !v.el.contains(m) && (a("spill"), this.onSpill({
      dragEl: l,
      putSortable: o
    }));
  }
};
function Hf() {
}
Hf.prototype = {
  startIndex: null,
  dragStart: function(n) {
    var i = n.oldDraggableIndex;
    this.startIndex = i;
  },
  onSpill: function(n) {
    var i = n.dragEl, o = n.putSortable;
    this.sortable.captureAnimationState(), o && o.captureAnimationState();
    var l = Li(this.sortable.el, this.startIndex - (at.length ? at.indexOf(i) : 0), this.options);
    l ? this.sortable.el.insertBefore(i, l) : this.sortable.el.appendChild(i), this.sortable.animateAll(), o && o.animateAll();
  },
  drop: Np
};
Dn(Hf, {
  pluginName: "revertOnSpill"
});
function $f() {
}
$f.prototype = {
  onSpill: function(n) {
    var i = n.dragEl, o = n.putSortable, l = o || this.sortable;
    l.captureAnimationState(), i.parentNode && i.parentNode.removeChild(i), l.animateAll();
  },
  drop: Np
};
Dn($f, {
  pluginName: "removeOnSpill"
});
var at = [], on = [], eo, On, no = !1, Je = !1, Ei = !1, Kt, ro, Ds;
function Yx() {
  function e(n) {
    for (var i in this)
      i.charAt(0) === "_" && typeof this[i] == "function" && (this[i] = this[i].bind(this));
    n.options.avoidImplicitDeselect || (n.options.supportPointer ? At(document, "pointerup", this._deselectMultiDrag) : (At(document, "mouseup", this._deselectMultiDrag), At(document, "touchend", this._deselectMultiDrag))), At(document, "keydown", this._checkKeyDown), At(document, "keyup", this._checkKeyUp), this.defaults = {
      selectedClass: "sortable-selected",
      multiDragKey: null,
      avoidImplicitDeselect: !1,
      setData: function(l, u) {
        var a = "";
        at.length && On === n ? at.forEach(function(h, g) {
          a += (g ? ", " : "") + h.textContent;
        }) : a = u.textContent, l.setData("Text", a);
      }
    };
  }
  return e.prototype = {
    multiDragKeyDown: !1,
    isMultiDrag: !1,
    delayStartGlobal: function(i) {
      var o = i.dragEl;
      Kt = o;
    },
    delayEnded: function() {
      this.isMultiDrag = ~at.indexOf(Kt);
    },
    setupClone: function(i) {
      var o = i.sortable, l = i.cancel;
      if (this.isMultiDrag) {
        for (var u = 0; u < at.length; u++)
          on.push(Uf(at[u])), on[u].sortableIndex = at[u].sortableIndex, on[u].draggable = !1, on[u].style["will-change"] = "", fe(on[u], this.options.selectedClass, !1), at[u] === Kt && fe(on[u], this.options.chosenClass, !1);
        o._hideClone(), l();
      }
    },
    clone: function(i) {
      var o = i.sortable, l = i.rootEl, u = i.dispatchSortableEvent, a = i.cancel;
      this.isMultiDrag && (this.options.removeCloneOnHide || at.length && On === o && (ud(!0, l), u("clone"), a()));
    },
    showClone: function(i) {
      var o = i.cloneNowShown, l = i.rootEl, u = i.cancel;
      this.isMultiDrag && (ud(!1, l), on.forEach(function(a) {
        Q(a, "display", "");
      }), o(), Ds = !1, u());
    },
    hideClone: function(i) {
      var o = this;
      i.sortable;
      var l = i.cloneNowHidden, u = i.cancel;
      this.isMultiDrag && (on.forEach(function(a) {
        Q(a, "display", "none"), o.options.removeCloneOnHide && a.parentNode && a.parentNode.removeChild(a);
      }), l(), Ds = !0, u());
    },
    dragStartGlobal: function(i) {
      i.sortable, !this.isMultiDrag && On && On.multiDrag._deselectMultiDrag(), at.forEach(function(o) {
        o.sortableIndex = ae(o);
      }), at = at.sort(function(o, l) {
        return o.sortableIndex - l.sortableIndex;
      }), Ei = !0;
    },
    dragStarted: function(i) {
      var o = this, l = i.sortable;
      if (this.isMultiDrag) {
        if (this.options.sort && (l.captureAnimationState(), this.options.animation)) {
          at.forEach(function(a) {
            a !== Kt && Q(a, "position", "absolute");
          });
          var u = Xt(Kt, !1, !0, !0);
          at.forEach(function(a) {
            a !== Kt && rd(a, u);
          }), Je = !0, no = !0;
        }
        l.animateAll(function() {
          Je = !1, no = !1, o.options.animation && at.forEach(function(a) {
            $u(a);
          }), o.options.sort && Rs();
        });
      }
    },
    dragOver: function(i) {
      var o = i.target, l = i.completed, u = i.cancel;
      Je && ~at.indexOf(o) && (l(!1), u());
    },
    revert: function(i) {
      var o = i.fromSortable, l = i.rootEl, u = i.sortable, a = i.dragRect;
      at.length > 1 && (at.forEach(function(h) {
        u.addAnimationState({
          target: h,
          rect: Je ? Xt(h) : a
        }), $u(h), h.fromRect = a, o.removeAnimationState(h);
      }), Je = !1, Xx(!this.options.removeCloneOnHide, l));
    },
    dragOverCompleted: function(i) {
      var o = i.sortable, l = i.isOwner, u = i.insertion, a = i.activeSortable, h = i.parentEl, g = i.putSortable, v = this.options;
      if (u) {
        if (l && a._hideClone(), no = !1, v.animation && at.length > 1 && (Je || !l && !a.options.sort && !g)) {
          var _ = Xt(Kt, !1, !0, !0);
          at.forEach(function(x) {
            x !== Kt && (rd(x, _), h.appendChild(x));
          }), Je = !0;
        }
        if (!l)
          if (Je || Rs(), at.length > 1) {
            var m = Ds;
            a._showClone(o), a.options.animation && !Ds && m && on.forEach(function(x) {
              a.addAnimationState({
                target: x,
                rect: ro
              }), x.fromRect = ro, x.thisAnimationDuration = null;
            });
          } else
            a._showClone(o);
      }
    },
    dragOverAnimationCapture: function(i) {
      var o = i.dragRect, l = i.isOwner, u = i.activeSortable;
      if (at.forEach(function(h) {
        h.thisAnimationDuration = null;
      }), u.options.animation && !l && u.multiDrag.isMultiDrag) {
        ro = Dn({}, o);
        var a = Qr(Kt, !0);
        ro.top -= a.f, ro.left -= a.e;
      }
    },
    dragOverAnimationComplete: function() {
      Je && (Je = !1, Rs());
    },
    drop: function(i) {
      var o = i.originalEvent, l = i.rootEl, u = i.parentEl, a = i.sortable, h = i.dispatchSortableEvent, g = i.oldIndex, v = i.putSortable, _ = v || this.sortable;
      if (o) {
        var m = this.options, x = u.children;
        if (!Ei)
          if (m.multiDragKey && !this.multiDragKeyDown && this._deselectMultiDrag(), fe(Kt, m.selectedClass, !~at.indexOf(Kt)), ~at.indexOf(Kt))
            at.splice(at.indexOf(Kt), 1), eo = null, oo({
              sortable: a,
              rootEl: l,
              name: "deselect",
              targetEl: Kt,
              originalEvent: o
            });
          else {
            if (at.push(Kt), oo({
              sortable: a,
              rootEl: l,
              name: "select",
              targetEl: Kt,
              originalEvent: o
            }), o.shiftKey && eo && a.el.contains(eo)) {
              var A = ae(eo), O = ae(Kt);
              if (~A && ~O && A !== O) {
                var R, N;
                for (O > A ? (N = A, R = O) : (N = O, R = A + 1); N < R; N++)
                  ~at.indexOf(x[N]) || (fe(x[N], m.selectedClass, !0), at.push(x[N]), oo({
                    sortable: a,
                    rootEl: l,
                    name: "select",
                    targetEl: x[N],
                    originalEvent: o
                  }));
              }
            } else
              eo = Kt;
            On = _;
          }
        if (Ei && this.isMultiDrag) {
          if (Je = !1, (u[Re].options.sort || u !== l) && at.length > 1) {
            var W = Xt(Kt), V = ae(Kt, ":not(." + this.options.selectedClass + ")");
            if (!no && m.animation && (Kt.thisAnimationDuration = null), _.captureAnimationState(), !no && (m.animation && (Kt.fromRect = W, at.forEach(function(L) {
              if (L.thisAnimationDuration = null, L !== Kt) {
                var tt = Je ? Xt(L) : W;
                L.fromRect = tt, _.addAnimationState({
                  target: L,
                  rect: tt
                });
              }
            })), Rs(), at.forEach(function(L) {
              x[V] ? u.insertBefore(L, x[V]) : u.appendChild(L), V++;
            }), g === ae(Kt))) {
              var X = !1;
              at.forEach(function(L) {
                if (L.sortableIndex !== ae(L)) {
                  X = !0;
                  return;
                }
              }), X && (h("update"), h("sort"));
            }
            at.forEach(function(L) {
              $u(L);
            }), _.animateAll();
          }
          On = _;
        }
        (l === u || v && v.lastPutMode !== "clone") && on.forEach(function(L) {
          L.parentNode && L.parentNode.removeChild(L);
        });
      }
    },
    nullingGlobal: function() {
      this.isMultiDrag = Ei = !1, on.length = 0;
    },
    destroyGlobal: function() {
      this._deselectMultiDrag(), wt(document, "pointerup", this._deselectMultiDrag), wt(document, "mouseup", this._deselectMultiDrag), wt(document, "touchend", this._deselectMultiDrag), wt(document, "keydown", this._checkKeyDown), wt(document, "keyup", this._checkKeyUp);
    },
    _deselectMultiDrag: function(i) {
      if (!(typeof Ei < "u" && Ei) && On === this.sortable && !(i && yn(i.target, this.options.draggable, this.sortable.el, !1)) && !(i && i.button !== 0))
        for (; at.length; ) {
          var o = at[0];
          fe(o, this.options.selectedClass, !1), at.shift(), oo({
            sortable: this.sortable,
            rootEl: this.sortable.el,
            name: "deselect",
            targetEl: o,
            originalEvent: i
          });
        }
    },
    _checkKeyDown: function(i) {
      i.key === this.options.multiDragKey && (this.multiDragKeyDown = !0);
    },
    _checkKeyUp: function(i) {
      i.key === this.options.multiDragKey && (this.multiDragKeyDown = !1);
    }
  }, Dn(e, {
    // Static methods & properties
    pluginName: "multiDrag",
    utils: {
      /**
       * Selects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be selected
       */
      select: function(i) {
        var o = i.parentNode[Re];
        !o || !o.options.multiDrag || ~at.indexOf(i) || (On && On !== o && (On.multiDrag._deselectMultiDrag(), On = o), fe(i, o.options.selectedClass, !0), at.push(i));
      },
      /**
       * Deselects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be deselected
       */
      deselect: function(i) {
        var o = i.parentNode[Re], l = at.indexOf(i);
        !o || !o.options.multiDrag || !~l || (fe(i, o.options.selectedClass, !1), at.splice(l, 1));
      }
    },
    eventProperties: function() {
      var i = this, o = [], l = [];
      return at.forEach(function(u) {
        o.push({
          multiDragElement: u,
          index: u.sortableIndex
        });
        var a;
        Je && u !== Kt ? a = -1 : Je ? a = ae(u, ":not(." + i.options.selectedClass + ")") : a = ae(u), l.push({
          multiDragElement: u,
          index: a
        });
      }), {
        items: _x(at),
        clones: [].concat(on),
        oldIndicies: o,
        newIndicies: l
      };
    },
    optionListeners: {
      multiDragKey: function(i) {
        return i = i.toLowerCase(), i === "ctrl" ? i = "Control" : i.length > 1 && (i = i.charAt(0).toUpperCase() + i.substr(1)), i;
      }
    }
  });
}
function Xx(e, n) {
  at.forEach(function(i, o) {
    var l = n.children[i.sortableIndex + (e ? Number(o) : 0)];
    l ? n.insertBefore(i, l) : n.appendChild(i);
  });
}
function ud(e, n) {
  on.forEach(function(i, o) {
    var l = n.children[i.sortableIndex + (e ? Number(o) : 0)];
    l ? n.insertBefore(i, l) : n.appendChild(i);
  });
}
function Rs() {
  at.forEach(function(e) {
    e !== Kt && e.parentNode && e.parentNode.removeChild(e);
  });
}
st.mount(new Gx());
st.mount($f, Hf);
st.mount(new Yx());
function Ps(e) {
  return typeof e == "number" && !isNaN(e) ? e : typeof e == "string" && !isNaN(Number(e.trim())) ? parseFloat(e) : String(e);
}
let Ms;
function zx() {
  return {
    draggingEntityName: Ms,
    setDraggingEntityName: (o) => {
      Ms = o;
    },
    getDraggingEntityName: () => Ms,
    clearDraggingEntityName: () => {
      Ms = void 0;
    }
  };
}
const qx = ["data-id"], Vx = ["data-id"], kx = /* @__PURE__ */ Pf({
  __name: "SortableList",
  props: {
    items: {},
    sortableListId: {},
    sortableGroupName: {}
  },
  emits: ["sort"],
  setup(e, { emit: n }) {
    const i = n, o = e, l = Pe(null), { setDraggingEntityName: u, clearDraggingEntityName: a } = zx();
    let h;
    Ge(() => o.items, async () => {
      await Mi(), h && h.destroy(), g();
    }, {
      immediate: !0
    });
    function g() {
      l.value && (h = st.create(l.value, {
        disabled: !o.sortableListId,
        group: o.sortableGroupName || "shared",
        handle: ".sortable-handle",
        multiDrag: !0,
        revertOnSpill: !0,
        setData(v, _) {
          if (l.value) {
            o.sortableGroupName && u(o.sortableGroupName);
            const m = [...l.value.querySelectorAll(`.${h.option("selectedClass")}`)];
            v.setData("text/plain", JSON.stringify({
              taskIds: (m.length ? m : [_]).map((x) => Ps(x.dataset.id))
            }));
          }
        },
        onEnd: (v) => {
          if (a(), v.oldIndex === v.newIndex && v.from.dataset.listId === v.to.dataset.listId)
            return;
          const _ = Ps(v.to.dataset.id), m = v.items.length ? v.items : [v.item], x = m[0].previousElementSibling ? Ps(m[0].previousElementSibling.dataset.id) ?? null : null, A = m.map((O) => Ps(O.dataset.id)).filter((O) => O);
          _ && i("sort", _, x, A);
        }
      }));
    }
    return (v, _) => (Hn(), Jr("div", {
      ref_key: "sortableRef",
      ref: l,
      class: "tw-flex tw-flex-col",
      "data-id": o.sortableListId
    }, [
      (Hn(!0), Jr(un, null, O1(o.items, (m) => (Hn(), Jr("div", {
        key: m.uuid || m.id,
        "data-id": m.id
      }, [
        I1(v.$slots, "default", { item: m })
      ], 8, Vx))), 128))
    ], 8, qx));
  }
}), Jx = "live", Zx = 3, Qx = 1e3, jx = "ping", tS = 3e4, eS = 1e3, nS = async (e) => {
  var o;
  let n = null;
  const { data: i } = await e(`pocketlists.system.getWebsocketUrl?channel=${Jx}`).get().json();
  return (o = i.value) != null && o.data.url && (n = ox(i.value.data.url, {
    immediate: !1,
    heartbeat: {
      message: jx,
      interval: tS,
      pongTimeout: eS
    },
    autoReconnect: {
      retries: Zx,
      delay: Qx,
      onFailed() {
      }
    }
  })), n;
}, fd = (e) => {
  const n = Object.entries(e).filter(([i, o]) => o).reduce((i, o) => {
    const l = jw(o[1]);
    if (Array.isArray(l))
      for (const u of l)
        i.push([o[0], u.toString()]);
    else
      i.push([o[0], String(o[1])]);
    return i;
  }, []);
  return n.length ? `?${new URLSearchParams(n).toString()}` : "";
};
function rS(e) {
  const n = Pe([]), i = ir("useFetch"), o = ir("options"), l = Ep(e, [() => n.value.length]), u = async () => {
    const { data: x } = await i(`pocketlists.items.get${fd({ external_app_id: o.externalAppId, external_entity_type: o.externalEntityType, external_entity_id: o.externalEntityId })}`).get().json();
    n.value = x.value.data;
  }, a = async () => {
    const x = m();
    n.value.unshift(x), await Mi(), l.focusTaskById(x.id);
  }, h = async (x, A) => {
    const O = n.value.findIndex((R) => R.id === x.id);
    if (O > -1) {
      const R = A.newName ? m({ name: A.newName }) : m();
      n.value.splice(O + (A.currentName ? 1 : 0), 0, R), await Mi(), l.focusTaskById(R.id), A.newName && g(R);
    }
  }, g = async (x, A) => {
    if (typeof x.id == "string") {
      const { data: O } = await i("pocketlists.items.add", {
        method: "PUT",
        body: JSON.stringify([
          {
            ...x,
            external_links: [
              {
                app_id: o.externalAppId,
                entity_type: o.externalEntityType,
                entity_id: o.externalEntityId
              }
            ]
          }
        ])
      }).json();
      if (O.value.status_code === "ok" && Array.isArray(O.value.data)) {
        const R = O.value.data.find((N) => N.success && N.data.uuid === x.id).data.id;
        if (R) {
          const N = n.value.findIndex((W) => W.id === x.id);
          N > -1 && n.value.splice(N, 1, {
            ...x,
            id: R
          });
        }
      }
    } else
      await i("pocketlists.items.update", {
        method: "PATCH",
        body: JSON.stringify([{
          id: x.id,
          ...A
        }])
      }).json();
  }, v = async (x, A) => {
    const O = n.value.findIndex((R) => R.id === x.id);
    O > -1 && (n.value.splice(O, 1), A != null && A.silently || await i(`pocketlists.items.delete${fd({ "id[]": x.id })}`).delete().json());
  }, _ = (x, A) => {
    typeof x.id == "string" && !A.name && v(x, { silently: !0 });
  };
  function m(x) {
    const A = crypto.randomUUID();
    return {
      id: A,
      uuid: A,
      name: "",
      ...x
    };
  }
  return {
    items: n,
    fetchItems: u,
    onAdd: a,
    onInsert: h,
    onUpdate: g,
    onDelete: v,
    onBlur: _
  };
}
const iS = { class: "tw-p-8" }, oS = /* @__PURE__ */ Pf({
  __name: "App",
  setup(e) {
    const n = ir("useFetch"), i = ir("options"), o = Pe(), {
      items: l,
      fetchItems: u,
      onAdd: a,
      onInsert: h,
      onUpdate: g,
      onDelete: v,
      onBlur: _
    } = rS(o);
    return Zd("listNavigation", Ep(o, [() => l.value.length])), zd(async () => {
      u();
      const m = await nS(n);
      m && Ge(m.data, (x) => {
        console.log(x);
      });
    }), (m, x) => (Hn(), Jr("div", iS, [
      Bn("div", {
        class: "tw-mb-4",
        onClick: x[0] || (x[0] = //@ts-ignore
        (...A) => re(a) && re(a)(...A))
      }, " + New To-Do "),
      Bn("div", {
        ref_key: "navigatableRef",
        ref: o
      }, [
        En(kx, {
          "sortable-list-id": 666,
          "sortable-group-name": "tasks",
          items: re(l),
          onSort: () => {
          }
        }, {
          default: Kd(({ item: A }) => [
            En(dx, {
              task: A,
              sortable: !1,
              editable: !0,
              "addable-props": {
                external_app_id: re(i).externalAppId,
                external_entity_type: re(i).externalEntityType,
                external_entity_id: re(i).externalEntityId
              },
              onInsert: re(h),
              onUpdate: re(g),
              onDelete: re(v),
              onBlur: re(_)
            }, null, 8, ["task", "addable-props", "onInsert", "onUpdate", "onDelete", "onBlur"])
          ]),
          _: 1
        }, 8, ["items"])
      ], 512)
    ]));
  }
}), sS = {
  apiBaseUrl: "",
  apiToken: ""
}, lS = (e = {}) => {
  const n = {
    ...sS,
    ...e
  }, i = $E(oS);
  i.provide("options", n), i.provide("useFetch", nx({
    baseUrl: n.apiBaseUrl,
    options: {
      beforeFetch({ options: o }) {
        return o.headers = {
          ...o.headers,
          Authorization: `Bearer ${n.apiToken}`,
          "X-PL-API-Client": crypto.randomUUID()
        }, {
          options: o
        };
      }
    }
  })), i.mount("#app");
};
export {
  lS as init
};
