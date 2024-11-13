/**
* @vue/shared v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function hf(e) {
  const r = /* @__PURE__ */ Object.create(null);
  for (const i of e.split(","))
    r[i] = 1;
  return (i) => i in r;
}
const Ut = {}, Ti = [], Un = () => {
}, uw = () => !1, Zs = (e) => e.charCodeAt(0) === 111 && e.charCodeAt(1) === 110 && // uppercase letter
(e.charCodeAt(2) > 122 || e.charCodeAt(2) < 97), df = (e) => e.startsWith("onUpdate:"), Se = Object.assign, pf = (e, r) => {
  const i = e.indexOf(r);
  i > -1 && e.splice(i, 1);
}, fw = Object.prototype.hasOwnProperty, Pt = (e, r) => fw.call(e, r), pt = Array.isArray, Ai = (e) => Qs(e) === "[object Map]", sd = (e) => Qs(e) === "[object Set]", dt = (e) => typeof e == "function", ue = (e) => typeof e == "string", Or = (e) => typeof e == "symbol", qt = (e) => e !== null && typeof e == "object", ld = (e) => (qt(e) || dt(e)) && dt(e.then) && dt(e.catch), ud = Object.prototype.toString, Qs = (e) => ud.call(e), aw = (e) => Qs(e).slice(8, -1), fd = (e) => Qs(e) === "[object Object]", gf = (e) => ue(e) && e !== "NaN" && e[0] !== "-" && "" + parseInt(e, 10) === e, so = /* @__PURE__ */ hf(
  // the leading comma is intentional so empty string "" is also included
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), js = (e) => {
  const r = /* @__PURE__ */ Object.create(null);
  return (i) => r[i] || (r[i] = e(i));
}, cw = /-(\w)/g, Qr = js(
  (e) => e.replace(cw, (r, i) => i ? i.toUpperCase() : "")
), hw = /\B([A-Z])/g, Dr = js(
  (e) => e.replace(hw, "-$1").toLowerCase()
), ad = js((e) => e.charAt(0).toUpperCase() + e.slice(1)), xu = js(
  (e) => e ? `on${ad(e)}` : ""
), Cr = (e, r) => !Object.is(e, r), Eu = (e, ...r) => {
  for (let i = 0; i < e.length; i++)
    e[i](...r);
}, cd = (e, r, i, o = !1) => {
  Object.defineProperty(e, r, {
    configurable: !0,
    enumerable: !1,
    writable: o,
    value: i
  });
}, dw = (e) => {
  const r = parseFloat(e);
  return isNaN(r) ? e : r;
};
let ph;
const hd = () => ph || (ph = typeof globalThis < "u" ? globalThis : typeof self < "u" ? self : typeof window < "u" ? window : typeof global < "u" ? global : {});
function vf(e) {
  if (pt(e)) {
    const r = {};
    for (let i = 0; i < e.length; i++) {
      const o = e[i], l = ue(o) ? _w(o) : vf(o);
      if (l)
        for (const f in l)
          r[f] = l[f];
    }
    return r;
  } else if (ue(e) || qt(e))
    return e;
}
const pw = /;(?![^(]*\))/g, gw = /:([^]+)/, vw = /\/\*[^]*?\*\//g;
function _w(e) {
  const r = {};
  return e.replace(vw, "").split(pw).forEach((i) => {
    if (i) {
      const o = i.split(gw);
      o.length > 1 && (r[o[0].trim()] = o[1].trim());
    }
  }), r;
}
function tl(e) {
  let r = "";
  if (ue(e))
    r = e;
  else if (pt(e))
    for (let i = 0; i < e.length; i++) {
      const o = tl(e[i]);
      o && (r += o + " ");
    }
  else if (qt(e))
    for (const i in e)
      e[i] && (r += i + " ");
  return r.trim();
}
const mw = "itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly", bw = /* @__PURE__ */ hf(mw);
function dd(e) {
  return !!e || e === "";
}
const pd = (e) => !!(e && e.__v_isRef === !0), qu = (e) => ue(e) ? e : e == null ? "" : pt(e) || qt(e) && (e.toString === ud || !dt(e.toString)) ? pd(e) ? qu(e.value) : JSON.stringify(e, gd, 2) : String(e), gd = (e, r) => pd(r) ? gd(e, r.value) : Ai(r) ? {
  [`Map(${r.size})`]: [...r.entries()].reduce(
    (i, [o, l], f) => (i[Su(o, f) + " =>"] = l, i),
    {}
  )
} : sd(r) ? {
  [`Set(${r.size})`]: [...r.values()].map((i) => Su(i))
} : Or(r) ? Su(r) : qt(r) && !pt(r) && !fd(r) ? String(r) : r, Su = (e, r = "") => {
  var i;
  return (
    // Symbol.description in es2019+ so we need to cast here to pass
    // the lib: es2016 check
    Or(e) ? `Symbol(${(i = e.description) != null ? i : r})` : e
  );
};
/**
* @vue/reactivity v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let We;
class yw {
  constructor(r = !1) {
    this.detached = r, this._active = !0, this.effects = [], this.cleanups = [], this._isPaused = !1, this.parent = We, !r && We && (this.index = (We.scopes || (We.scopes = [])).push(
      this
    ) - 1);
  }
  get active() {
    return this._active;
  }
  pause() {
    if (this._active) {
      this._isPaused = !0;
      let r, i;
      if (this.scopes)
        for (r = 0, i = this.scopes.length; r < i; r++)
          this.scopes[r].pause();
      for (r = 0, i = this.effects.length; r < i; r++)
        this.effects[r].pause();
    }
  }
  /**
   * Resumes the effect scope, including all child scopes and effects.
   */
  resume() {
    if (this._active && this._isPaused) {
      this._isPaused = !1;
      let r, i;
      if (this.scopes)
        for (r = 0, i = this.scopes.length; r < i; r++)
          this.scopes[r].resume();
      for (r = 0, i = this.effects.length; r < i; r++)
        this.effects[r].resume();
    }
  }
  run(r) {
    if (this._active) {
      const i = We;
      try {
        return We = this, r();
      } finally {
        We = i;
      }
    }
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  on() {
    We = this;
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  off() {
    We = this.parent;
  }
  stop(r) {
    if (this._active) {
      let i, o;
      for (i = 0, o = this.effects.length; i < o; i++)
        this.effects[i].stop();
      for (i = 0, o = this.cleanups.length; i < o; i++)
        this.cleanups[i]();
      if (this.scopes)
        for (i = 0, o = this.scopes.length; i < o; i++)
          this.scopes[i].stop(!0);
      if (!this.detached && this.parent && !r) {
        const l = this.parent.scopes.pop();
        l && l !== this && (this.parent.scopes[this.index] = l, l.index = this.index);
      }
      this.parent = void 0, this._active = !1;
    }
  }
}
function vd() {
  return We;
}
function ww(e, r = !1) {
  We && We.cleanups.push(e);
}
let Nt;
const Tu = /* @__PURE__ */ new WeakSet();
class _d {
  constructor(r) {
    this.fn = r, this.deps = void 0, this.depsTail = void 0, this.flags = 5, this.next = void 0, this.cleanup = void 0, this.scheduler = void 0, We && We.active && We.effects.push(this);
  }
  pause() {
    this.flags |= 64;
  }
  resume() {
    this.flags & 64 && (this.flags &= -65, Tu.has(this) && (Tu.delete(this), this.trigger()));
  }
  /**
   * @internal
   */
  notify() {
    this.flags & 2 && !(this.flags & 32) || this.flags & 8 || bd(this);
  }
  run() {
    if (!(this.flags & 1))
      return this.fn();
    this.flags |= 2, gh(this), yd(this);
    const r = Nt, i = Cn;
    Nt = this, Cn = !0;
    try {
      return this.fn();
    } finally {
      wd(this), Nt = r, Cn = i, this.flags &= -3;
    }
  }
  stop() {
    if (this.flags & 1) {
      for (let r = this.deps; r; r = r.nextDep)
        bf(r);
      this.deps = this.depsTail = void 0, gh(this), this.onStop && this.onStop(), this.flags &= -2;
    }
  }
  trigger() {
    this.flags & 64 ? Tu.add(this) : this.scheduler ? this.scheduler() : this.runIfDirty();
  }
  /**
   * @internal
   */
  runIfDirty() {
    Vu(this) && this.run();
  }
  get dirty() {
    return Vu(this);
  }
}
let md = 0, lo;
function bd(e) {
  e.flags |= 8, e.next = lo, lo = e;
}
function _f() {
  md++;
}
function mf() {
  if (--md > 0)
    return;
  let e;
  for (; lo; ) {
    let r = lo;
    for (lo = void 0; r; ) {
      const i = r.next;
      if (r.next = void 0, r.flags &= -9, r.flags & 1)
        try {
          r.trigger();
        } catch (o) {
          e || (e = o);
        }
      r = i;
    }
  }
  if (e)
    throw e;
}
function yd(e) {
  for (let r = e.deps; r; r = r.nextDep)
    r.version = -1, r.prevActiveLink = r.dep.activeLink, r.dep.activeLink = r;
}
function wd(e) {
  let r, i = e.depsTail, o = i;
  for (; o; ) {
    const l = o.prevDep;
    o.version === -1 ? (o === i && (i = l), bf(o), xw(o)) : r = o, o.dep.activeLink = o.prevActiveLink, o.prevActiveLink = void 0, o = l;
  }
  e.deps = r, e.depsTail = i;
}
function Vu(e) {
  for (let r = e.deps; r; r = r.nextDep)
    if (r.dep.version !== r.version || r.dep.computed && (xd(r.dep.computed) || r.dep.version !== r.version))
      return !0;
  return !!e._dirty;
}
function xd(e) {
  if (e.flags & 4 && !(e.flags & 16) || (e.flags &= -17, e.globalVersion === _o))
    return;
  e.globalVersion = _o;
  const r = e.dep;
  if (e.flags |= 2, r.version > 0 && !e.isSSR && e.deps && !Vu(e)) {
    e.flags &= -3;
    return;
  }
  const i = Nt, o = Cn;
  Nt = e, Cn = !0;
  try {
    yd(e);
    const l = e.fn(e._value);
    (r.version === 0 || Cr(l, e._value)) && (e._value = l, r.version++);
  } catch (l) {
    throw r.version++, l;
  } finally {
    Nt = i, Cn = o, wd(e), e.flags &= -3;
  }
}
function bf(e) {
  const { dep: r, prevSub: i, nextSub: o } = e;
  if (i && (i.nextSub = o, e.prevSub = void 0), o && (o.prevSub = i, e.nextSub = void 0), r.subs === e && (r.subs = i), !r.subs && r.computed) {
    r.computed.flags &= -5;
    for (let l = r.computed.deps; l; l = l.nextDep)
      bf(l);
  }
}
function xw(e) {
  const { prevDep: r, nextDep: i } = e;
  r && (r.nextDep = i, e.prevDep = void 0), i && (i.prevDep = r, e.nextDep = void 0);
}
let Cn = !0;
const Ed = [];
function Rr() {
  Ed.push(Cn), Cn = !1;
}
function Pr() {
  const e = Ed.pop();
  Cn = e === void 0 ? !0 : e;
}
function gh(e) {
  const { cleanup: r } = e;
  if (e.cleanup = void 0, r) {
    const i = Nt;
    Nt = void 0;
    try {
      r();
    } finally {
      Nt = i;
    }
  }
}
let _o = 0;
class Ew {
  constructor(r, i) {
    this.sub = r, this.dep = i, this.version = i.version, this.nextDep = this.prevDep = this.nextSub = this.prevSub = this.prevActiveLink = void 0;
  }
}
class el {
  constructor(r) {
    this.computed = r, this.version = 0, this.activeLink = void 0, this.subs = void 0;
  }
  track(r) {
    if (!Nt || !Cn || Nt === this.computed)
      return;
    let i = this.activeLink;
    if (i === void 0 || i.sub !== Nt)
      i = this.activeLink = new Ew(Nt, this), Nt.deps ? (i.prevDep = Nt.depsTail, Nt.depsTail.nextDep = i, Nt.depsTail = i) : Nt.deps = Nt.depsTail = i, Nt.flags & 4 && Sd(i);
    else if (i.version === -1 && (i.version = this.version, i.nextDep)) {
      const o = i.nextDep;
      o.prevDep = i.prevDep, i.prevDep && (i.prevDep.nextDep = o), i.prevDep = Nt.depsTail, i.nextDep = void 0, Nt.depsTail.nextDep = i, Nt.depsTail = i, Nt.deps === i && (Nt.deps = o);
    }
    return i;
  }
  trigger(r) {
    this.version++, _o++, this.notify(r);
  }
  notify(r) {
    _f();
    try {
      for (let i = this.subs; i; i = i.prevSub)
        i.sub.notify() && i.sub.dep.notify();
    } finally {
      mf();
    }
  }
}
function Sd(e) {
  const r = e.dep.computed;
  if (r && !e.dep.subs) {
    r.flags |= 20;
    for (let o = r.deps; o; o = o.nextDep)
      Sd(o);
  }
  const i = e.dep.subs;
  i !== e && (e.prevSub = i, i && (i.nextSub = e)), e.dep.subs = e;
}
const Us = /* @__PURE__ */ new WeakMap(), Vr = Symbol(
  ""
), ku = Symbol(
  ""
), mo = Symbol(
  ""
);
function Re(e, r, i) {
  if (Cn && Nt) {
    let o = Us.get(e);
    o || Us.set(e, o = /* @__PURE__ */ new Map());
    let l = o.get(i);
    l || o.set(i, l = new el()), l.track();
  }
}
function rr(e, r, i, o, l, f) {
  const a = Us.get(e);
  if (!a) {
    _o++;
    return;
  }
  const h = (g) => {
    g && g.trigger();
  };
  if (_f(), r === "clear")
    a.forEach(h);
  else {
    const g = pt(e), v = g && gf(i);
    if (g && i === "length") {
      const b = Number(o);
      a.forEach((_, T) => {
        (T === "length" || T === mo || !Or(T) && T >= b) && h(_);
      });
    } else
      switch (i !== void 0 && h(a.get(i)), v && h(a.get(mo)), r) {
        case "add":
          g ? v && h(a.get("length")) : (h(a.get(Vr)), Ai(e) && h(a.get(ku)));
          break;
        case "delete":
          g || (h(a.get(Vr)), Ai(e) && h(a.get(ku)));
          break;
        case "set":
          Ai(e) && h(a.get(Vr));
          break;
      }
  }
  mf();
}
function Sw(e, r) {
  var i;
  return (i = Us.get(e)) == null ? void 0 : i.get(r);
}
function vi(e) {
  const r = Dt(e);
  return r === e ? r : (Re(r, "iterate", mo), bn(e) ? r : r.map(Oe));
}
function nl(e) {
  return Re(e = Dt(e), "iterate", mo), e;
}
const Tw = {
  __proto__: null,
  [Symbol.iterator]() {
    return Au(this, Symbol.iterator, Oe);
  },
  concat(...e) {
    return vi(this).concat(
      ...e.map((r) => pt(r) ? vi(r) : r)
    );
  },
  entries() {
    return Au(this, "entries", (e) => (e[1] = Oe(e[1]), e));
  },
  every(e, r) {
    return er(this, "every", e, r, void 0, arguments);
  },
  filter(e, r) {
    return er(this, "filter", e, r, (i) => i.map(Oe), arguments);
  },
  find(e, r) {
    return er(this, "find", e, r, Oe, arguments);
  },
  findIndex(e, r) {
    return er(this, "findIndex", e, r, void 0, arguments);
  },
  findLast(e, r) {
    return er(this, "findLast", e, r, Oe, arguments);
  },
  findLastIndex(e, r) {
    return er(this, "findLastIndex", e, r, void 0, arguments);
  },
  // flat, flatMap could benefit from ARRAY_ITERATE but are not straight-forward to implement
  forEach(e, r) {
    return er(this, "forEach", e, r, void 0, arguments);
  },
  includes(...e) {
    return Cu(this, "includes", e);
  },
  indexOf(...e) {
    return Cu(this, "indexOf", e);
  },
  join(e) {
    return vi(this).join(e);
  },
  // keys() iterator only reads `length`, no optimisation required
  lastIndexOf(...e) {
    return Cu(this, "lastIndexOf", e);
  },
  map(e, r) {
    return er(this, "map", e, r, void 0, arguments);
  },
  pop() {
    return ki(this, "pop");
  },
  push(...e) {
    return ki(this, "push", e);
  },
  reduce(e, ...r) {
    return vh(this, "reduce", e, r);
  },
  reduceRight(e, ...r) {
    return vh(this, "reduceRight", e, r);
  },
  shift() {
    return ki(this, "shift");
  },
  // slice could use ARRAY_ITERATE but also seems to beg for range tracking
  some(e, r) {
    return er(this, "some", e, r, void 0, arguments);
  },
  splice(...e) {
    return ki(this, "splice", e);
  },
  toReversed() {
    return vi(this).toReversed();
  },
  toSorted(e) {
    return vi(this).toSorted(e);
  },
  toSpliced(...e) {
    return vi(this).toSpliced(...e);
  },
  unshift(...e) {
    return ki(this, "unshift", e);
  },
  values() {
    return Au(this, "values", Oe);
  }
};
function Au(e, r, i) {
  const o = nl(e), l = o[r]();
  return o !== e && !bn(e) && (l._next = l.next, l.next = () => {
    const f = l._next();
    return f.value && (f.value = i(f.value)), f;
  }), l;
}
const Aw = Array.prototype;
function er(e, r, i, o, l, f) {
  const a = nl(e), h = a !== e && !bn(e), g = a[r];
  if (g !== Aw[r]) {
    const _ = g.apply(e, f);
    return h ? Oe(_) : _;
  }
  let v = i;
  a !== e && (h ? v = function(_, T) {
    return i.call(this, Oe(_), T, e);
  } : i.length > 2 && (v = function(_, T) {
    return i.call(this, _, T, e);
  }));
  const b = g.call(a, v, o);
  return h && l ? l(b) : b;
}
function vh(e, r, i, o) {
  const l = nl(e);
  let f = i;
  return l !== e && (bn(e) ? i.length > 3 && (f = function(a, h, g) {
    return i.call(this, a, h, g, e);
  }) : f = function(a, h, g) {
    return i.call(this, a, Oe(h), g, e);
  }), l[r](f, ...o);
}
function Cu(e, r, i) {
  const o = Dt(e);
  Re(o, "iterate", mo);
  const l = o[r](...i);
  return (l === -1 || l === !1) && Sf(i[0]) ? (i[0] = Dt(i[0]), o[r](...i)) : l;
}
function ki(e, r, i = []) {
  Rr(), _f();
  const o = Dt(e)[r].apply(e, i);
  return mf(), Pr(), o;
}
const Cw = /* @__PURE__ */ hf("__proto__,__v_isRef,__isVue"), Td = new Set(
  /* @__PURE__ */ Object.getOwnPropertyNames(Symbol).filter((e) => e !== "arguments" && e !== "caller").map((e) => Symbol[e]).filter(Or)
);
function Iw(e) {
  Or(e) || (e = String(e));
  const r = Dt(this);
  return Re(r, "has", e), r.hasOwnProperty(e);
}
class Ad {
  constructor(r = !1, i = !1) {
    this._isReadonly = r, this._isShallow = i;
  }
  get(r, i, o) {
    const l = this._isReadonly, f = this._isShallow;
    if (i === "__v_isReactive")
      return !l;
    if (i === "__v_isReadonly")
      return l;
    if (i === "__v_isShallow")
      return f;
    if (i === "__v_raw")
      return o === (l ? f ? $w : Dd : f ? Od : Id).get(r) || // receiver is not the reactive proxy, but has the same prototype
      // this means the receiver is a user proxy of the reactive proxy
      Object.getPrototypeOf(r) === Object.getPrototypeOf(o) ? r : void 0;
    const a = pt(r);
    if (!l) {
      let g;
      if (a && (g = Tw[i]))
        return g;
      if (i === "hasOwnProperty")
        return Iw;
    }
    const h = Reflect.get(
      r,
      i,
      // if this is a proxy wrapping a ref, return methods using the raw ref
      // as receiver so that we don't have to call `toRaw` on the ref in all
      // its class methods
      le(r) ? r : o
    );
    return (Or(i) ? Td.has(i) : Cw(i)) || (l || Re(r, "get", i), f) ? h : le(h) ? a && gf(i) ? h : h.value : qt(h) ? l ? Ri(h) : xf(h) : h;
  }
}
class Cd extends Ad {
  constructor(r = !1) {
    super(!1, r);
  }
  set(r, i, o, l) {
    let f = r[i];
    if (!this._isShallow) {
      const g = jr(f);
      if (!bn(o) && !jr(o) && (f = Dt(f), o = Dt(o)), !pt(r) && le(f) && !le(o))
        return g ? !1 : (f.value = o, !0);
    }
    const a = pt(r) && gf(i) ? Number(i) < r.length : Pt(r, i), h = Reflect.set(
      r,
      i,
      o,
      le(r) ? r : l
    );
    return r === Dt(l) && (a ? Cr(o, f) && rr(r, "set", i, o) : rr(r, "add", i, o)), h;
  }
  deleteProperty(r, i) {
    const o = Pt(r, i);
    r[i];
    const l = Reflect.deleteProperty(r, i);
    return l && o && rr(r, "delete", i, void 0), l;
  }
  has(r, i) {
    const o = Reflect.has(r, i);
    return (!Or(i) || !Td.has(i)) && Re(r, "has", i), o;
  }
  ownKeys(r) {
    return Re(
      r,
      "iterate",
      pt(r) ? "length" : Vr
    ), Reflect.ownKeys(r);
  }
}
class Ow extends Ad {
  constructor(r = !1) {
    super(!0, r);
  }
  set(r, i) {
    return !0;
  }
  deleteProperty(r, i) {
    return !0;
  }
}
const Dw = /* @__PURE__ */ new Cd(), Rw = /* @__PURE__ */ new Ow(), Pw = /* @__PURE__ */ new Cd(!0);
const yf = (e) => e, rl = (e) => Reflect.getPrototypeOf(e);
function vs(e, r, i = !1, o = !1) {
  e = e.__v_raw;
  const l = Dt(e), f = Dt(r);
  i || (Cr(r, f) && Re(l, "get", r), Re(l, "get", f));
  const { has: a } = rl(l), h = o ? yf : i ? Tf : Oe;
  if (a.call(l, r))
    return h(e.get(r));
  if (a.call(l, f))
    return h(e.get(f));
  e !== l && e.get(r);
}
function _s(e, r = !1) {
  const i = this.__v_raw, o = Dt(i), l = Dt(e);
  return r || (Cr(e, l) && Re(o, "has", e), Re(o, "has", l)), e === l ? i.has(e) : i.has(e) || i.has(l);
}
function ms(e, r = !1) {
  return e = e.__v_raw, !r && Re(Dt(e), "iterate", Vr), Reflect.get(e, "size", e);
}
function _h(e, r = !1) {
  !r && !bn(e) && !jr(e) && (e = Dt(e));
  const i = Dt(this);
  return rl(i).has.call(i, e) || (i.add(e), rr(i, "add", e, e)), this;
}
function mh(e, r, i = !1) {
  !i && !bn(r) && !jr(r) && (r = Dt(r));
  const o = Dt(this), { has: l, get: f } = rl(o);
  let a = l.call(o, e);
  a || (e = Dt(e), a = l.call(o, e));
  const h = f.call(o, e);
  return o.set(e, r), a ? Cr(r, h) && rr(o, "set", e, r) : rr(o, "add", e, r), this;
}
function bh(e) {
  const r = Dt(this), { has: i, get: o } = rl(r);
  let l = i.call(r, e);
  l || (e = Dt(e), l = i.call(r, e)), o && o.call(r, e);
  const f = r.delete(e);
  return l && rr(r, "delete", e, void 0), f;
}
function yh() {
  const e = Dt(this), r = e.size !== 0, i = e.clear();
  return r && rr(e, "clear", void 0, void 0), i;
}
function bs(e, r) {
  return function(o, l) {
    const f = this, a = f.__v_raw, h = Dt(a), g = r ? yf : e ? Tf : Oe;
    return !e && Re(h, "iterate", Vr), a.forEach((v, b) => o.call(l, g(v), g(b), f));
  };
}
function ys(e, r, i) {
  return function(...o) {
    const l = this.__v_raw, f = Dt(l), a = Ai(f), h = e === "entries" || e === Symbol.iterator && a, g = e === "keys" && a, v = l[e](...o), b = i ? yf : r ? Tf : Oe;
    return !r && Re(
      f,
      "iterate",
      g ? ku : Vr
    ), {
      // iterator protocol
      next() {
        const { value: _, done: T } = v.next();
        return T ? { value: _, done: T } : {
          value: h ? [b(_[0]), b(_[1])] : b(_),
          done: T
        };
      },
      // iterable protocol
      [Symbol.iterator]() {
        return this;
      }
    };
  };
}
function yr(e) {
  return function(...r) {
    return e === "delete" ? !1 : e === "clear" ? void 0 : this;
  };
}
function Mw() {
  const e = {
    get(f) {
      return vs(this, f);
    },
    get size() {
      return ms(this);
    },
    has: _s,
    add: _h,
    set: mh,
    delete: bh,
    clear: yh,
    forEach: bs(!1, !1)
  }, r = {
    get(f) {
      return vs(this, f, !1, !0);
    },
    get size() {
      return ms(this);
    },
    has: _s,
    add(f) {
      return _h.call(this, f, !0);
    },
    set(f, a) {
      return mh.call(this, f, a, !0);
    },
    delete: bh,
    clear: yh,
    forEach: bs(!1, !0)
  }, i = {
    get(f) {
      return vs(this, f, !0);
    },
    get size() {
      return ms(this, !0);
    },
    has(f) {
      return _s.call(this, f, !0);
    },
    add: yr("add"),
    set: yr("set"),
    delete: yr("delete"),
    clear: yr("clear"),
    forEach: bs(!0, !1)
  }, o = {
    get(f) {
      return vs(this, f, !0, !0);
    },
    get size() {
      return ms(this, !0);
    },
    has(f) {
      return _s.call(this, f, !0);
    },
    add: yr("add"),
    set: yr("set"),
    delete: yr("delete"),
    clear: yr("clear"),
    forEach: bs(!0, !0)
  };
  return [
    "keys",
    "values",
    "entries",
    Symbol.iterator
  ].forEach((f) => {
    e[f] = ys(f, !1, !1), i[f] = ys(f, !0, !1), r[f] = ys(f, !1, !0), o[f] = ys(
      f,
      !0,
      !0
    );
  }), [
    e,
    i,
    r,
    o
  ];
}
const [
  Fw,
  Nw,
  Lw,
  Bw
] = /* @__PURE__ */ Mw();
function wf(e, r) {
  const i = r ? e ? Bw : Lw : e ? Nw : Fw;
  return (o, l, f) => l === "__v_isReactive" ? !e : l === "__v_isReadonly" ? e : l === "__v_raw" ? o : Reflect.get(
    Pt(i, l) && l in o ? i : o,
    l,
    f
  );
}
const Ww = {
  get: /* @__PURE__ */ wf(!1, !1)
}, Uw = {
  get: /* @__PURE__ */ wf(!1, !0)
}, Hw = {
  get: /* @__PURE__ */ wf(!0, !1)
};
const Id = /* @__PURE__ */ new WeakMap(), Od = /* @__PURE__ */ new WeakMap(), Dd = /* @__PURE__ */ new WeakMap(), $w = /* @__PURE__ */ new WeakMap();
function Kw(e) {
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
function Gw(e) {
  return e.__v_skip || !Object.isExtensible(e) ? 0 : Kw(aw(e));
}
function xf(e) {
  return jr(e) ? e : Ef(
    e,
    !1,
    Dw,
    Ww,
    Id
  );
}
function Yw(e) {
  return Ef(
    e,
    !1,
    Pw,
    Uw,
    Od
  );
}
function Ri(e) {
  return Ef(
    e,
    !0,
    Rw,
    Hw,
    Dd
  );
}
function Ef(e, r, i, o, l) {
  if (!qt(e) || e.__v_raw && !(r && e.__v_isReactive))
    return e;
  const f = l.get(e);
  if (f)
    return f;
  const a = Gw(e);
  if (a === 0)
    return e;
  const h = new Proxy(
    e,
    a === 2 ? o : i
  );
  return l.set(e, h), h;
}
function Ci(e) {
  return jr(e) ? Ci(e.__v_raw) : !!(e && e.__v_isReactive);
}
function jr(e) {
  return !!(e && e.__v_isReadonly);
}
function bn(e) {
  return !!(e && e.__v_isShallow);
}
function Sf(e) {
  return e ? !!e.__v_raw : !1;
}
function Dt(e) {
  const r = e && e.__v_raw;
  return r ? Dt(r) : e;
}
function zw(e) {
  return !Pt(e, "__v_skip") && Object.isExtensible(e) && cd(e, "__v_skip", !0), e;
}
const Oe = (e) => qt(e) ? xf(e) : e, Tf = (e) => qt(e) ? Ri(e) : e;
function le(e) {
  return e ? e.__v_isRef === !0 : !1;
}
function mn(e) {
  return Rd(e, !1);
}
function Iu(e) {
  return Rd(e, !0);
}
function Rd(e, r) {
  return le(e) ? e : new Xw(e, r);
}
class Xw {
  constructor(r, i) {
    this.dep = new el(), this.__v_isRef = !0, this.__v_isShallow = !1, this._rawValue = i ? r : Dt(r), this._value = i ? r : Oe(r), this.__v_isShallow = i;
  }
  get value() {
    return this.dep.track(), this._value;
  }
  set value(r) {
    const i = this._rawValue, o = this.__v_isShallow || bn(r) || jr(r);
    r = o ? r : Dt(r), Cr(r, i) && (this._rawValue = r, this._value = o ? r : Oe(r), this.dep.trigger());
  }
}
function Ve(e) {
  return le(e) ? e.value : e;
}
function qw(e) {
  return dt(e) ? e() : Ve(e);
}
const Vw = {
  get: (e, r, i) => r === "__v_raw" ? e : Ve(Reflect.get(e, r, i)),
  set: (e, r, i, o) => {
    const l = e[r];
    return le(l) && !le(i) ? (l.value = i, !0) : Reflect.set(e, r, i, o);
  }
};
function Pd(e) {
  return Ci(e) ? e : new Proxy(e, Vw);
}
class kw {
  constructor(r) {
    this.__v_isRef = !0, this._value = void 0;
    const i = this.dep = new el(), { get: o, set: l } = r(i.track.bind(i), i.trigger.bind(i));
    this._get = o, this._set = l;
  }
  get value() {
    return this._value = this._get();
  }
  set value(r) {
    this._set(r);
  }
}
function Jw(e) {
  return new kw(e);
}
class Zw {
  constructor(r, i, o) {
    this._object = r, this._key = i, this._defaultValue = o, this.__v_isRef = !0, this._value = void 0;
  }
  get value() {
    const r = this._object[this._key];
    return this._value = r === void 0 ? this._defaultValue : r;
  }
  set value(r) {
    this._object[this._key] = r;
  }
  get dep() {
    return Sw(Dt(this._object), this._key);
  }
}
class Qw {
  constructor(r) {
    this._getter = r, this.__v_isRef = !0, this.__v_isReadonly = !0, this._value = void 0;
  }
  get value() {
    return this._value = this._getter();
  }
}
function jw(e, r, i) {
  return le(e) ? e : dt(e) ? new Qw(e) : qt(e) && arguments.length > 1 ? t1(e, r, i) : mn(e);
}
function t1(e, r, i) {
  const o = e[r];
  return le(o) ? o : new Zw(e, r, i);
}
class e1 {
  constructor(r, i, o) {
    this.fn = r, this.setter = i, this._value = void 0, this.dep = new el(this), this.__v_isRef = !0, this.deps = void 0, this.depsTail = void 0, this.flags = 16, this.globalVersion = _o - 1, this.effect = this, this.__v_isReadonly = !i, this.isSSR = o;
  }
  /**
   * @internal
   */
  notify() {
    if (this.flags |= 16, !(this.flags & 8) && // avoid infinite self recursion
    Nt !== this)
      return bd(this), !0;
  }
  get value() {
    const r = this.dep.track();
    return xd(this), r && (r.version = this.dep.version), this._value;
  }
  set value(r) {
    this.setter && this.setter(r);
  }
}
function n1(e, r, i = !1) {
  let o, l;
  return dt(e) ? o = e : (o = e.get, l = e.set), new e1(o, l, i);
}
const ws = {}, Hs = /* @__PURE__ */ new WeakMap();
let Xr;
function r1(e, r = !1, i = Xr) {
  if (i) {
    let o = Hs.get(i);
    o || Hs.set(i, o = []), o.push(e);
  }
}
function i1(e, r, i = Ut) {
  const { immediate: o, deep: l, once: f, scheduler: a, augmentJob: h, call: g } = i, v = (U) => l ? U : bn(U) || l === !1 || l === 0 ? Sr(U, 1) : Sr(U);
  let b, _, T, C, O = !1, F = !1;
  if (le(e) ? (_ = () => e.value, O = bn(e)) : Ci(e) ? (_ = () => v(e), O = !0) : pt(e) ? (F = !0, O = e.some((U) => Ci(U) || bn(U)), _ = () => e.map((U) => {
    if (le(U))
      return U.value;
    if (Ci(U))
      return v(U);
    if (dt(U))
      return g ? g(U, 2) : U();
  })) : dt(e) ? r ? _ = g ? () => g(e, 2) : e : _ = () => {
    if (T) {
      Rr();
      try {
        T();
      } finally {
        Pr();
      }
    }
    const U = Xr;
    Xr = b;
    try {
      return g ? g(e, 3, [C]) : e(C);
    } finally {
      Xr = U;
    }
  } : _ = Un, r && l) {
    const U = _, nt = l === !0 ? 1 / 0 : l;
    _ = () => Sr(U(), nt);
  }
  const N = vd(), K = () => {
    b.stop(), N && pf(N.effects, b);
  };
  if (f && r) {
    const U = r;
    r = (...nt) => {
      U(...nt), K();
    };
  }
  let J = F ? new Array(e.length).fill(ws) : ws;
  const V = (U) => {
    if (!(!(b.flags & 1) || !b.dirty && !U))
      if (r) {
        const nt = b.run();
        if (l || O || (F ? nt.some((Tt, It) => Cr(Tt, J[It])) : Cr(nt, J))) {
          T && T();
          const Tt = Xr;
          Xr = b;
          try {
            const It = [
              nt,
              // pass undefined as the old value when it's changed for the first time
              J === ws ? void 0 : F && J[0] === ws ? [] : J,
              C
            ];
            g ? g(r, 3, It) : (
              // @ts-expect-error
              r(...It)
            ), J = nt;
          } finally {
            Xr = Tt;
          }
        }
      } else
        b.run();
  };
  return h && h(V), b = new _d(_), b.scheduler = a ? () => a(V, !1) : V, C = (U) => r1(U, !1, b), T = b.onStop = () => {
    const U = Hs.get(b);
    if (U) {
      if (g)
        g(U, 4);
      else
        for (const nt of U)
          nt();
      Hs.delete(b);
    }
  }, r ? o ? V(!0) : J = b.run() : a ? a(V.bind(null, !0), !0) : b.run(), K.pause = b.pause.bind(b), K.resume = b.resume.bind(b), K.stop = K, K;
}
function Sr(e, r = 1 / 0, i) {
  if (r <= 0 || !qt(e) || e.__v_skip || (i = i || /* @__PURE__ */ new Set(), i.has(e)))
    return e;
  if (i.add(e), r--, le(e))
    Sr(e.value, r, i);
  else if (pt(e))
    for (let o = 0; o < e.length; o++)
      Sr(e[o], r, i);
  else if (sd(e) || Ai(e))
    e.forEach((o) => {
      Sr(o, r, i);
    });
  else if (fd(e)) {
    for (const o in e)
      Sr(e[o], r, i);
    for (const o of Object.getOwnPropertySymbols(e))
      Object.prototype.propertyIsEnumerable.call(e, o) && Sr(e[o], r, i);
  }
  return e;
}
/**
* @vue/runtime-core v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
function Eo(e, r, i, o) {
  try {
    return o ? e(...o) : e();
  } catch (l) {
    il(l, r, i);
  }
}
function Kn(e, r, i, o) {
  if (dt(e)) {
    const l = Eo(e, r, i, o);
    return l && ld(l) && l.catch((f) => {
      il(f, r, i);
    }), l;
  }
  if (pt(e)) {
    const l = [];
    for (let f = 0; f < e.length; f++)
      l.push(Kn(e[f], r, i, o));
    return l;
  }
}
function il(e, r, i, o = !0) {
  const l = r ? r.vnode : null, { errorHandler: f, throwUnhandledErrorInProduction: a } = r && r.appContext.config || Ut;
  if (r) {
    let h = r.parent;
    const g = r.proxy, v = `https://vuejs.org/error-reference/#runtime-${i}`;
    for (; h; ) {
      const b = h.ec;
      if (b) {
        for (let _ = 0; _ < b.length; _++)
          if (b[_](e, g, v) === !1)
            return;
      }
      h = h.parent;
    }
    if (f) {
      Rr(), Eo(f, null, 10, [
        e,
        g,
        v
      ]), Pr();
      return;
    }
  }
  o1(e, i, l, o, a);
}
function o1(e, r, i, o = !0, l = !1) {
  if (l)
    throw e;
  console.error(e);
}
let bo = !1, Ju = !1;
const Ue = [];
let Nn = 0;
const Ii = [];
let wr = null, xi = 0;
const Md = /* @__PURE__ */ Promise.resolve();
let Af = null;
function yo(e) {
  const r = Af || Md;
  return e ? r.then(this ? e.bind(this) : e) : r;
}
function s1(e) {
  let r = bo ? Nn + 1 : 0, i = Ue.length;
  for (; r < i; ) {
    const o = r + i >>> 1, l = Ue[o], f = wo(l);
    f < e || f === e && l.flags & 2 ? r = o + 1 : i = o;
  }
  return r;
}
function Cf(e) {
  if (!(e.flags & 1)) {
    const r = wo(e), i = Ue[Ue.length - 1];
    !i || // fast path when the job id is larger than the tail
    !(e.flags & 2) && r >= wo(i) ? Ue.push(e) : Ue.splice(s1(r), 0, e), e.flags |= 1, Fd();
  }
}
function Fd() {
  !bo && !Ju && (Ju = !0, Af = Md.then(Ld));
}
function l1(e) {
  pt(e) ? Ii.push(...e) : wr && e.id === -1 ? wr.splice(xi + 1, 0, e) : e.flags & 1 || (Ii.push(e), e.flags |= 1), Fd();
}
function wh(e, r, i = bo ? Nn + 1 : 0) {
  for (; i < Ue.length; i++) {
    const o = Ue[i];
    if (o && o.flags & 2) {
      if (e && o.id !== e.uid)
        continue;
      Ue.splice(i, 1), i--, o.flags & 4 && (o.flags &= -2), o(), o.flags &= -2;
    }
  }
}
function Nd(e) {
  if (Ii.length) {
    const r = [...new Set(Ii)].sort(
      (i, o) => wo(i) - wo(o)
    );
    if (Ii.length = 0, wr) {
      wr.push(...r);
      return;
    }
    for (wr = r, xi = 0; xi < wr.length; xi++) {
      const i = wr[xi];
      i.flags & 4 && (i.flags &= -2), i.flags & 8 || i(), i.flags &= -2;
    }
    wr = null, xi = 0;
  }
}
const wo = (e) => e.id == null ? e.flags & 2 ? -1 : 1 / 0 : e.id;
function Ld(e) {
  Ju = !1, bo = !0;
  try {
    for (Nn = 0; Nn < Ue.length; Nn++) {
      const r = Ue[Nn];
      r && !(r.flags & 8) && (r.flags & 4 && (r.flags &= -2), Eo(
        r,
        r.i,
        r.i ? 15 : 14
      ), r.flags &= -2);
    }
  } finally {
    for (; Nn < Ue.length; Nn++) {
      const r = Ue[Nn];
      r && (r.flags &= -2);
    }
    Nn = 0, Ue.length = 0, Nd(), bo = !1, Af = null, (Ue.length || Ii.length) && Ld();
  }
}
let ke = null, Bd = null;
function $s(e) {
  const r = ke;
  return ke = e, Bd = e && e.type.__scopeId || null, r;
}
function Wd(e, r = ke, i) {
  if (!r || e._n)
    return e;
  const o = (...l) => {
    o._d && Oh(-1);
    const f = $s(r);
    let a;
    try {
      a = e(...l);
    } finally {
      $s(f), o._d && Oh(1);
    }
    return a;
  };
  return o._n = !0, o._c = !0, o._d = !0, o;
}
function Kr(e, r, i, o) {
  const l = e.dirs, f = r && r.dirs;
  for (let a = 0; a < l.length; a++) {
    const h = l[a];
    f && (h.oldValue = f[a].value);
    let g = h.dir[o];
    g && (Rr(), Kn(g, i, 8, [
      e.el,
      h,
      e,
      r
    ]), Pr());
  }
}
const u1 = Symbol("_vte"), f1 = (e) => e.__isTeleport;
function If(e, r) {
  e.shapeFlag & 6 && e.component ? (e.transition = r, If(e.component.subTree, r)) : e.shapeFlag & 128 ? (e.ssContent.transition = r.clone(e.ssContent), e.ssFallback.transition = r.clone(e.ssFallback)) : e.transition = r;
}
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function Of(e, r) {
  return dt(e) ? (
    // #8236: extend call and options.name access are considered side-effects
    // by Rollup, so we have to wrap it in a pure-annotated IIFE.
    Se({ name: e.name }, r, { setup: e })
  ) : e;
}
function Ud(e) {
  e.ids = [e.ids[0] + e.ids[2]++ + "-", 0, 0];
}
function Zu(e, r, i, o, l = !1) {
  if (pt(e)) {
    e.forEach(
      (O, F) => Zu(
        O,
        r && (pt(r) ? r[F] : r),
        i,
        o,
        l
      )
    );
    return;
  }
  if (Oi(o) && !l)
    return;
  const f = o.shapeFlag & 4 ? Mf(o.component) : o.el, a = l ? null : f, { i: h, r: g } = e, v = r && r.r, b = h.refs === Ut ? h.refs = {} : h.refs, _ = h.setupState, T = Dt(_), C = _ === Ut ? () => !1 : (O) => Pt(T, O);
  if (v != null && v !== g && (ue(v) ? (b[v] = null, C(v) && (_[v] = null)) : le(v) && (v.value = null)), dt(g))
    Eo(g, h, 12, [a, b]);
  else {
    const O = ue(g), F = le(g);
    if (O || F) {
      const N = () => {
        if (e.f) {
          const K = O ? C(g) ? _[g] : b[g] : g.value;
          l ? pt(K) && pf(K, f) : pt(K) ? K.includes(f) || K.push(f) : O ? (b[g] = [f], C(g) && (_[g] = b[g])) : (g.value = [f], e.k && (b[e.k] = g.value));
        } else
          O ? (b[g] = a, C(g) && (_[g] = a)) : F && (g.value = a, e.k && (b[e.k] = a));
      };
      a ? (N.id = -1, rn(N, i)) : N();
    }
  }
}
const Oi = (e) => !!e.type.__asyncLoader, Hd = (e) => e.type.__isKeepAlive;
function a1(e, r) {
  $d(e, "a", r);
}
function c1(e, r) {
  $d(e, "da", r);
}
function $d(e, r, i = He) {
  const o = e.__wdc || (e.__wdc = () => {
    let l = i;
    for (; l; ) {
      if (l.isDeactivated)
        return;
      l = l.parent;
    }
    return e();
  });
  if (ol(r, o, i), i) {
    let l = i.parent;
    for (; l && l.parent; )
      Hd(l.parent.vnode) && h1(o, r, i, l), l = l.parent;
  }
}
function h1(e, r, i, o) {
  const l = ol(
    r,
    e,
    o,
    !0
    /* prepend */
  );
  Kd(() => {
    pf(o[r], l);
  }, i);
}
function ol(e, r, i = He, o = !1) {
  if (i) {
    const l = i[e] || (i[e] = []), f = r.__weh || (r.__weh = (...a) => {
      Rr();
      const h = So(i), g = Kn(r, i, e, a);
      return h(), Pr(), g;
    });
    return o ? l.unshift(f) : l.push(f), f;
  }
}
const or = (e) => (r, i = He) => {
  (!ul || e === "sp") && ol(e, (...o) => r(...o), i);
}, d1 = or("bm"), p1 = or("m"), g1 = or(
  "bu"
), v1 = or("u"), _1 = or(
  "bum"
), Kd = or("um"), m1 = or(
  "sp"
), b1 = or("rtg"), y1 = or("rtc");
function w1(e, r = He) {
  ol("ec", e, r);
}
const x1 = Symbol.for("v-ndc");
function E1(e, r, i, o) {
  let l;
  const f = i && i[o], a = pt(e);
  if (a || ue(e)) {
    const h = a && Ci(e);
    let g = !1;
    h && (g = !bn(e), e = nl(e)), l = new Array(e.length);
    for (let v = 0, b = e.length; v < b; v++)
      l[v] = r(
        g ? Oe(e[v]) : e[v],
        v,
        void 0,
        f && f[v]
      );
  } else if (typeof e == "number") {
    l = new Array(e);
    for (let h = 0; h < e; h++)
      l[h] = r(h + 1, h, void 0, f && f[h]);
  } else if (qt(e))
    if (e[Symbol.iterator])
      l = Array.from(
        e,
        (h, g) => r(h, g, void 0, f && f[g])
      );
    else {
      const h = Object.keys(e);
      l = new Array(h.length);
      for (let g = 0, v = h.length; g < v; g++) {
        const b = h[g];
        l[g] = r(e[b], b, g, f && f[g]);
      }
    }
  else
    l = [];
  return i && (i[o] = l), l;
}
function S1(e, r, i = {}, o, l) {
  if (ke.ce || ke.parent && Oi(ke.parent) && ke.parent.ce)
    return r !== "default" && (i.name = r), Hn(), nf(
      sn,
      null,
      [yn("slot", i, o && o())],
      64
    );
  let f = e[r];
  f && f._c && (f._d = !1), Hn();
  const a = f && Gd(f(i)), h = nf(
    sn,
    {
      key: (i.key || // slot content array of a dynamic conditional slot may have a branch
      // key attached in the `createSlots` helper, respect that
      a && a.key || `_${r}`) + // #7256 force differentiate fallback content from actual content
      (!a && o ? "_fb" : "")
    },
    a || (o ? o() : []),
    a && e._ === 1 ? 64 : -2
  );
  return !l && h.scopeId && (h.slotScopeIds = [h.scopeId + "-s"]), f && f._c && (f._d = !0), h;
}
function Gd(e) {
  return e.some((r) => fp(r) ? !(r.type === Ir || r.type === sn && !Gd(r.children)) : !0) ? e : null;
}
const Qu = (e) => e ? hp(e) ? Mf(e) : Qu(e.parent) : null, uo = (
  // Move PURE marker to new line to workaround compiler discarding it
  // due to type annotation
  /* @__PURE__ */ Se(/* @__PURE__ */ Object.create(null), {
    $: (e) => e,
    $el: (e) => e.vnode.el,
    $data: (e) => e.data,
    $props: (e) => e.props,
    $attrs: (e) => e.attrs,
    $slots: (e) => e.slots,
    $refs: (e) => e.refs,
    $parent: (e) => Qu(e.parent),
    $root: (e) => Qu(e.root),
    $host: (e) => e.ce,
    $emit: (e) => e.emit,
    $options: (e) => Df(e),
    $forceUpdate: (e) => e.f || (e.f = () => {
      Cf(e.update);
    }),
    $nextTick: (e) => e.n || (e.n = yo.bind(e.proxy)),
    $watch: (e) => z1.bind(e)
  })
), Ou = (e, r) => e !== Ut && !e.__isScriptSetup && Pt(e, r), T1 = {
  get({ _: e }, r) {
    if (r === "__v_skip")
      return !0;
    const { ctx: i, setupState: o, data: l, props: f, accessCache: a, type: h, appContext: g } = e;
    let v;
    if (r[0] !== "$") {
      const C = a[r];
      if (C !== void 0)
        switch (C) {
          case 1:
            return o[r];
          case 2:
            return l[r];
          case 4:
            return i[r];
          case 3:
            return f[r];
        }
      else {
        if (Ou(o, r))
          return a[r] = 1, o[r];
        if (l !== Ut && Pt(l, r))
          return a[r] = 2, l[r];
        if (
          // only cache other properties when instance has declared (thus stable)
          // props
          (v = e.propsOptions[0]) && Pt(v, r)
        )
          return a[r] = 3, f[r];
        if (i !== Ut && Pt(i, r))
          return a[r] = 4, i[r];
        ju && (a[r] = 0);
      }
    }
    const b = uo[r];
    let _, T;
    if (b)
      return r === "$attrs" && Re(e.attrs, "get", ""), b(e);
    if (
      // css module (injected by vue-loader)
      (_ = h.__cssModules) && (_ = _[r])
    )
      return _;
    if (i !== Ut && Pt(i, r))
      return a[r] = 4, i[r];
    if (
      // global properties
      T = g.config.globalProperties, Pt(T, r)
    )
      return T[r];
  },
  set({ _: e }, r, i) {
    const { data: o, setupState: l, ctx: f } = e;
    return Ou(l, r) ? (l[r] = i, !0) : o !== Ut && Pt(o, r) ? (o[r] = i, !0) : Pt(e.props, r) || r[0] === "$" && r.slice(1) in e ? !1 : (f[r] = i, !0);
  },
  has({
    _: { data: e, setupState: r, accessCache: i, ctx: o, appContext: l, propsOptions: f }
  }, a) {
    let h;
    return !!i[a] || e !== Ut && Pt(e, a) || Ou(r, a) || (h = f[0]) && Pt(h, a) || Pt(o, a) || Pt(uo, a) || Pt(l.config.globalProperties, a);
  },
  defineProperty(e, r, i) {
    return i.get != null ? e._.accessCache[r] = 0 : Pt(i, "value") && this.set(e, r, i.value, null), Reflect.defineProperty(e, r, i);
  }
};
function xh(e) {
  return pt(e) ? e.reduce(
    (r, i) => (r[i] = null, r),
    {}
  ) : e;
}
let ju = !0;
function A1(e) {
  const r = Df(e), i = e.proxy, o = e.ctx;
  ju = !1, r.beforeCreate && Eh(r.beforeCreate, e, "bc");
  const {
    // state
    data: l,
    computed: f,
    methods: a,
    watch: h,
    provide: g,
    inject: v,
    // lifecycle
    created: b,
    beforeMount: _,
    mounted: T,
    beforeUpdate: C,
    updated: O,
    activated: F,
    deactivated: N,
    beforeDestroy: K,
    beforeUnmount: J,
    destroyed: V,
    unmounted: U,
    render: nt,
    renderTracked: Tt,
    renderTriggered: It,
    errorCaptured: Kt,
    serverPrefetch: Lt,
    // public API
    expose: te,
    inheritAttrs: fe,
    // assets
    components: ge,
    directives: Gt,
    filters: he
  } = r;
  if (v && C1(v, o, null), a)
    for (const lt in a) {
      const rt = a[lt];
      dt(rt) && (o[lt] = rt.bind(i));
    }
  if (l) {
    const lt = l.call(i, i);
    qt(lt) && (e.data = xf(lt));
  }
  if (ju = !0, f)
    for (const lt in f) {
      const rt = f[lt], ve = dt(rt) ? rt.bind(i, i) : dt(rt.get) ? rt.get.bind(i, i) : Un, de = !dt(rt) && dt(rt.set) ? rt.set.bind(i) : Un, Yt = Ff({
        get: ve,
        set: de
      });
      Object.defineProperty(o, lt, {
        enumerable: !0,
        configurable: !0,
        get: () => Yt.value,
        set: (ee) => Yt.value = ee
      });
    }
  if (h)
    for (const lt in h)
      Yd(h[lt], o, i, lt);
  if (g) {
    const lt = dt(g) ? g.call(i) : g;
    Reflect.ownKeys(lt).forEach((rt) => {
      Xd(rt, lt[rt]);
    });
  }
  b && Eh(b, e, "c");
  function gt(lt, rt) {
    pt(rt) ? rt.forEach((ve) => lt(ve.bind(i))) : rt && lt(rt.bind(i));
  }
  if (gt(d1, _), gt(p1, T), gt(g1, C), gt(v1, O), gt(a1, F), gt(c1, N), gt(w1, Kt), gt(y1, Tt), gt(b1, It), gt(_1, J), gt(Kd, U), gt(m1, Lt), pt(te))
    if (te.length) {
      const lt = e.exposed || (e.exposed = {});
      te.forEach((rt) => {
        Object.defineProperty(lt, rt, {
          get: () => i[rt],
          set: (ve) => i[rt] = ve
        });
      });
    } else
      e.exposed || (e.exposed = {});
  nt && e.render === Un && (e.render = nt), fe != null && (e.inheritAttrs = fe), ge && (e.components = ge), Gt && (e.directives = Gt), Lt && Ud(e);
}
function C1(e, r, i = Un) {
  pt(e) && (e = tf(e));
  for (const o in e) {
    const l = e[o];
    let f;
    qt(l) ? "default" in l ? f = kr(
      l.from || o,
      l.default,
      !0
    ) : f = kr(l.from || o) : f = kr(l), le(f) ? Object.defineProperty(r, o, {
      enumerable: !0,
      configurable: !0,
      get: () => f.value,
      set: (a) => f.value = a
    }) : r[o] = f;
  }
}
function Eh(e, r, i) {
  Kn(
    pt(e) ? e.map((o) => o.bind(r.proxy)) : e.bind(r.proxy),
    r,
    i
  );
}
function Yd(e, r, i, o) {
  let l = o.includes(".") ? op(i, o) : () => i[o];
  if (ue(e)) {
    const f = r[e];
    dt(f) && In(l, f);
  } else if (dt(e))
    In(l, e.bind(i));
  else if (qt(e))
    if (pt(e))
      e.forEach((f) => Yd(f, r, i, o));
    else {
      const f = dt(e.handler) ? e.handler.bind(i) : r[e.handler];
      dt(f) && In(l, f, e);
    }
}
function Df(e) {
  const r = e.type, { mixins: i, extends: o } = r, {
    mixins: l,
    optionsCache: f,
    config: { optionMergeStrategies: a }
  } = e.appContext, h = f.get(r);
  let g;
  return h ? g = h : !l.length && !i && !o ? g = r : (g = {}, l.length && l.forEach(
    (v) => Ks(g, v, a, !0)
  ), Ks(g, r, a)), qt(r) && f.set(r, g), g;
}
function Ks(e, r, i, o = !1) {
  const { mixins: l, extends: f } = r;
  f && Ks(e, f, i, !0), l && l.forEach(
    (a) => Ks(e, a, i, !0)
  );
  for (const a in r)
    if (!(o && a === "expose")) {
      const h = I1[a] || i && i[a];
      e[a] = h ? h(e[a], r[a]) : r[a];
    }
  return e;
}
const I1 = {
  data: Sh,
  props: Th,
  emits: Th,
  // objects
  methods: eo,
  computed: eo,
  // lifecycle
  beforeCreate: Le,
  created: Le,
  beforeMount: Le,
  mounted: Le,
  beforeUpdate: Le,
  updated: Le,
  beforeDestroy: Le,
  beforeUnmount: Le,
  destroyed: Le,
  unmounted: Le,
  activated: Le,
  deactivated: Le,
  errorCaptured: Le,
  serverPrefetch: Le,
  // assets
  components: eo,
  directives: eo,
  // watch
  watch: D1,
  // provide / inject
  provide: Sh,
  inject: O1
};
function Sh(e, r) {
  return r ? e ? function() {
    return Se(
      dt(e) ? e.call(this, this) : e,
      dt(r) ? r.call(this, this) : r
    );
  } : r : e;
}
function O1(e, r) {
  return eo(tf(e), tf(r));
}
function tf(e) {
  if (pt(e)) {
    const r = {};
    for (let i = 0; i < e.length; i++)
      r[e[i]] = e[i];
    return r;
  }
  return e;
}
function Le(e, r) {
  return e ? [...new Set([].concat(e, r))] : r;
}
function eo(e, r) {
  return e ? Se(/* @__PURE__ */ Object.create(null), e, r) : r;
}
function Th(e, r) {
  return e ? pt(e) && pt(r) ? [.../* @__PURE__ */ new Set([...e, ...r])] : Se(
    /* @__PURE__ */ Object.create(null),
    xh(e),
    xh(r ?? {})
  ) : r;
}
function D1(e, r) {
  if (!e)
    return r;
  if (!r)
    return e;
  const i = Se(/* @__PURE__ */ Object.create(null), e);
  for (const o in r)
    i[o] = Le(e[o], r[o]);
  return i;
}
function zd() {
  return {
    app: null,
    config: {
      isNativeTag: uw,
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
let R1 = 0;
function P1(e, r) {
  return function(o, l = null) {
    dt(o) || (o = Se({}, o)), l != null && !qt(l) && (l = null);
    const f = zd(), a = /* @__PURE__ */ new WeakSet(), h = [];
    let g = !1;
    const v = f.app = {
      _uid: R1++,
      _component: o,
      _props: l,
      _container: null,
      _context: f,
      _instance: null,
      version: cx,
      get config() {
        return f.config;
      },
      set config(b) {
      },
      use(b, ..._) {
        return a.has(b) || (b && dt(b.install) ? (a.add(b), b.install(v, ..._)) : dt(b) && (a.add(b), b(v, ..._))), v;
      },
      mixin(b) {
        return f.mixins.includes(b) || f.mixins.push(b), v;
      },
      component(b, _) {
        return _ ? (f.components[b] = _, v) : f.components[b];
      },
      directive(b, _) {
        return _ ? (f.directives[b] = _, v) : f.directives[b];
      },
      mount(b, _, T) {
        if (!g) {
          const C = v._ceVNode || yn(o, l);
          return C.appContext = f, T === !0 ? T = "svg" : T === !1 && (T = void 0), _ && r ? r(C, b) : e(C, b, T), g = !0, v._container = b, b.__vue_app__ = v, Mf(C.component);
        }
      },
      onUnmount(b) {
        h.push(b);
      },
      unmount() {
        g && (Kn(
          h,
          v._instance,
          16
        ), e(null, v._container), delete v._container.__vue_app__);
      },
      provide(b, _) {
        return f.provides[b] = _, v;
      },
      runWithContext(b) {
        const _ = Di;
        Di = v;
        try {
          return b();
        } finally {
          Di = _;
        }
      }
    };
    return v;
  };
}
let Di = null;
function Xd(e, r) {
  if (He) {
    let i = He.provides;
    const o = He.parent && He.parent.provides;
    o === i && (i = He.provides = Object.create(o)), i[e] = r;
  }
}
function kr(e, r, i = !1) {
  const o = He || ke;
  if (o || Di) {
    const l = Di ? Di._context.provides : o ? o.parent == null ? o.vnode.appContext && o.vnode.appContext.provides : o.parent.provides : void 0;
    if (l && e in l)
      return l[e];
    if (arguments.length > 1)
      return i && dt(r) ? r.call(o && o.proxy) : r;
  }
}
const qd = {}, Vd = () => Object.create(qd), kd = (e) => Object.getPrototypeOf(e) === qd;
function M1(e, r, i, o = !1) {
  const l = {}, f = Vd();
  e.propsDefaults = /* @__PURE__ */ Object.create(null), Jd(e, r, l, f);
  for (const a in e.propsOptions[0])
    a in l || (l[a] = void 0);
  i ? e.props = o ? l : Yw(l) : e.type.props ? e.props = l : e.props = f, e.attrs = f;
}
function F1(e, r, i, o) {
  const {
    props: l,
    attrs: f,
    vnode: { patchFlag: a }
  } = e, h = Dt(l), [g] = e.propsOptions;
  let v = !1;
  if (
    // always force full diff in dev
    // - #1942 if hmr is enabled with sfc component
    // - vite#872 non-sfc component used by sfc component
    (o || a > 0) && !(a & 16)
  ) {
    if (a & 8) {
      const b = e.vnode.dynamicProps;
      for (let _ = 0; _ < b.length; _++) {
        let T = b[_];
        if (sl(e.emitsOptions, T))
          continue;
        const C = r[T];
        if (g)
          if (Pt(f, T))
            C !== f[T] && (f[T] = C, v = !0);
          else {
            const O = Qr(T);
            l[O] = ef(
              g,
              h,
              O,
              C,
              e,
              !1
            );
          }
        else
          C !== f[T] && (f[T] = C, v = !0);
      }
    }
  } else {
    Jd(e, r, l, f) && (v = !0);
    let b;
    for (const _ in h)
      (!r || // for camelCase
      !Pt(r, _) && // it's possible the original props was passed in as kebab-case
      // and converted to camelCase (#955)
      ((b = Dr(_)) === _ || !Pt(r, b))) && (g ? i && // for camelCase
      (i[_] !== void 0 || // for kebab-case
      i[b] !== void 0) && (l[_] = ef(
        g,
        h,
        _,
        void 0,
        e,
        !0
      )) : delete l[_]);
    if (f !== h)
      for (const _ in f)
        (!r || !Pt(r, _)) && (delete f[_], v = !0);
  }
  v && rr(e.attrs, "set", "");
}
function Jd(e, r, i, o) {
  const [l, f] = e.propsOptions;
  let a = !1, h;
  if (r)
    for (let g in r) {
      if (so(g))
        continue;
      const v = r[g];
      let b;
      l && Pt(l, b = Qr(g)) ? !f || !f.includes(b) ? i[b] = v : (h || (h = {}))[b] = v : sl(e.emitsOptions, g) || (!(g in o) || v !== o[g]) && (o[g] = v, a = !0);
    }
  if (f) {
    const g = Dt(i), v = h || Ut;
    for (let b = 0; b < f.length; b++) {
      const _ = f[b];
      i[_] = ef(
        l,
        g,
        _,
        v[_],
        e,
        !Pt(v, _)
      );
    }
  }
  return a;
}
function ef(e, r, i, o, l, f) {
  const a = e[i];
  if (a != null) {
    const h = Pt(a, "default");
    if (h && o === void 0) {
      const g = a.default;
      if (a.type !== Function && !a.skipFactory && dt(g)) {
        const { propsDefaults: v } = l;
        if (i in v)
          o = v[i];
        else {
          const b = So(l);
          o = v[i] = g.call(
            null,
            r
          ), b();
        }
      } else
        o = g;
      l.ce && l.ce._setProp(i, o);
    }
    a[
      0
      /* shouldCast */
    ] && (f && !h ? o = !1 : a[
      1
      /* shouldCastTrue */
    ] && (o === "" || o === Dr(i)) && (o = !0));
  }
  return o;
}
const N1 = /* @__PURE__ */ new WeakMap();
function Zd(e, r, i = !1) {
  const o = i ? N1 : r.propsCache, l = o.get(e);
  if (l)
    return l;
  const f = e.props, a = {}, h = [];
  let g = !1;
  if (!dt(e)) {
    const b = (_) => {
      g = !0;
      const [T, C] = Zd(_, r, !0);
      Se(a, T), C && h.push(...C);
    };
    !i && r.mixins.length && r.mixins.forEach(b), e.extends && b(e.extends), e.mixins && e.mixins.forEach(b);
  }
  if (!f && !g)
    return qt(e) && o.set(e, Ti), Ti;
  if (pt(f))
    for (let b = 0; b < f.length; b++) {
      const _ = Qr(f[b]);
      Ah(_) && (a[_] = Ut);
    }
  else if (f)
    for (const b in f) {
      const _ = Qr(b);
      if (Ah(_)) {
        const T = f[b], C = a[_] = pt(T) || dt(T) ? { type: T } : Se({}, T), O = C.type;
        let F = !1, N = !0;
        if (pt(O))
          for (let K = 0; K < O.length; ++K) {
            const J = O[K], V = dt(J) && J.name;
            if (V === "Boolean") {
              F = !0;
              break;
            } else
              V === "String" && (N = !1);
          }
        else
          F = dt(O) && O.name === "Boolean";
        C[
          0
          /* shouldCast */
        ] = F, C[
          1
          /* shouldCastTrue */
        ] = N, (F || Pt(C, "default")) && h.push(_);
      }
    }
  const v = [a, h];
  return qt(e) && o.set(e, v), v;
}
function Ah(e) {
  return e[0] !== "$" && !so(e);
}
const Qd = (e) => e[0] === "_" || e === "$stable", Rf = (e) => pt(e) ? e.map(Bn) : [Bn(e)], L1 = (e, r, i) => {
  if (r._n)
    return r;
  const o = Wd((...l) => Rf(r(...l)), i);
  return o._c = !1, o;
}, jd = (e, r, i) => {
  const o = e._ctx;
  for (const l in e) {
    if (Qd(l))
      continue;
    const f = e[l];
    if (dt(f))
      r[l] = L1(l, f, o);
    else if (f != null) {
      const a = Rf(f);
      r[l] = () => a;
    }
  }
}, tp = (e, r) => {
  const i = Rf(r);
  e.slots.default = () => i;
}, ep = (e, r, i) => {
  for (const o in r)
    (i || o !== "_") && (e[o] = r[o]);
}, B1 = (e, r, i) => {
  const o = e.slots = Vd();
  if (e.vnode.shapeFlag & 32) {
    const l = r._;
    l ? (ep(o, r, i), i && cd(o, "_", l, !0)) : jd(r, o);
  } else
    r && tp(e, r);
}, W1 = (e, r, i) => {
  const { vnode: o, slots: l } = e;
  let f = !0, a = Ut;
  if (o.shapeFlag & 32) {
    const h = r._;
    h ? i && h === 1 ? f = !1 : ep(l, r, i) : (f = !r.$stable, jd(r, l)), a = r;
  } else
    r && (tp(e, r), a = { default: 1 });
  if (f)
    for (const h in l)
      !Qd(h) && a[h] == null && delete l[h];
}, rn = Q1;
function U1(e) {
  return H1(e);
}
function H1(e, r) {
  const i = hd();
  i.__VUE__ = !0;
  const {
    insert: o,
    remove: l,
    patchProp: f,
    createElement: a,
    createText: h,
    createComment: g,
    setText: v,
    setElementText: b,
    parentNode: _,
    nextSibling: T,
    setScopeId: C = Un,
    insertStaticContent: O
  } = e, F = (y, x, I, H = null, M = null, W = null, z = void 0, G = null, $ = !!x.dynamicChildren) => {
    if (y === x)
      return;
    y && !Ji(y, x) && (H = ti(y), ee(y, M, W, !0), y = null), x.patchFlag === -2 && ($ = !1, x.dynamicChildren = null);
    const { type: B, ref: tt, shapeFlag: q } = x;
    switch (B) {
      case ll:
        N(y, x, I, H);
        break;
      case Ir:
        K(y, x, I, H);
        break;
      case Pu:
        y == null && J(x, I, H, z);
        break;
      case sn:
        ge(
          y,
          x,
          I,
          H,
          M,
          W,
          z,
          G,
          $
        );
        break;
      default:
        q & 1 ? nt(
          y,
          x,
          I,
          H,
          M,
          W,
          z,
          G,
          $
        ) : q & 6 ? Gt(
          y,
          x,
          I,
          H,
          M,
          W,
          z,
          G,
          $
        ) : (q & 64 || q & 128) && B.process(
          y,
          x,
          I,
          H,
          M,
          W,
          z,
          G,
          $,
          ur
        );
    }
    tt != null && M && Zu(tt, y && y.ref, W, x || y, !x);
  }, N = (y, x, I, H) => {
    if (y == null)
      o(
        x.el = h(x.children),
        I,
        H
      );
    else {
      const M = x.el = y.el;
      x.children !== y.children && v(M, x.children);
    }
  }, K = (y, x, I, H) => {
    y == null ? o(
      x.el = g(x.children || ""),
      I,
      H
    ) : x.el = y.el;
  }, J = (y, x, I, H) => {
    [y.el, y.anchor] = O(
      y.children,
      x,
      I,
      H,
      y.el,
      y.anchor
    );
  }, V = ({ el: y, anchor: x }, I, H) => {
    let M;
    for (; y && y !== x; )
      M = T(y), o(y, I, H), y = M;
    o(x, I, H);
  }, U = ({ el: y, anchor: x }) => {
    let I;
    for (; y && y !== x; )
      I = T(y), l(y), y = I;
    l(x);
  }, nt = (y, x, I, H, M, W, z, G, $) => {
    x.type === "svg" ? z = "svg" : x.type === "math" && (z = "mathml"), y == null ? Tt(
      x,
      I,
      H,
      M,
      W,
      z,
      G,
      $
    ) : Lt(
      y,
      x,
      M,
      W,
      z,
      G,
      $
    );
  }, Tt = (y, x, I, H, M, W, z, G) => {
    let $, B;
    const { props: tt, shapeFlag: q, transition: Z, dirs: ut } = y;
    if ($ = y.el = a(
      y.type,
      W,
      tt && tt.is,
      tt
    ), q & 8 ? b($, y.children) : q & 16 && Kt(
      y.children,
      $,
      null,
      H,
      M,
      Du(y, W),
      z,
      G
    ), ut && Kr(y, null, H, "created"), It($, y, y.scopeId, z, H), tt) {
      for (const At in tt)
        At !== "value" && !so(At) && f($, At, null, tt[At], W, H);
      "value" in tt && f($, "value", null, tt.value, W), (B = tt.onVnodeBeforeMount) && Fn(B, H, y);
    }
    ut && Kr(y, null, H, "beforeMount");
    const _t = $1(M, Z);
    _t && Z.beforeEnter($), o($, x, I), ((B = tt && tt.onVnodeMounted) || _t || ut) && rn(() => {
      B && Fn(B, H, y), _t && Z.enter($), ut && Kr(y, null, H, "mounted");
    }, M);
  }, It = (y, x, I, H, M) => {
    if (I && C(y, I), H)
      for (let W = 0; W < H.length; W++)
        C(y, H[W]);
    if (M) {
      let W = M.subTree;
      if (x === W || lp(W.type) && (W.ssContent === x || W.ssFallback === x)) {
        const z = M.vnode;
        It(
          y,
          z,
          z.scopeId,
          z.slotScopeIds,
          M.parent
        );
      }
    }
  }, Kt = (y, x, I, H, M, W, z, G, $ = 0) => {
    for (let B = $; B < y.length; B++) {
      const tt = y[B] = G ? xr(y[B]) : Bn(y[B]);
      F(
        null,
        tt,
        x,
        I,
        H,
        M,
        W,
        z,
        G
      );
    }
  }, Lt = (y, x, I, H, M, W, z) => {
    const G = x.el = y.el;
    let { patchFlag: $, dynamicChildren: B, dirs: tt } = x;
    $ |= y.patchFlag & 16;
    const q = y.props || Ut, Z = x.props || Ut;
    let ut;
    if (I && Gr(I, !1), (ut = Z.onVnodeBeforeUpdate) && Fn(ut, I, x, y), tt && Kr(x, y, I, "beforeUpdate"), I && Gr(I, !0), (q.innerHTML && Z.innerHTML == null || q.textContent && Z.textContent == null) && b(G, ""), B ? te(
      y.dynamicChildren,
      B,
      G,
      I,
      H,
      Du(x, M),
      W
    ) : z || rt(
      y,
      x,
      G,
      null,
      I,
      H,
      Du(x, M),
      W,
      !1
    ), $ > 0) {
      if ($ & 16)
        fe(G, q, Z, I, M);
      else if ($ & 2 && q.class !== Z.class && f(G, "class", null, Z.class, M), $ & 4 && f(G, "style", q.style, Z.style, M), $ & 8) {
        const _t = x.dynamicProps;
        for (let At = 0; At < _t.length; At++) {
          const Ct = _t[At], me = q[Ct], re = Z[Ct];
          (re !== me || Ct === "value") && f(G, Ct, me, re, M, I);
        }
      }
      $ & 1 && y.children !== x.children && b(G, x.children);
    } else
      !z && B == null && fe(G, q, Z, I, M);
    ((ut = Z.onVnodeUpdated) || tt) && rn(() => {
      ut && Fn(ut, I, x, y), tt && Kr(x, y, I, "updated");
    }, H);
  }, te = (y, x, I, H, M, W, z) => {
    for (let G = 0; G < x.length; G++) {
      const $ = y[G], B = x[G], tt = (
        // oldVNode may be an errored async setup() component inside Suspense
        // which will not have a mounted element
        $.el && // - In the case of a Fragment, we need to provide the actual parent
        // of the Fragment itself so it can move its children.
        ($.type === sn || // - In the case of different nodes, there is going to be a replacement
        // which also requires the correct parent container
        !Ji($, B) || // - In the case of a component, it could contain anything.
        $.shapeFlag & 70) ? _($.el) : (
          // In other cases, the parent container is not actually used so we
          // just pass the block element here to avoid a DOM parentNode call.
          I
        )
      );
      F(
        $,
        B,
        tt,
        null,
        H,
        M,
        W,
        z,
        !0
      );
    }
  }, fe = (y, x, I, H, M) => {
    if (x !== I) {
      if (x !== Ut)
        for (const W in x)
          !so(W) && !(W in I) && f(
            y,
            W,
            x[W],
            null,
            M,
            H
          );
      for (const W in I) {
        if (so(W))
          continue;
        const z = I[W], G = x[W];
        z !== G && W !== "value" && f(y, W, G, z, M, H);
      }
      "value" in I && f(y, "value", x.value, I.value, M);
    }
  }, ge = (y, x, I, H, M, W, z, G, $) => {
    const B = x.el = y ? y.el : h(""), tt = x.anchor = y ? y.anchor : h("");
    let { patchFlag: q, dynamicChildren: Z, slotScopeIds: ut } = x;
    ut && (G = G ? G.concat(ut) : ut), y == null ? (o(B, I, H), o(tt, I, H), Kt(
      // #10007
      // such fragment like `<></>` will be compiled into
      // a fragment which doesn't have a children.
      // In this case fallback to an empty array
      x.children || [],
      I,
      tt,
      M,
      W,
      z,
      G,
      $
    )) : q > 0 && q & 64 && Z && // #2715 the previous fragment could've been a BAILed one as a result
    // of renderSlot() with no valid children
    y.dynamicChildren ? (te(
      y.dynamicChildren,
      Z,
      I,
      M,
      W,
      z,
      G
    ), // #2080 if the stable fragment has a key, it's a <template v-for> that may
    //  get moved around. Make sure all root level vnodes inherit el.
    // #2134 or if it's a component root, it may also get moved around
    // as the component is being moved.
    (x.key != null || M && x === M.subTree) && np(
      y,
      x,
      !0
      /* shallow */
    )) : rt(
      y,
      x,
      I,
      tt,
      M,
      W,
      z,
      G,
      $
    );
  }, Gt = (y, x, I, H, M, W, z, G, $) => {
    x.slotScopeIds = G, y == null ? x.shapeFlag & 512 ? M.ctx.activate(
      x,
      I,
      H,
      z,
      $
    ) : he(
      x,
      I,
      H,
      M,
      W,
      z,
      $
    ) : Te(y, x, $);
  }, he = (y, x, I, H, M, W, z) => {
    const G = y.component = ox(
      y,
      H,
      M
    );
    if (Hd(y) && (G.ctx.renderer = ur), sx(G, !1, z), G.asyncDep) {
      if (M && M.registerDep(G, gt, z), !y.el) {
        const $ = G.subTree = yn(Ir);
        K(null, $, x, I);
      }
    } else
      gt(
        G,
        y,
        x,
        I,
        M,
        W,
        z
      );
  }, Te = (y, x, I) => {
    const H = x.component = y.component;
    if (J1(y, x, I))
      if (H.asyncDep && !H.asyncResolved) {
        lt(H, x, I);
        return;
      } else
        H.next = x, H.update();
    else
      x.el = y.el, H.vnode = x;
  }, gt = (y, x, I, H, M, W, z) => {
    const G = () => {
      if (y.isMounted) {
        let { next: q, bu: Z, u: ut, parent: _t, vnode: At } = y;
        {
          const be = rp(y);
          if (be) {
            q && (q.el = At.el, lt(y, q, z)), be.asyncDep.then(() => {
              y.isUnmounted || G();
            });
            return;
          }
        }
        let Ct = q, me;
        Gr(y, !1), q ? (q.el = At.el, lt(y, q, z)) : q = At, Z && Eu(Z), (me = q.props && q.props.onVnodeBeforeUpdate) && Fn(me, _t, q, At), Gr(y, !0);
        const re = Ru(y), Pe = y.subTree;
        y.subTree = re, F(
          Pe,
          re,
          // parent may have changed if it's in a teleport
          _(Pe.el),
          // anchor may have changed if it's in a fragment
          ti(Pe),
          y,
          M,
          W
        ), q.el = re.el, Ct === null && Z1(y, re.el), ut && rn(ut, M), (me = q.props && q.props.onVnodeUpdated) && rn(
          () => Fn(me, _t, q, At),
          M
        );
      } else {
        let q;
        const { el: Z, props: ut } = x, { bm: _t, m: At, parent: Ct, root: me, type: re } = y, Pe = Oi(x);
        if (Gr(y, !1), _t && Eu(_t), !Pe && (q = ut && ut.onVnodeBeforeMount) && Fn(q, Ct, x), Gr(y, !0), Z && ar) {
          const be = () => {
            y.subTree = Ru(y), ar(
              Z,
              y.subTree,
              y,
              M,
              null
            );
          };
          Pe && re.__asyncHydrate ? re.__asyncHydrate(
            Z,
            y,
            be
          ) : be();
        } else {
          me.ce && me.ce._injectChildStyle(re);
          const be = y.subTree = Ru(y);
          F(
            null,
            be,
            I,
            H,
            y,
            M,
            W
          ), x.el = be.el;
        }
        if (At && rn(At, M), !Pe && (q = ut && ut.onVnodeMounted)) {
          const be = x;
          rn(
            () => Fn(q, Ct, be),
            M
          );
        }
        (x.shapeFlag & 256 || Ct && Oi(Ct.vnode) && Ct.vnode.shapeFlag & 256) && y.a && rn(y.a, M), y.isMounted = !0, x = I = H = null;
      }
    };
    y.scope.on();
    const $ = y.effect = new _d(G);
    y.scope.off();
    const B = y.update = $.run.bind($), tt = y.job = $.runIfDirty.bind($);
    tt.i = y, tt.id = y.uid, $.scheduler = () => Cf(tt), Gr(y, !0), B();
  }, lt = (y, x, I) => {
    x.component = y;
    const H = y.vnode.props;
    y.vnode = x, y.next = null, F1(y, x.props, H, I), W1(y, x.children, I), Rr(), wh(y), Pr();
  }, rt = (y, x, I, H, M, W, z, G, $ = !1) => {
    const B = y && y.children, tt = y ? y.shapeFlag : 0, q = x.children, { patchFlag: Z, shapeFlag: ut } = x;
    if (Z > 0) {
      if (Z & 128) {
        de(
          B,
          q,
          I,
          H,
          M,
          W,
          z,
          G,
          $
        );
        return;
      } else if (Z & 256) {
        ve(
          B,
          q,
          I,
          H,
          M,
          W,
          z,
          G,
          $
        );
        return;
      }
    }
    ut & 8 ? (tt & 16 && Dn(B, M, W), q !== B && b(I, q)) : tt & 16 ? ut & 16 ? de(
      B,
      q,
      I,
      H,
      M,
      W,
      z,
      G,
      $
    ) : Dn(B, M, W, !0) : (tt & 8 && b(I, ""), ut & 16 && Kt(
      q,
      I,
      H,
      M,
      W,
      z,
      G,
      $
    ));
  }, ve = (y, x, I, H, M, W, z, G, $) => {
    y = y || Ti, x = x || Ti;
    const B = y.length, tt = x.length, q = Math.min(B, tt);
    let Z;
    for (Z = 0; Z < q; Z++) {
      const ut = x[Z] = $ ? xr(x[Z]) : Bn(x[Z]);
      F(
        y[Z],
        ut,
        I,
        null,
        M,
        W,
        z,
        G,
        $
      );
    }
    B > tt ? Dn(
      y,
      M,
      W,
      !0,
      !1,
      q
    ) : Kt(
      x,
      I,
      H,
      M,
      W,
      z,
      G,
      $,
      q
    );
  }, de = (y, x, I, H, M, W, z, G, $) => {
    let B = 0;
    const tt = x.length;
    let q = y.length - 1, Z = tt - 1;
    for (; B <= q && B <= Z; ) {
      const ut = y[B], _t = x[B] = $ ? xr(x[B]) : Bn(x[B]);
      if (Ji(ut, _t))
        F(
          ut,
          _t,
          I,
          null,
          M,
          W,
          z,
          G,
          $
        );
      else
        break;
      B++;
    }
    for (; B <= q && B <= Z; ) {
      const ut = y[q], _t = x[Z] = $ ? xr(x[Z]) : Bn(x[Z]);
      if (Ji(ut, _t))
        F(
          ut,
          _t,
          I,
          null,
          M,
          W,
          z,
          G,
          $
        );
      else
        break;
      q--, Z--;
    }
    if (B > q) {
      if (B <= Z) {
        const ut = Z + 1, _t = ut < tt ? x[ut].el : H;
        for (; B <= Z; )
          F(
            null,
            x[B] = $ ? xr(x[B]) : Bn(x[B]),
            I,
            _t,
            M,
            W,
            z,
            G,
            $
          ), B++;
      }
    } else if (B > Z)
      for (; B <= q; )
        ee(y[B], M, W, !0), B++;
    else {
      const ut = B, _t = B, At = /* @__PURE__ */ new Map();
      for (B = _t; B <= Z; B++) {
        const ye = x[B] = $ ? xr(x[B]) : Bn(x[B]);
        ye.key != null && At.set(ye.key, B);
      }
      let Ct, me = 0;
      const re = Z - _t + 1;
      let Pe = !1, be = 0;
      const zn = new Array(re);
      for (B = 0; B < re; B++)
        zn[B] = 0;
      for (B = ut; B <= q; B++) {
        const ye = y[B];
        if (me >= re) {
          ee(ye, M, W, !0);
          continue;
        }
        let $e;
        if (ye.key != null)
          $e = At.get(ye.key);
        else
          for (Ct = _t; Ct <= Z; Ct++)
            if (zn[Ct - _t] === 0 && Ji(ye, x[Ct])) {
              $e = Ct;
              break;
            }
        $e === void 0 ? ee(ye, M, W, !0) : (zn[$e - _t] = B + 1, $e >= be ? be = $e : Pe = !0, F(
          ye,
          x[$e],
          I,
          null,
          M,
          W,
          z,
          G,
          $
        ), me++);
      }
      const ei = Pe ? K1(zn) : Ti;
      for (Ct = ei.length - 1, B = re - 1; B >= 0; B--) {
        const ye = _t + B, $e = x[ye], Co = ye + 1 < tt ? x[ye + 1].el : H;
        zn[B] === 0 ? F(
          null,
          $e,
          I,
          Co,
          M,
          W,
          z,
          G,
          $
        ) : Pe && (Ct < 0 || B !== ei[Ct] ? Yt($e, I, Co, 2) : Ct--);
      }
    }
  }, Yt = (y, x, I, H, M = null) => {
    const { el: W, type: z, transition: G, children: $, shapeFlag: B } = y;
    if (B & 6) {
      Yt(y.component.subTree, x, I, H);
      return;
    }
    if (B & 128) {
      y.suspense.move(x, I, H);
      return;
    }
    if (B & 64) {
      z.move(y, x, I, ur);
      return;
    }
    if (z === sn) {
      o(W, x, I);
      for (let q = 0; q < $.length; q++)
        Yt($[q], x, I, H);
      o(y.anchor, x, I);
      return;
    }
    if (z === Pu) {
      V(y, x, I);
      return;
    }
    if (H !== 2 && B & 1 && G)
      if (H === 0)
        G.beforeEnter(W), o(W, x, I), rn(() => G.enter(W), M);
      else {
        const { leave: q, delayLeave: Z, afterLeave: ut } = G, _t = () => o(W, x, I), At = () => {
          q(W, () => {
            _t(), ut && ut();
          });
        };
        Z ? Z(W, _t, At) : At();
      }
    else
      o(W, x, I);
  }, ee = (y, x, I, H = !1, M = !1) => {
    const {
      type: W,
      props: z,
      ref: G,
      children: $,
      dynamicChildren: B,
      shapeFlag: tt,
      patchFlag: q,
      dirs: Z,
      cacheIndex: ut
    } = y;
    if (q === -2 && (M = !1), G != null && Zu(G, null, I, y, !0), ut != null && (x.renderCache[ut] = void 0), tt & 256) {
      x.ctx.deactivate(y);
      return;
    }
    const _t = tt & 1 && Z, At = !Oi(y);
    let Ct;
    if (At && (Ct = z && z.onVnodeBeforeUnmount) && Fn(Ct, x, y), tt & 6)
      _e(y.component, I, H);
    else {
      if (tt & 128) {
        y.suspense.unmount(I, H);
        return;
      }
      _t && Kr(y, null, x, "beforeUnmount"), tt & 64 ? y.type.remove(
        y,
        x,
        I,
        ur,
        H
      ) : B && // #5154
      // when v-once is used inside a block, setBlockTracking(-1) marks the
      // parent block with hasOnce: true
      // so that it doesn't take the fast path during unmount - otherwise
      // components nested in v-once are never unmounted.
      !B.hasOnce && // #1153: fast path should not be taken for non-stable (v-for) fragments
      (W !== sn || q > 0 && q & 64) ? Dn(
        B,
        x,
        I,
        !1,
        !0
      ) : (W === sn && q & 384 || !M && tt & 16) && Dn($, x, I), H && un(y);
    }
    (At && (Ct = z && z.onVnodeUnmounted) || _t) && rn(() => {
      Ct && Fn(Ct, x, y), _t && Kr(y, null, x, "unmounted");
    }, I);
  }, un = (y) => {
    const { type: x, el: I, anchor: H, transition: M } = y;
    if (x === sn) {
      ne(I, H);
      return;
    }
    if (x === Pu) {
      U(y);
      return;
    }
    const W = () => {
      l(I), M && !M.persisted && M.afterLeave && M.afterLeave();
    };
    if (y.shapeFlag & 1 && M && !M.persisted) {
      const { leave: z, delayLeave: G } = M, $ = () => z(I, W);
      G ? G(y.el, W, $) : $();
    } else
      W();
  }, ne = (y, x) => {
    let I;
    for (; y !== x; )
      I = T(y), l(y), y = I;
    l(x);
  }, _e = (y, x, I) => {
    const { bum: H, scope: M, job: W, subTree: z, um: G, m: $, a: B } = y;
    Ch($), Ch(B), H && Eu(H), M.stop(), W && (W.flags |= 8, ee(z, y, x, I)), G && rn(G, x), rn(() => {
      y.isUnmounted = !0;
    }, x), x && x.pendingBranch && !x.isUnmounted && y.asyncDep && !y.asyncResolved && y.suspenseId === x.pendingId && (x.deps--, x.deps === 0 && x.resolve());
  }, Dn = (y, x, I, H = !1, M = !1, W = 0) => {
    for (let z = W; z < y.length; z++)
      ee(y[z], x, I, H, M);
  }, ti = (y) => {
    if (y.shapeFlag & 6)
      return ti(y.component.subTree);
    if (y.shapeFlag & 128)
      return y.suspense.next();
    const x = T(y.anchor || y.el), I = x && x[u1];
    return I ? T(I) : x;
  };
  let Yn = !1;
  const lr = (y, x, I) => {
    y == null ? x._vnode && ee(x._vnode, null, null, !0) : F(
      x._vnode || null,
      y,
      x,
      null,
      null,
      null,
      I
    ), x._vnode = y, Yn || (Yn = !0, wh(), Nd(), Yn = !1);
  }, ur = {
    p: F,
    um: ee,
    m: Yt,
    r: un,
    mt: he,
    mc: Kt,
    pc: rt,
    pbc: te,
    n: ti,
    o: e
  };
  let fr, ar;
  return r && ([fr, ar] = r(
    ur
  )), {
    render: lr,
    hydrate: fr,
    createApp: P1(lr, fr)
  };
}
function Du({ type: e, props: r }, i) {
  return i === "svg" && e === "foreignObject" || i === "mathml" && e === "annotation-xml" && r && r.encoding && r.encoding.includes("html") ? void 0 : i;
}
function Gr({ effect: e, job: r }, i) {
  i ? (e.flags |= 32, r.flags |= 4) : (e.flags &= -33, r.flags &= -5);
}
function $1(e, r) {
  return (!e || e && !e.pendingBranch) && r && !r.persisted;
}
function np(e, r, i = !1) {
  const o = e.children, l = r.children;
  if (pt(o) && pt(l))
    for (let f = 0; f < o.length; f++) {
      const a = o[f];
      let h = l[f];
      h.shapeFlag & 1 && !h.dynamicChildren && ((h.patchFlag <= 0 || h.patchFlag === 32) && (h = l[f] = xr(l[f]), h.el = a.el), !i && h.patchFlag !== -2 && np(a, h)), h.type === ll && (h.el = a.el);
    }
}
function K1(e) {
  const r = e.slice(), i = [0];
  let o, l, f, a, h;
  const g = e.length;
  for (o = 0; o < g; o++) {
    const v = e[o];
    if (v !== 0) {
      if (l = i[i.length - 1], e[l] < v) {
        r[o] = l, i.push(o);
        continue;
      }
      for (f = 0, a = i.length - 1; f < a; )
        h = f + a >> 1, e[i[h]] < v ? f = h + 1 : a = h;
      v < e[i[f]] && (f > 0 && (r[o] = i[f - 1]), i[f] = o);
    }
  }
  for (f = i.length, a = i[f - 1]; f-- > 0; )
    i[f] = a, a = r[a];
  return i;
}
function rp(e) {
  const r = e.subTree.component;
  if (r)
    return r.asyncDep && !r.asyncResolved ? r : rp(r);
}
function Ch(e) {
  if (e)
    for (let r = 0; r < e.length; r++)
      e[r].flags |= 8;
}
const G1 = Symbol.for("v-scx"), Y1 = () => kr(G1);
function In(e, r, i) {
  return ip(e, r, i);
}
function ip(e, r, i = Ut) {
  const { immediate: o, deep: l, flush: f, once: a } = i, h = Se({}, i);
  let g;
  if (ul)
    if (f === "sync") {
      const T = Y1();
      g = T.__watcherHandles || (T.__watcherHandles = []);
    } else if (!r || o)
      h.once = !0;
    else {
      const T = () => {
      };
      return T.stop = Un, T.resume = Un, T.pause = Un, T;
    }
  const v = He;
  h.call = (T, C, O) => Kn(T, v, C, O);
  let b = !1;
  f === "post" ? h.scheduler = (T) => {
    rn(T, v && v.suspense);
  } : f !== "sync" && (b = !0, h.scheduler = (T, C) => {
    C ? T() : Cf(T);
  }), h.augmentJob = (T) => {
    r && (T.flags |= 4), b && (T.flags |= 2, v && (T.id = v.uid, T.i = v));
  };
  const _ = i1(e, r, h);
  return g && g.push(_), _;
}
function z1(e, r, i) {
  const o = this.proxy, l = ue(e) ? e.includes(".") ? op(o, e) : () => o[e] : e.bind(o, o);
  let f;
  dt(r) ? f = r : (f = r.handler, i = r);
  const a = So(this), h = ip(l, f.bind(o), i);
  return a(), h;
}
function op(e, r) {
  const i = r.split(".");
  return () => {
    let o = e;
    for (let l = 0; l < i.length && o; l++)
      o = o[i[l]];
    return o;
  };
}
const X1 = (e, r) => r === "modelValue" || r === "model-value" ? e.modelModifiers : e[`${r}Modifiers`] || e[`${Qr(r)}Modifiers`] || e[`${Dr(r)}Modifiers`];
function q1(e, r, ...i) {
  if (e.isUnmounted)
    return;
  const o = e.vnode.props || Ut;
  let l = i;
  const f = r.startsWith("update:"), a = f && X1(o, r.slice(7));
  a && (a.trim && (l = i.map((b) => ue(b) ? b.trim() : b)), a.number && (l = i.map(dw)));
  let h, g = o[h = xu(r)] || // also try camelCase event handler (#2249)
  o[h = xu(Qr(r))];
  !g && f && (g = o[h = xu(Dr(r))]), g && Kn(
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
function sp(e, r, i = !1) {
  const o = r.emitsCache, l = o.get(e);
  if (l !== void 0)
    return l;
  const f = e.emits;
  let a = {}, h = !1;
  if (!dt(e)) {
    const g = (v) => {
      const b = sp(v, r, !0);
      b && (h = !0, Se(a, b));
    };
    !i && r.mixins.length && r.mixins.forEach(g), e.extends && g(e.extends), e.mixins && e.mixins.forEach(g);
  }
  return !f && !h ? (qt(e) && o.set(e, null), null) : (pt(f) ? f.forEach((g) => a[g] = null) : Se(a, f), qt(e) && o.set(e, a), a);
}
function sl(e, r) {
  return !e || !Zs(r) ? !1 : (r = r.slice(2).replace(/Once$/, ""), Pt(e, r[0].toLowerCase() + r.slice(1)) || Pt(e, Dr(r)) || Pt(e, r));
}
function Ru(e) {
  const {
    type: r,
    vnode: i,
    proxy: o,
    withProxy: l,
    propsOptions: [f],
    slots: a,
    attrs: h,
    emit: g,
    render: v,
    renderCache: b,
    props: _,
    data: T,
    setupState: C,
    ctx: O,
    inheritAttrs: F
  } = e, N = $s(e);
  let K, J;
  try {
    if (i.shapeFlag & 4) {
      const U = l || o, nt = U;
      K = Bn(
        v.call(
          nt,
          U,
          b,
          _,
          C,
          T,
          O
        )
      ), J = h;
    } else {
      const U = r;
      K = Bn(
        U.length > 1 ? U(
          _,
          { attrs: h, slots: a, emit: g }
        ) : U(
          _,
          null
        )
      ), J = r.props ? h : V1(h);
    }
  } catch (U) {
    fo.length = 0, il(U, e, 1), K = yn(Ir);
  }
  let V = K;
  if (J && F !== !1) {
    const U = Object.keys(J), { shapeFlag: nt } = V;
    U.length && nt & 7 && (f && U.some(df) && (J = k1(
      J,
      f
    )), V = Pi(V, J, !1, !0));
  }
  return i.dirs && (V = Pi(V, null, !1, !0), V.dirs = V.dirs ? V.dirs.concat(i.dirs) : i.dirs), i.transition && If(V, i.transition), K = V, $s(N), K;
}
const V1 = (e) => {
  let r;
  for (const i in e)
    (i === "class" || i === "style" || Zs(i)) && ((r || (r = {}))[i] = e[i]);
  return r;
}, k1 = (e, r) => {
  const i = {};
  for (const o in e)
    (!df(o) || !(o.slice(9) in r)) && (i[o] = e[o]);
  return i;
};
function J1(e, r, i) {
  const { props: o, children: l, component: f } = e, { props: a, children: h, patchFlag: g } = r, v = f.emitsOptions;
  if (r.dirs || r.transition)
    return !0;
  if (i && g >= 0) {
    if (g & 1024)
      return !0;
    if (g & 16)
      return o ? Ih(o, a, v) : !!a;
    if (g & 8) {
      const b = r.dynamicProps;
      for (let _ = 0; _ < b.length; _++) {
        const T = b[_];
        if (a[T] !== o[T] && !sl(v, T))
          return !0;
      }
    }
  } else
    return (l || h) && (!h || !h.$stable) ? !0 : o === a ? !1 : o ? a ? Ih(o, a, v) : !0 : !!a;
  return !1;
}
function Ih(e, r, i) {
  const o = Object.keys(r);
  if (o.length !== Object.keys(e).length)
    return !0;
  for (let l = 0; l < o.length; l++) {
    const f = o[l];
    if (r[f] !== e[f] && !sl(i, f))
      return !0;
  }
  return !1;
}
function Z1({ vnode: e, parent: r }, i) {
  for (; r; ) {
    const o = r.subTree;
    if (o.suspense && o.suspense.activeBranch === e && (o.el = e.el), o === e)
      (e = r.vnode).el = i, r = r.parent;
    else
      break;
  }
}
const lp = (e) => e.__isSuspense;
function Q1(e, r) {
  r && r.pendingBranch ? pt(e) ? r.effects.push(...e) : r.effects.push(e) : l1(e);
}
const sn = Symbol.for("v-fgt"), ll = Symbol.for("v-txt"), Ir = Symbol.for("v-cmt"), Pu = Symbol.for("v-stc"), fo = [];
let ln = null;
function Hn(e = !1) {
  fo.push(ln = e ? null : []);
}
function j1() {
  fo.pop(), ln = fo[fo.length - 1] || null;
}
let xo = 1;
function Oh(e) {
  xo += e, e < 0 && ln && (ln.hasOnce = !0);
}
function up(e) {
  return e.dynamicChildren = xo > 0 ? ln || Ti : null, j1(), xo > 0 && ln && ln.push(e), e;
}
function Jr(e, r, i, o, l, f) {
  return up(
    Ln(
      e,
      r,
      i,
      o,
      l,
      f,
      !0
    )
  );
}
function nf(e, r, i, o, l) {
  return up(
    yn(
      e,
      r,
      i,
      o,
      l,
      !0
    )
  );
}
function fp(e) {
  return e ? e.__v_isVNode === !0 : !1;
}
function Ji(e, r) {
  return e.type === r.type && e.key === r.key;
}
const ap = ({ key: e }) => e ?? null, Rs = ({
  ref: e,
  ref_key: r,
  ref_for: i
}) => (typeof e == "number" && (e = "" + e), e != null ? ue(e) || le(e) || dt(e) ? { i: ke, r: e, k: r, f: !!i } : e : null);
function Ln(e, r = null, i = null, o = 0, l = null, f = e === sn ? 0 : 1, a = !1, h = !1) {
  const g = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: e,
    props: r,
    key: r && ap(r),
    ref: r && Rs(r),
    scopeId: Bd,
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
    shapeFlag: f,
    patchFlag: o,
    dynamicProps: l,
    dynamicChildren: null,
    appContext: null,
    ctx: ke
  };
  return h ? (Pf(g, i), f & 128 && e.normalize(g)) : i && (g.shapeFlag |= ue(i) ? 8 : 16), xo > 0 && // avoid a block node from tracking itself
  !a && // has current parent block
  ln && // presence of a patch flag indicates this node needs patching on updates.
  // component nodes also should always be patched, because even if the
  // component doesn't need to update, it needs to persist the instance on to
  // the next vnode so that it can be properly unmounted later.
  (g.patchFlag > 0 || f & 6) && // the EVENTS flag is only for hydration and if it is the only flag, the
  // vnode should not be considered dynamic due to handler caching.
  g.patchFlag !== 32 && ln.push(g), g;
}
const yn = tx;
function tx(e, r = null, i = null, o = 0, l = null, f = !1) {
  if ((!e || e === x1) && (e = Ir), fp(e)) {
    const h = Pi(
      e,
      r,
      !0
      /* mergeRef: true */
    );
    return i && Pf(h, i), xo > 0 && !f && ln && (h.shapeFlag & 6 ? ln[ln.indexOf(e)] = h : ln.push(h)), h.patchFlag = -2, h;
  }
  if (ax(e) && (e = e.__vccOpts), r) {
    r = ex(r);
    let { class: h, style: g } = r;
    h && !ue(h) && (r.class = tl(h)), qt(g) && (Sf(g) && !pt(g) && (g = Se({}, g)), r.style = vf(g));
  }
  const a = ue(e) ? 1 : lp(e) ? 128 : f1(e) ? 64 : qt(e) ? 4 : dt(e) ? 2 : 0;
  return Ln(
    e,
    r,
    i,
    o,
    l,
    a,
    f,
    !0
  );
}
function ex(e) {
  return e ? Sf(e) || kd(e) ? Se({}, e) : e : null;
}
function Pi(e, r, i = !1, o = !1) {
  const { props: l, ref: f, patchFlag: a, children: h, transition: g } = e, v = r ? nx(l || {}, r) : l, b = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: e.type,
    props: v,
    key: v && ap(v),
    ref: r && r.ref ? (
      // #2078 in the case of <component :is="vnode" ref="extra"/>
      // if the vnode itself already has a ref, cloneVNode will need to merge
      // the refs so the single vnode can be set on multiple refs
      i && f ? pt(f) ? f.concat(Rs(r)) : [f, Rs(r)] : Rs(r)
    ) : f,
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
    patchFlag: r && e.type !== sn ? a === -1 ? 16 : a | 16 : a,
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
    ssContent: e.ssContent && Pi(e.ssContent),
    ssFallback: e.ssFallback && Pi(e.ssFallback),
    el: e.el,
    anchor: e.anchor,
    ctx: e.ctx,
    ce: e.ce
  };
  return g && o && If(
    b,
    g.clone(b)
  ), b;
}
function cp(e = " ", r = 0) {
  return yn(ll, null, e, r);
}
function Dh(e = "", r = !1) {
  return r ? (Hn(), nf(Ir, null, e)) : yn(Ir, null, e);
}
function Bn(e) {
  return e == null || typeof e == "boolean" ? yn(Ir) : pt(e) ? yn(
    sn,
    null,
    // #3666, avoid reference pollution when reusing vnode
    e.slice()
  ) : typeof e == "object" ? xr(e) : yn(ll, null, String(e));
}
function xr(e) {
  return e.el === null && e.patchFlag !== -1 || e.memo ? e : Pi(e);
}
function Pf(e, r) {
  let i = 0;
  const { shapeFlag: o } = e;
  if (r == null)
    r = null;
  else if (pt(r))
    i = 16;
  else if (typeof r == "object")
    if (o & 65) {
      const l = r.default;
      l && (l._c && (l._d = !1), Pf(e, l()), l._c && (l._d = !0));
      return;
    } else {
      i = 32;
      const l = r._;
      !l && !kd(r) ? r._ctx = ke : l === 3 && ke && (ke.slots._ === 1 ? r._ = 1 : (r._ = 2, e.patchFlag |= 1024));
    }
  else
    dt(r) ? (r = { default: r, _ctx: ke }, i = 32) : (r = String(r), o & 64 ? (i = 16, r = [cp(r)]) : i = 8);
  e.children = r, e.shapeFlag |= i;
}
function nx(...e) {
  const r = {};
  for (let i = 0; i < e.length; i++) {
    const o = e[i];
    for (const l in o)
      if (l === "class")
        r.class !== o.class && (r.class = tl([r.class, o.class]));
      else if (l === "style")
        r.style = vf([r.style, o.style]);
      else if (Zs(l)) {
        const f = r[l], a = o[l];
        a && f !== a && !(pt(f) && f.includes(a)) && (r[l] = f ? [].concat(f, a) : a);
      } else
        l !== "" && (r[l] = o[l]);
  }
  return r;
}
function Fn(e, r, i, o = null) {
  Kn(e, r, 7, [
    i,
    o
  ]);
}
const rx = zd();
let ix = 0;
function ox(e, r, i) {
  const o = e.type, l = (r ? r.appContext : e.appContext) || rx, f = {
    uid: ix++,
    vnode: e,
    type: o,
    parent: r,
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
    scope: new yw(
      !0
      /* detached */
    ),
    render: null,
    proxy: null,
    exposed: null,
    exposeProxy: null,
    withProxy: null,
    provides: r ? r.provides : Object.create(l.provides),
    ids: r ? r.ids : ["", 0, 0],
    accessCache: null,
    renderCache: [],
    // local resolved assets
    components: null,
    directives: null,
    // resolved props and emits options
    propsOptions: Zd(o, l),
    emitsOptions: sp(o, l),
    // emit
    emit: null,
    // to be set immediately
    emitted: null,
    // props default value
    propsDefaults: Ut,
    // inheritAttrs
    inheritAttrs: o.inheritAttrs,
    // state
    ctx: Ut,
    data: Ut,
    props: Ut,
    attrs: Ut,
    slots: Ut,
    refs: Ut,
    setupState: Ut,
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
  return f.ctx = { _: f }, f.root = r ? r.root : f, f.emit = q1.bind(null, f), e.ce && e.ce(f), f;
}
let He = null, Gs, rf;
{
  const e = hd(), r = (i, o) => {
    let l;
    return (l = e[i]) || (l = e[i] = []), l.push(o), (f) => {
      l.length > 1 ? l.forEach((a) => a(f)) : l[0](f);
    };
  };
  Gs = r(
    "__VUE_INSTANCE_SETTERS__",
    (i) => He = i
  ), rf = r(
    "__VUE_SSR_SETTERS__",
    (i) => ul = i
  );
}
const So = (e) => {
  const r = He;
  return Gs(e), e.scope.on(), () => {
    e.scope.off(), Gs(r);
  };
}, Rh = () => {
  He && He.scope.off(), Gs(null);
};
function hp(e) {
  return e.vnode.shapeFlag & 4;
}
let ul = !1;
function sx(e, r = !1, i = !1) {
  r && rf(r);
  const { props: o, children: l } = e.vnode, f = hp(e);
  M1(e, o, f, r), B1(e, l, i);
  const a = f ? lx(e, r) : void 0;
  return r && rf(!1), a;
}
function lx(e, r) {
  const i = e.type;
  e.accessCache = /* @__PURE__ */ Object.create(null), e.proxy = new Proxy(e.ctx, T1);
  const { setup: o } = i;
  if (o) {
    const l = e.setupContext = o.length > 1 ? fx(e) : null, f = So(e);
    Rr();
    const a = Eo(
      o,
      e,
      0,
      [
        e.props,
        l
      ]
    );
    if (Pr(), f(), ld(a)) {
      if (Oi(e) || Ud(e), a.then(Rh, Rh), r)
        return a.then((h) => {
          Ph(e, h, r);
        }).catch((h) => {
          il(h, e, 0);
        });
      e.asyncDep = a;
    } else
      Ph(e, a, r);
  } else
    dp(e, r);
}
function Ph(e, r, i) {
  dt(r) ? e.type.__ssrInlineRender ? e.ssrRender = r : e.render = r : qt(r) && (e.setupState = Pd(r)), dp(e, i);
}
let Mh;
function dp(e, r, i) {
  const o = e.type;
  if (!e.render) {
    if (!r && Mh && !o.render) {
      const l = o.template || Df(e).template;
      if (l) {
        const { isCustomElement: f, compilerOptions: a } = e.appContext.config, { delimiters: h, compilerOptions: g } = o, v = Se(
          Se(
            {
              isCustomElement: f,
              delimiters: h
            },
            a
          ),
          g
        );
        o.render = Mh(l, v);
      }
    }
    e.render = o.render || Un;
  }
  {
    const l = So(e);
    Rr();
    try {
      A1(e);
    } finally {
      Pr(), l();
    }
  }
}
const ux = {
  get(e, r) {
    return Re(e, "get", ""), e[r];
  }
};
function fx(e) {
  const r = (i) => {
    e.exposed = i || {};
  };
  return {
    attrs: new Proxy(e.attrs, ux),
    slots: e.slots,
    emit: e.emit,
    expose: r
  };
}
function Mf(e) {
  return e.exposed ? e.exposeProxy || (e.exposeProxy = new Proxy(Pd(zw(e.exposed)), {
    get(r, i) {
      if (i in r)
        return r[i];
      if (i in uo)
        return uo[i](e);
    },
    has(r, i) {
      return i in r || i in uo;
    }
  })) : e.proxy;
}
function ax(e) {
  return dt(e) && "__vccOpts" in e;
}
const Ff = (e, r) => n1(e, r, ul), cx = "3.5.6";
/**
* @vue/runtime-dom v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let of;
const Fh = typeof window < "u" && window.trustedTypes;
if (Fh)
  try {
    of = /* @__PURE__ */ Fh.createPolicy("vue", {
      createHTML: (e) => e
    });
  } catch {
  }
const pp = of ? (e) => of.createHTML(e) : (e) => e, hx = "http://www.w3.org/2000/svg", dx = "http://www.w3.org/1998/Math/MathML", nr = typeof document < "u" ? document : null, Nh = nr && /* @__PURE__ */ nr.createElement("template"), px = {
  insert: (e, r, i) => {
    r.insertBefore(e, i || null);
  },
  remove: (e) => {
    const r = e.parentNode;
    r && r.removeChild(e);
  },
  createElement: (e, r, i, o) => {
    const l = r === "svg" ? nr.createElementNS(hx, e) : r === "mathml" ? nr.createElementNS(dx, e) : i ? nr.createElement(e, { is: i }) : nr.createElement(e);
    return e === "select" && o && o.multiple != null && l.setAttribute("multiple", o.multiple), l;
  },
  createText: (e) => nr.createTextNode(e),
  createComment: (e) => nr.createComment(e),
  setText: (e, r) => {
    e.nodeValue = r;
  },
  setElementText: (e, r) => {
    e.textContent = r;
  },
  parentNode: (e) => e.parentNode,
  nextSibling: (e) => e.nextSibling,
  querySelector: (e) => nr.querySelector(e),
  setScopeId(e, r) {
    e.setAttribute(r, "");
  },
  // __UNSAFE__
  // Reason: innerHTML.
  // Static content here can only come from compiled templates.
  // As long as the user only uses trusted templates, this is safe.
  insertStaticContent(e, r, i, o, l, f) {
    const a = i ? i.previousSibling : r.lastChild;
    if (l && (l === f || l.nextSibling))
      for (; r.insertBefore(l.cloneNode(!0), i), !(l === f || !(l = l.nextSibling)); )
        ;
    else {
      Nh.innerHTML = pp(
        o === "svg" ? `<svg>${e}</svg>` : o === "mathml" ? `<math>${e}</math>` : e
      );
      const h = Nh.content;
      if (o === "svg" || o === "mathml") {
        const g = h.firstChild;
        for (; g.firstChild; )
          h.appendChild(g.firstChild);
        h.removeChild(g);
      }
      r.insertBefore(h, i);
    }
    return [
      // first
      a ? a.nextSibling : r.firstChild,
      // last
      i ? i.previousSibling : r.lastChild
    ];
  }
}, gx = Symbol("_vtc");
function vx(e, r, i) {
  const o = e[gx];
  o && (r = (r ? [r, ...o] : [...o]).join(" ")), r == null ? e.removeAttribute("class") : i ? e.setAttribute("class", r) : e.className = r;
}
const Lh = Symbol("_vod"), _x = Symbol("_vsh"), mx = Symbol(""), bx = /(^|;)\s*display\s*:/;
function yx(e, r, i) {
  const o = e.style, l = ue(i);
  let f = !1;
  if (i && !l) {
    if (r)
      if (ue(r))
        for (const a of r.split(";")) {
          const h = a.slice(0, a.indexOf(":")).trim();
          i[h] == null && Ps(o, h, "");
        }
      else
        for (const a in r)
          i[a] == null && Ps(o, a, "");
    for (const a in i)
      a === "display" && (f = !0), Ps(o, a, i[a]);
  } else if (l) {
    if (r !== i) {
      const a = o[mx];
      a && (i += ";" + a), o.cssText = i, f = bx.test(i);
    }
  } else
    r && e.removeAttribute("style");
  Lh in e && (e[Lh] = f ? o.display : "", e[_x] && (o.display = "none"));
}
const Bh = /\s*!important$/;
function Ps(e, r, i) {
  if (pt(i))
    i.forEach((o) => Ps(e, r, o));
  else if (i == null && (i = ""), r.startsWith("--"))
    e.setProperty(r, i);
  else {
    const o = wx(e, r);
    Bh.test(i) ? e.setProperty(
      Dr(o),
      i.replace(Bh, ""),
      "important"
    ) : e[o] = i;
  }
}
const Wh = ["Webkit", "Moz", "ms"], Mu = {};
function wx(e, r) {
  const i = Mu[r];
  if (i)
    return i;
  let o = Qr(r);
  if (o !== "filter" && o in e)
    return Mu[r] = o;
  o = ad(o);
  for (let l = 0; l < Wh.length; l++) {
    const f = Wh[l] + o;
    if (f in e)
      return Mu[r] = f;
  }
  return r;
}
const Uh = "http://www.w3.org/1999/xlink";
function Hh(e, r, i, o, l, f = bw(r)) {
  o && r.startsWith("xlink:") ? i == null ? e.removeAttributeNS(Uh, r.slice(6, r.length)) : e.setAttributeNS(Uh, r, i) : i == null || f && !dd(i) ? e.removeAttribute(r) : e.setAttribute(
    r,
    f ? "" : Or(i) ? String(i) : i
  );
}
function xx(e, r, i, o) {
  if (r === "innerHTML" || r === "textContent") {
    i != null && (e[r] = r === "innerHTML" ? pp(i) : i);
    return;
  }
  const l = e.tagName;
  if (r === "value" && l !== "PROGRESS" && // custom elements may use _value internally
  !l.includes("-")) {
    const a = l === "OPTION" ? e.getAttribute("value") || "" : e.value, h = i == null ? (
      // #11647: value should be set as empty string for null and undefined,
      // but <input type="checkbox"> should be set as 'on'.
      e.type === "checkbox" ? "on" : ""
    ) : String(i);
    (a !== h || !("_value" in e)) && (e.value = h), i == null && e.removeAttribute(r), e._value = i;
    return;
  }
  let f = !1;
  if (i === "" || i == null) {
    const a = typeof e[r];
    a === "boolean" ? i = dd(i) : i == null && a === "string" ? (i = "", f = !0) : a === "number" && (i = 0, f = !0);
  }
  try {
    e[r] = i;
  } catch {
  }
  f && e.removeAttribute(r);
}
function Ex(e, r, i, o) {
  e.addEventListener(r, i, o);
}
function Sx(e, r, i, o) {
  e.removeEventListener(r, i, o);
}
const $h = Symbol("_vei");
function Tx(e, r, i, o, l = null) {
  const f = e[$h] || (e[$h] = {}), a = f[r];
  if (o && a)
    a.value = o;
  else {
    const [h, g] = Ax(r);
    if (o) {
      const v = f[r] = Ox(
        o,
        l
      );
      Ex(e, h, v, g);
    } else
      a && (Sx(e, h, a, g), f[r] = void 0);
  }
}
const Kh = /(?:Once|Passive|Capture)$/;
function Ax(e) {
  let r;
  if (Kh.test(e)) {
    r = {};
    let o;
    for (; o = e.match(Kh); )
      e = e.slice(0, e.length - o[0].length), r[o[0].toLowerCase()] = !0;
  }
  return [e[2] === ":" ? e.slice(3) : Dr(e.slice(2)), r];
}
let Fu = 0;
const Cx = /* @__PURE__ */ Promise.resolve(), Ix = () => Fu || (Cx.then(() => Fu = 0), Fu = Date.now());
function Ox(e, r) {
  const i = (o) => {
    if (!o._vts)
      o._vts = Date.now();
    else if (o._vts <= i.attached)
      return;
    Kn(
      Dx(o, i.value),
      r,
      5,
      [o]
    );
  };
  return i.value = e, i.attached = Ix(), i;
}
function Dx(e, r) {
  if (pt(r)) {
    const i = e.stopImmediatePropagation;
    return e.stopImmediatePropagation = () => {
      i.call(e), e._stopped = !0;
    }, r.map(
      (o) => (l) => !l._stopped && o && o(l)
    );
  } else
    return r;
}
const Gh = (e) => e.charCodeAt(0) === 111 && e.charCodeAt(1) === 110 && // lowercase letter
e.charCodeAt(2) > 96 && e.charCodeAt(2) < 123, Rx = (e, r, i, o, l, f) => {
  const a = l === "svg";
  r === "class" ? vx(e, o, a) : r === "style" ? yx(e, i, o) : Zs(r) ? df(r) || Tx(e, r, i, o, f) : (r[0] === "." ? (r = r.slice(1), !0) : r[0] === "^" ? (r = r.slice(1), !1) : Px(e, r, o, a)) ? (xx(e, r, o), !e.tagName.includes("-") && (r === "value" || r === "checked" || r === "selected") && Hh(e, r, o, a, f, r !== "value")) : (r === "true-value" ? e._trueValue = o : r === "false-value" && (e._falseValue = o), Hh(e, r, o, a));
};
function Px(e, r, i, o) {
  if (o)
    return !!(r === "innerHTML" || r === "textContent" || r in e && Gh(r) && dt(i));
  if (r === "spellcheck" || r === "draggable" || r === "translate" || r === "form" || r === "list" && e.tagName === "INPUT" || r === "type" && e.tagName === "TEXTAREA")
    return !1;
  if (r === "width" || r === "height") {
    const l = e.tagName;
    if (l === "IMG" || l === "VIDEO" || l === "CANVAS" || l === "SOURCE")
      return !1;
  }
  return Gh(r) && ue(i) ? !1 : !!(r in e || e._isVueCE && (/[A-Z]/.test(r) || !ue(i)));
}
const Mx = {
  esc: "escape",
  space: " ",
  up: "arrow-up",
  left: "arrow-left",
  right: "arrow-right",
  down: "arrow-down",
  delete: "backspace"
}, _i = (e, r) => {
  const i = e._withKeys || (e._withKeys = {}), o = r.join(".");
  return i[o] || (i[o] = (l) => {
    if (!("key" in l))
      return;
    const f = Dr(l.key);
    if (r.some(
      (a) => a === f || Mx[a] === f
    ))
      return e(l);
  });
}, Fx = /* @__PURE__ */ Se({ patchProp: Rx }, px);
let Yh;
function Nx() {
  return Yh || (Yh = U1(Fx));
}
const Lx = (...e) => {
  const r = Nx().createApp(...e), { mount: i } = r;
  return r.mount = (o) => {
    const l = Wx(o);
    if (!l)
      return;
    const f = r._component;
    !dt(f) && !f.render && !f.template && (f.template = l.innerHTML), l.nodeType === 1 && (l.textContent = "");
    const a = i(l, !1, Bx(l));
    return l instanceof Element && (l.removeAttribute("v-cloak"), l.setAttribute("data-v-app", "")), a;
  }, r;
};
function Bx(e) {
  if (e instanceof SVGElement)
    return "svg";
  if (typeof MathMLElement == "function" && e instanceof MathMLElement)
    return "mathml";
}
function Wx(e) {
  return ue(e) ? document.querySelector(e) : e;
}
function gp(e) {
  return vd() ? (ww(e), !0) : !1;
}
function Nu() {
  const e = /* @__PURE__ */ new Set(), r = (l) => {
    e.delete(l);
  };
  return {
    on: (l) => {
      e.add(l);
      const f = () => r(l);
      return gp(f), {
        off: f
      };
    },
    off: r,
    trigger: (...l) => Promise.all(Array.from(e).map((f) => f(...l)))
  };
}
function Wn(e) {
  return typeof e == "function" ? e() : Ve(e);
}
const vp = typeof window < "u" && typeof document < "u";
typeof WorkerGlobalScope < "u" && globalThis instanceof WorkerGlobalScope;
const Ux = () => {
};
function zh(e, r = !1, i = "Timeout") {
  return new Promise((o, l) => {
    setTimeout(r ? () => l(i) : o, e);
  });
}
function Hx(e, ...r) {
  return r.some((i) => i in e);
}
function Lu(...e) {
  if (e.length !== 1)
    return jw(...e);
  const r = e[0];
  return typeof r == "function" ? Ri(Jw(() => ({ get: r, set: Ux }))) : mn(r);
}
function sf(e, r = !1) {
  function i(_, { flush: T = "sync", deep: C = !1, timeout: O, throwOnTimeout: F } = {}) {
    let N = null;
    const J = [new Promise((V) => {
      N = In(
        e,
        (U) => {
          _(U) !== r && (N ? N() : yo(() => N == null ? void 0 : N()), V(U));
        },
        {
          flush: T,
          deep: C,
          immediate: !0
        }
      );
    })];
    return O != null && J.push(
      zh(O, F).then(() => Wn(e)).finally(() => N == null ? void 0 : N())
    ), Promise.race(J);
  }
  function o(_, T) {
    if (!le(_))
      return i((U) => U === _, T);
    const { flush: C = "sync", deep: O = !1, timeout: F, throwOnTimeout: N } = T ?? {};
    let K = null;
    const V = [new Promise((U) => {
      K = In(
        [e, _],
        ([nt, Tt]) => {
          r !== (nt === Tt) && (K ? K() : yo(() => K == null ? void 0 : K()), U(nt));
        },
        {
          flush: C,
          deep: O,
          immediate: !0
        }
      );
    })];
    return F != null && V.push(
      zh(F, N).then(() => Wn(e)).finally(() => (K == null || K(), Wn(e)))
    ), Promise.race(V);
  }
  function l(_) {
    return i((T) => !!T, _);
  }
  function f(_) {
    return o(null, _);
  }
  function a(_) {
    return o(void 0, _);
  }
  function h(_) {
    return i(Number.isNaN, _);
  }
  function g(_, T) {
    return i((C) => {
      const O = Array.from(C);
      return O.includes(_) || O.includes(Wn(_));
    }, T);
  }
  function v(_) {
    return b(1, _);
  }
  function b(_ = 1, T) {
    let C = -1;
    return i(() => (C += 1, C >= _), T);
  }
  return Array.isArray(Wn(e)) ? {
    toMatch: i,
    toContains: g,
    changed: v,
    changedTimes: b,
    get not() {
      return sf(e, !r);
    }
  } : {
    toMatch: i,
    toBe: o,
    toBeTruthy: l,
    toBeNull: f,
    toBeNaN: h,
    toBeUndefined: a,
    changed: v,
    changedTimes: b,
    get not() {
      return sf(e, !r);
    }
  };
}
function $x(e) {
  return sf(e);
}
function Kx(e, r, i = {}) {
  const {
    immediate: o = !0
  } = i, l = mn(!1);
  let f = null;
  function a() {
    f && (clearTimeout(f), f = null);
  }
  function h() {
    l.value = !1, a();
  }
  function g(...v) {
    a(), l.value = !0, f = setTimeout(() => {
      l.value = !1, f = null, e(...v);
    }, Wn(r));
  }
  return o && (l.value = !0, vp && g()), gp(h), {
    isPending: Ri(l),
    start: g,
    stop: h
  };
}
const Gx = vp ? window : void 0, Yx = {
  json: "application/json",
  text: "text/plain"
};
function Ys(e) {
  return e && Hx(e, "immediate", "refetch", "initialData", "timeout", "beforeFetch", "afterFetch", "onFetchError", "fetch", "updateDataOnError");
}
const zx = /^(?:[a-z][a-z\d+\-.]*:)?\/\//i;
function Xx(e) {
  return zx.test(e);
}
function ao(e) {
  return typeof Headers < "u" && e instanceof Headers ? Object.fromEntries(e.entries()) : e;
}
function mi(e, ...r) {
  return e === "overwrite" ? async (i) => {
    const o = r[r.length - 1];
    return o ? { ...i, ...await o(i) } : i;
  } : async (i) => {
    for (const o of r)
      o && (i = { ...i, ...await o(i) });
    return i;
  };
}
function qx(e = {}) {
  const r = e.combination || "chain", i = e.options || {}, o = e.fetchOptions || {};
  function l(f, ...a) {
    const h = Ff(() => {
      const b = Wn(e.baseUrl), _ = Wn(f);
      return b && !Xx(_) ? kx(b, _) : _;
    });
    let g = i, v = o;
    return a.length > 0 && (Ys(a[0]) ? g = {
      ...g,
      ...a[0],
      beforeFetch: mi(r, i.beforeFetch, a[0].beforeFetch),
      afterFetch: mi(r, i.afterFetch, a[0].afterFetch),
      onFetchError: mi(r, i.onFetchError, a[0].onFetchError)
    } : v = {
      ...v,
      ...a[0],
      headers: {
        ...ao(v.headers) || {},
        ...ao(a[0].headers) || {}
      }
    }), a.length > 1 && Ys(a[1]) && (g = {
      ...g,
      ...a[1],
      beforeFetch: mi(r, i.beforeFetch, a[1].beforeFetch),
      afterFetch: mi(r, i.afterFetch, a[1].afterFetch),
      onFetchError: mi(r, i.onFetchError, a[1].onFetchError)
    }), Vx(h, v, g);
  }
  return l;
}
function Vx(e, ...r) {
  var i;
  const o = typeof AbortController == "function";
  let l = {}, f = {
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
  r.length > 0 && (Ys(r[0]) ? f = { ...f, ...r[0] } : l = r[0]), r.length > 1 && Ys(r[1]) && (f = { ...f, ...r[1] });
  const {
    fetch: h = (i = Gx) == null ? void 0 : i.fetch,
    initialData: g,
    timeout: v
  } = f, b = Nu(), _ = Nu(), T = Nu(), C = mn(!1), O = mn(!1), F = mn(!1), N = mn(null), K = Iu(null), J = Iu(null), V = Iu(g || null), U = Ff(() => o && O.value);
  let nt, Tt;
  const It = () => {
    o && (nt == null || nt.abort(), nt = new AbortController(), nt.signal.onabort = () => F.value = !0, l = {
      ...l,
      signal: nt.signal
    });
  }, Kt = (gt) => {
    O.value = gt, C.value = !gt;
  };
  v && (Tt = Kx(It, v, { immediate: !1 }));
  let Lt = 0;
  const te = async (gt = !1) => {
    var lt, rt;
    It(), Kt(!0), J.value = null, N.value = null, F.value = !1, Lt += 1;
    const ve = Lt, de = {
      method: a.method,
      headers: {}
    };
    if (a.payload) {
      const ne = ao(de.headers), _e = Wn(a.payload);
      !a.payloadType && _e && Object.getPrototypeOf(_e) === Object.prototype && !(_e instanceof FormData) && (a.payloadType = "json"), a.payloadType && (ne["Content-Type"] = (lt = Yx[a.payloadType]) != null ? lt : a.payloadType), de.body = a.payloadType === "json" ? JSON.stringify(_e) : _e;
    }
    let Yt = !1;
    const ee = {
      url: Wn(e),
      options: {
        ...de,
        ...l
      },
      cancel: () => {
        Yt = !0;
      }
    };
    if (f.beforeFetch && Object.assign(ee, await f.beforeFetch(ee)), Yt || !h)
      return Kt(!1), Promise.resolve(null);
    let un = null;
    return Tt && Tt.start(), h(
      ee.url,
      {
        ...de,
        ...ee.options,
        headers: {
          ...ao(de.headers),
          ...ao((rt = ee.options) == null ? void 0 : rt.headers)
        }
      }
    ).then(async (ne) => {
      if (K.value = ne, N.value = ne.status, un = await ne.clone()[a.type](), !ne.ok)
        throw V.value = g || null, new Error(ne.statusText);
      return f.afterFetch && ({ data: un } = await f.afterFetch({
        data: un,
        response: ne
      })), V.value = un, b.trigger(ne), ne;
    }).catch(async (ne) => {
      let _e = ne.message || ne.name;
      if (f.onFetchError && ({ error: _e, data: un } = await f.onFetchError({
        data: un,
        error: ne,
        response: K.value
      })), J.value = _e, f.updateDataOnError && (V.value = un), _.trigger(ne), gt)
        throw ne;
      return null;
    }).finally(() => {
      ve === Lt && Kt(!1), Tt && Tt.stop(), T.trigger(null);
    });
  }, fe = Lu(f.refetch);
  In(
    [
      fe,
      Lu(e)
    ],
    ([gt]) => gt && te(),
    { deep: !0 }
  );
  const ge = {
    isFinished: Ri(C),
    isFetching: Ri(O),
    statusCode: N,
    response: K,
    error: J,
    data: V,
    canAbort: U,
    aborted: F,
    abort: It,
    execute: te,
    onFetchResponse: b.on,
    onFetchError: _.on,
    onFetchFinally: T.on,
    // method
    get: Gt("GET"),
    put: Gt("PUT"),
    post: Gt("POST"),
    delete: Gt("DELETE"),
    patch: Gt("PATCH"),
    head: Gt("HEAD"),
    options: Gt("OPTIONS"),
    // type
    json: Te("json"),
    text: Te("text"),
    blob: Te("blob"),
    arrayBuffer: Te("arrayBuffer"),
    formData: Te("formData")
  };
  function Gt(gt) {
    return (lt, rt) => {
      if (!O.value)
        return a.method = gt, a.payload = lt, a.payloadType = rt, le(a.payload) && In(
          [
            fe,
            Lu(a.payload)
          ],
          ([ve]) => ve && te(),
          { deep: !0 }
        ), {
          ...ge,
          then(ve, de) {
            return he().then(ve, de);
          }
        };
    };
  }
  function he() {
    return new Promise((gt, lt) => {
      $x(C).toBe(!0).then(() => gt(ge)).catch((rt) => lt(rt));
    });
  }
  function Te(gt) {
    return () => {
      if (!O.value)
        return a.type = gt, {
          ...ge,
          then(lt, rt) {
            return he().then(lt, rt);
          }
        };
    };
  }
  return f.immediate && Promise.resolve().then(() => te()), {
    ...ge,
    then(gt, lt) {
      return he().then(gt, lt);
    }
  };
}
function kx(e, r) {
  return !e.endsWith("/") && !r.startsWith("/") ? `${e}/${r}` : `${e}${r}`;
}
var Zi = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, zs = { exports: {} };
/**
 * @license
 * Lodash <https://lodash.com/>
 * Copyright OpenJS Foundation and other contributors <https://openjsf.org/>
 * Released under MIT license <https://lodash.com/license>
 * Based on Underscore.js 1.8.3 <http://underscorejs.org/LICENSE>
 * Copyright Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 */
zs.exports;
(function(e, r) {
  (function() {
    var i, o = "4.17.21", l = 200, f = "Unsupported core-js use. Try https://npms.io/search?q=ponyfill.", a = "Expected a function", h = "Invalid `variable` option passed into `_.template`", g = "__lodash_hash_undefined__", v = 500, b = "__lodash_placeholder__", _ = 1, T = 2, C = 4, O = 1, F = 2, N = 1, K = 2, J = 4, V = 8, U = 16, nt = 32, Tt = 64, It = 128, Kt = 256, Lt = 512, te = 30, fe = "...", ge = 800, Gt = 16, he = 1, Te = 2, gt = 3, lt = 1 / 0, rt = 9007199254740991, ve = 17976931348623157e292, de = NaN, Yt = 4294967295, ee = Yt - 1, un = Yt >>> 1, ne = [
      ["ary", It],
      ["bind", N],
      ["bindKey", K],
      ["curry", V],
      ["curryRight", U],
      ["flip", Lt],
      ["partial", nt],
      ["partialRight", Tt],
      ["rearg", Kt]
    ], _e = "[object Arguments]", Dn = "[object Array]", ti = "[object AsyncFunction]", Yn = "[object Boolean]", lr = "[object Date]", ur = "[object DOMException]", fr = "[object Error]", ar = "[object Function]", y = "[object GeneratorFunction]", x = "[object Map]", I = "[object Number]", H = "[object Null]", M = "[object Object]", W = "[object Promise]", z = "[object Proxy]", G = "[object RegExp]", $ = "[object Set]", B = "[object String]", tt = "[object Symbol]", q = "[object Undefined]", Z = "[object WeakMap]", ut = "[object WeakSet]", _t = "[object ArrayBuffer]", At = "[object DataView]", Ct = "[object Float32Array]", me = "[object Float64Array]", re = "[object Int8Array]", Pe = "[object Int16Array]", be = "[object Int32Array]", zn = "[object Uint8Array]", ei = "[object Uint8ClampedArray]", ye = "[object Uint16Array]", $e = "[object Uint32Array]", Co = /\b__p \+= '';/g, Dp = /\b(__p \+=) '' \+/g, Rp = /(__e\(.*?\)|\b__t\)) \+\n'';/g, Uf = /&(?:amp|lt|gt|quot|#39);/g, Hf = /[&<>"']/g, Pp = RegExp(Uf.source), Mp = RegExp(Hf.source), Fp = /<%-([\s\S]+?)%>/g, Np = /<%([\s\S]+?)%>/g, $f = /<%=([\s\S]+?)%>/g, Lp = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/, Bp = /^\w*$/, Wp = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g, al = /[\\^$.*+?()[\]{}|]/g, Up = RegExp(al.source), cl = /^\s+/, Hp = /\s/, $p = /\{(?:\n\/\* \[wrapped with .+\] \*\/)?\n?/, Kp = /\{\n\/\* \[wrapped with (.+)\] \*/, Gp = /,? & /, Yp = /[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g, zp = /[()=,{}\[\]\/\s]/, Xp = /\\(\\)?/g, qp = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g, Kf = /\w*$/, Vp = /^[-+]0x[0-9a-f]+$/i, kp = /^0b[01]+$/i, Jp = /^\[object .+?Constructor\]$/, Zp = /^0o[0-7]+$/i, Qp = /^(?:0|[1-9]\d*)$/, jp = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g, Io = /($^)/, tg = /['\n\r\u2028\u2029\\]/g, Oo = "\\ud800-\\udfff", eg = "\\u0300-\\u036f", ng = "\\ufe20-\\ufe2f", rg = "\\u20d0-\\u20ff", Gf = eg + ng + rg, Yf = "\\u2700-\\u27bf", zf = "a-z\\xdf-\\xf6\\xf8-\\xff", ig = "\\xac\\xb1\\xd7\\xf7", og = "\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf", sg = "\\u2000-\\u206f", lg = " \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000", Xf = "A-Z\\xc0-\\xd6\\xd8-\\xde", qf = "\\ufe0e\\ufe0f", Vf = ig + og + sg + lg, hl = "['’]", ug = "[" + Oo + "]", kf = "[" + Vf + "]", Do = "[" + Gf + "]", Jf = "\\d+", fg = "[" + Yf + "]", Zf = "[" + zf + "]", Qf = "[^" + Oo + Vf + Jf + Yf + zf + Xf + "]", dl = "\\ud83c[\\udffb-\\udfff]", ag = "(?:" + Do + "|" + dl + ")", jf = "[^" + Oo + "]", pl = "(?:\\ud83c[\\udde6-\\uddff]){2}", gl = "[\\ud800-\\udbff][\\udc00-\\udfff]", ni = "[" + Xf + "]", ta = "\\u200d", ea = "(?:" + Zf + "|" + Qf + ")", cg = "(?:" + ni + "|" + Qf + ")", na = "(?:" + hl + "(?:d|ll|m|re|s|t|ve))?", ra = "(?:" + hl + "(?:D|LL|M|RE|S|T|VE))?", ia = ag + "?", oa = "[" + qf + "]?", hg = "(?:" + ta + "(?:" + [jf, pl, gl].join("|") + ")" + oa + ia + ")*", dg = "\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])", pg = "\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])", sa = oa + ia + hg, gg = "(?:" + [fg, pl, gl].join("|") + ")" + sa, vg = "(?:" + [jf + Do + "?", Do, pl, gl, ug].join("|") + ")", _g = RegExp(hl, "g"), mg = RegExp(Do, "g"), vl = RegExp(dl + "(?=" + dl + ")|" + vg + sa, "g"), bg = RegExp([
      ni + "?" + Zf + "+" + na + "(?=" + [kf, ni, "$"].join("|") + ")",
      cg + "+" + ra + "(?=" + [kf, ni + ea, "$"].join("|") + ")",
      ni + "?" + ea + "+" + na,
      ni + "+" + ra,
      pg,
      dg,
      Jf,
      gg
    ].join("|"), "g"), yg = RegExp("[" + ta + Oo + Gf + qf + "]"), wg = /[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/, xg = [
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
    ], Eg = -1, Ht = {};
    Ht[Ct] = Ht[me] = Ht[re] = Ht[Pe] = Ht[be] = Ht[zn] = Ht[ei] = Ht[ye] = Ht[$e] = !0, Ht[_e] = Ht[Dn] = Ht[_t] = Ht[Yn] = Ht[At] = Ht[lr] = Ht[fr] = Ht[ar] = Ht[x] = Ht[I] = Ht[M] = Ht[G] = Ht[$] = Ht[B] = Ht[Z] = !1;
    var Bt = {};
    Bt[_e] = Bt[Dn] = Bt[_t] = Bt[At] = Bt[Yn] = Bt[lr] = Bt[Ct] = Bt[me] = Bt[re] = Bt[Pe] = Bt[be] = Bt[x] = Bt[I] = Bt[M] = Bt[G] = Bt[$] = Bt[B] = Bt[tt] = Bt[zn] = Bt[ei] = Bt[ye] = Bt[$e] = !0, Bt[fr] = Bt[ar] = Bt[Z] = !1;
    var Sg = {
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
    }, Tg = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;"
    }, Ag = {
      "&amp;": "&",
      "&lt;": "<",
      "&gt;": ">",
      "&quot;": '"',
      "&#39;": "'"
    }, Cg = {
      "\\": "\\",
      "'": "'",
      "\n": "n",
      "\r": "r",
      "\u2028": "u2028",
      "\u2029": "u2029"
    }, Ig = parseFloat, Og = parseInt, la = typeof Zi == "object" && Zi && Zi.Object === Object && Zi, Dg = typeof self == "object" && self && self.Object === Object && self, we = la || Dg || Function("return this")(), _l = r && !r.nodeType && r, Mr = _l && !0 && e && !e.nodeType && e, ua = Mr && Mr.exports === _l, ml = ua && la.process, fn = function() {
      try {
        var E = Mr && Mr.require && Mr.require("util").types;
        return E || ml && ml.binding && ml.binding("util");
      } catch {
      }
    }(), fa = fn && fn.isArrayBuffer, aa = fn && fn.isDate, ca = fn && fn.isMap, ha = fn && fn.isRegExp, da = fn && fn.isSet, pa = fn && fn.isTypedArray;
    function Je(E, D, A) {
      switch (A.length) {
        case 0:
          return E.call(D);
        case 1:
          return E.call(D, A[0]);
        case 2:
          return E.call(D, A[0], A[1]);
        case 3:
          return E.call(D, A[0], A[1], A[2]);
      }
      return E.apply(D, A);
    }
    function Rg(E, D, A, k) {
      for (var at = -1, Ot = E == null ? 0 : E.length; ++at < Ot; ) {
        var ae = E[at];
        D(k, ae, A(ae), E);
      }
      return k;
    }
    function an(E, D) {
      for (var A = -1, k = E == null ? 0 : E.length; ++A < k && D(E[A], A, E) !== !1; )
        ;
      return E;
    }
    function Pg(E, D) {
      for (var A = E == null ? 0 : E.length; A-- && D(E[A], A, E) !== !1; )
        ;
      return E;
    }
    function ga(E, D) {
      for (var A = -1, k = E == null ? 0 : E.length; ++A < k; )
        if (!D(E[A], A, E))
          return !1;
      return !0;
    }
    function cr(E, D) {
      for (var A = -1, k = E == null ? 0 : E.length, at = 0, Ot = []; ++A < k; ) {
        var ae = E[A];
        D(ae, A, E) && (Ot[at++] = ae);
      }
      return Ot;
    }
    function Ro(E, D) {
      var A = E == null ? 0 : E.length;
      return !!A && ri(E, D, 0) > -1;
    }
    function bl(E, D, A) {
      for (var k = -1, at = E == null ? 0 : E.length; ++k < at; )
        if (A(D, E[k]))
          return !0;
      return !1;
    }
    function zt(E, D) {
      for (var A = -1, k = E == null ? 0 : E.length, at = Array(k); ++A < k; )
        at[A] = D(E[A], A, E);
      return at;
    }
    function hr(E, D) {
      for (var A = -1, k = D.length, at = E.length; ++A < k; )
        E[at + A] = D[A];
      return E;
    }
    function yl(E, D, A, k) {
      var at = -1, Ot = E == null ? 0 : E.length;
      for (k && Ot && (A = E[++at]); ++at < Ot; )
        A = D(A, E[at], at, E);
      return A;
    }
    function Mg(E, D, A, k) {
      var at = E == null ? 0 : E.length;
      for (k && at && (A = E[--at]); at--; )
        A = D(A, E[at], at, E);
      return A;
    }
    function wl(E, D) {
      for (var A = -1, k = E == null ? 0 : E.length; ++A < k; )
        if (D(E[A], A, E))
          return !0;
      return !1;
    }
    var Fg = xl("length");
    function Ng(E) {
      return E.split("");
    }
    function Lg(E) {
      return E.match(Yp) || [];
    }
    function va(E, D, A) {
      var k;
      return A(E, function(at, Ot, ae) {
        if (D(at, Ot, ae))
          return k = Ot, !1;
      }), k;
    }
    function Po(E, D, A, k) {
      for (var at = E.length, Ot = A + (k ? 1 : -1); k ? Ot-- : ++Ot < at; )
        if (D(E[Ot], Ot, E))
          return Ot;
      return -1;
    }
    function ri(E, D, A) {
      return D === D ? Vg(E, D, A) : Po(E, _a, A);
    }
    function Bg(E, D, A, k) {
      for (var at = A - 1, Ot = E.length; ++at < Ot; )
        if (k(E[at], D))
          return at;
      return -1;
    }
    function _a(E) {
      return E !== E;
    }
    function ma(E, D) {
      var A = E == null ? 0 : E.length;
      return A ? Sl(E, D) / A : de;
    }
    function xl(E) {
      return function(D) {
        return D == null ? i : D[E];
      };
    }
    function El(E) {
      return function(D) {
        return E == null ? i : E[D];
      };
    }
    function ba(E, D, A, k, at) {
      return at(E, function(Ot, ae, Ft) {
        A = k ? (k = !1, Ot) : D(A, Ot, ae, Ft);
      }), A;
    }
    function Wg(E, D) {
      var A = E.length;
      for (E.sort(D); A--; )
        E[A] = E[A].value;
      return E;
    }
    function Sl(E, D) {
      for (var A, k = -1, at = E.length; ++k < at; ) {
        var Ot = D(E[k]);
        Ot !== i && (A = A === i ? Ot : A + Ot);
      }
      return A;
    }
    function Tl(E, D) {
      for (var A = -1, k = Array(E); ++A < E; )
        k[A] = D(A);
      return k;
    }
    function Ug(E, D) {
      return zt(D, function(A) {
        return [A, E[A]];
      });
    }
    function ya(E) {
      return E && E.slice(0, Sa(E) + 1).replace(cl, "");
    }
    function Ze(E) {
      return function(D) {
        return E(D);
      };
    }
    function Al(E, D) {
      return zt(D, function(A) {
        return E[A];
      });
    }
    function Fi(E, D) {
      return E.has(D);
    }
    function wa(E, D) {
      for (var A = -1, k = E.length; ++A < k && ri(D, E[A], 0) > -1; )
        ;
      return A;
    }
    function xa(E, D) {
      for (var A = E.length; A-- && ri(D, E[A], 0) > -1; )
        ;
      return A;
    }
    function Hg(E, D) {
      for (var A = E.length, k = 0; A--; )
        E[A] === D && ++k;
      return k;
    }
    var $g = El(Sg), Kg = El(Tg);
    function Gg(E) {
      return "\\" + Cg[E];
    }
    function Yg(E, D) {
      return E == null ? i : E[D];
    }
    function ii(E) {
      return yg.test(E);
    }
    function zg(E) {
      return wg.test(E);
    }
    function Xg(E) {
      for (var D, A = []; !(D = E.next()).done; )
        A.push(D.value);
      return A;
    }
    function Cl(E) {
      var D = -1, A = Array(E.size);
      return E.forEach(function(k, at) {
        A[++D] = [at, k];
      }), A;
    }
    function Ea(E, D) {
      return function(A) {
        return E(D(A));
      };
    }
    function dr(E, D) {
      for (var A = -1, k = E.length, at = 0, Ot = []; ++A < k; ) {
        var ae = E[A];
        (ae === D || ae === b) && (E[A] = b, Ot[at++] = A);
      }
      return Ot;
    }
    function Mo(E) {
      var D = -1, A = Array(E.size);
      return E.forEach(function(k) {
        A[++D] = k;
      }), A;
    }
    function qg(E) {
      var D = -1, A = Array(E.size);
      return E.forEach(function(k) {
        A[++D] = [k, k];
      }), A;
    }
    function Vg(E, D, A) {
      for (var k = A - 1, at = E.length; ++k < at; )
        if (E[k] === D)
          return k;
      return -1;
    }
    function kg(E, D, A) {
      for (var k = A + 1; k--; )
        if (E[k] === D)
          return k;
      return k;
    }
    function oi(E) {
      return ii(E) ? Zg(E) : Fg(E);
    }
    function wn(E) {
      return ii(E) ? Qg(E) : Ng(E);
    }
    function Sa(E) {
      for (var D = E.length; D-- && Hp.test(E.charAt(D)); )
        ;
      return D;
    }
    var Jg = El(Ag);
    function Zg(E) {
      for (var D = vl.lastIndex = 0; vl.test(E); )
        ++D;
      return D;
    }
    function Qg(E) {
      return E.match(vl) || [];
    }
    function jg(E) {
      return E.match(bg) || [];
    }
    var tv = function E(D) {
      D = D == null ? we : si.defaults(we.Object(), D, si.pick(we, xg));
      var A = D.Array, k = D.Date, at = D.Error, Ot = D.Function, ae = D.Math, Ft = D.Object, Il = D.RegExp, ev = D.String, cn = D.TypeError, Fo = A.prototype, nv = Ot.prototype, li = Ft.prototype, No = D["__core-js_shared__"], Lo = nv.toString, Mt = li.hasOwnProperty, rv = 0, Ta = function() {
        var t = /[^.]+$/.exec(No && No.keys && No.keys.IE_PROTO || "");
        return t ? "Symbol(src)_1." + t : "";
      }(), Bo = li.toString, iv = Lo.call(Ft), ov = we._, sv = Il(
        "^" + Lo.call(Mt).replace(al, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"
      ), Wo = ua ? D.Buffer : i, pr = D.Symbol, Uo = D.Uint8Array, Aa = Wo ? Wo.allocUnsafe : i, Ho = Ea(Ft.getPrototypeOf, Ft), Ca = Ft.create, Ia = li.propertyIsEnumerable, $o = Fo.splice, Oa = pr ? pr.isConcatSpreadable : i, Ni = pr ? pr.iterator : i, Fr = pr ? pr.toStringTag : i, Ko = function() {
        try {
          var t = Ur(Ft, "defineProperty");
          return t({}, "", {}), t;
        } catch {
        }
      }(), lv = D.clearTimeout !== we.clearTimeout && D.clearTimeout, uv = k && k.now !== we.Date.now && k.now, fv = D.setTimeout !== we.setTimeout && D.setTimeout, Go = ae.ceil, Yo = ae.floor, Ol = Ft.getOwnPropertySymbols, av = Wo ? Wo.isBuffer : i, Da = D.isFinite, cv = Fo.join, hv = Ea(Ft.keys, Ft), ce = ae.max, Ae = ae.min, dv = k.now, pv = D.parseInt, Ra = ae.random, gv = Fo.reverse, Dl = Ur(D, "DataView"), Li = Ur(D, "Map"), Rl = Ur(D, "Promise"), ui = Ur(D, "Set"), Bi = Ur(D, "WeakMap"), Wi = Ur(Ft, "create"), zo = Bi && new Bi(), fi = {}, vv = Hr(Dl), _v = Hr(Li), mv = Hr(Rl), bv = Hr(ui), yv = Hr(Bi), Xo = pr ? pr.prototype : i, Ui = Xo ? Xo.valueOf : i, Pa = Xo ? Xo.toString : i;
      function d(t) {
        if (Jt(t) && !ht(t) && !(t instanceof xt)) {
          if (t instanceof hn)
            return t;
          if (Mt.call(t, "__wrapped__"))
            return Mc(t);
        }
        return new hn(t);
      }
      var ai = /* @__PURE__ */ function() {
        function t() {
        }
        return function(n) {
          if (!Vt(n))
            return {};
          if (Ca)
            return Ca(n);
          t.prototype = n;
          var s = new t();
          return t.prototype = i, s;
        };
      }();
      function qo() {
      }
      function hn(t, n) {
        this.__wrapped__ = t, this.__actions__ = [], this.__chain__ = !!n, this.__index__ = 0, this.__values__ = i;
      }
      d.templateSettings = {
        /**
         * Used to detect `data` property values to be HTML-escaped.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        escape: Fp,
        /**
         * Used to detect code to be evaluated.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        evaluate: Np,
        /**
         * Used to detect `data` property values to inject.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        interpolate: $f,
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
      }, d.prototype = qo.prototype, d.prototype.constructor = d, hn.prototype = ai(qo.prototype), hn.prototype.constructor = hn;
      function xt(t) {
        this.__wrapped__ = t, this.__actions__ = [], this.__dir__ = 1, this.__filtered__ = !1, this.__iteratees__ = [], this.__takeCount__ = Yt, this.__views__ = [];
      }
      function wv() {
        var t = new xt(this.__wrapped__);
        return t.__actions__ = Ke(this.__actions__), t.__dir__ = this.__dir__, t.__filtered__ = this.__filtered__, t.__iteratees__ = Ke(this.__iteratees__), t.__takeCount__ = this.__takeCount__, t.__views__ = Ke(this.__views__), t;
      }
      function xv() {
        if (this.__filtered__) {
          var t = new xt(this);
          t.__dir__ = -1, t.__filtered__ = !0;
        } else
          t = this.clone(), t.__dir__ *= -1;
        return t;
      }
      function Ev() {
        var t = this.__wrapped__.value(), n = this.__dir__, s = ht(t), u = n < 0, c = s ? t.length : 0, p = N_(0, c, this.__views__), m = p.start, w = p.end, S = w - m, R = u ? w : m - 1, P = this.__iteratees__, L = P.length, X = 0, j = Ae(S, this.__takeCount__);
        if (!s || !u && c == S && j == S)
          return nc(t, this.__actions__);
        var it = [];
        t:
          for (; S-- && X < j; ) {
            R += n;
            for (var mt = -1, ot = t[R]; ++mt < L; ) {
              var wt = P[mt], St = wt.iteratee, tn = wt.type, Ne = St(ot);
              if (tn == Te)
                ot = Ne;
              else if (!Ne) {
                if (tn == he)
                  continue t;
                break t;
              }
            }
            it[X++] = ot;
          }
        return it;
      }
      xt.prototype = ai(qo.prototype), xt.prototype.constructor = xt;
      function Nr(t) {
        var n = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++n < s; ) {
          var u = t[n];
          this.set(u[0], u[1]);
        }
      }
      function Sv() {
        this.__data__ = Wi ? Wi(null) : {}, this.size = 0;
      }
      function Tv(t) {
        var n = this.has(t) && delete this.__data__[t];
        return this.size -= n ? 1 : 0, n;
      }
      function Av(t) {
        var n = this.__data__;
        if (Wi) {
          var s = n[t];
          return s === g ? i : s;
        }
        return Mt.call(n, t) ? n[t] : i;
      }
      function Cv(t) {
        var n = this.__data__;
        return Wi ? n[t] !== i : Mt.call(n, t);
      }
      function Iv(t, n) {
        var s = this.__data__;
        return this.size += this.has(t) ? 0 : 1, s[t] = Wi && n === i ? g : n, this;
      }
      Nr.prototype.clear = Sv, Nr.prototype.delete = Tv, Nr.prototype.get = Av, Nr.prototype.has = Cv, Nr.prototype.set = Iv;
      function Xn(t) {
        var n = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++n < s; ) {
          var u = t[n];
          this.set(u[0], u[1]);
        }
      }
      function Ov() {
        this.__data__ = [], this.size = 0;
      }
      function Dv(t) {
        var n = this.__data__, s = Vo(n, t);
        if (s < 0)
          return !1;
        var u = n.length - 1;
        return s == u ? n.pop() : $o.call(n, s, 1), --this.size, !0;
      }
      function Rv(t) {
        var n = this.__data__, s = Vo(n, t);
        return s < 0 ? i : n[s][1];
      }
      function Pv(t) {
        return Vo(this.__data__, t) > -1;
      }
      function Mv(t, n) {
        var s = this.__data__, u = Vo(s, t);
        return u < 0 ? (++this.size, s.push([t, n])) : s[u][1] = n, this;
      }
      Xn.prototype.clear = Ov, Xn.prototype.delete = Dv, Xn.prototype.get = Rv, Xn.prototype.has = Pv, Xn.prototype.set = Mv;
      function qn(t) {
        var n = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++n < s; ) {
          var u = t[n];
          this.set(u[0], u[1]);
        }
      }
      function Fv() {
        this.size = 0, this.__data__ = {
          hash: new Nr(),
          map: new (Li || Xn)(),
          string: new Nr()
        };
      }
      function Nv(t) {
        var n = ss(this, t).delete(t);
        return this.size -= n ? 1 : 0, n;
      }
      function Lv(t) {
        return ss(this, t).get(t);
      }
      function Bv(t) {
        return ss(this, t).has(t);
      }
      function Wv(t, n) {
        var s = ss(this, t), u = s.size;
        return s.set(t, n), this.size += s.size == u ? 0 : 1, this;
      }
      qn.prototype.clear = Fv, qn.prototype.delete = Nv, qn.prototype.get = Lv, qn.prototype.has = Bv, qn.prototype.set = Wv;
      function Lr(t) {
        var n = -1, s = t == null ? 0 : t.length;
        for (this.__data__ = new qn(); ++n < s; )
          this.add(t[n]);
      }
      function Uv(t) {
        return this.__data__.set(t, g), this;
      }
      function Hv(t) {
        return this.__data__.has(t);
      }
      Lr.prototype.add = Lr.prototype.push = Uv, Lr.prototype.has = Hv;
      function xn(t) {
        var n = this.__data__ = new Xn(t);
        this.size = n.size;
      }
      function $v() {
        this.__data__ = new Xn(), this.size = 0;
      }
      function Kv(t) {
        var n = this.__data__, s = n.delete(t);
        return this.size = n.size, s;
      }
      function Gv(t) {
        return this.__data__.get(t);
      }
      function Yv(t) {
        return this.__data__.has(t);
      }
      function zv(t, n) {
        var s = this.__data__;
        if (s instanceof Xn) {
          var u = s.__data__;
          if (!Li || u.length < l - 1)
            return u.push([t, n]), this.size = ++s.size, this;
          s = this.__data__ = new qn(u);
        }
        return s.set(t, n), this.size = s.size, this;
      }
      xn.prototype.clear = $v, xn.prototype.delete = Kv, xn.prototype.get = Gv, xn.prototype.has = Yv, xn.prototype.set = zv;
      function Ma(t, n) {
        var s = ht(t), u = !s && $r(t), c = !s && !u && br(t), p = !s && !u && !c && pi(t), m = s || u || c || p, w = m ? Tl(t.length, ev) : [], S = w.length;
        for (var R in t)
          (n || Mt.call(t, R)) && !(m && // Safari 9 has enumerable `arguments.length` in strict mode.
          (R == "length" || // Node.js 0.10 has enumerable non-index properties on buffers.
          c && (R == "offset" || R == "parent") || // PhantomJS 2 has enumerable non-index properties on typed arrays.
          p && (R == "buffer" || R == "byteLength" || R == "byteOffset") || // Skip index properties.
          Zn(R, S))) && w.push(R);
        return w;
      }
      function Fa(t) {
        var n = t.length;
        return n ? t[Kl(0, n - 1)] : i;
      }
      function Xv(t, n) {
        return ls(Ke(t), Br(n, 0, t.length));
      }
      function qv(t) {
        return ls(Ke(t));
      }
      function Pl(t, n, s) {
        (s !== i && !En(t[n], s) || s === i && !(n in t)) && Vn(t, n, s);
      }
      function Hi(t, n, s) {
        var u = t[n];
        (!(Mt.call(t, n) && En(u, s)) || s === i && !(n in t)) && Vn(t, n, s);
      }
      function Vo(t, n) {
        for (var s = t.length; s--; )
          if (En(t[s][0], n))
            return s;
        return -1;
      }
      function Vv(t, n, s, u) {
        return gr(t, function(c, p, m) {
          n(u, c, s(c), m);
        }), u;
      }
      function Na(t, n) {
        return t && Pn(n, pe(n), t);
      }
      function kv(t, n) {
        return t && Pn(n, Ye(n), t);
      }
      function Vn(t, n, s) {
        n == "__proto__" && Ko ? Ko(t, n, {
          configurable: !0,
          enumerable: !0,
          value: s,
          writable: !0
        }) : t[n] = s;
      }
      function Ml(t, n) {
        for (var s = -1, u = n.length, c = A(u), p = t == null; ++s < u; )
          c[s] = p ? i : du(t, n[s]);
        return c;
      }
      function Br(t, n, s) {
        return t === t && (s !== i && (t = t <= s ? t : s), n !== i && (t = t >= n ? t : n)), t;
      }
      function dn(t, n, s, u, c, p) {
        var m, w = n & _, S = n & T, R = n & C;
        if (s && (m = c ? s(t, u, c, p) : s(t)), m !== i)
          return m;
        if (!Vt(t))
          return t;
        var P = ht(t);
        if (P) {
          if (m = B_(t), !w)
            return Ke(t, m);
        } else {
          var L = Ce(t), X = L == ar || L == y;
          if (br(t))
            return oc(t, w);
          if (L == M || L == _e || X && !c) {
            if (m = S || X ? {} : Sc(t), !w)
              return S ? A_(t, kv(m, t)) : T_(t, Na(m, t));
          } else {
            if (!Bt[L])
              return c ? t : {};
            m = W_(t, L, w);
          }
        }
        p || (p = new xn());
        var j = p.get(t);
        if (j)
          return j;
        p.set(t, m), jc(t) ? t.forEach(function(ot) {
          m.add(dn(ot, n, s, ot, t, p));
        }) : Zc(t) && t.forEach(function(ot, wt) {
          m.set(wt, dn(ot, n, s, wt, t, p));
        });
        var it = R ? S ? jl : Ql : S ? Ye : pe, mt = P ? i : it(t);
        return an(mt || t, function(ot, wt) {
          mt && (wt = ot, ot = t[wt]), Hi(m, wt, dn(ot, n, s, wt, t, p));
        }), m;
      }
      function Jv(t) {
        var n = pe(t);
        return function(s) {
          return La(s, t, n);
        };
      }
      function La(t, n, s) {
        var u = s.length;
        if (t == null)
          return !u;
        for (t = Ft(t); u--; ) {
          var c = s[u], p = n[c], m = t[c];
          if (m === i && !(c in t) || !p(m))
            return !1;
        }
        return !0;
      }
      function Ba(t, n, s) {
        if (typeof t != "function")
          throw new cn(a);
        return qi(function() {
          t.apply(i, s);
        }, n);
      }
      function $i(t, n, s, u) {
        var c = -1, p = Ro, m = !0, w = t.length, S = [], R = n.length;
        if (!w)
          return S;
        s && (n = zt(n, Ze(s))), u ? (p = bl, m = !1) : n.length >= l && (p = Fi, m = !1, n = new Lr(n));
        t:
          for (; ++c < w; ) {
            var P = t[c], L = s == null ? P : s(P);
            if (P = u || P !== 0 ? P : 0, m && L === L) {
              for (var X = R; X--; )
                if (n[X] === L)
                  continue t;
              S.push(P);
            } else
              p(n, L, u) || S.push(P);
          }
        return S;
      }
      var gr = ac(Rn), Wa = ac(Nl, !0);
      function Zv(t, n) {
        var s = !0;
        return gr(t, function(u, c, p) {
          return s = !!n(u, c, p), s;
        }), s;
      }
      function ko(t, n, s) {
        for (var u = -1, c = t.length; ++u < c; ) {
          var p = t[u], m = n(p);
          if (m != null && (w === i ? m === m && !je(m) : s(m, w)))
            var w = m, S = p;
        }
        return S;
      }
      function Qv(t, n, s, u) {
        var c = t.length;
        for (s = vt(s), s < 0 && (s = -s > c ? 0 : c + s), u = u === i || u > c ? c : vt(u), u < 0 && (u += c), u = s > u ? 0 : eh(u); s < u; )
          t[s++] = n;
        return t;
      }
      function Ua(t, n) {
        var s = [];
        return gr(t, function(u, c, p) {
          n(u, c, p) && s.push(u);
        }), s;
      }
      function xe(t, n, s, u, c) {
        var p = -1, m = t.length;
        for (s || (s = H_), c || (c = []); ++p < m; ) {
          var w = t[p];
          n > 0 && s(w) ? n > 1 ? xe(w, n - 1, s, u, c) : hr(c, w) : u || (c[c.length] = w);
        }
        return c;
      }
      var Fl = cc(), Ha = cc(!0);
      function Rn(t, n) {
        return t && Fl(t, n, pe);
      }
      function Nl(t, n) {
        return t && Ha(t, n, pe);
      }
      function Jo(t, n) {
        return cr(n, function(s) {
          return Qn(t[s]);
        });
      }
      function Wr(t, n) {
        n = _r(n, t);
        for (var s = 0, u = n.length; t != null && s < u; )
          t = t[Mn(n[s++])];
        return s && s == u ? t : i;
      }
      function $a(t, n, s) {
        var u = n(t);
        return ht(t) ? u : hr(u, s(t));
      }
      function Me(t) {
        return t == null ? t === i ? q : H : Fr && Fr in Ft(t) ? F_(t) : q_(t);
      }
      function Ll(t, n) {
        return t > n;
      }
      function jv(t, n) {
        return t != null && Mt.call(t, n);
      }
      function t_(t, n) {
        return t != null && n in Ft(t);
      }
      function e_(t, n, s) {
        return t >= Ae(n, s) && t < ce(n, s);
      }
      function Bl(t, n, s) {
        for (var u = s ? bl : Ro, c = t[0].length, p = t.length, m = p, w = A(p), S = 1 / 0, R = []; m--; ) {
          var P = t[m];
          m && n && (P = zt(P, Ze(n))), S = Ae(P.length, S), w[m] = !s && (n || c >= 120 && P.length >= 120) ? new Lr(m && P) : i;
        }
        P = t[0];
        var L = -1, X = w[0];
        t:
          for (; ++L < c && R.length < S; ) {
            var j = P[L], it = n ? n(j) : j;
            if (j = s || j !== 0 ? j : 0, !(X ? Fi(X, it) : u(R, it, s))) {
              for (m = p; --m; ) {
                var mt = w[m];
                if (!(mt ? Fi(mt, it) : u(t[m], it, s)))
                  continue t;
              }
              X && X.push(it), R.push(j);
            }
          }
        return R;
      }
      function n_(t, n, s, u) {
        return Rn(t, function(c, p, m) {
          n(u, s(c), p, m);
        }), u;
      }
      function Ki(t, n, s) {
        n = _r(n, t), t = Ic(t, n);
        var u = t == null ? t : t[Mn(gn(n))];
        return u == null ? i : Je(u, t, s);
      }
      function Ka(t) {
        return Jt(t) && Me(t) == _e;
      }
      function r_(t) {
        return Jt(t) && Me(t) == _t;
      }
      function i_(t) {
        return Jt(t) && Me(t) == lr;
      }
      function Gi(t, n, s, u, c) {
        return t === n ? !0 : t == null || n == null || !Jt(t) && !Jt(n) ? t !== t && n !== n : o_(t, n, s, u, Gi, c);
      }
      function o_(t, n, s, u, c, p) {
        var m = ht(t), w = ht(n), S = m ? Dn : Ce(t), R = w ? Dn : Ce(n);
        S = S == _e ? M : S, R = R == _e ? M : R;
        var P = S == M, L = R == M, X = S == R;
        if (X && br(t)) {
          if (!br(n))
            return !1;
          m = !0, P = !1;
        }
        if (X && !P)
          return p || (p = new xn()), m || pi(t) ? wc(t, n, s, u, c, p) : P_(t, n, S, s, u, c, p);
        if (!(s & O)) {
          var j = P && Mt.call(t, "__wrapped__"), it = L && Mt.call(n, "__wrapped__");
          if (j || it) {
            var mt = j ? t.value() : t, ot = it ? n.value() : n;
            return p || (p = new xn()), c(mt, ot, s, u, p);
          }
        }
        return X ? (p || (p = new xn()), M_(t, n, s, u, c, p)) : !1;
      }
      function s_(t) {
        return Jt(t) && Ce(t) == x;
      }
      function Wl(t, n, s, u) {
        var c = s.length, p = c, m = !u;
        if (t == null)
          return !p;
        for (t = Ft(t); c--; ) {
          var w = s[c];
          if (m && w[2] ? w[1] !== t[w[0]] : !(w[0] in t))
            return !1;
        }
        for (; ++c < p; ) {
          w = s[c];
          var S = w[0], R = t[S], P = w[1];
          if (m && w[2]) {
            if (R === i && !(S in t))
              return !1;
          } else {
            var L = new xn();
            if (u)
              var X = u(R, P, S, t, n, L);
            if (!(X === i ? Gi(P, R, O | F, u, L) : X))
              return !1;
          }
        }
        return !0;
      }
      function Ga(t) {
        if (!Vt(t) || K_(t))
          return !1;
        var n = Qn(t) ? sv : Jp;
        return n.test(Hr(t));
      }
      function l_(t) {
        return Jt(t) && Me(t) == G;
      }
      function u_(t) {
        return Jt(t) && Ce(t) == $;
      }
      function f_(t) {
        return Jt(t) && ds(t.length) && !!Ht[Me(t)];
      }
      function Ya(t) {
        return typeof t == "function" ? t : t == null ? ze : typeof t == "object" ? ht(t) ? qa(t[0], t[1]) : Xa(t) : hh(t);
      }
      function Ul(t) {
        if (!Xi(t))
          return hv(t);
        var n = [];
        for (var s in Ft(t))
          Mt.call(t, s) && s != "constructor" && n.push(s);
        return n;
      }
      function a_(t) {
        if (!Vt(t))
          return X_(t);
        var n = Xi(t), s = [];
        for (var u in t)
          u == "constructor" && (n || !Mt.call(t, u)) || s.push(u);
        return s;
      }
      function Hl(t, n) {
        return t < n;
      }
      function za(t, n) {
        var s = -1, u = Ge(t) ? A(t.length) : [];
        return gr(t, function(c, p, m) {
          u[++s] = n(c, p, m);
        }), u;
      }
      function Xa(t) {
        var n = eu(t);
        return n.length == 1 && n[0][2] ? Ac(n[0][0], n[0][1]) : function(s) {
          return s === t || Wl(s, t, n);
        };
      }
      function qa(t, n) {
        return ru(t) && Tc(n) ? Ac(Mn(t), n) : function(s) {
          var u = du(s, t);
          return u === i && u === n ? pu(s, t) : Gi(n, u, O | F);
        };
      }
      function Zo(t, n, s, u, c) {
        t !== n && Fl(n, function(p, m) {
          if (c || (c = new xn()), Vt(p))
            c_(t, n, m, s, Zo, u, c);
          else {
            var w = u ? u(ou(t, m), p, m + "", t, n, c) : i;
            w === i && (w = p), Pl(t, m, w);
          }
        }, Ye);
      }
      function c_(t, n, s, u, c, p, m) {
        var w = ou(t, s), S = ou(n, s), R = m.get(S);
        if (R) {
          Pl(t, s, R);
          return;
        }
        var P = p ? p(w, S, s + "", t, n, m) : i, L = P === i;
        if (L) {
          var X = ht(S), j = !X && br(S), it = !X && !j && pi(S);
          P = S, X || j || it ? ht(w) ? P = w : Qt(w) ? P = Ke(w) : j ? (L = !1, P = oc(S, !0)) : it ? (L = !1, P = sc(S, !0)) : P = [] : Vi(S) || $r(S) ? (P = w, $r(w) ? P = nh(w) : (!Vt(w) || Qn(w)) && (P = Sc(S))) : L = !1;
        }
        L && (m.set(S, P), c(P, S, u, p, m), m.delete(S)), Pl(t, s, P);
      }
      function Va(t, n) {
        var s = t.length;
        if (s)
          return n += n < 0 ? s : 0, Zn(n, s) ? t[n] : i;
      }
      function ka(t, n, s) {
        n.length ? n = zt(n, function(p) {
          return ht(p) ? function(m) {
            return Wr(m, p.length === 1 ? p[0] : p);
          } : p;
        }) : n = [ze];
        var u = -1;
        n = zt(n, Ze(et()));
        var c = za(t, function(p, m, w) {
          var S = zt(n, function(R) {
            return R(p);
          });
          return { criteria: S, index: ++u, value: p };
        });
        return Wg(c, function(p, m) {
          return S_(p, m, s);
        });
      }
      function h_(t, n) {
        return Ja(t, n, function(s, u) {
          return pu(t, u);
        });
      }
      function Ja(t, n, s) {
        for (var u = -1, c = n.length, p = {}; ++u < c; ) {
          var m = n[u], w = Wr(t, m);
          s(w, m) && Yi(p, _r(m, t), w);
        }
        return p;
      }
      function d_(t) {
        return function(n) {
          return Wr(n, t);
        };
      }
      function $l(t, n, s, u) {
        var c = u ? Bg : ri, p = -1, m = n.length, w = t;
        for (t === n && (n = Ke(n)), s && (w = zt(t, Ze(s))); ++p < m; )
          for (var S = 0, R = n[p], P = s ? s(R) : R; (S = c(w, P, S, u)) > -1; )
            w !== t && $o.call(w, S, 1), $o.call(t, S, 1);
        return t;
      }
      function Za(t, n) {
        for (var s = t ? n.length : 0, u = s - 1; s--; ) {
          var c = n[s];
          if (s == u || c !== p) {
            var p = c;
            Zn(c) ? $o.call(t, c, 1) : zl(t, c);
          }
        }
        return t;
      }
      function Kl(t, n) {
        return t + Yo(Ra() * (n - t + 1));
      }
      function p_(t, n, s, u) {
        for (var c = -1, p = ce(Go((n - t) / (s || 1)), 0), m = A(p); p--; )
          m[u ? p : ++c] = t, t += s;
        return m;
      }
      function Gl(t, n) {
        var s = "";
        if (!t || n < 1 || n > rt)
          return s;
        do
          n % 2 && (s += t), n = Yo(n / 2), n && (t += t);
        while (n);
        return s;
      }
      function bt(t, n) {
        return su(Cc(t, n, ze), t + "");
      }
      function g_(t) {
        return Fa(gi(t));
      }
      function v_(t, n) {
        var s = gi(t);
        return ls(s, Br(n, 0, s.length));
      }
      function Yi(t, n, s, u) {
        if (!Vt(t))
          return t;
        n = _r(n, t);
        for (var c = -1, p = n.length, m = p - 1, w = t; w != null && ++c < p; ) {
          var S = Mn(n[c]), R = s;
          if (S === "__proto__" || S === "constructor" || S === "prototype")
            return t;
          if (c != m) {
            var P = w[S];
            R = u ? u(P, S, w) : i, R === i && (R = Vt(P) ? P : Zn(n[c + 1]) ? [] : {});
          }
          Hi(w, S, R), w = w[S];
        }
        return t;
      }
      var Qa = zo ? function(t, n) {
        return zo.set(t, n), t;
      } : ze, __ = Ko ? function(t, n) {
        return Ko(t, "toString", {
          configurable: !0,
          enumerable: !1,
          value: vu(n),
          writable: !0
        });
      } : ze;
      function m_(t) {
        return ls(gi(t));
      }
      function pn(t, n, s) {
        var u = -1, c = t.length;
        n < 0 && (n = -n > c ? 0 : c + n), s = s > c ? c : s, s < 0 && (s += c), c = n > s ? 0 : s - n >>> 0, n >>>= 0;
        for (var p = A(c); ++u < c; )
          p[u] = t[u + n];
        return p;
      }
      function b_(t, n) {
        var s;
        return gr(t, function(u, c, p) {
          return s = n(u, c, p), !s;
        }), !!s;
      }
      function Qo(t, n, s) {
        var u = 0, c = t == null ? u : t.length;
        if (typeof n == "number" && n === n && c <= un) {
          for (; u < c; ) {
            var p = u + c >>> 1, m = t[p];
            m !== null && !je(m) && (s ? m <= n : m < n) ? u = p + 1 : c = p;
          }
          return c;
        }
        return Yl(t, n, ze, s);
      }
      function Yl(t, n, s, u) {
        var c = 0, p = t == null ? 0 : t.length;
        if (p === 0)
          return 0;
        n = s(n);
        for (var m = n !== n, w = n === null, S = je(n), R = n === i; c < p; ) {
          var P = Yo((c + p) / 2), L = s(t[P]), X = L !== i, j = L === null, it = L === L, mt = je(L);
          if (m)
            var ot = u || it;
          else
            R ? ot = it && (u || X) : w ? ot = it && X && (u || !j) : S ? ot = it && X && !j && (u || !mt) : j || mt ? ot = !1 : ot = u ? L <= n : L < n;
          ot ? c = P + 1 : p = P;
        }
        return Ae(p, ee);
      }
      function ja(t, n) {
        for (var s = -1, u = t.length, c = 0, p = []; ++s < u; ) {
          var m = t[s], w = n ? n(m) : m;
          if (!s || !En(w, S)) {
            var S = w;
            p[c++] = m === 0 ? 0 : m;
          }
        }
        return p;
      }
      function tc(t) {
        return typeof t == "number" ? t : je(t) ? de : +t;
      }
      function Qe(t) {
        if (typeof t == "string")
          return t;
        if (ht(t))
          return zt(t, Qe) + "";
        if (je(t))
          return Pa ? Pa.call(t) : "";
        var n = t + "";
        return n == "0" && 1 / t == -lt ? "-0" : n;
      }
      function vr(t, n, s) {
        var u = -1, c = Ro, p = t.length, m = !0, w = [], S = w;
        if (s)
          m = !1, c = bl;
        else if (p >= l) {
          var R = n ? null : D_(t);
          if (R)
            return Mo(R);
          m = !1, c = Fi, S = new Lr();
        } else
          S = n ? [] : w;
        t:
          for (; ++u < p; ) {
            var P = t[u], L = n ? n(P) : P;
            if (P = s || P !== 0 ? P : 0, m && L === L) {
              for (var X = S.length; X--; )
                if (S[X] === L)
                  continue t;
              n && S.push(L), w.push(P);
            } else
              c(S, L, s) || (S !== w && S.push(L), w.push(P));
          }
        return w;
      }
      function zl(t, n) {
        return n = _r(n, t), t = Ic(t, n), t == null || delete t[Mn(gn(n))];
      }
      function ec(t, n, s, u) {
        return Yi(t, n, s(Wr(t, n)), u);
      }
      function jo(t, n, s, u) {
        for (var c = t.length, p = u ? c : -1; (u ? p-- : ++p < c) && n(t[p], p, t); )
          ;
        return s ? pn(t, u ? 0 : p, u ? p + 1 : c) : pn(t, u ? p + 1 : 0, u ? c : p);
      }
      function nc(t, n) {
        var s = t;
        return s instanceof xt && (s = s.value()), yl(n, function(u, c) {
          return c.func.apply(c.thisArg, hr([u], c.args));
        }, s);
      }
      function Xl(t, n, s) {
        var u = t.length;
        if (u < 2)
          return u ? vr(t[0]) : [];
        for (var c = -1, p = A(u); ++c < u; )
          for (var m = t[c], w = -1; ++w < u; )
            w != c && (p[c] = $i(p[c] || m, t[w], n, s));
        return vr(xe(p, 1), n, s);
      }
      function rc(t, n, s) {
        for (var u = -1, c = t.length, p = n.length, m = {}; ++u < c; ) {
          var w = u < p ? n[u] : i;
          s(m, t[u], w);
        }
        return m;
      }
      function ql(t) {
        return Qt(t) ? t : [];
      }
      function Vl(t) {
        return typeof t == "function" ? t : ze;
      }
      function _r(t, n) {
        return ht(t) ? t : ru(t, n) ? [t] : Pc(Rt(t));
      }
      var y_ = bt;
      function mr(t, n, s) {
        var u = t.length;
        return s = s === i ? u : s, !n && s >= u ? t : pn(t, n, s);
      }
      var ic = lv || function(t) {
        return we.clearTimeout(t);
      };
      function oc(t, n) {
        if (n)
          return t.slice();
        var s = t.length, u = Aa ? Aa(s) : new t.constructor(s);
        return t.copy(u), u;
      }
      function kl(t) {
        var n = new t.constructor(t.byteLength);
        return new Uo(n).set(new Uo(t)), n;
      }
      function w_(t, n) {
        var s = n ? kl(t.buffer) : t.buffer;
        return new t.constructor(s, t.byteOffset, t.byteLength);
      }
      function x_(t) {
        var n = new t.constructor(t.source, Kf.exec(t));
        return n.lastIndex = t.lastIndex, n;
      }
      function E_(t) {
        return Ui ? Ft(Ui.call(t)) : {};
      }
      function sc(t, n) {
        var s = n ? kl(t.buffer) : t.buffer;
        return new t.constructor(s, t.byteOffset, t.length);
      }
      function lc(t, n) {
        if (t !== n) {
          var s = t !== i, u = t === null, c = t === t, p = je(t), m = n !== i, w = n === null, S = n === n, R = je(n);
          if (!w && !R && !p && t > n || p && m && S && !w && !R || u && m && S || !s && S || !c)
            return 1;
          if (!u && !p && !R && t < n || R && s && c && !u && !p || w && s && c || !m && c || !S)
            return -1;
        }
        return 0;
      }
      function S_(t, n, s) {
        for (var u = -1, c = t.criteria, p = n.criteria, m = c.length, w = s.length; ++u < m; ) {
          var S = lc(c[u], p[u]);
          if (S) {
            if (u >= w)
              return S;
            var R = s[u];
            return S * (R == "desc" ? -1 : 1);
          }
        }
        return t.index - n.index;
      }
      function uc(t, n, s, u) {
        for (var c = -1, p = t.length, m = s.length, w = -1, S = n.length, R = ce(p - m, 0), P = A(S + R), L = !u; ++w < S; )
          P[w] = n[w];
        for (; ++c < m; )
          (L || c < p) && (P[s[c]] = t[c]);
        for (; R--; )
          P[w++] = t[c++];
        return P;
      }
      function fc(t, n, s, u) {
        for (var c = -1, p = t.length, m = -1, w = s.length, S = -1, R = n.length, P = ce(p - w, 0), L = A(P + R), X = !u; ++c < P; )
          L[c] = t[c];
        for (var j = c; ++S < R; )
          L[j + S] = n[S];
        for (; ++m < w; )
          (X || c < p) && (L[j + s[m]] = t[c++]);
        return L;
      }
      function Ke(t, n) {
        var s = -1, u = t.length;
        for (n || (n = A(u)); ++s < u; )
          n[s] = t[s];
        return n;
      }
      function Pn(t, n, s, u) {
        var c = !s;
        s || (s = {});
        for (var p = -1, m = n.length; ++p < m; ) {
          var w = n[p], S = u ? u(s[w], t[w], w, s, t) : i;
          S === i && (S = t[w]), c ? Vn(s, w, S) : Hi(s, w, S);
        }
        return s;
      }
      function T_(t, n) {
        return Pn(t, nu(t), n);
      }
      function A_(t, n) {
        return Pn(t, xc(t), n);
      }
      function ts(t, n) {
        return function(s, u) {
          var c = ht(s) ? Rg : Vv, p = n ? n() : {};
          return c(s, t, et(u, 2), p);
        };
      }
      function ci(t) {
        return bt(function(n, s) {
          var u = -1, c = s.length, p = c > 1 ? s[c - 1] : i, m = c > 2 ? s[2] : i;
          for (p = t.length > 3 && typeof p == "function" ? (c--, p) : i, m && Fe(s[0], s[1], m) && (p = c < 3 ? i : p, c = 1), n = Ft(n); ++u < c; ) {
            var w = s[u];
            w && t(n, w, u, p);
          }
          return n;
        });
      }
      function ac(t, n) {
        return function(s, u) {
          if (s == null)
            return s;
          if (!Ge(s))
            return t(s, u);
          for (var c = s.length, p = n ? c : -1, m = Ft(s); (n ? p-- : ++p < c) && u(m[p], p, m) !== !1; )
            ;
          return s;
        };
      }
      function cc(t) {
        return function(n, s, u) {
          for (var c = -1, p = Ft(n), m = u(n), w = m.length; w--; ) {
            var S = m[t ? w : ++c];
            if (s(p[S], S, p) === !1)
              break;
          }
          return n;
        };
      }
      function C_(t, n, s) {
        var u = n & N, c = zi(t);
        function p() {
          var m = this && this !== we && this instanceof p ? c : t;
          return m.apply(u ? s : this, arguments);
        }
        return p;
      }
      function hc(t) {
        return function(n) {
          n = Rt(n);
          var s = ii(n) ? wn(n) : i, u = s ? s[0] : n.charAt(0), c = s ? mr(s, 1).join("") : n.slice(1);
          return u[t]() + c;
        };
      }
      function hi(t) {
        return function(n) {
          return yl(ah(fh(n).replace(_g, "")), t, "");
        };
      }
      function zi(t) {
        return function() {
          var n = arguments;
          switch (n.length) {
            case 0:
              return new t();
            case 1:
              return new t(n[0]);
            case 2:
              return new t(n[0], n[1]);
            case 3:
              return new t(n[0], n[1], n[2]);
            case 4:
              return new t(n[0], n[1], n[2], n[3]);
            case 5:
              return new t(n[0], n[1], n[2], n[3], n[4]);
            case 6:
              return new t(n[0], n[1], n[2], n[3], n[4], n[5]);
            case 7:
              return new t(n[0], n[1], n[2], n[3], n[4], n[5], n[6]);
          }
          var s = ai(t.prototype), u = t.apply(s, n);
          return Vt(u) ? u : s;
        };
      }
      function I_(t, n, s) {
        var u = zi(t);
        function c() {
          for (var p = arguments.length, m = A(p), w = p, S = di(c); w--; )
            m[w] = arguments[w];
          var R = p < 3 && m[0] !== S && m[p - 1] !== S ? [] : dr(m, S);
          if (p -= R.length, p < s)
            return _c(
              t,
              n,
              es,
              c.placeholder,
              i,
              m,
              R,
              i,
              i,
              s - p
            );
          var P = this && this !== we && this instanceof c ? u : t;
          return Je(P, this, m);
        }
        return c;
      }
      function dc(t) {
        return function(n, s, u) {
          var c = Ft(n);
          if (!Ge(n)) {
            var p = et(s, 3);
            n = pe(n), s = function(w) {
              return p(c[w], w, c);
            };
          }
          var m = t(n, s, u);
          return m > -1 ? c[p ? n[m] : m] : i;
        };
      }
      function pc(t) {
        return Jn(function(n) {
          var s = n.length, u = s, c = hn.prototype.thru;
          for (t && n.reverse(); u--; ) {
            var p = n[u];
            if (typeof p != "function")
              throw new cn(a);
            if (c && !m && os(p) == "wrapper")
              var m = new hn([], !0);
          }
          for (u = m ? u : s; ++u < s; ) {
            p = n[u];
            var w = os(p), S = w == "wrapper" ? tu(p) : i;
            S && iu(S[0]) && S[1] == (It | V | nt | Kt) && !S[4].length && S[9] == 1 ? m = m[os(S[0])].apply(m, S[3]) : m = p.length == 1 && iu(p) ? m[w]() : m.thru(p);
          }
          return function() {
            var R = arguments, P = R[0];
            if (m && R.length == 1 && ht(P))
              return m.plant(P).value();
            for (var L = 0, X = s ? n[L].apply(this, R) : P; ++L < s; )
              X = n[L].call(this, X);
            return X;
          };
        });
      }
      function es(t, n, s, u, c, p, m, w, S, R) {
        var P = n & It, L = n & N, X = n & K, j = n & (V | U), it = n & Lt, mt = X ? i : zi(t);
        function ot() {
          for (var wt = arguments.length, St = A(wt), tn = wt; tn--; )
            St[tn] = arguments[tn];
          if (j)
            var Ne = di(ot), en = Hg(St, Ne);
          if (u && (St = uc(St, u, c, j)), p && (St = fc(St, p, m, j)), wt -= en, j && wt < R) {
            var jt = dr(St, Ne);
            return _c(
              t,
              n,
              es,
              ot.placeholder,
              s,
              St,
              jt,
              w,
              S,
              R - wt
            );
          }
          var Sn = L ? s : this, tr = X ? Sn[t] : t;
          return wt = St.length, w ? St = V_(St, w) : it && wt > 1 && St.reverse(), P && S < wt && (St.length = S), this && this !== we && this instanceof ot && (tr = mt || zi(tr)), tr.apply(Sn, St);
        }
        return ot;
      }
      function gc(t, n) {
        return function(s, u) {
          return n_(s, t, n(u), {});
        };
      }
      function ns(t, n) {
        return function(s, u) {
          var c;
          if (s === i && u === i)
            return n;
          if (s !== i && (c = s), u !== i) {
            if (c === i)
              return u;
            typeof s == "string" || typeof u == "string" ? (s = Qe(s), u = Qe(u)) : (s = tc(s), u = tc(u)), c = t(s, u);
          }
          return c;
        };
      }
      function Jl(t) {
        return Jn(function(n) {
          return n = zt(n, Ze(et())), bt(function(s) {
            var u = this;
            return t(n, function(c) {
              return Je(c, u, s);
            });
          });
        });
      }
      function rs(t, n) {
        n = n === i ? " " : Qe(n);
        var s = n.length;
        if (s < 2)
          return s ? Gl(n, t) : n;
        var u = Gl(n, Go(t / oi(n)));
        return ii(n) ? mr(wn(u), 0, t).join("") : u.slice(0, t);
      }
      function O_(t, n, s, u) {
        var c = n & N, p = zi(t);
        function m() {
          for (var w = -1, S = arguments.length, R = -1, P = u.length, L = A(P + S), X = this && this !== we && this instanceof m ? p : t; ++R < P; )
            L[R] = u[R];
          for (; S--; )
            L[R++] = arguments[++w];
          return Je(X, c ? s : this, L);
        }
        return m;
      }
      function vc(t) {
        return function(n, s, u) {
          return u && typeof u != "number" && Fe(n, s, u) && (s = u = i), n = jn(n), s === i ? (s = n, n = 0) : s = jn(s), u = u === i ? n < s ? 1 : -1 : jn(u), p_(n, s, u, t);
        };
      }
      function is(t) {
        return function(n, s) {
          return typeof n == "string" && typeof s == "string" || (n = vn(n), s = vn(s)), t(n, s);
        };
      }
      function _c(t, n, s, u, c, p, m, w, S, R) {
        var P = n & V, L = P ? m : i, X = P ? i : m, j = P ? p : i, it = P ? i : p;
        n |= P ? nt : Tt, n &= ~(P ? Tt : nt), n & J || (n &= ~(N | K));
        var mt = [
          t,
          n,
          c,
          j,
          L,
          it,
          X,
          w,
          S,
          R
        ], ot = s.apply(i, mt);
        return iu(t) && Oc(ot, mt), ot.placeholder = u, Dc(ot, t, n);
      }
      function Zl(t) {
        var n = ae[t];
        return function(s, u) {
          if (s = vn(s), u = u == null ? 0 : Ae(vt(u), 292), u && Da(s)) {
            var c = (Rt(s) + "e").split("e"), p = n(c[0] + "e" + (+c[1] + u));
            return c = (Rt(p) + "e").split("e"), +(c[0] + "e" + (+c[1] - u));
          }
          return n(s);
        };
      }
      var D_ = ui && 1 / Mo(new ui([, -0]))[1] == lt ? function(t) {
        return new ui(t);
      } : bu;
      function mc(t) {
        return function(n) {
          var s = Ce(n);
          return s == x ? Cl(n) : s == $ ? qg(n) : Ug(n, t(n));
        };
      }
      function kn(t, n, s, u, c, p, m, w) {
        var S = n & K;
        if (!S && typeof t != "function")
          throw new cn(a);
        var R = u ? u.length : 0;
        if (R || (n &= ~(nt | Tt), u = c = i), m = m === i ? m : ce(vt(m), 0), w = w === i ? w : vt(w), R -= c ? c.length : 0, n & Tt) {
          var P = u, L = c;
          u = c = i;
        }
        var X = S ? i : tu(t), j = [
          t,
          n,
          s,
          u,
          c,
          P,
          L,
          p,
          m,
          w
        ];
        if (X && z_(j, X), t = j[0], n = j[1], s = j[2], u = j[3], c = j[4], w = j[9] = j[9] === i ? S ? 0 : t.length : ce(j[9] - R, 0), !w && n & (V | U) && (n &= ~(V | U)), !n || n == N)
          var it = C_(t, n, s);
        else
          n == V || n == U ? it = I_(t, n, w) : (n == nt || n == (N | nt)) && !c.length ? it = O_(t, n, s, u) : it = es.apply(i, j);
        var mt = X ? Qa : Oc;
        return Dc(mt(it, j), t, n);
      }
      function bc(t, n, s, u) {
        return t === i || En(t, li[s]) && !Mt.call(u, s) ? n : t;
      }
      function yc(t, n, s, u, c, p) {
        return Vt(t) && Vt(n) && (p.set(n, t), Zo(t, n, i, yc, p), p.delete(n)), t;
      }
      function R_(t) {
        return Vi(t) ? i : t;
      }
      function wc(t, n, s, u, c, p) {
        var m = s & O, w = t.length, S = n.length;
        if (w != S && !(m && S > w))
          return !1;
        var R = p.get(t), P = p.get(n);
        if (R && P)
          return R == n && P == t;
        var L = -1, X = !0, j = s & F ? new Lr() : i;
        for (p.set(t, n), p.set(n, t); ++L < w; ) {
          var it = t[L], mt = n[L];
          if (u)
            var ot = m ? u(mt, it, L, n, t, p) : u(it, mt, L, t, n, p);
          if (ot !== i) {
            if (ot)
              continue;
            X = !1;
            break;
          }
          if (j) {
            if (!wl(n, function(wt, St) {
              if (!Fi(j, St) && (it === wt || c(it, wt, s, u, p)))
                return j.push(St);
            })) {
              X = !1;
              break;
            }
          } else if (!(it === mt || c(it, mt, s, u, p))) {
            X = !1;
            break;
          }
        }
        return p.delete(t), p.delete(n), X;
      }
      function P_(t, n, s, u, c, p, m) {
        switch (s) {
          case At:
            if (t.byteLength != n.byteLength || t.byteOffset != n.byteOffset)
              return !1;
            t = t.buffer, n = n.buffer;
          case _t:
            return !(t.byteLength != n.byteLength || !p(new Uo(t), new Uo(n)));
          case Yn:
          case lr:
          case I:
            return En(+t, +n);
          case fr:
            return t.name == n.name && t.message == n.message;
          case G:
          case B:
            return t == n + "";
          case x:
            var w = Cl;
          case $:
            var S = u & O;
            if (w || (w = Mo), t.size != n.size && !S)
              return !1;
            var R = m.get(t);
            if (R)
              return R == n;
            u |= F, m.set(t, n);
            var P = wc(w(t), w(n), u, c, p, m);
            return m.delete(t), P;
          case tt:
            if (Ui)
              return Ui.call(t) == Ui.call(n);
        }
        return !1;
      }
      function M_(t, n, s, u, c, p) {
        var m = s & O, w = Ql(t), S = w.length, R = Ql(n), P = R.length;
        if (S != P && !m)
          return !1;
        for (var L = S; L--; ) {
          var X = w[L];
          if (!(m ? X in n : Mt.call(n, X)))
            return !1;
        }
        var j = p.get(t), it = p.get(n);
        if (j && it)
          return j == n && it == t;
        var mt = !0;
        p.set(t, n), p.set(n, t);
        for (var ot = m; ++L < S; ) {
          X = w[L];
          var wt = t[X], St = n[X];
          if (u)
            var tn = m ? u(St, wt, X, n, t, p) : u(wt, St, X, t, n, p);
          if (!(tn === i ? wt === St || c(wt, St, s, u, p) : tn)) {
            mt = !1;
            break;
          }
          ot || (ot = X == "constructor");
        }
        if (mt && !ot) {
          var Ne = t.constructor, en = n.constructor;
          Ne != en && "constructor" in t && "constructor" in n && !(typeof Ne == "function" && Ne instanceof Ne && typeof en == "function" && en instanceof en) && (mt = !1);
        }
        return p.delete(t), p.delete(n), mt;
      }
      function Jn(t) {
        return su(Cc(t, i, Lc), t + "");
      }
      function Ql(t) {
        return $a(t, pe, nu);
      }
      function jl(t) {
        return $a(t, Ye, xc);
      }
      var tu = zo ? function(t) {
        return zo.get(t);
      } : bu;
      function os(t) {
        for (var n = t.name + "", s = fi[n], u = Mt.call(fi, n) ? s.length : 0; u--; ) {
          var c = s[u], p = c.func;
          if (p == null || p == t)
            return c.name;
        }
        return n;
      }
      function di(t) {
        var n = Mt.call(d, "placeholder") ? d : t;
        return n.placeholder;
      }
      function et() {
        var t = d.iteratee || _u;
        return t = t === _u ? Ya : t, arguments.length ? t(arguments[0], arguments[1]) : t;
      }
      function ss(t, n) {
        var s = t.__data__;
        return $_(n) ? s[typeof n == "string" ? "string" : "hash"] : s.map;
      }
      function eu(t) {
        for (var n = pe(t), s = n.length; s--; ) {
          var u = n[s], c = t[u];
          n[s] = [u, c, Tc(c)];
        }
        return n;
      }
      function Ur(t, n) {
        var s = Yg(t, n);
        return Ga(s) ? s : i;
      }
      function F_(t) {
        var n = Mt.call(t, Fr), s = t[Fr];
        try {
          t[Fr] = i;
          var u = !0;
        } catch {
        }
        var c = Bo.call(t);
        return u && (n ? t[Fr] = s : delete t[Fr]), c;
      }
      var nu = Ol ? function(t) {
        return t == null ? [] : (t = Ft(t), cr(Ol(t), function(n) {
          return Ia.call(t, n);
        }));
      } : yu, xc = Ol ? function(t) {
        for (var n = []; t; )
          hr(n, nu(t)), t = Ho(t);
        return n;
      } : yu, Ce = Me;
      (Dl && Ce(new Dl(new ArrayBuffer(1))) != At || Li && Ce(new Li()) != x || Rl && Ce(Rl.resolve()) != W || ui && Ce(new ui()) != $ || Bi && Ce(new Bi()) != Z) && (Ce = function(t) {
        var n = Me(t), s = n == M ? t.constructor : i, u = s ? Hr(s) : "";
        if (u)
          switch (u) {
            case vv:
              return At;
            case _v:
              return x;
            case mv:
              return W;
            case bv:
              return $;
            case yv:
              return Z;
          }
        return n;
      });
      function N_(t, n, s) {
        for (var u = -1, c = s.length; ++u < c; ) {
          var p = s[u], m = p.size;
          switch (p.type) {
            case "drop":
              t += m;
              break;
            case "dropRight":
              n -= m;
              break;
            case "take":
              n = Ae(n, t + m);
              break;
            case "takeRight":
              t = ce(t, n - m);
              break;
          }
        }
        return { start: t, end: n };
      }
      function L_(t) {
        var n = t.match(Kp);
        return n ? n[1].split(Gp) : [];
      }
      function Ec(t, n, s) {
        n = _r(n, t);
        for (var u = -1, c = n.length, p = !1; ++u < c; ) {
          var m = Mn(n[u]);
          if (!(p = t != null && s(t, m)))
            break;
          t = t[m];
        }
        return p || ++u != c ? p : (c = t == null ? 0 : t.length, !!c && ds(c) && Zn(m, c) && (ht(t) || $r(t)));
      }
      function B_(t) {
        var n = t.length, s = new t.constructor(n);
        return n && typeof t[0] == "string" && Mt.call(t, "index") && (s.index = t.index, s.input = t.input), s;
      }
      function Sc(t) {
        return typeof t.constructor == "function" && !Xi(t) ? ai(Ho(t)) : {};
      }
      function W_(t, n, s) {
        var u = t.constructor;
        switch (n) {
          case _t:
            return kl(t);
          case Yn:
          case lr:
            return new u(+t);
          case At:
            return w_(t, s);
          case Ct:
          case me:
          case re:
          case Pe:
          case be:
          case zn:
          case ei:
          case ye:
          case $e:
            return sc(t, s);
          case x:
            return new u();
          case I:
          case B:
            return new u(t);
          case G:
            return x_(t);
          case $:
            return new u();
          case tt:
            return E_(t);
        }
      }
      function U_(t, n) {
        var s = n.length;
        if (!s)
          return t;
        var u = s - 1;
        return n[u] = (s > 1 ? "& " : "") + n[u], n = n.join(s > 2 ? ", " : " "), t.replace($p, `{
/* [wrapped with ` + n + `] */
`);
      }
      function H_(t) {
        return ht(t) || $r(t) || !!(Oa && t && t[Oa]);
      }
      function Zn(t, n) {
        var s = typeof t;
        return n = n ?? rt, !!n && (s == "number" || s != "symbol" && Qp.test(t)) && t > -1 && t % 1 == 0 && t < n;
      }
      function Fe(t, n, s) {
        if (!Vt(s))
          return !1;
        var u = typeof n;
        return (u == "number" ? Ge(s) && Zn(n, s.length) : u == "string" && n in s) ? En(s[n], t) : !1;
      }
      function ru(t, n) {
        if (ht(t))
          return !1;
        var s = typeof t;
        return s == "number" || s == "symbol" || s == "boolean" || t == null || je(t) ? !0 : Bp.test(t) || !Lp.test(t) || n != null && t in Ft(n);
      }
      function $_(t) {
        var n = typeof t;
        return n == "string" || n == "number" || n == "symbol" || n == "boolean" ? t !== "__proto__" : t === null;
      }
      function iu(t) {
        var n = os(t), s = d[n];
        if (typeof s != "function" || !(n in xt.prototype))
          return !1;
        if (t === s)
          return !0;
        var u = tu(s);
        return !!u && t === u[0];
      }
      function K_(t) {
        return !!Ta && Ta in t;
      }
      var G_ = No ? Qn : wu;
      function Xi(t) {
        var n = t && t.constructor, s = typeof n == "function" && n.prototype || li;
        return t === s;
      }
      function Tc(t) {
        return t === t && !Vt(t);
      }
      function Ac(t, n) {
        return function(s) {
          return s == null ? !1 : s[t] === n && (n !== i || t in Ft(s));
        };
      }
      function Y_(t) {
        var n = cs(t, function(u) {
          return s.size === v && s.clear(), u;
        }), s = n.cache;
        return n;
      }
      function z_(t, n) {
        var s = t[1], u = n[1], c = s | u, p = c < (N | K | It), m = u == It && s == V || u == It && s == Kt && t[7].length <= n[8] || u == (It | Kt) && n[7].length <= n[8] && s == V;
        if (!(p || m))
          return t;
        u & N && (t[2] = n[2], c |= s & N ? 0 : J);
        var w = n[3];
        if (w) {
          var S = t[3];
          t[3] = S ? uc(S, w, n[4]) : w, t[4] = S ? dr(t[3], b) : n[4];
        }
        return w = n[5], w && (S = t[5], t[5] = S ? fc(S, w, n[6]) : w, t[6] = S ? dr(t[5], b) : n[6]), w = n[7], w && (t[7] = w), u & It && (t[8] = t[8] == null ? n[8] : Ae(t[8], n[8])), t[9] == null && (t[9] = n[9]), t[0] = n[0], t[1] = c, t;
      }
      function X_(t) {
        var n = [];
        if (t != null)
          for (var s in Ft(t))
            n.push(s);
        return n;
      }
      function q_(t) {
        return Bo.call(t);
      }
      function Cc(t, n, s) {
        return n = ce(n === i ? t.length - 1 : n, 0), function() {
          for (var u = arguments, c = -1, p = ce(u.length - n, 0), m = A(p); ++c < p; )
            m[c] = u[n + c];
          c = -1;
          for (var w = A(n + 1); ++c < n; )
            w[c] = u[c];
          return w[n] = s(m), Je(t, this, w);
        };
      }
      function Ic(t, n) {
        return n.length < 2 ? t : Wr(t, pn(n, 0, -1));
      }
      function V_(t, n) {
        for (var s = t.length, u = Ae(n.length, s), c = Ke(t); u--; ) {
          var p = n[u];
          t[u] = Zn(p, s) ? c[p] : i;
        }
        return t;
      }
      function ou(t, n) {
        if (!(n === "constructor" && typeof t[n] == "function") && n != "__proto__")
          return t[n];
      }
      var Oc = Rc(Qa), qi = fv || function(t, n) {
        return we.setTimeout(t, n);
      }, su = Rc(__);
      function Dc(t, n, s) {
        var u = n + "";
        return su(t, U_(u, k_(L_(u), s)));
      }
      function Rc(t) {
        var n = 0, s = 0;
        return function() {
          var u = dv(), c = Gt - (u - s);
          if (s = u, c > 0) {
            if (++n >= ge)
              return arguments[0];
          } else
            n = 0;
          return t.apply(i, arguments);
        };
      }
      function ls(t, n) {
        var s = -1, u = t.length, c = u - 1;
        for (n = n === i ? u : n; ++s < n; ) {
          var p = Kl(s, c), m = t[p];
          t[p] = t[s], t[s] = m;
        }
        return t.length = n, t;
      }
      var Pc = Y_(function(t) {
        var n = [];
        return t.charCodeAt(0) === 46 && n.push(""), t.replace(Wp, function(s, u, c, p) {
          n.push(c ? p.replace(Xp, "$1") : u || s);
        }), n;
      });
      function Mn(t) {
        if (typeof t == "string" || je(t))
          return t;
        var n = t + "";
        return n == "0" && 1 / t == -lt ? "-0" : n;
      }
      function Hr(t) {
        if (t != null) {
          try {
            return Lo.call(t);
          } catch {
          }
          try {
            return t + "";
          } catch {
          }
        }
        return "";
      }
      function k_(t, n) {
        return an(ne, function(s) {
          var u = "_." + s[0];
          n & s[1] && !Ro(t, u) && t.push(u);
        }), t.sort();
      }
      function Mc(t) {
        if (t instanceof xt)
          return t.clone();
        var n = new hn(t.__wrapped__, t.__chain__);
        return n.__actions__ = Ke(t.__actions__), n.__index__ = t.__index__, n.__values__ = t.__values__, n;
      }
      function J_(t, n, s) {
        (s ? Fe(t, n, s) : n === i) ? n = 1 : n = ce(vt(n), 0);
        var u = t == null ? 0 : t.length;
        if (!u || n < 1)
          return [];
        for (var c = 0, p = 0, m = A(Go(u / n)); c < u; )
          m[p++] = pn(t, c, c += n);
        return m;
      }
      function Z_(t) {
        for (var n = -1, s = t == null ? 0 : t.length, u = 0, c = []; ++n < s; ) {
          var p = t[n];
          p && (c[u++] = p);
        }
        return c;
      }
      function Q_() {
        var t = arguments.length;
        if (!t)
          return [];
        for (var n = A(t - 1), s = arguments[0], u = t; u--; )
          n[u - 1] = arguments[u];
        return hr(ht(s) ? Ke(s) : [s], xe(n, 1));
      }
      var j_ = bt(function(t, n) {
        return Qt(t) ? $i(t, xe(n, 1, Qt, !0)) : [];
      }), tm = bt(function(t, n) {
        var s = gn(n);
        return Qt(s) && (s = i), Qt(t) ? $i(t, xe(n, 1, Qt, !0), et(s, 2)) : [];
      }), em = bt(function(t, n) {
        var s = gn(n);
        return Qt(s) && (s = i), Qt(t) ? $i(t, xe(n, 1, Qt, !0), i, s) : [];
      });
      function nm(t, n, s) {
        var u = t == null ? 0 : t.length;
        return u ? (n = s || n === i ? 1 : vt(n), pn(t, n < 0 ? 0 : n, u)) : [];
      }
      function rm(t, n, s) {
        var u = t == null ? 0 : t.length;
        return u ? (n = s || n === i ? 1 : vt(n), n = u - n, pn(t, 0, n < 0 ? 0 : n)) : [];
      }
      function im(t, n) {
        return t && t.length ? jo(t, et(n, 3), !0, !0) : [];
      }
      function om(t, n) {
        return t && t.length ? jo(t, et(n, 3), !0) : [];
      }
      function sm(t, n, s, u) {
        var c = t == null ? 0 : t.length;
        return c ? (s && typeof s != "number" && Fe(t, n, s) && (s = 0, u = c), Qv(t, n, s, u)) : [];
      }
      function Fc(t, n, s) {
        var u = t == null ? 0 : t.length;
        if (!u)
          return -1;
        var c = s == null ? 0 : vt(s);
        return c < 0 && (c = ce(u + c, 0)), Po(t, et(n, 3), c);
      }
      function Nc(t, n, s) {
        var u = t == null ? 0 : t.length;
        if (!u)
          return -1;
        var c = u - 1;
        return s !== i && (c = vt(s), c = s < 0 ? ce(u + c, 0) : Ae(c, u - 1)), Po(t, et(n, 3), c, !0);
      }
      function Lc(t) {
        var n = t == null ? 0 : t.length;
        return n ? xe(t, 1) : [];
      }
      function lm(t) {
        var n = t == null ? 0 : t.length;
        return n ? xe(t, lt) : [];
      }
      function um(t, n) {
        var s = t == null ? 0 : t.length;
        return s ? (n = n === i ? 1 : vt(n), xe(t, n)) : [];
      }
      function fm(t) {
        for (var n = -1, s = t == null ? 0 : t.length, u = {}; ++n < s; ) {
          var c = t[n];
          u[c[0]] = c[1];
        }
        return u;
      }
      function Bc(t) {
        return t && t.length ? t[0] : i;
      }
      function am(t, n, s) {
        var u = t == null ? 0 : t.length;
        if (!u)
          return -1;
        var c = s == null ? 0 : vt(s);
        return c < 0 && (c = ce(u + c, 0)), ri(t, n, c);
      }
      function cm(t) {
        var n = t == null ? 0 : t.length;
        return n ? pn(t, 0, -1) : [];
      }
      var hm = bt(function(t) {
        var n = zt(t, ql);
        return n.length && n[0] === t[0] ? Bl(n) : [];
      }), dm = bt(function(t) {
        var n = gn(t), s = zt(t, ql);
        return n === gn(s) ? n = i : s.pop(), s.length && s[0] === t[0] ? Bl(s, et(n, 2)) : [];
      }), pm = bt(function(t) {
        var n = gn(t), s = zt(t, ql);
        return n = typeof n == "function" ? n : i, n && s.pop(), s.length && s[0] === t[0] ? Bl(s, i, n) : [];
      });
      function gm(t, n) {
        return t == null ? "" : cv.call(t, n);
      }
      function gn(t) {
        var n = t == null ? 0 : t.length;
        return n ? t[n - 1] : i;
      }
      function vm(t, n, s) {
        var u = t == null ? 0 : t.length;
        if (!u)
          return -1;
        var c = u;
        return s !== i && (c = vt(s), c = c < 0 ? ce(u + c, 0) : Ae(c, u - 1)), n === n ? kg(t, n, c) : Po(t, _a, c, !0);
      }
      function _m(t, n) {
        return t && t.length ? Va(t, vt(n)) : i;
      }
      var mm = bt(Wc);
      function Wc(t, n) {
        return t && t.length && n && n.length ? $l(t, n) : t;
      }
      function bm(t, n, s) {
        return t && t.length && n && n.length ? $l(t, n, et(s, 2)) : t;
      }
      function ym(t, n, s) {
        return t && t.length && n && n.length ? $l(t, n, i, s) : t;
      }
      var wm = Jn(function(t, n) {
        var s = t == null ? 0 : t.length, u = Ml(t, n);
        return Za(t, zt(n, function(c) {
          return Zn(c, s) ? +c : c;
        }).sort(lc)), u;
      });
      function xm(t, n) {
        var s = [];
        if (!(t && t.length))
          return s;
        var u = -1, c = [], p = t.length;
        for (n = et(n, 3); ++u < p; ) {
          var m = t[u];
          n(m, u, t) && (s.push(m), c.push(u));
        }
        return Za(t, c), s;
      }
      function lu(t) {
        return t == null ? t : gv.call(t);
      }
      function Em(t, n, s) {
        var u = t == null ? 0 : t.length;
        return u ? (s && typeof s != "number" && Fe(t, n, s) ? (n = 0, s = u) : (n = n == null ? 0 : vt(n), s = s === i ? u : vt(s)), pn(t, n, s)) : [];
      }
      function Sm(t, n) {
        return Qo(t, n);
      }
      function Tm(t, n, s) {
        return Yl(t, n, et(s, 2));
      }
      function Am(t, n) {
        var s = t == null ? 0 : t.length;
        if (s) {
          var u = Qo(t, n);
          if (u < s && En(t[u], n))
            return u;
        }
        return -1;
      }
      function Cm(t, n) {
        return Qo(t, n, !0);
      }
      function Im(t, n, s) {
        return Yl(t, n, et(s, 2), !0);
      }
      function Om(t, n) {
        var s = t == null ? 0 : t.length;
        if (s) {
          var u = Qo(t, n, !0) - 1;
          if (En(t[u], n))
            return u;
        }
        return -1;
      }
      function Dm(t) {
        return t && t.length ? ja(t) : [];
      }
      function Rm(t, n) {
        return t && t.length ? ja(t, et(n, 2)) : [];
      }
      function Pm(t) {
        var n = t == null ? 0 : t.length;
        return n ? pn(t, 1, n) : [];
      }
      function Mm(t, n, s) {
        return t && t.length ? (n = s || n === i ? 1 : vt(n), pn(t, 0, n < 0 ? 0 : n)) : [];
      }
      function Fm(t, n, s) {
        var u = t == null ? 0 : t.length;
        return u ? (n = s || n === i ? 1 : vt(n), n = u - n, pn(t, n < 0 ? 0 : n, u)) : [];
      }
      function Nm(t, n) {
        return t && t.length ? jo(t, et(n, 3), !1, !0) : [];
      }
      function Lm(t, n) {
        return t && t.length ? jo(t, et(n, 3)) : [];
      }
      var Bm = bt(function(t) {
        return vr(xe(t, 1, Qt, !0));
      }), Wm = bt(function(t) {
        var n = gn(t);
        return Qt(n) && (n = i), vr(xe(t, 1, Qt, !0), et(n, 2));
      }), Um = bt(function(t) {
        var n = gn(t);
        return n = typeof n == "function" ? n : i, vr(xe(t, 1, Qt, !0), i, n);
      });
      function Hm(t) {
        return t && t.length ? vr(t) : [];
      }
      function $m(t, n) {
        return t && t.length ? vr(t, et(n, 2)) : [];
      }
      function Km(t, n) {
        return n = typeof n == "function" ? n : i, t && t.length ? vr(t, i, n) : [];
      }
      function uu(t) {
        if (!(t && t.length))
          return [];
        var n = 0;
        return t = cr(t, function(s) {
          if (Qt(s))
            return n = ce(s.length, n), !0;
        }), Tl(n, function(s) {
          return zt(t, xl(s));
        });
      }
      function Uc(t, n) {
        if (!(t && t.length))
          return [];
        var s = uu(t);
        return n == null ? s : zt(s, function(u) {
          return Je(n, i, u);
        });
      }
      var Gm = bt(function(t, n) {
        return Qt(t) ? $i(t, n) : [];
      }), Ym = bt(function(t) {
        return Xl(cr(t, Qt));
      }), zm = bt(function(t) {
        var n = gn(t);
        return Qt(n) && (n = i), Xl(cr(t, Qt), et(n, 2));
      }), Xm = bt(function(t) {
        var n = gn(t);
        return n = typeof n == "function" ? n : i, Xl(cr(t, Qt), i, n);
      }), qm = bt(uu);
      function Vm(t, n) {
        return rc(t || [], n || [], Hi);
      }
      function km(t, n) {
        return rc(t || [], n || [], Yi);
      }
      var Jm = bt(function(t) {
        var n = t.length, s = n > 1 ? t[n - 1] : i;
        return s = typeof s == "function" ? (t.pop(), s) : i, Uc(t, s);
      });
      function Hc(t) {
        var n = d(t);
        return n.__chain__ = !0, n;
      }
      function Zm(t, n) {
        return n(t), t;
      }
      function us(t, n) {
        return n(t);
      }
      var Qm = Jn(function(t) {
        var n = t.length, s = n ? t[0] : 0, u = this.__wrapped__, c = function(p) {
          return Ml(p, t);
        };
        return n > 1 || this.__actions__.length || !(u instanceof xt) || !Zn(s) ? this.thru(c) : (u = u.slice(s, +s + (n ? 1 : 0)), u.__actions__.push({
          func: us,
          args: [c],
          thisArg: i
        }), new hn(u, this.__chain__).thru(function(p) {
          return n && !p.length && p.push(i), p;
        }));
      });
      function jm() {
        return Hc(this);
      }
      function t0() {
        return new hn(this.value(), this.__chain__);
      }
      function e0() {
        this.__values__ === i && (this.__values__ = th(this.value()));
        var t = this.__index__ >= this.__values__.length, n = t ? i : this.__values__[this.__index__++];
        return { done: t, value: n };
      }
      function n0() {
        return this;
      }
      function r0(t) {
        for (var n, s = this; s instanceof qo; ) {
          var u = Mc(s);
          u.__index__ = 0, u.__values__ = i, n ? c.__wrapped__ = u : n = u;
          var c = u;
          s = s.__wrapped__;
        }
        return c.__wrapped__ = t, n;
      }
      function i0() {
        var t = this.__wrapped__;
        if (t instanceof xt) {
          var n = t;
          return this.__actions__.length && (n = new xt(this)), n = n.reverse(), n.__actions__.push({
            func: us,
            args: [lu],
            thisArg: i
          }), new hn(n, this.__chain__);
        }
        return this.thru(lu);
      }
      function o0() {
        return nc(this.__wrapped__, this.__actions__);
      }
      var s0 = ts(function(t, n, s) {
        Mt.call(t, s) ? ++t[s] : Vn(t, s, 1);
      });
      function l0(t, n, s) {
        var u = ht(t) ? ga : Zv;
        return s && Fe(t, n, s) && (n = i), u(t, et(n, 3));
      }
      function u0(t, n) {
        var s = ht(t) ? cr : Ua;
        return s(t, et(n, 3));
      }
      var f0 = dc(Fc), a0 = dc(Nc);
      function c0(t, n) {
        return xe(fs(t, n), 1);
      }
      function h0(t, n) {
        return xe(fs(t, n), lt);
      }
      function d0(t, n, s) {
        return s = s === i ? 1 : vt(s), xe(fs(t, n), s);
      }
      function $c(t, n) {
        var s = ht(t) ? an : gr;
        return s(t, et(n, 3));
      }
      function Kc(t, n) {
        var s = ht(t) ? Pg : Wa;
        return s(t, et(n, 3));
      }
      var p0 = ts(function(t, n, s) {
        Mt.call(t, s) ? t[s].push(n) : Vn(t, s, [n]);
      });
      function g0(t, n, s, u) {
        t = Ge(t) ? t : gi(t), s = s && !u ? vt(s) : 0;
        var c = t.length;
        return s < 0 && (s = ce(c + s, 0)), ps(t) ? s <= c && t.indexOf(n, s) > -1 : !!c && ri(t, n, s) > -1;
      }
      var v0 = bt(function(t, n, s) {
        var u = -1, c = typeof n == "function", p = Ge(t) ? A(t.length) : [];
        return gr(t, function(m) {
          p[++u] = c ? Je(n, m, s) : Ki(m, n, s);
        }), p;
      }), _0 = ts(function(t, n, s) {
        Vn(t, s, n);
      });
      function fs(t, n) {
        var s = ht(t) ? zt : za;
        return s(t, et(n, 3));
      }
      function m0(t, n, s, u) {
        return t == null ? [] : (ht(n) || (n = n == null ? [] : [n]), s = u ? i : s, ht(s) || (s = s == null ? [] : [s]), ka(t, n, s));
      }
      var b0 = ts(function(t, n, s) {
        t[s ? 0 : 1].push(n);
      }, function() {
        return [[], []];
      });
      function y0(t, n, s) {
        var u = ht(t) ? yl : ba, c = arguments.length < 3;
        return u(t, et(n, 4), s, c, gr);
      }
      function w0(t, n, s) {
        var u = ht(t) ? Mg : ba, c = arguments.length < 3;
        return u(t, et(n, 4), s, c, Wa);
      }
      function x0(t, n) {
        var s = ht(t) ? cr : Ua;
        return s(t, hs(et(n, 3)));
      }
      function E0(t) {
        var n = ht(t) ? Fa : g_;
        return n(t);
      }
      function S0(t, n, s) {
        (s ? Fe(t, n, s) : n === i) ? n = 1 : n = vt(n);
        var u = ht(t) ? Xv : v_;
        return u(t, n);
      }
      function T0(t) {
        var n = ht(t) ? qv : m_;
        return n(t);
      }
      function A0(t) {
        if (t == null)
          return 0;
        if (Ge(t))
          return ps(t) ? oi(t) : t.length;
        var n = Ce(t);
        return n == x || n == $ ? t.size : Ul(t).length;
      }
      function C0(t, n, s) {
        var u = ht(t) ? wl : b_;
        return s && Fe(t, n, s) && (n = i), u(t, et(n, 3));
      }
      var I0 = bt(function(t, n) {
        if (t == null)
          return [];
        var s = n.length;
        return s > 1 && Fe(t, n[0], n[1]) ? n = [] : s > 2 && Fe(n[0], n[1], n[2]) && (n = [n[0]]), ka(t, xe(n, 1), []);
      }), as = uv || function() {
        return we.Date.now();
      };
      function O0(t, n) {
        if (typeof n != "function")
          throw new cn(a);
        return t = vt(t), function() {
          if (--t < 1)
            return n.apply(this, arguments);
        };
      }
      function Gc(t, n, s) {
        return n = s ? i : n, n = t && n == null ? t.length : n, kn(t, It, i, i, i, i, n);
      }
      function Yc(t, n) {
        var s;
        if (typeof n != "function")
          throw new cn(a);
        return t = vt(t), function() {
          return --t > 0 && (s = n.apply(this, arguments)), t <= 1 && (n = i), s;
        };
      }
      var fu = bt(function(t, n, s) {
        var u = N;
        if (s.length) {
          var c = dr(s, di(fu));
          u |= nt;
        }
        return kn(t, u, n, s, c);
      }), zc = bt(function(t, n, s) {
        var u = N | K;
        if (s.length) {
          var c = dr(s, di(zc));
          u |= nt;
        }
        return kn(n, u, t, s, c);
      });
      function Xc(t, n, s) {
        n = s ? i : n;
        var u = kn(t, V, i, i, i, i, i, n);
        return u.placeholder = Xc.placeholder, u;
      }
      function qc(t, n, s) {
        n = s ? i : n;
        var u = kn(t, U, i, i, i, i, i, n);
        return u.placeholder = qc.placeholder, u;
      }
      function Vc(t, n, s) {
        var u, c, p, m, w, S, R = 0, P = !1, L = !1, X = !0;
        if (typeof t != "function")
          throw new cn(a);
        n = vn(n) || 0, Vt(s) && (P = !!s.leading, L = "maxWait" in s, p = L ? ce(vn(s.maxWait) || 0, n) : p, X = "trailing" in s ? !!s.trailing : X);
        function j(jt) {
          var Sn = u, tr = c;
          return u = c = i, R = jt, m = t.apply(tr, Sn), m;
        }
        function it(jt) {
          return R = jt, w = qi(wt, n), P ? j(jt) : m;
        }
        function mt(jt) {
          var Sn = jt - S, tr = jt - R, dh = n - Sn;
          return L ? Ae(dh, p - tr) : dh;
        }
        function ot(jt) {
          var Sn = jt - S, tr = jt - R;
          return S === i || Sn >= n || Sn < 0 || L && tr >= p;
        }
        function wt() {
          var jt = as();
          if (ot(jt))
            return St(jt);
          w = qi(wt, mt(jt));
        }
        function St(jt) {
          return w = i, X && u ? j(jt) : (u = c = i, m);
        }
        function tn() {
          w !== i && ic(w), R = 0, u = S = c = w = i;
        }
        function Ne() {
          return w === i ? m : St(as());
        }
        function en() {
          var jt = as(), Sn = ot(jt);
          if (u = arguments, c = this, S = jt, Sn) {
            if (w === i)
              return it(S);
            if (L)
              return ic(w), w = qi(wt, n), j(S);
          }
          return w === i && (w = qi(wt, n)), m;
        }
        return en.cancel = tn, en.flush = Ne, en;
      }
      var D0 = bt(function(t, n) {
        return Ba(t, 1, n);
      }), R0 = bt(function(t, n, s) {
        return Ba(t, vn(n) || 0, s);
      });
      function P0(t) {
        return kn(t, Lt);
      }
      function cs(t, n) {
        if (typeof t != "function" || n != null && typeof n != "function")
          throw new cn(a);
        var s = function() {
          var u = arguments, c = n ? n.apply(this, u) : u[0], p = s.cache;
          if (p.has(c))
            return p.get(c);
          var m = t.apply(this, u);
          return s.cache = p.set(c, m) || p, m;
        };
        return s.cache = new (cs.Cache || qn)(), s;
      }
      cs.Cache = qn;
      function hs(t) {
        if (typeof t != "function")
          throw new cn(a);
        return function() {
          var n = arguments;
          switch (n.length) {
            case 0:
              return !t.call(this);
            case 1:
              return !t.call(this, n[0]);
            case 2:
              return !t.call(this, n[0], n[1]);
            case 3:
              return !t.call(this, n[0], n[1], n[2]);
          }
          return !t.apply(this, n);
        };
      }
      function M0(t) {
        return Yc(2, t);
      }
      var F0 = y_(function(t, n) {
        n = n.length == 1 && ht(n[0]) ? zt(n[0], Ze(et())) : zt(xe(n, 1), Ze(et()));
        var s = n.length;
        return bt(function(u) {
          for (var c = -1, p = Ae(u.length, s); ++c < p; )
            u[c] = n[c].call(this, u[c]);
          return Je(t, this, u);
        });
      }), au = bt(function(t, n) {
        var s = dr(n, di(au));
        return kn(t, nt, i, n, s);
      }), kc = bt(function(t, n) {
        var s = dr(n, di(kc));
        return kn(t, Tt, i, n, s);
      }), N0 = Jn(function(t, n) {
        return kn(t, Kt, i, i, i, n);
      });
      function L0(t, n) {
        if (typeof t != "function")
          throw new cn(a);
        return n = n === i ? n : vt(n), bt(t, n);
      }
      function B0(t, n) {
        if (typeof t != "function")
          throw new cn(a);
        return n = n == null ? 0 : ce(vt(n), 0), bt(function(s) {
          var u = s[n], c = mr(s, 0, n);
          return u && hr(c, u), Je(t, this, c);
        });
      }
      function W0(t, n, s) {
        var u = !0, c = !0;
        if (typeof t != "function")
          throw new cn(a);
        return Vt(s) && (u = "leading" in s ? !!s.leading : u, c = "trailing" in s ? !!s.trailing : c), Vc(t, n, {
          leading: u,
          maxWait: n,
          trailing: c
        });
      }
      function U0(t) {
        return Gc(t, 1);
      }
      function H0(t, n) {
        return au(Vl(n), t);
      }
      function $0() {
        if (!arguments.length)
          return [];
        var t = arguments[0];
        return ht(t) ? t : [t];
      }
      function K0(t) {
        return dn(t, C);
      }
      function G0(t, n) {
        return n = typeof n == "function" ? n : i, dn(t, C, n);
      }
      function Y0(t) {
        return dn(t, _ | C);
      }
      function z0(t, n) {
        return n = typeof n == "function" ? n : i, dn(t, _ | C, n);
      }
      function X0(t, n) {
        return n == null || La(t, n, pe(n));
      }
      function En(t, n) {
        return t === n || t !== t && n !== n;
      }
      var q0 = is(Ll), V0 = is(function(t, n) {
        return t >= n;
      }), $r = Ka(/* @__PURE__ */ function() {
        return arguments;
      }()) ? Ka : function(t) {
        return Jt(t) && Mt.call(t, "callee") && !Ia.call(t, "callee");
      }, ht = A.isArray, k0 = fa ? Ze(fa) : r_;
      function Ge(t) {
        return t != null && ds(t.length) && !Qn(t);
      }
      function Qt(t) {
        return Jt(t) && Ge(t);
      }
      function J0(t) {
        return t === !0 || t === !1 || Jt(t) && Me(t) == Yn;
      }
      var br = av || wu, Z0 = aa ? Ze(aa) : i_;
      function Q0(t) {
        return Jt(t) && t.nodeType === 1 && !Vi(t);
      }
      function j0(t) {
        if (t == null)
          return !0;
        if (Ge(t) && (ht(t) || typeof t == "string" || typeof t.splice == "function" || br(t) || pi(t) || $r(t)))
          return !t.length;
        var n = Ce(t);
        if (n == x || n == $)
          return !t.size;
        if (Xi(t))
          return !Ul(t).length;
        for (var s in t)
          if (Mt.call(t, s))
            return !1;
        return !0;
      }
      function tb(t, n) {
        return Gi(t, n);
      }
      function eb(t, n, s) {
        s = typeof s == "function" ? s : i;
        var u = s ? s(t, n) : i;
        return u === i ? Gi(t, n, i, s) : !!u;
      }
      function cu(t) {
        if (!Jt(t))
          return !1;
        var n = Me(t);
        return n == fr || n == ur || typeof t.message == "string" && typeof t.name == "string" && !Vi(t);
      }
      function nb(t) {
        return typeof t == "number" && Da(t);
      }
      function Qn(t) {
        if (!Vt(t))
          return !1;
        var n = Me(t);
        return n == ar || n == y || n == ti || n == z;
      }
      function Jc(t) {
        return typeof t == "number" && t == vt(t);
      }
      function ds(t) {
        return typeof t == "number" && t > -1 && t % 1 == 0 && t <= rt;
      }
      function Vt(t) {
        var n = typeof t;
        return t != null && (n == "object" || n == "function");
      }
      function Jt(t) {
        return t != null && typeof t == "object";
      }
      var Zc = ca ? Ze(ca) : s_;
      function rb(t, n) {
        return t === n || Wl(t, n, eu(n));
      }
      function ib(t, n, s) {
        return s = typeof s == "function" ? s : i, Wl(t, n, eu(n), s);
      }
      function ob(t) {
        return Qc(t) && t != +t;
      }
      function sb(t) {
        if (G_(t))
          throw new at(f);
        return Ga(t);
      }
      function lb(t) {
        return t === null;
      }
      function ub(t) {
        return t == null;
      }
      function Qc(t) {
        return typeof t == "number" || Jt(t) && Me(t) == I;
      }
      function Vi(t) {
        if (!Jt(t) || Me(t) != M)
          return !1;
        var n = Ho(t);
        if (n === null)
          return !0;
        var s = Mt.call(n, "constructor") && n.constructor;
        return typeof s == "function" && s instanceof s && Lo.call(s) == iv;
      }
      var hu = ha ? Ze(ha) : l_;
      function fb(t) {
        return Jc(t) && t >= -rt && t <= rt;
      }
      var jc = da ? Ze(da) : u_;
      function ps(t) {
        return typeof t == "string" || !ht(t) && Jt(t) && Me(t) == B;
      }
      function je(t) {
        return typeof t == "symbol" || Jt(t) && Me(t) == tt;
      }
      var pi = pa ? Ze(pa) : f_;
      function ab(t) {
        return t === i;
      }
      function cb(t) {
        return Jt(t) && Ce(t) == Z;
      }
      function hb(t) {
        return Jt(t) && Me(t) == ut;
      }
      var db = is(Hl), pb = is(function(t, n) {
        return t <= n;
      });
      function th(t) {
        if (!t)
          return [];
        if (Ge(t))
          return ps(t) ? wn(t) : Ke(t);
        if (Ni && t[Ni])
          return Xg(t[Ni]());
        var n = Ce(t), s = n == x ? Cl : n == $ ? Mo : gi;
        return s(t);
      }
      function jn(t) {
        if (!t)
          return t === 0 ? t : 0;
        if (t = vn(t), t === lt || t === -lt) {
          var n = t < 0 ? -1 : 1;
          return n * ve;
        }
        return t === t ? t : 0;
      }
      function vt(t) {
        var n = jn(t), s = n % 1;
        return n === n ? s ? n - s : n : 0;
      }
      function eh(t) {
        return t ? Br(vt(t), 0, Yt) : 0;
      }
      function vn(t) {
        if (typeof t == "number")
          return t;
        if (je(t))
          return de;
        if (Vt(t)) {
          var n = typeof t.valueOf == "function" ? t.valueOf() : t;
          t = Vt(n) ? n + "" : n;
        }
        if (typeof t != "string")
          return t === 0 ? t : +t;
        t = ya(t);
        var s = kp.test(t);
        return s || Zp.test(t) ? Og(t.slice(2), s ? 2 : 8) : Vp.test(t) ? de : +t;
      }
      function nh(t) {
        return Pn(t, Ye(t));
      }
      function gb(t) {
        return t ? Br(vt(t), -rt, rt) : t === 0 ? t : 0;
      }
      function Rt(t) {
        return t == null ? "" : Qe(t);
      }
      var vb = ci(function(t, n) {
        if (Xi(n) || Ge(n)) {
          Pn(n, pe(n), t);
          return;
        }
        for (var s in n)
          Mt.call(n, s) && Hi(t, s, n[s]);
      }), rh = ci(function(t, n) {
        Pn(n, Ye(n), t);
      }), gs = ci(function(t, n, s, u) {
        Pn(n, Ye(n), t, u);
      }), _b = ci(function(t, n, s, u) {
        Pn(n, pe(n), t, u);
      }), mb = Jn(Ml);
      function bb(t, n) {
        var s = ai(t);
        return n == null ? s : Na(s, n);
      }
      var yb = bt(function(t, n) {
        t = Ft(t);
        var s = -1, u = n.length, c = u > 2 ? n[2] : i;
        for (c && Fe(n[0], n[1], c) && (u = 1); ++s < u; )
          for (var p = n[s], m = Ye(p), w = -1, S = m.length; ++w < S; ) {
            var R = m[w], P = t[R];
            (P === i || En(P, li[R]) && !Mt.call(t, R)) && (t[R] = p[R]);
          }
        return t;
      }), wb = bt(function(t) {
        return t.push(i, yc), Je(ih, i, t);
      });
      function xb(t, n) {
        return va(t, et(n, 3), Rn);
      }
      function Eb(t, n) {
        return va(t, et(n, 3), Nl);
      }
      function Sb(t, n) {
        return t == null ? t : Fl(t, et(n, 3), Ye);
      }
      function Tb(t, n) {
        return t == null ? t : Ha(t, et(n, 3), Ye);
      }
      function Ab(t, n) {
        return t && Rn(t, et(n, 3));
      }
      function Cb(t, n) {
        return t && Nl(t, et(n, 3));
      }
      function Ib(t) {
        return t == null ? [] : Jo(t, pe(t));
      }
      function Ob(t) {
        return t == null ? [] : Jo(t, Ye(t));
      }
      function du(t, n, s) {
        var u = t == null ? i : Wr(t, n);
        return u === i ? s : u;
      }
      function Db(t, n) {
        return t != null && Ec(t, n, jv);
      }
      function pu(t, n) {
        return t != null && Ec(t, n, t_);
      }
      var Rb = gc(function(t, n, s) {
        n != null && typeof n.toString != "function" && (n = Bo.call(n)), t[n] = s;
      }, vu(ze)), Pb = gc(function(t, n, s) {
        n != null && typeof n.toString != "function" && (n = Bo.call(n)), Mt.call(t, n) ? t[n].push(s) : t[n] = [s];
      }, et), Mb = bt(Ki);
      function pe(t) {
        return Ge(t) ? Ma(t) : Ul(t);
      }
      function Ye(t) {
        return Ge(t) ? Ma(t, !0) : a_(t);
      }
      function Fb(t, n) {
        var s = {};
        return n = et(n, 3), Rn(t, function(u, c, p) {
          Vn(s, n(u, c, p), u);
        }), s;
      }
      function Nb(t, n) {
        var s = {};
        return n = et(n, 3), Rn(t, function(u, c, p) {
          Vn(s, c, n(u, c, p));
        }), s;
      }
      var Lb = ci(function(t, n, s) {
        Zo(t, n, s);
      }), ih = ci(function(t, n, s, u) {
        Zo(t, n, s, u);
      }), Bb = Jn(function(t, n) {
        var s = {};
        if (t == null)
          return s;
        var u = !1;
        n = zt(n, function(p) {
          return p = _r(p, t), u || (u = p.length > 1), p;
        }), Pn(t, jl(t), s), u && (s = dn(s, _ | T | C, R_));
        for (var c = n.length; c--; )
          zl(s, n[c]);
        return s;
      });
      function Wb(t, n) {
        return oh(t, hs(et(n)));
      }
      var Ub = Jn(function(t, n) {
        return t == null ? {} : h_(t, n);
      });
      function oh(t, n) {
        if (t == null)
          return {};
        var s = zt(jl(t), function(u) {
          return [u];
        });
        return n = et(n), Ja(t, s, function(u, c) {
          return n(u, c[0]);
        });
      }
      function Hb(t, n, s) {
        n = _r(n, t);
        var u = -1, c = n.length;
        for (c || (c = 1, t = i); ++u < c; ) {
          var p = t == null ? i : t[Mn(n[u])];
          p === i && (u = c, p = s), t = Qn(p) ? p.call(t) : p;
        }
        return t;
      }
      function $b(t, n, s) {
        return t == null ? t : Yi(t, n, s);
      }
      function Kb(t, n, s, u) {
        return u = typeof u == "function" ? u : i, t == null ? t : Yi(t, n, s, u);
      }
      var sh = mc(pe), lh = mc(Ye);
      function Gb(t, n, s) {
        var u = ht(t), c = u || br(t) || pi(t);
        if (n = et(n, 4), s == null) {
          var p = t && t.constructor;
          c ? s = u ? new p() : [] : Vt(t) ? s = Qn(p) ? ai(Ho(t)) : {} : s = {};
        }
        return (c ? an : Rn)(t, function(m, w, S) {
          return n(s, m, w, S);
        }), s;
      }
      function Yb(t, n) {
        return t == null ? !0 : zl(t, n);
      }
      function zb(t, n, s) {
        return t == null ? t : ec(t, n, Vl(s));
      }
      function Xb(t, n, s, u) {
        return u = typeof u == "function" ? u : i, t == null ? t : ec(t, n, Vl(s), u);
      }
      function gi(t) {
        return t == null ? [] : Al(t, pe(t));
      }
      function qb(t) {
        return t == null ? [] : Al(t, Ye(t));
      }
      function Vb(t, n, s) {
        return s === i && (s = n, n = i), s !== i && (s = vn(s), s = s === s ? s : 0), n !== i && (n = vn(n), n = n === n ? n : 0), Br(vn(t), n, s);
      }
      function kb(t, n, s) {
        return n = jn(n), s === i ? (s = n, n = 0) : s = jn(s), t = vn(t), e_(t, n, s);
      }
      function Jb(t, n, s) {
        if (s && typeof s != "boolean" && Fe(t, n, s) && (n = s = i), s === i && (typeof n == "boolean" ? (s = n, n = i) : typeof t == "boolean" && (s = t, t = i)), t === i && n === i ? (t = 0, n = 1) : (t = jn(t), n === i ? (n = t, t = 0) : n = jn(n)), t > n) {
          var u = t;
          t = n, n = u;
        }
        if (s || t % 1 || n % 1) {
          var c = Ra();
          return Ae(t + c * (n - t + Ig("1e-" + ((c + "").length - 1))), n);
        }
        return Kl(t, n);
      }
      var Zb = hi(function(t, n, s) {
        return n = n.toLowerCase(), t + (s ? uh(n) : n);
      });
      function uh(t) {
        return gu(Rt(t).toLowerCase());
      }
      function fh(t) {
        return t = Rt(t), t && t.replace(jp, $g).replace(mg, "");
      }
      function Qb(t, n, s) {
        t = Rt(t), n = Qe(n);
        var u = t.length;
        s = s === i ? u : Br(vt(s), 0, u);
        var c = s;
        return s -= n.length, s >= 0 && t.slice(s, c) == n;
      }
      function jb(t) {
        return t = Rt(t), t && Mp.test(t) ? t.replace(Hf, Kg) : t;
      }
      function ty(t) {
        return t = Rt(t), t && Up.test(t) ? t.replace(al, "\\$&") : t;
      }
      var ey = hi(function(t, n, s) {
        return t + (s ? "-" : "") + n.toLowerCase();
      }), ny = hi(function(t, n, s) {
        return t + (s ? " " : "") + n.toLowerCase();
      }), ry = hc("toLowerCase");
      function iy(t, n, s) {
        t = Rt(t), n = vt(n);
        var u = n ? oi(t) : 0;
        if (!n || u >= n)
          return t;
        var c = (n - u) / 2;
        return rs(Yo(c), s) + t + rs(Go(c), s);
      }
      function oy(t, n, s) {
        t = Rt(t), n = vt(n);
        var u = n ? oi(t) : 0;
        return n && u < n ? t + rs(n - u, s) : t;
      }
      function sy(t, n, s) {
        t = Rt(t), n = vt(n);
        var u = n ? oi(t) : 0;
        return n && u < n ? rs(n - u, s) + t : t;
      }
      function ly(t, n, s) {
        return s || n == null ? n = 0 : n && (n = +n), pv(Rt(t).replace(cl, ""), n || 0);
      }
      function uy(t, n, s) {
        return (s ? Fe(t, n, s) : n === i) ? n = 1 : n = vt(n), Gl(Rt(t), n);
      }
      function fy() {
        var t = arguments, n = Rt(t[0]);
        return t.length < 3 ? n : n.replace(t[1], t[2]);
      }
      var ay = hi(function(t, n, s) {
        return t + (s ? "_" : "") + n.toLowerCase();
      });
      function cy(t, n, s) {
        return s && typeof s != "number" && Fe(t, n, s) && (n = s = i), s = s === i ? Yt : s >>> 0, s ? (t = Rt(t), t && (typeof n == "string" || n != null && !hu(n)) && (n = Qe(n), !n && ii(t)) ? mr(wn(t), 0, s) : t.split(n, s)) : [];
      }
      var hy = hi(function(t, n, s) {
        return t + (s ? " " : "") + gu(n);
      });
      function dy(t, n, s) {
        return t = Rt(t), s = s == null ? 0 : Br(vt(s), 0, t.length), n = Qe(n), t.slice(s, s + n.length) == n;
      }
      function py(t, n, s) {
        var u = d.templateSettings;
        s && Fe(t, n, s) && (n = i), t = Rt(t), n = gs({}, n, u, bc);
        var c = gs({}, n.imports, u.imports, bc), p = pe(c), m = Al(c, p), w, S, R = 0, P = n.interpolate || Io, L = "__p += '", X = Il(
          (n.escape || Io).source + "|" + P.source + "|" + (P === $f ? qp : Io).source + "|" + (n.evaluate || Io).source + "|$",
          "g"
        ), j = "//# sourceURL=" + (Mt.call(n, "sourceURL") ? (n.sourceURL + "").replace(/\s/g, " ") : "lodash.templateSources[" + ++Eg + "]") + `
`;
        t.replace(X, function(ot, wt, St, tn, Ne, en) {
          return St || (St = tn), L += t.slice(R, en).replace(tg, Gg), wt && (w = !0, L += `' +
__e(` + wt + `) +
'`), Ne && (S = !0, L += `';
` + Ne + `;
__p += '`), St && (L += `' +
((__t = (` + St + `)) == null ? '' : __t) +
'`), R = en + ot.length, ot;
        }), L += `';
`;
        var it = Mt.call(n, "variable") && n.variable;
        if (!it)
          L = `with (obj) {
` + L + `
}
`;
        else if (zp.test(it))
          throw new at(h);
        L = (S ? L.replace(Co, "") : L).replace(Dp, "$1").replace(Rp, "$1;"), L = "function(" + (it || "obj") + `) {
` + (it ? "" : `obj || (obj = {});
`) + "var __t, __p = ''" + (w ? ", __e = _.escape" : "") + (S ? `, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
` : `;
`) + L + `return __p
}`;
        var mt = ch(function() {
          return Ot(p, j + "return " + L).apply(i, m);
        });
        if (mt.source = L, cu(mt))
          throw mt;
        return mt;
      }
      function gy(t) {
        return Rt(t).toLowerCase();
      }
      function vy(t) {
        return Rt(t).toUpperCase();
      }
      function _y(t, n, s) {
        if (t = Rt(t), t && (s || n === i))
          return ya(t);
        if (!t || !(n = Qe(n)))
          return t;
        var u = wn(t), c = wn(n), p = wa(u, c), m = xa(u, c) + 1;
        return mr(u, p, m).join("");
      }
      function my(t, n, s) {
        if (t = Rt(t), t && (s || n === i))
          return t.slice(0, Sa(t) + 1);
        if (!t || !(n = Qe(n)))
          return t;
        var u = wn(t), c = xa(u, wn(n)) + 1;
        return mr(u, 0, c).join("");
      }
      function by(t, n, s) {
        if (t = Rt(t), t && (s || n === i))
          return t.replace(cl, "");
        if (!t || !(n = Qe(n)))
          return t;
        var u = wn(t), c = wa(u, wn(n));
        return mr(u, c).join("");
      }
      function yy(t, n) {
        var s = te, u = fe;
        if (Vt(n)) {
          var c = "separator" in n ? n.separator : c;
          s = "length" in n ? vt(n.length) : s, u = "omission" in n ? Qe(n.omission) : u;
        }
        t = Rt(t);
        var p = t.length;
        if (ii(t)) {
          var m = wn(t);
          p = m.length;
        }
        if (s >= p)
          return t;
        var w = s - oi(u);
        if (w < 1)
          return u;
        var S = m ? mr(m, 0, w).join("") : t.slice(0, w);
        if (c === i)
          return S + u;
        if (m && (w += S.length - w), hu(c)) {
          if (t.slice(w).search(c)) {
            var R, P = S;
            for (c.global || (c = Il(c.source, Rt(Kf.exec(c)) + "g")), c.lastIndex = 0; R = c.exec(P); )
              var L = R.index;
            S = S.slice(0, L === i ? w : L);
          }
        } else if (t.indexOf(Qe(c), w) != w) {
          var X = S.lastIndexOf(c);
          X > -1 && (S = S.slice(0, X));
        }
        return S + u;
      }
      function wy(t) {
        return t = Rt(t), t && Pp.test(t) ? t.replace(Uf, Jg) : t;
      }
      var xy = hi(function(t, n, s) {
        return t + (s ? " " : "") + n.toUpperCase();
      }), gu = hc("toUpperCase");
      function ah(t, n, s) {
        return t = Rt(t), n = s ? i : n, n === i ? zg(t) ? jg(t) : Lg(t) : t.match(n) || [];
      }
      var ch = bt(function(t, n) {
        try {
          return Je(t, i, n);
        } catch (s) {
          return cu(s) ? s : new at(s);
        }
      }), Ey = Jn(function(t, n) {
        return an(n, function(s) {
          s = Mn(s), Vn(t, s, fu(t[s], t));
        }), t;
      });
      function Sy(t) {
        var n = t == null ? 0 : t.length, s = et();
        return t = n ? zt(t, function(u) {
          if (typeof u[1] != "function")
            throw new cn(a);
          return [s(u[0]), u[1]];
        }) : [], bt(function(u) {
          for (var c = -1; ++c < n; ) {
            var p = t[c];
            if (Je(p[0], this, u))
              return Je(p[1], this, u);
          }
        });
      }
      function Ty(t) {
        return Jv(dn(t, _));
      }
      function vu(t) {
        return function() {
          return t;
        };
      }
      function Ay(t, n) {
        return t == null || t !== t ? n : t;
      }
      var Cy = pc(), Iy = pc(!0);
      function ze(t) {
        return t;
      }
      function _u(t) {
        return Ya(typeof t == "function" ? t : dn(t, _));
      }
      function Oy(t) {
        return Xa(dn(t, _));
      }
      function Dy(t, n) {
        return qa(t, dn(n, _));
      }
      var Ry = bt(function(t, n) {
        return function(s) {
          return Ki(s, t, n);
        };
      }), Py = bt(function(t, n) {
        return function(s) {
          return Ki(t, s, n);
        };
      });
      function mu(t, n, s) {
        var u = pe(n), c = Jo(n, u);
        s == null && !(Vt(n) && (c.length || !u.length)) && (s = n, n = t, t = this, c = Jo(n, pe(n)));
        var p = !(Vt(s) && "chain" in s) || !!s.chain, m = Qn(t);
        return an(c, function(w) {
          var S = n[w];
          t[w] = S, m && (t.prototype[w] = function() {
            var R = this.__chain__;
            if (p || R) {
              var P = t(this.__wrapped__), L = P.__actions__ = Ke(this.__actions__);
              return L.push({ func: S, args: arguments, thisArg: t }), P.__chain__ = R, P;
            }
            return S.apply(t, hr([this.value()], arguments));
          });
        }), t;
      }
      function My() {
        return we._ === this && (we._ = ov), this;
      }
      function bu() {
      }
      function Fy(t) {
        return t = vt(t), bt(function(n) {
          return Va(n, t);
        });
      }
      var Ny = Jl(zt), Ly = Jl(ga), By = Jl(wl);
      function hh(t) {
        return ru(t) ? xl(Mn(t)) : d_(t);
      }
      function Wy(t) {
        return function(n) {
          return t == null ? i : Wr(t, n);
        };
      }
      var Uy = vc(), Hy = vc(!0);
      function yu() {
        return [];
      }
      function wu() {
        return !1;
      }
      function $y() {
        return {};
      }
      function Ky() {
        return "";
      }
      function Gy() {
        return !0;
      }
      function Yy(t, n) {
        if (t = vt(t), t < 1 || t > rt)
          return [];
        var s = Yt, u = Ae(t, Yt);
        n = et(n), t -= Yt;
        for (var c = Tl(u, n); ++s < t; )
          n(s);
        return c;
      }
      function zy(t) {
        return ht(t) ? zt(t, Mn) : je(t) ? [t] : Ke(Pc(Rt(t)));
      }
      function Xy(t) {
        var n = ++rv;
        return Rt(t) + n;
      }
      var qy = ns(function(t, n) {
        return t + n;
      }, 0), Vy = Zl("ceil"), ky = ns(function(t, n) {
        return t / n;
      }, 1), Jy = Zl("floor");
      function Zy(t) {
        return t && t.length ? ko(t, ze, Ll) : i;
      }
      function Qy(t, n) {
        return t && t.length ? ko(t, et(n, 2), Ll) : i;
      }
      function jy(t) {
        return ma(t, ze);
      }
      function tw(t, n) {
        return ma(t, et(n, 2));
      }
      function ew(t) {
        return t && t.length ? ko(t, ze, Hl) : i;
      }
      function nw(t, n) {
        return t && t.length ? ko(t, et(n, 2), Hl) : i;
      }
      var rw = ns(function(t, n) {
        return t * n;
      }, 1), iw = Zl("round"), ow = ns(function(t, n) {
        return t - n;
      }, 0);
      function sw(t) {
        return t && t.length ? Sl(t, ze) : 0;
      }
      function lw(t, n) {
        return t && t.length ? Sl(t, et(n, 2)) : 0;
      }
      return d.after = O0, d.ary = Gc, d.assign = vb, d.assignIn = rh, d.assignInWith = gs, d.assignWith = _b, d.at = mb, d.before = Yc, d.bind = fu, d.bindAll = Ey, d.bindKey = zc, d.castArray = $0, d.chain = Hc, d.chunk = J_, d.compact = Z_, d.concat = Q_, d.cond = Sy, d.conforms = Ty, d.constant = vu, d.countBy = s0, d.create = bb, d.curry = Xc, d.curryRight = qc, d.debounce = Vc, d.defaults = yb, d.defaultsDeep = wb, d.defer = D0, d.delay = R0, d.difference = j_, d.differenceBy = tm, d.differenceWith = em, d.drop = nm, d.dropRight = rm, d.dropRightWhile = im, d.dropWhile = om, d.fill = sm, d.filter = u0, d.flatMap = c0, d.flatMapDeep = h0, d.flatMapDepth = d0, d.flatten = Lc, d.flattenDeep = lm, d.flattenDepth = um, d.flip = P0, d.flow = Cy, d.flowRight = Iy, d.fromPairs = fm, d.functions = Ib, d.functionsIn = Ob, d.groupBy = p0, d.initial = cm, d.intersection = hm, d.intersectionBy = dm, d.intersectionWith = pm, d.invert = Rb, d.invertBy = Pb, d.invokeMap = v0, d.iteratee = _u, d.keyBy = _0, d.keys = pe, d.keysIn = Ye, d.map = fs, d.mapKeys = Fb, d.mapValues = Nb, d.matches = Oy, d.matchesProperty = Dy, d.memoize = cs, d.merge = Lb, d.mergeWith = ih, d.method = Ry, d.methodOf = Py, d.mixin = mu, d.negate = hs, d.nthArg = Fy, d.omit = Bb, d.omitBy = Wb, d.once = M0, d.orderBy = m0, d.over = Ny, d.overArgs = F0, d.overEvery = Ly, d.overSome = By, d.partial = au, d.partialRight = kc, d.partition = b0, d.pick = Ub, d.pickBy = oh, d.property = hh, d.propertyOf = Wy, d.pull = mm, d.pullAll = Wc, d.pullAllBy = bm, d.pullAllWith = ym, d.pullAt = wm, d.range = Uy, d.rangeRight = Hy, d.rearg = N0, d.reject = x0, d.remove = xm, d.rest = L0, d.reverse = lu, d.sampleSize = S0, d.set = $b, d.setWith = Kb, d.shuffle = T0, d.slice = Em, d.sortBy = I0, d.sortedUniq = Dm, d.sortedUniqBy = Rm, d.split = cy, d.spread = B0, d.tail = Pm, d.take = Mm, d.takeRight = Fm, d.takeRightWhile = Nm, d.takeWhile = Lm, d.tap = Zm, d.throttle = W0, d.thru = us, d.toArray = th, d.toPairs = sh, d.toPairsIn = lh, d.toPath = zy, d.toPlainObject = nh, d.transform = Gb, d.unary = U0, d.union = Bm, d.unionBy = Wm, d.unionWith = Um, d.uniq = Hm, d.uniqBy = $m, d.uniqWith = Km, d.unset = Yb, d.unzip = uu, d.unzipWith = Uc, d.update = zb, d.updateWith = Xb, d.values = gi, d.valuesIn = qb, d.without = Gm, d.words = ah, d.wrap = H0, d.xor = Ym, d.xorBy = zm, d.xorWith = Xm, d.zip = qm, d.zipObject = Vm, d.zipObjectDeep = km, d.zipWith = Jm, d.entries = sh, d.entriesIn = lh, d.extend = rh, d.extendWith = gs, mu(d, d), d.add = qy, d.attempt = ch, d.camelCase = Zb, d.capitalize = uh, d.ceil = Vy, d.clamp = Vb, d.clone = K0, d.cloneDeep = Y0, d.cloneDeepWith = z0, d.cloneWith = G0, d.conformsTo = X0, d.deburr = fh, d.defaultTo = Ay, d.divide = ky, d.endsWith = Qb, d.eq = En, d.escape = jb, d.escapeRegExp = ty, d.every = l0, d.find = f0, d.findIndex = Fc, d.findKey = xb, d.findLast = a0, d.findLastIndex = Nc, d.findLastKey = Eb, d.floor = Jy, d.forEach = $c, d.forEachRight = Kc, d.forIn = Sb, d.forInRight = Tb, d.forOwn = Ab, d.forOwnRight = Cb, d.get = du, d.gt = q0, d.gte = V0, d.has = Db, d.hasIn = pu, d.head = Bc, d.identity = ze, d.includes = g0, d.indexOf = am, d.inRange = kb, d.invoke = Mb, d.isArguments = $r, d.isArray = ht, d.isArrayBuffer = k0, d.isArrayLike = Ge, d.isArrayLikeObject = Qt, d.isBoolean = J0, d.isBuffer = br, d.isDate = Z0, d.isElement = Q0, d.isEmpty = j0, d.isEqual = tb, d.isEqualWith = eb, d.isError = cu, d.isFinite = nb, d.isFunction = Qn, d.isInteger = Jc, d.isLength = ds, d.isMap = Zc, d.isMatch = rb, d.isMatchWith = ib, d.isNaN = ob, d.isNative = sb, d.isNil = ub, d.isNull = lb, d.isNumber = Qc, d.isObject = Vt, d.isObjectLike = Jt, d.isPlainObject = Vi, d.isRegExp = hu, d.isSafeInteger = fb, d.isSet = jc, d.isString = ps, d.isSymbol = je, d.isTypedArray = pi, d.isUndefined = ab, d.isWeakMap = cb, d.isWeakSet = hb, d.join = gm, d.kebabCase = ey, d.last = gn, d.lastIndexOf = vm, d.lowerCase = ny, d.lowerFirst = ry, d.lt = db, d.lte = pb, d.max = Zy, d.maxBy = Qy, d.mean = jy, d.meanBy = tw, d.min = ew, d.minBy = nw, d.stubArray = yu, d.stubFalse = wu, d.stubObject = $y, d.stubString = Ky, d.stubTrue = Gy, d.multiply = rw, d.nth = _m, d.noConflict = My, d.noop = bu, d.now = as, d.pad = iy, d.padEnd = oy, d.padStart = sy, d.parseInt = ly, d.random = Jb, d.reduce = y0, d.reduceRight = w0, d.repeat = uy, d.replace = fy, d.result = Hb, d.round = iw, d.runInContext = E, d.sample = E0, d.size = A0, d.snakeCase = ay, d.some = C0, d.sortedIndex = Sm, d.sortedIndexBy = Tm, d.sortedIndexOf = Am, d.sortedLastIndex = Cm, d.sortedLastIndexBy = Im, d.sortedLastIndexOf = Om, d.startCase = hy, d.startsWith = dy, d.subtract = ow, d.sum = sw, d.sumBy = lw, d.template = py, d.times = Yy, d.toFinite = jn, d.toInteger = vt, d.toLength = eh, d.toLower = gy, d.toNumber = vn, d.toSafeInteger = gb, d.toString = Rt, d.toUpper = vy, d.trim = _y, d.trimEnd = my, d.trimStart = by, d.truncate = yy, d.unescape = wy, d.uniqueId = Xy, d.upperCase = xy, d.upperFirst = gu, d.each = $c, d.eachRight = Kc, d.first = Bc, mu(d, function() {
        var t = {};
        return Rn(d, function(n, s) {
          Mt.call(d.prototype, s) || (t[s] = n);
        }), t;
      }(), { chain: !1 }), d.VERSION = o, an(["bind", "bindKey", "curry", "curryRight", "partial", "partialRight"], function(t) {
        d[t].placeholder = d;
      }), an(["drop", "take"], function(t, n) {
        xt.prototype[t] = function(s) {
          s = s === i ? 1 : ce(vt(s), 0);
          var u = this.__filtered__ && !n ? new xt(this) : this.clone();
          return u.__filtered__ ? u.__takeCount__ = Ae(s, u.__takeCount__) : u.__views__.push({
            size: Ae(s, Yt),
            type: t + (u.__dir__ < 0 ? "Right" : "")
          }), u;
        }, xt.prototype[t + "Right"] = function(s) {
          return this.reverse()[t](s).reverse();
        };
      }), an(["filter", "map", "takeWhile"], function(t, n) {
        var s = n + 1, u = s == he || s == gt;
        xt.prototype[t] = function(c) {
          var p = this.clone();
          return p.__iteratees__.push({
            iteratee: et(c, 3),
            type: s
          }), p.__filtered__ = p.__filtered__ || u, p;
        };
      }), an(["head", "last"], function(t, n) {
        var s = "take" + (n ? "Right" : "");
        xt.prototype[t] = function() {
          return this[s](1).value()[0];
        };
      }), an(["initial", "tail"], function(t, n) {
        var s = "drop" + (n ? "" : "Right");
        xt.prototype[t] = function() {
          return this.__filtered__ ? new xt(this) : this[s](1);
        };
      }), xt.prototype.compact = function() {
        return this.filter(ze);
      }, xt.prototype.find = function(t) {
        return this.filter(t).head();
      }, xt.prototype.findLast = function(t) {
        return this.reverse().find(t);
      }, xt.prototype.invokeMap = bt(function(t, n) {
        return typeof t == "function" ? new xt(this) : this.map(function(s) {
          return Ki(s, t, n);
        });
      }), xt.prototype.reject = function(t) {
        return this.filter(hs(et(t)));
      }, xt.prototype.slice = function(t, n) {
        t = vt(t);
        var s = this;
        return s.__filtered__ && (t > 0 || n < 0) ? new xt(s) : (t < 0 ? s = s.takeRight(-t) : t && (s = s.drop(t)), n !== i && (n = vt(n), s = n < 0 ? s.dropRight(-n) : s.take(n - t)), s);
      }, xt.prototype.takeRightWhile = function(t) {
        return this.reverse().takeWhile(t).reverse();
      }, xt.prototype.toArray = function() {
        return this.take(Yt);
      }, Rn(xt.prototype, function(t, n) {
        var s = /^(?:filter|find|map|reject)|While$/.test(n), u = /^(?:head|last)$/.test(n), c = d[u ? "take" + (n == "last" ? "Right" : "") : n], p = u || /^find/.test(n);
        c && (d.prototype[n] = function() {
          var m = this.__wrapped__, w = u ? [1] : arguments, S = m instanceof xt, R = w[0], P = S || ht(m), L = function(wt) {
            var St = c.apply(d, hr([wt], w));
            return u && X ? St[0] : St;
          };
          P && s && typeof R == "function" && R.length != 1 && (S = P = !1);
          var X = this.__chain__, j = !!this.__actions__.length, it = p && !X, mt = S && !j;
          if (!p && P) {
            m = mt ? m : new xt(this);
            var ot = t.apply(m, w);
            return ot.__actions__.push({ func: us, args: [L], thisArg: i }), new hn(ot, X);
          }
          return it && mt ? t.apply(this, w) : (ot = this.thru(L), it ? u ? ot.value()[0] : ot.value() : ot);
        });
      }), an(["pop", "push", "shift", "sort", "splice", "unshift"], function(t) {
        var n = Fo[t], s = /^(?:push|sort|unshift)$/.test(t) ? "tap" : "thru", u = /^(?:pop|shift)$/.test(t);
        d.prototype[t] = function() {
          var c = arguments;
          if (u && !this.__chain__) {
            var p = this.value();
            return n.apply(ht(p) ? p : [], c);
          }
          return this[s](function(m) {
            return n.apply(ht(m) ? m : [], c);
          });
        };
      }), Rn(xt.prototype, function(t, n) {
        var s = d[n];
        if (s) {
          var u = s.name + "";
          Mt.call(fi, u) || (fi[u] = []), fi[u].push({ name: n, func: s });
        }
      }), fi[es(i, K).name] = [{
        name: "wrapper",
        func: i
      }], xt.prototype.clone = wv, xt.prototype.reverse = xv, xt.prototype.value = Ev, d.prototype.at = Qm, d.prototype.chain = jm, d.prototype.commit = t0, d.prototype.next = e0, d.prototype.plant = r0, d.prototype.reverse = i0, d.prototype.toJSON = d.prototype.valueOf = d.prototype.value = o0, d.prototype.first = d.prototype.head, Ni && (d.prototype[Ni] = n0), d;
    }, si = tv();
    Mr ? ((Mr.exports = si)._ = si, _l._ = si) : we._ = si;
  }).call(Zi);
})(zs, zs.exports);
var xs = zs.exports;
function Ms(e) {
  const i = document.getSelection().getRangeAt(0), o = i.cloneRange();
  return o.selectNodeContents(e), o.setEnd(i.endContainer, i.endOffset), o.toString().length;
}
function Xh(e, r) {
  const i = Jx(e, r), o = document.getSelection();
  o.removeAllRanges(), o.addRange(i);
}
function qh(e) {
  const r = document.createRange(), i = document.getSelection();
  r.setStart(e, e.childNodes.length), r.collapse(!0), i.removeAllRanges(), i.addRange(r);
}
const Jx = (e, r) => {
  var f;
  const i = document.createRange();
  i.selectNode(e), i.setStart(e, 0);
  let o = 0;
  const l = [e];
  for (; l.length > 0; ) {
    const a = l.pop();
    if (a && a.nodeType === Node.TEXT_NODE) {
      const h = ((f = a.textContent) == null ? void 0 : f.length) || 0;
      if (o + h >= r)
        return i.setStart(a, r - o), i.setEnd(a, r - o), i;
      o += h;
    } else if (a && a.childNodes && a.childNodes.length > 0)
      for (let h = a.childNodes.length - 1; h >= 0; h--)
        l.push(a.childNodes[h]);
  }
  return i.setStart(e, e.childNodes.length), i.setEnd(e, e.childNodes.length), i;
};
function Zx(e, r) {
  let i = [];
  In([e, ...r], () => {
    e.value && (i = [...e.value.querySelectorAll("[contenteditable]")]);
  }, {
    flush: "post"
  });
  const o = (C) => i.findIndex((O) => O.isEqualNode(C)), l = (C) => i[o(C) - 1], f = (C) => i[o(C) + 1], a = (C, O) => {
    if (C) {
      const F = f(C);
      if (F instanceof HTMLElement)
        return F.focus(), O && qh(F), F;
    } else {
      const F = i[0];
      F instanceof HTMLElement && F.focus();
    }
  }, h = (C, O) => {
    if (C.target instanceof HTMLElement) {
      const F = C.target, N = Ms(F), K = O === "up" ? l(F) : f(F);
      K instanceof HTMLElement && (C.preventDefault(), K.focus(), Xh(K, N));
    }
  };
  return {
    getPreviousElementSibling: l,
    getNextElementSibling: f,
    getCurrentPositionInNavigationList: o,
    focusNextTask: a,
    focusTaskById: (C) => {
      const O = i.find((F) => {
        var N;
        return F instanceof HTMLElement && ((N = F.dataset) == null ? void 0 : N.id) == C;
      });
      O instanceof HTMLElement && O.focus();
    },
    onUp: (C) => {
      h(C, "up");
    },
    onDown: (C) => {
      h(C, "down");
    },
    onLeft: (C, O) => {
      if (C.target instanceof HTMLElement) {
        const F = C.target;
        if (Ms(F) === 0) {
          const N = l(F);
          N instanceof HTMLElement && (C.preventDefault(), O && (N.innerText += O), N.focus(), qh(N), O && Xh(N, N.innerText.length - O.length));
        }
      }
    },
    onRight: (C) => {
      if (C.target instanceof HTMLElement) {
        const O = C.target;
        if (Ms(O) === O.innerText.length) {
          const F = f(O);
          F instanceof HTMLElement && (C.preventDefault(), F.focus());
        }
      }
    }
  };
}
const Qx = { class: "tw-group tw-flex tw-relative tw-gap-2 tw-py-4 tw-px-6 -tw-mx-6" }, jx = {
  key: 0,
  class: "sortable-handle tw-cursor-grabbing tw-absolute -tw-translate-x-4 tw-hidden group-hover:tw-block"
}, tE = {
  class: "tw-w-4 tw-h-4 tw-flex-none",
  onClick: () => {
  }
}, eE = { class: "tw-flex-auto" }, nE = ["contenteditable", "placeholder", "data-id", "textContent"], rE = { key: 1 }, iE = /* @__PURE__ */ Of({
  __name: "TaskListItem2",
  props: {
    task: {},
    sortable: { type: Boolean },
    editable: { type: Boolean },
    placeholder: {},
    addableProps: {}
  },
  emits: ["delete", "update", "insert", "blur"],
  setup(e, { emit: r }) {
    const i = e, o = r, l = mn(!1);
    let f = {
      name: i.task.name,
      due_date: i.task.due_date,
      due_datetime: i.task.due_datetime,
      priority: i.task.priority,
      location_id: i.task.location_id
    };
    const a = mn({ ...f });
    let h;
    In([() => i.task, () => i.addableProps], async ([N]) => {
      h && h(), f = xs.pick(N, Object.keys(f)), a.value = { ...f }, console.log("start"), h = In(a, async () => {
        const K = xs.reduce(a.value, (J, V, U) => xs.isEqual(V, f[U]) ? J : [...J, U], []);
        K.length && o("update", i.task, xs.pick(a.value, K));
      }, {
        deep: !0
      });
    }, {
      immediate: !0
    });
    const g = async (N) => {
      N.target instanceof HTMLElement && (N.target.innerText.trim() ? (N.target.innerText = N.target.innerText.trim(), a.value.name = N.target.innerText) : o("blur", i.task));
    }, v = (N) => {
      var J;
      N.preventDefault();
      const K = (J = N.clipboardData) == null ? void 0 : J.getData("text/plain");
      K && document.execCommand("insertText", !1, K);
    }, b = (N) => {
      N.target instanceof HTMLElement && Ms(N.target) === 0 && (O(N, N.target.innerText.trim()), o("delete", i.task));
    }, _ = async (N) => {
      N.preventDefault();
    }, {
      onUp: T,
      onDown: C,
      onLeft: O,
      onRight: F
    } = kr("listNavigation");
    return (N, K) => {
      var J;
      return Hn(), Jr("div", Qx, [
        i.sortable ? (Hn(), Jr("div", jx, K[6] || (K[6] = [
          Ln("i", { class: "fas fa-grip-vertical" }, null, -1)
        ]))) : Dh("", !0),
        Ln("div", tE, [
          Ln("div", {
            class: tl(["tw-w-4 tw-h-4 tw-border tw-border-solid tw-border-gray-300 tw-rounded-full tw-cursor-pointer", !1])
          })
        ]),
        Ln("div", eE, [
          Ln("div", {
            contenteditable: i.editable || void 0,
            placeholder: i.placeholder,
            "data-id": i.task.id,
            onKeydown: [
              _i(_, ["enter"]),
              K[0] || (K[0] = _i(
                //@ts-ignore
                (...V) => Ve(O) && Ve(O)(...V),
                ["left"]
              )),
              K[1] || (K[1] = _i(
                //@ts-ignore
                (...V) => Ve(T) && Ve(T)(...V),
                ["up"]
              )),
              K[2] || (K[2] = _i(
                //@ts-ignore
                (...V) => Ve(F) && Ve(F)(...V),
                ["right"]
              )),
              K[3] || (K[3] = _i(
                //@ts-ignore
                (...V) => Ve(C) && Ve(C)(...V),
                ["down"]
              )),
              _i(b, ["delete"])
            ],
            onBlur: g,
            onFocus: K[4] || (K[4] = (V) => l.value = !0),
            onFocusout: K[5] || (K[5] = (V) => l.value = !1),
            onPaste: v,
            textContent: qu(i.task.name)
          }, null, 40, nE)
        ]),
        (J = i.task.extended_data) != null && J.comments_count ? (Hn(), Jr("div", rE, [
          K[7] || (K[7] = Ln("i", { class: "far fa-comment" }, null, -1)),
          cp(" " + qu(i.task.extended_data.comments_count), 1)
        ])) : Dh("", !0)
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
function Vh(e, r) {
  var i = Object.keys(e);
  if (Object.getOwnPropertySymbols) {
    var o = Object.getOwnPropertySymbols(e);
    r && (o = o.filter(function(l) {
      return Object.getOwnPropertyDescriptor(e, l).enumerable;
    })), i.push.apply(i, o);
  }
  return i;
}
function Gn(e) {
  for (var r = 1; r < arguments.length; r++) {
    var i = arguments[r] != null ? arguments[r] : {};
    r % 2 ? Vh(Object(i), !0).forEach(function(o) {
      oE(e, o, i[o]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(i)) : Vh(Object(i)).forEach(function(o) {
      Object.defineProperty(e, o, Object.getOwnPropertyDescriptor(i, o));
    });
  }
  return e;
}
function Fs(e) {
  "@babel/helpers - typeof";
  return typeof Symbol == "function" && typeof Symbol.iterator == "symbol" ? Fs = function(r) {
    return typeof r;
  } : Fs = function(r) {
    return r && typeof Symbol == "function" && r.constructor === Symbol && r !== Symbol.prototype ? "symbol" : typeof r;
  }, Fs(e);
}
function oE(e, r, i) {
  return r in e ? Object.defineProperty(e, r, {
    value: i,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : e[r] = i, e;
}
function On() {
  return On = Object.assign || function(e) {
    for (var r = 1; r < arguments.length; r++) {
      var i = arguments[r];
      for (var o in i)
        Object.prototype.hasOwnProperty.call(i, o) && (e[o] = i[o]);
    }
    return e;
  }, On.apply(this, arguments);
}
function sE(e, r) {
  if (e == null)
    return {};
  var i = {}, o = Object.keys(e), l, f;
  for (f = 0; f < o.length; f++)
    l = o[f], !(r.indexOf(l) >= 0) && (i[l] = e[l]);
  return i;
}
function lE(e, r) {
  if (e == null)
    return {};
  var i = sE(e, r), o, l;
  if (Object.getOwnPropertySymbols) {
    var f = Object.getOwnPropertySymbols(e);
    for (l = 0; l < f.length; l++)
      o = f[l], !(r.indexOf(o) >= 0) && Object.prototype.propertyIsEnumerable.call(e, o) && (i[o] = e[o]);
  }
  return i;
}
function uE(e) {
  return fE(e) || aE(e) || cE(e) || hE();
}
function fE(e) {
  if (Array.isArray(e))
    return lf(e);
}
function aE(e) {
  if (typeof Symbol < "u" && e[Symbol.iterator] != null || e["@@iterator"] != null)
    return Array.from(e);
}
function cE(e, r) {
  if (e) {
    if (typeof e == "string")
      return lf(e, r);
    var i = Object.prototype.toString.call(e).slice(8, -1);
    if (i === "Object" && e.constructor && (i = e.constructor.name), i === "Map" || i === "Set")
      return Array.from(e);
    if (i === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i))
      return lf(e, r);
  }
}
function lf(e, r) {
  (r == null || r > e.length) && (r = e.length);
  for (var i = 0, o = new Array(r); i < r; i++)
    o[i] = e[i];
  return o;
}
function hE() {
  throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`);
}
var dE = "1.15.2";
function ir(e) {
  if (typeof window < "u" && window.navigator)
    return !!/* @__PURE__ */ navigator.userAgent.match(e);
}
var sr = ir(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i), To = ir(/Edge/i), kh = ir(/firefox/i), co = ir(/safari/i) && !ir(/chrome/i) && !ir(/android/i), _p = ir(/iP(ad|od|hone)/i), mp = ir(/chrome/i) && ir(/android/i), bp = {
  capture: !1,
  passive: !1
};
function Et(e, r, i) {
  e.addEventListener(r, i, !sr && bp);
}
function yt(e, r, i) {
  e.removeEventListener(r, i, !sr && bp);
}
function Xs(e, r) {
  if (r) {
    if (r[0] === ">" && (r = r.substring(1)), e)
      try {
        if (e.matches)
          return e.matches(r);
        if (e.msMatchesSelector)
          return e.msMatchesSelector(r);
        if (e.webkitMatchesSelector)
          return e.webkitMatchesSelector(r);
      } catch {
        return !1;
      }
    return !1;
  }
}
function pE(e) {
  return e.host && e !== document && e.host.nodeType ? e.host : e.parentNode;
}
function _n(e, r, i, o) {
  if (e) {
    i = i || document;
    do {
      if (r != null && (r[0] === ">" ? e.parentNode === i && Xs(e, r) : Xs(e, r)) || o && e === i)
        return e;
      if (e === i)
        break;
    } while (e = pE(e));
  }
  return null;
}
var Jh = /\s+/g;
function oe(e, r, i) {
  if (e && r)
    if (e.classList)
      e.classList[i ? "add" : "remove"](r);
    else {
      var o = (" " + e.className + " ").replace(Jh, " ").replace(" " + r + " ", " ");
      e.className = (o + (i ? " " + r : "")).replace(Jh, " ");
    }
}
function Q(e, r, i) {
  var o = e && e.style;
  if (o) {
    if (i === void 0)
      return document.defaultView && document.defaultView.getComputedStyle ? i = document.defaultView.getComputedStyle(e, "") : e.currentStyle && (i = e.currentStyle), r === void 0 ? i : i[r];
    !(r in o) && r.indexOf("webkit") === -1 && (r = "-webkit-" + r), o[r] = i + (typeof i == "string" ? "" : "px");
  }
}
function Zr(e, r) {
  var i = "";
  if (typeof e == "string")
    i = e;
  else
    do {
      var o = Q(e, "transform");
      o && o !== "none" && (i = o + " " + i);
    } while (!r && (e = e.parentNode));
  var l = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
  return l && new l(i);
}
function yp(e, r, i) {
  if (e) {
    var o = e.getElementsByTagName(r), l = 0, f = o.length;
    if (i)
      for (; l < f; l++)
        i(o[l], l);
    return o;
  }
  return [];
}
function $n() {
  var e = document.scrollingElement;
  return e || document.documentElement;
}
function $t(e, r, i, o, l) {
  if (!(!e.getBoundingClientRect && e !== window)) {
    var f, a, h, g, v, b, _;
    if (e !== window && e.parentNode && e !== $n() ? (f = e.getBoundingClientRect(), a = f.top, h = f.left, g = f.bottom, v = f.right, b = f.height, _ = f.width) : (a = 0, h = 0, g = window.innerHeight, v = window.innerWidth, b = window.innerHeight, _ = window.innerWidth), (r || i) && e !== window && (l = l || e.parentNode, !sr))
      do
        if (l && l.getBoundingClientRect && (Q(l, "transform") !== "none" || i && Q(l, "position") !== "static")) {
          var T = l.getBoundingClientRect();
          a -= T.top + parseInt(Q(l, "border-top-width")), h -= T.left + parseInt(Q(l, "border-left-width")), g = a + f.height, v = h + f.width;
          break;
        }
      while (l = l.parentNode);
    if (o && e !== window) {
      var C = Zr(l || e), O = C && C.a, F = C && C.d;
      C && (a /= F, h /= O, _ /= O, b /= F, g = a + b, v = h + _);
    }
    return {
      top: a,
      left: h,
      bottom: g,
      right: v,
      width: _,
      height: b
    };
  }
}
function Zh(e, r, i) {
  for (var o = Ar(e, !0), l = $t(e)[r]; o; ) {
    var f = $t(o)[i], a = void 0;
    if (i === "top" || i === "left" ? a = l >= f : a = l <= f, !a)
      return o;
    if (o === $n())
      break;
    o = Ar(o, !1);
  }
  return !1;
}
function Mi(e, r, i, o) {
  for (var l = 0, f = 0, a = e.children; f < a.length; ) {
    if (a[f].style.display !== "none" && a[f] !== st.ghost && (o || a[f] !== st.dragged) && _n(a[f], i.draggable, e, !1)) {
      if (l === r)
        return a[f];
      l++;
    }
    f++;
  }
  return null;
}
function Nf(e, r) {
  for (var i = e.lastElementChild; i && (i === st.ghost || Q(i, "display") === "none" || r && !Xs(i, r)); )
    i = i.previousElementSibling;
  return i || null;
}
function se(e, r) {
  var i = 0;
  if (!e || !e.parentNode)
    return -1;
  for (; e = e.previousElementSibling; )
    e.nodeName.toUpperCase() !== "TEMPLATE" && e !== st.clone && (!r || Xs(e, r)) && i++;
  return i;
}
function Qh(e) {
  var r = 0, i = 0, o = $n();
  if (e)
    do {
      var l = Zr(e), f = l.a, a = l.d;
      r += e.scrollLeft * f, i += e.scrollTop * a;
    } while (e !== o && (e = e.parentNode));
  return [r, i];
}
function gE(e, r) {
  for (var i in e)
    if (e.hasOwnProperty(i)) {
      for (var o in r)
        if (r.hasOwnProperty(o) && r[o] === e[i][o])
          return Number(i);
    }
  return -1;
}
function Ar(e, r) {
  if (!e || !e.getBoundingClientRect)
    return $n();
  var i = e, o = !1;
  do
    if (i.clientWidth < i.scrollWidth || i.clientHeight < i.scrollHeight) {
      var l = Q(i);
      if (i.clientWidth < i.scrollWidth && (l.overflowX == "auto" || l.overflowX == "scroll") || i.clientHeight < i.scrollHeight && (l.overflowY == "auto" || l.overflowY == "scroll")) {
        if (!i.getBoundingClientRect || i === document.body)
          return $n();
        if (o || r)
          return i;
        o = !0;
      }
    }
  while (i = i.parentNode);
  return $n();
}
function vE(e, r) {
  if (e && r)
    for (var i in r)
      r.hasOwnProperty(i) && (e[i] = r[i]);
  return e;
}
function Bu(e, r) {
  return Math.round(e.top) === Math.round(r.top) && Math.round(e.left) === Math.round(r.left) && Math.round(e.height) === Math.round(r.height) && Math.round(e.width) === Math.round(r.width);
}
var ho;
function wp(e, r) {
  return function() {
    if (!ho) {
      var i = arguments, o = this;
      i.length === 1 ? e.call(o, i[0]) : e.apply(o, i), ho = setTimeout(function() {
        ho = void 0;
      }, r);
    }
  };
}
function _E() {
  clearTimeout(ho), ho = void 0;
}
function xp(e, r, i) {
  e.scrollLeft += r, e.scrollTop += i;
}
function Lf(e) {
  var r = window.Polymer, i = window.jQuery || window.Zepto;
  return r && r.dom ? r.dom(e).cloneNode(!0) : i ? i(e).clone(!0)[0] : e.cloneNode(!0);
}
function jh(e, r) {
  Q(e, "position", "absolute"), Q(e, "top", r.top), Q(e, "left", r.left), Q(e, "width", r.width), Q(e, "height", r.height);
}
function Wu(e) {
  Q(e, "position", ""), Q(e, "top", ""), Q(e, "left", ""), Q(e, "width", ""), Q(e, "height", "");
}
function Ep(e, r, i) {
  var o = {};
  return Array.from(e.children).forEach(function(l) {
    var f, a, h, g;
    if (!(!_n(l, r.draggable, e, !1) || l.animated || l === i)) {
      var v = $t(l);
      o.left = Math.min((f = o.left) !== null && f !== void 0 ? f : 1 / 0, v.left), o.top = Math.min((a = o.top) !== null && a !== void 0 ? a : 1 / 0, v.top), o.right = Math.max((h = o.right) !== null && h !== void 0 ? h : -1 / 0, v.right), o.bottom = Math.max((g = o.bottom) !== null && g !== void 0 ? g : -1 / 0, v.bottom);
    }
  }), o.width = o.right - o.left, o.height = o.bottom - o.top, o.x = o.left, o.y = o.top, o;
}
var De = "Sortable" + (/* @__PURE__ */ new Date()).getTime();
function mE() {
  var e = [], r;
  return {
    captureAnimationState: function() {
      if (e = [], !!this.options.animation) {
        var o = [].slice.call(this.el.children);
        o.forEach(function(l) {
          if (!(Q(l, "display") === "none" || l === st.ghost)) {
            e.push({
              target: l,
              rect: $t(l)
            });
            var f = Gn({}, e[e.length - 1].rect);
            if (l.thisAnimationDuration) {
              var a = Zr(l, !0);
              a && (f.top -= a.f, f.left -= a.e);
            }
            l.fromRect = f;
          }
        });
      }
    },
    addAnimationState: function(o) {
      e.push(o);
    },
    removeAnimationState: function(o) {
      e.splice(gE(e, {
        target: o
      }), 1);
    },
    animateAll: function(o) {
      var l = this;
      if (!this.options.animation) {
        clearTimeout(r), typeof o == "function" && o();
        return;
      }
      var f = !1, a = 0;
      e.forEach(function(h) {
        var g = 0, v = h.target, b = v.fromRect, _ = $t(v), T = v.prevFromRect, C = v.prevToRect, O = h.rect, F = Zr(v, !0);
        F && (_.top -= F.f, _.left -= F.e), v.toRect = _, v.thisAnimationDuration && Bu(T, _) && !Bu(b, _) && // Make sure animatingRect is on line between toRect & fromRect
        (O.top - _.top) / (O.left - _.left) === (b.top - _.top) / (b.left - _.left) && (g = yE(O, T, C, l.options)), Bu(_, b) || (v.prevFromRect = b, v.prevToRect = _, g || (g = l.options.animation), l.animate(v, O, _, g)), g && (f = !0, a = Math.max(a, g), clearTimeout(v.animationResetTimer), v.animationResetTimer = setTimeout(function() {
          v.animationTime = 0, v.prevFromRect = null, v.fromRect = null, v.prevToRect = null, v.thisAnimationDuration = null;
        }, g), v.thisAnimationDuration = g);
      }), clearTimeout(r), f ? r = setTimeout(function() {
        typeof o == "function" && o();
      }, a) : typeof o == "function" && o(), e = [];
    },
    animate: function(o, l, f, a) {
      if (a) {
        Q(o, "transition", ""), Q(o, "transform", "");
        var h = Zr(this.el), g = h && h.a, v = h && h.d, b = (l.left - f.left) / (g || 1), _ = (l.top - f.top) / (v || 1);
        o.animatingX = !!b, o.animatingY = !!_, Q(o, "transform", "translate3d(" + b + "px," + _ + "px,0)"), this.forRepaintDummy = bE(o), Q(o, "transition", "transform " + a + "ms" + (this.options.easing ? " " + this.options.easing : "")), Q(o, "transform", "translate3d(0,0,0)"), typeof o.animated == "number" && clearTimeout(o.animated), o.animated = setTimeout(function() {
          Q(o, "transition", ""), Q(o, "transform", ""), o.animated = !1, o.animatingX = !1, o.animatingY = !1;
        }, a);
      }
    }
  };
}
function bE(e) {
  return e.offsetWidth;
}
function yE(e, r, i, o) {
  return Math.sqrt(Math.pow(r.top - e.top, 2) + Math.pow(r.left - e.left, 2)) / Math.sqrt(Math.pow(r.top - i.top, 2) + Math.pow(r.left - i.left, 2)) * o.animation;
}
var bi = [], Uu = {
  initializeByDefault: !0
}, Ao = {
  mount: function(r) {
    for (var i in Uu)
      Uu.hasOwnProperty(i) && !(i in r) && (r[i] = Uu[i]);
    bi.forEach(function(o) {
      if (o.pluginName === r.pluginName)
        throw "Sortable: Cannot mount plugin ".concat(r.pluginName, " more than once");
    }), bi.push(r);
  },
  pluginEvent: function(r, i, o) {
    var l = this;
    this.eventCanceled = !1, o.cancel = function() {
      l.eventCanceled = !0;
    };
    var f = r + "Global";
    bi.forEach(function(a) {
      i[a.pluginName] && (i[a.pluginName][f] && i[a.pluginName][f](Gn({
        sortable: i
      }, o)), i.options[a.pluginName] && i[a.pluginName][r] && i[a.pluginName][r](Gn({
        sortable: i
      }, o)));
    });
  },
  initializePlugins: function(r, i, o, l) {
    bi.forEach(function(h) {
      var g = h.pluginName;
      if (!(!r.options[g] && !h.initializeByDefault)) {
        var v = new h(r, i, r.options);
        v.sortable = r, v.options = r.options, r[g] = v, On(o, v.defaults);
      }
    });
    for (var f in r.options)
      if (r.options.hasOwnProperty(f)) {
        var a = this.modifyOption(r, f, r.options[f]);
        typeof a < "u" && (r.options[f] = a);
      }
  },
  getEventProperties: function(r, i) {
    var o = {};
    return bi.forEach(function(l) {
      typeof l.eventProperties == "function" && On(o, l.eventProperties.call(i[l.pluginName], r));
    }), o;
  },
  modifyOption: function(r, i, o) {
    var l;
    return bi.forEach(function(f) {
      r[f.pluginName] && f.optionListeners && typeof f.optionListeners[i] == "function" && (l = f.optionListeners[i].call(r[f.pluginName], o));
    }), l;
  }
};
function no(e) {
  var r = e.sortable, i = e.rootEl, o = e.name, l = e.targetEl, f = e.cloneEl, a = e.toEl, h = e.fromEl, g = e.oldIndex, v = e.newIndex, b = e.oldDraggableIndex, _ = e.newDraggableIndex, T = e.originalEvent, C = e.putSortable, O = e.extraEventProperties;
  if (r = r || i && i[De], !!r) {
    var F, N = r.options, K = "on" + o.charAt(0).toUpperCase() + o.substr(1);
    window.CustomEvent && !sr && !To ? F = new CustomEvent(o, {
      bubbles: !0,
      cancelable: !0
    }) : (F = document.createEvent("Event"), F.initEvent(o, !0, !0)), F.to = a || i, F.from = h || i, F.item = l || i, F.clone = f, F.oldIndex = g, F.newIndex = v, F.oldDraggableIndex = b, F.newDraggableIndex = _, F.originalEvent = T, F.pullMode = C ? C.lastPutMode : void 0;
    var J = Gn(Gn({}, O), Ao.getEventProperties(o, r));
    for (var V in J)
      F[V] = J[V];
    i && i.dispatchEvent(F), N[K] && N[K].call(r, F);
  }
}
var wE = ["evt"], Xe = function(r, i) {
  var o = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, l = o.evt, f = lE(o, wE);
  Ao.pluginEvent.bind(st)(r, i, Gn({
    dragEl: Y,
    parentEl: Zt,
    ghostEl: ct,
    rootEl: Xt,
    nextEl: qr,
    lastDownEl: Ns,
    cloneEl: kt,
    cloneHidden: Tr,
    dragStarted: ro,
    putSortable: Ee,
    activeSortable: st.active,
    originalEvent: l,
    oldIndex: Si,
    oldDraggableIndex: po,
    newIndex: on,
    newDraggableIndex: Er,
    hideGhostForTarget: Cp,
    unhideGhostForTarget: Ip,
    cloneNowHidden: function() {
      Tr = !0;
    },
    cloneNowShown: function() {
      Tr = !1;
    },
    dispatchSortableEvent: function(h) {
      Be({
        sortable: i,
        name: h,
        originalEvent: l
      });
    }
  }, f));
};
function Be(e) {
  no(Gn({
    putSortable: Ee,
    cloneEl: kt,
    targetEl: Y,
    rootEl: Xt,
    oldIndex: Si,
    oldDraggableIndex: po,
    newIndex: on,
    newDraggableIndex: Er
  }, e));
}
var Y, Zt, ct, Xt, qr, Ns, kt, Tr, Si, on, po, Er, Es, Ee, Ei = !1, qs = !1, Vs = [], Yr, Tn, Hu, $u, td, ed, ro, yi, go, vo = !1, Ss = !1, Ls, Ie, Ku = [], uf = !1, ks = [], fl = typeof document < "u", Ts = _p, nd = To || sr ? "cssFloat" : "float", xE = fl && !mp && !_p && "draggable" in document.createElement("div"), Sp = function() {
  if (fl) {
    if (sr)
      return !1;
    var e = document.createElement("x");
    return e.style.cssText = "pointer-events:auto", e.style.pointerEvents === "auto";
  }
}(), Tp = function(r, i) {
  var o = Q(r), l = parseInt(o.width) - parseInt(o.paddingLeft) - parseInt(o.paddingRight) - parseInt(o.borderLeftWidth) - parseInt(o.borderRightWidth), f = Mi(r, 0, i), a = Mi(r, 1, i), h = f && Q(f), g = a && Q(a), v = h && parseInt(h.marginLeft) + parseInt(h.marginRight) + $t(f).width, b = g && parseInt(g.marginLeft) + parseInt(g.marginRight) + $t(a).width;
  if (o.display === "flex")
    return o.flexDirection === "column" || o.flexDirection === "column-reverse" ? "vertical" : "horizontal";
  if (o.display === "grid")
    return o.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
  if (f && h.float && h.float !== "none") {
    var _ = h.float === "left" ? "left" : "right";
    return a && (g.clear === "both" || g.clear === _) ? "vertical" : "horizontal";
  }
  return f && (h.display === "block" || h.display === "flex" || h.display === "table" || h.display === "grid" || v >= l && o[nd] === "none" || a && o[nd] === "none" && v + b > l) ? "vertical" : "horizontal";
}, EE = function(r, i, o) {
  var l = o ? r.left : r.top, f = o ? r.right : r.bottom, a = o ? r.width : r.height, h = o ? i.left : i.top, g = o ? i.right : i.bottom, v = o ? i.width : i.height;
  return l === h || f === g || l + a / 2 === h + v / 2;
}, SE = function(r, i) {
  var o;
  return Vs.some(function(l) {
    var f = l[De].options.emptyInsertThreshold;
    if (!(!f || Nf(l))) {
      var a = $t(l), h = r >= a.left - f && r <= a.right + f, g = i >= a.top - f && i <= a.bottom + f;
      if (h && g)
        return o = l;
    }
  }), o;
}, Ap = function(r) {
  function i(f, a) {
    return function(h, g, v, b) {
      var _ = h.options.group.name && g.options.group.name && h.options.group.name === g.options.group.name;
      if (f == null && (a || _))
        return !0;
      if (f == null || f === !1)
        return !1;
      if (a && f === "clone")
        return f;
      if (typeof f == "function")
        return i(f(h, g, v, b), a)(h, g, v, b);
      var T = (a ? h : g).options.group.name;
      return f === !0 || typeof f == "string" && f === T || f.join && f.indexOf(T) > -1;
    };
  }
  var o = {}, l = r.group;
  (!l || Fs(l) != "object") && (l = {
    name: l
  }), o.name = l.name, o.checkPull = i(l.pull, !0), o.checkPut = i(l.put), o.revertClone = l.revertClone, r.group = o;
}, Cp = function() {
  !Sp && ct && Q(ct, "display", "none");
}, Ip = function() {
  !Sp && ct && Q(ct, "display", "");
};
fl && !mp && document.addEventListener("click", function(e) {
  if (qs)
    return e.preventDefault(), e.stopPropagation && e.stopPropagation(), e.stopImmediatePropagation && e.stopImmediatePropagation(), qs = !1, !1;
}, !0);
var zr = function(r) {
  if (Y) {
    r = r.touches ? r.touches[0] : r;
    var i = SE(r.clientX, r.clientY);
    if (i) {
      var o = {};
      for (var l in r)
        r.hasOwnProperty(l) && (o[l] = r[l]);
      o.target = o.rootEl = i, o.preventDefault = void 0, o.stopPropagation = void 0, i[De]._onDragOver(o);
    }
  }
}, TE = function(r) {
  Y && Y.parentNode[De]._isOutsideThisEl(r.target);
};
function st(e, r) {
  if (!(e && e.nodeType && e.nodeType === 1))
    throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(e));
  this.el = e, this.options = r = On({}, r), e[De] = this;
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
      return Tp(e, this.options);
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
    supportPointer: st.supportPointer !== !1 && "PointerEvent" in window && !co,
    emptyInsertThreshold: 5
  };
  Ao.initializePlugins(this, e, i);
  for (var o in i)
    !(o in r) && (r[o] = i[o]);
  Ap(r);
  for (var l in this)
    l.charAt(0) === "_" && typeof this[l] == "function" && (this[l] = this[l].bind(this));
  this.nativeDraggable = r.forceFallback ? !1 : xE, this.nativeDraggable && (this.options.touchStartThreshold = 1), r.supportPointer ? Et(e, "pointerdown", this._onTapStart) : (Et(e, "mousedown", this._onTapStart), Et(e, "touchstart", this._onTapStart)), this.nativeDraggable && (Et(e, "dragover", this), Et(e, "dragenter", this)), Vs.push(this.el), r.store && r.store.get && this.sort(r.store.get(this) || []), On(this, mE());
}
st.prototype = /** @lends Sortable.prototype */
{
  constructor: st,
  _isOutsideThisEl: function(r) {
    !this.el.contains(r) && r !== this.el && (yi = null);
  },
  _getDirection: function(r, i) {
    return typeof this.options.direction == "function" ? this.options.direction.call(this, r, i, Y) : this.options.direction;
  },
  _onTapStart: function(r) {
    if (r.cancelable) {
      var i = this, o = this.el, l = this.options, f = l.preventOnFilter, a = r.type, h = r.touches && r.touches[0] || r.pointerType && r.pointerType === "touch" && r, g = (h || r).target, v = r.target.shadowRoot && (r.path && r.path[0] || r.composedPath && r.composedPath()[0]) || g, b = l.filter;
      if (ME(o), !Y && !(/mousedown|pointerdown/.test(a) && r.button !== 0 || l.disabled) && !v.isContentEditable && !(!this.nativeDraggable && co && g && g.tagName.toUpperCase() === "SELECT") && (g = _n(g, l.draggable, o, !1), !(g && g.animated) && Ns !== g)) {
        if (Si = se(g), po = se(g, l.draggable), typeof b == "function") {
          if (b.call(this, r, g, this)) {
            Be({
              sortable: i,
              rootEl: v,
              name: "filter",
              targetEl: g,
              toEl: o,
              fromEl: o
            }), Xe("filter", i, {
              evt: r
            }), f && r.cancelable && r.preventDefault();
            return;
          }
        } else if (b && (b = b.split(",").some(function(_) {
          if (_ = _n(v, _.trim(), o, !1), _)
            return Be({
              sortable: i,
              rootEl: _,
              name: "filter",
              targetEl: g,
              fromEl: o,
              toEl: o
            }), Xe("filter", i, {
              evt: r
            }), !0;
        }), b)) {
          f && r.cancelable && r.preventDefault();
          return;
        }
        l.handle && !_n(v, l.handle, o, !1) || this._prepareDragStart(r, h, g);
      }
    }
  },
  _prepareDragStart: function(r, i, o) {
    var l = this, f = l.el, a = l.options, h = f.ownerDocument, g;
    if (o && !Y && o.parentNode === f) {
      var v = $t(o);
      if (Xt = f, Y = o, Zt = Y.parentNode, qr = Y.nextSibling, Ns = o, Es = a.group, st.dragged = Y, Yr = {
        target: Y,
        clientX: (i || r).clientX,
        clientY: (i || r).clientY
      }, td = Yr.clientX - v.left, ed = Yr.clientY - v.top, this._lastX = (i || r).clientX, this._lastY = (i || r).clientY, Y.style["will-change"] = "all", g = function() {
        if (Xe("delayEnded", l, {
          evt: r
        }), st.eventCanceled) {
          l._onDrop();
          return;
        }
        l._disableDelayedDragEvents(), !kh && l.nativeDraggable && (Y.draggable = !0), l._triggerDragStart(r, i), Be({
          sortable: l,
          name: "choose",
          originalEvent: r
        }), oe(Y, a.chosenClass, !0);
      }, a.ignore.split(",").forEach(function(b) {
        yp(Y, b.trim(), Gu);
      }), Et(h, "dragover", zr), Et(h, "mousemove", zr), Et(h, "touchmove", zr), Et(h, "mouseup", l._onDrop), Et(h, "touchend", l._onDrop), Et(h, "touchcancel", l._onDrop), kh && this.nativeDraggable && (this.options.touchStartThreshold = 4, Y.draggable = !0), Xe("delayStart", this, {
        evt: r
      }), a.delay && (!a.delayOnTouchOnly || i) && (!this.nativeDraggable || !(To || sr))) {
        if (st.eventCanceled) {
          this._onDrop();
          return;
        }
        Et(h, "mouseup", l._disableDelayedDrag), Et(h, "touchend", l._disableDelayedDrag), Et(h, "touchcancel", l._disableDelayedDrag), Et(h, "mousemove", l._delayedDragTouchMoveHandler), Et(h, "touchmove", l._delayedDragTouchMoveHandler), a.supportPointer && Et(h, "pointermove", l._delayedDragTouchMoveHandler), l._dragStartTimer = setTimeout(g, a.delay);
      } else
        g();
    }
  },
  _delayedDragTouchMoveHandler: function(r) {
    var i = r.touches ? r.touches[0] : r;
    Math.max(Math.abs(i.clientX - this._lastX), Math.abs(i.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1)) && this._disableDelayedDrag();
  },
  _disableDelayedDrag: function() {
    Y && Gu(Y), clearTimeout(this._dragStartTimer), this._disableDelayedDragEvents();
  },
  _disableDelayedDragEvents: function() {
    var r = this.el.ownerDocument;
    yt(r, "mouseup", this._disableDelayedDrag), yt(r, "touchend", this._disableDelayedDrag), yt(r, "touchcancel", this._disableDelayedDrag), yt(r, "mousemove", this._delayedDragTouchMoveHandler), yt(r, "touchmove", this._delayedDragTouchMoveHandler), yt(r, "pointermove", this._delayedDragTouchMoveHandler);
  },
  _triggerDragStart: function(r, i) {
    i = i || r.pointerType == "touch" && r, !this.nativeDraggable || i ? this.options.supportPointer ? Et(document, "pointermove", this._onTouchMove) : i ? Et(document, "touchmove", this._onTouchMove) : Et(document, "mousemove", this._onTouchMove) : (Et(Y, "dragend", this), Et(Xt, "dragstart", this._onDragStart));
    try {
      document.selection ? Bs(function() {
        document.selection.empty();
      }) : window.getSelection().removeAllRanges();
    } catch {
    }
  },
  _dragStarted: function(r, i) {
    if (Ei = !1, Xt && Y) {
      Xe("dragStarted", this, {
        evt: i
      }), this.nativeDraggable && Et(document, "dragover", TE);
      var o = this.options;
      !r && oe(Y, o.dragClass, !1), oe(Y, o.ghostClass, !0), st.active = this, r && this._appendGhost(), Be({
        sortable: this,
        name: "start",
        originalEvent: i
      });
    } else
      this._nulling();
  },
  _emulateDragOver: function() {
    if (Tn) {
      this._lastX = Tn.clientX, this._lastY = Tn.clientY, Cp();
      for (var r = document.elementFromPoint(Tn.clientX, Tn.clientY), i = r; r && r.shadowRoot && (r = r.shadowRoot.elementFromPoint(Tn.clientX, Tn.clientY), r !== i); )
        i = r;
      if (Y.parentNode[De]._isOutsideThisEl(r), i)
        do {
          if (i[De]) {
            var o = void 0;
            if (o = i[De]._onDragOver({
              clientX: Tn.clientX,
              clientY: Tn.clientY,
              target: r,
              rootEl: i
            }), o && !this.options.dragoverBubble)
              break;
          }
          r = i;
        } while (i = i.parentNode);
      Ip();
    }
  },
  _onTouchMove: function(r) {
    if (Yr) {
      var i = this.options, o = i.fallbackTolerance, l = i.fallbackOffset, f = r.touches ? r.touches[0] : r, a = ct && Zr(ct, !0), h = ct && a && a.a, g = ct && a && a.d, v = Ts && Ie && Qh(Ie), b = (f.clientX - Yr.clientX + l.x) / (h || 1) + (v ? v[0] - Ku[0] : 0) / (h || 1), _ = (f.clientY - Yr.clientY + l.y) / (g || 1) + (v ? v[1] - Ku[1] : 0) / (g || 1);
      if (!st.active && !Ei) {
        if (o && Math.max(Math.abs(f.clientX - this._lastX), Math.abs(f.clientY - this._lastY)) < o)
          return;
        this._onDragStart(r, !0);
      }
      if (ct) {
        a ? (a.e += b - (Hu || 0), a.f += _ - ($u || 0)) : a = {
          a: 1,
          b: 0,
          c: 0,
          d: 1,
          e: b,
          f: _
        };
        var T = "matrix(".concat(a.a, ",").concat(a.b, ",").concat(a.c, ",").concat(a.d, ",").concat(a.e, ",").concat(a.f, ")");
        Q(ct, "webkitTransform", T), Q(ct, "mozTransform", T), Q(ct, "msTransform", T), Q(ct, "transform", T), Hu = b, $u = _, Tn = f;
      }
      r.cancelable && r.preventDefault();
    }
  },
  _appendGhost: function() {
    if (!ct) {
      var r = this.options.fallbackOnBody ? document.body : Xt, i = $t(Y, !0, Ts, !0, r), o = this.options;
      if (Ts) {
        for (Ie = r; Q(Ie, "position") === "static" && Q(Ie, "transform") === "none" && Ie !== document; )
          Ie = Ie.parentNode;
        Ie !== document.body && Ie !== document.documentElement ? (Ie === document && (Ie = $n()), i.top += Ie.scrollTop, i.left += Ie.scrollLeft) : Ie = $n(), Ku = Qh(Ie);
      }
      ct = Y.cloneNode(!0), oe(ct, o.ghostClass, !1), oe(ct, o.fallbackClass, !0), oe(ct, o.dragClass, !0), Q(ct, "transition", ""), Q(ct, "transform", ""), Q(ct, "box-sizing", "border-box"), Q(ct, "margin", 0), Q(ct, "top", i.top), Q(ct, "left", i.left), Q(ct, "width", i.width), Q(ct, "height", i.height), Q(ct, "opacity", "0.8"), Q(ct, "position", Ts ? "absolute" : "fixed"), Q(ct, "zIndex", "100000"), Q(ct, "pointerEvents", "none"), st.ghost = ct, r.appendChild(ct), Q(ct, "transform-origin", td / parseInt(ct.style.width) * 100 + "% " + ed / parseInt(ct.style.height) * 100 + "%");
    }
  },
  _onDragStart: function(r, i) {
    var o = this, l = r.dataTransfer, f = o.options;
    if (Xe("dragStart", this, {
      evt: r
    }), st.eventCanceled) {
      this._onDrop();
      return;
    }
    Xe("setupClone", this), st.eventCanceled || (kt = Lf(Y), kt.removeAttribute("id"), kt.draggable = !1, kt.style["will-change"] = "", this._hideClone(), oe(kt, this.options.chosenClass, !1), st.clone = kt), o.cloneId = Bs(function() {
      Xe("clone", o), !st.eventCanceled && (o.options.removeCloneOnHide || Xt.insertBefore(kt, Y), o._hideClone(), Be({
        sortable: o,
        name: "clone"
      }));
    }), !i && oe(Y, f.dragClass, !0), i ? (qs = !0, o._loopId = setInterval(o._emulateDragOver, 50)) : (yt(document, "mouseup", o._onDrop), yt(document, "touchend", o._onDrop), yt(document, "touchcancel", o._onDrop), l && (l.effectAllowed = "move", f.setData && f.setData.call(o, l, Y)), Et(document, "drop", o), Q(Y, "transform", "translateZ(0)")), Ei = !0, o._dragStartId = Bs(o._dragStarted.bind(o, i, r)), Et(document, "selectstart", o), ro = !0, co && Q(document.body, "user-select", "none");
  },
  // Returns true - if no further action is needed (either inserted or another condition)
  _onDragOver: function(r) {
    var i = this.el, o = r.target, l, f, a, h = this.options, g = h.group, v = st.active, b = Es === g, _ = h.sort, T = Ee || v, C, O = this, F = !1;
    if (uf)
      return;
    function N(rt, ve) {
      Xe(rt, O, Gn({
        evt: r,
        isOwner: b,
        axis: C ? "vertical" : "horizontal",
        revert: a,
        dragRect: l,
        targetRect: f,
        canSort: _,
        fromSortable: T,
        target: o,
        completed: J,
        onMove: function(Yt, ee) {
          return As(Xt, i, Y, l, Yt, $t(Yt), r, ee);
        },
        changed: V
      }, ve));
    }
    function K() {
      N("dragOverAnimationCapture"), O.captureAnimationState(), O !== T && T.captureAnimationState();
    }
    function J(rt) {
      return N("dragOverCompleted", {
        insertion: rt
      }), rt && (b ? v._hideClone() : v._showClone(O), O !== T && (oe(Y, Ee ? Ee.options.ghostClass : v.options.ghostClass, !1), oe(Y, h.ghostClass, !0)), Ee !== O && O !== st.active ? Ee = O : O === st.active && Ee && (Ee = null), T === O && (O._ignoreWhileAnimating = o), O.animateAll(function() {
        N("dragOverAnimationComplete"), O._ignoreWhileAnimating = null;
      }), O !== T && (T.animateAll(), T._ignoreWhileAnimating = null)), (o === Y && !Y.animated || o === i && !o.animated) && (yi = null), !h.dragoverBubble && !r.rootEl && o !== document && (Y.parentNode[De]._isOutsideThisEl(r.target), !rt && zr(r)), !h.dragoverBubble && r.stopPropagation && r.stopPropagation(), F = !0;
    }
    function V() {
      on = se(Y), Er = se(Y, h.draggable), Be({
        sortable: O,
        name: "change",
        toEl: i,
        newIndex: on,
        newDraggableIndex: Er,
        originalEvent: r
      });
    }
    if (r.preventDefault !== void 0 && r.cancelable && r.preventDefault(), o = _n(o, h.draggable, i, !0), N("dragOver"), st.eventCanceled)
      return F;
    if (Y.contains(r.target) || o.animated && o.animatingX && o.animatingY || O._ignoreWhileAnimating === o)
      return J(!1);
    if (qs = !1, v && !h.disabled && (b ? _ || (a = Zt !== Xt) : Ee === this || (this.lastPutMode = Es.checkPull(this, v, Y, r)) && g.checkPut(this, v, Y, r))) {
      if (C = this._getDirection(r, o) === "vertical", l = $t(Y), N("dragOverValid"), st.eventCanceled)
        return F;
      if (a)
        return Zt = Xt, K(), this._hideClone(), N("revert"), st.eventCanceled || (qr ? Xt.insertBefore(Y, qr) : Xt.appendChild(Y)), J(!0);
      var U = Nf(i, h.draggable);
      if (!U || OE(r, C, this) && !U.animated) {
        if (U === Y)
          return J(!1);
        if (U && i === r.target && (o = U), o && (f = $t(o)), As(Xt, i, Y, l, o, f, r, !!o) !== !1)
          return K(), U && U.nextSibling ? i.insertBefore(Y, U.nextSibling) : i.appendChild(Y), Zt = i, V(), J(!0);
      } else if (U && IE(r, C, this)) {
        var nt = Mi(i, 0, h, !0);
        if (nt === Y)
          return J(!1);
        if (o = nt, f = $t(o), As(Xt, i, Y, l, o, f, r, !1) !== !1)
          return K(), i.insertBefore(Y, nt), Zt = i, V(), J(!0);
      } else if (o.parentNode === i) {
        f = $t(o);
        var Tt = 0, It, Kt = Y.parentNode !== i, Lt = !EE(Y.animated && Y.toRect || l, o.animated && o.toRect || f, C), te = C ? "top" : "left", fe = Zh(o, "top", "top") || Zh(Y, "top", "top"), ge = fe ? fe.scrollTop : void 0;
        yi !== o && (It = f[te], vo = !1, Ss = !Lt && h.invertSwap || Kt), Tt = DE(r, o, f, C, Lt ? 1 : h.swapThreshold, h.invertedSwapThreshold == null ? h.swapThreshold : h.invertedSwapThreshold, Ss, yi === o);
        var Gt;
        if (Tt !== 0) {
          var he = se(Y);
          do
            he -= Tt, Gt = Zt.children[he];
          while (Gt && (Q(Gt, "display") === "none" || Gt === ct));
        }
        if (Tt === 0 || Gt === o)
          return J(!1);
        yi = o, go = Tt;
        var Te = o.nextElementSibling, gt = !1;
        gt = Tt === 1;
        var lt = As(Xt, i, Y, l, o, f, r, gt);
        if (lt !== !1)
          return (lt === 1 || lt === -1) && (gt = lt === 1), uf = !0, setTimeout(CE, 30), K(), gt && !Te ? i.appendChild(Y) : o.parentNode.insertBefore(Y, gt ? Te : o), fe && xp(fe, 0, ge - fe.scrollTop), Zt = Y.parentNode, It !== void 0 && !Ss && (Ls = Math.abs(It - $t(o)[te])), V(), J(!0);
      }
      if (i.contains(Y))
        return J(!1);
    }
    return !1;
  },
  _ignoreWhileAnimating: null,
  _offMoveEvents: function() {
    yt(document, "mousemove", this._onTouchMove), yt(document, "touchmove", this._onTouchMove), yt(document, "pointermove", this._onTouchMove), yt(document, "dragover", zr), yt(document, "mousemove", zr), yt(document, "touchmove", zr);
  },
  _offUpEvents: function() {
    var r = this.el.ownerDocument;
    yt(r, "mouseup", this._onDrop), yt(r, "touchend", this._onDrop), yt(r, "pointerup", this._onDrop), yt(r, "touchcancel", this._onDrop), yt(document, "selectstart", this);
  },
  _onDrop: function(r) {
    var i = this.el, o = this.options;
    if (on = se(Y), Er = se(Y, o.draggable), Xe("drop", this, {
      evt: r
    }), Zt = Y && Y.parentNode, on = se(Y), Er = se(Y, o.draggable), st.eventCanceled) {
      this._nulling();
      return;
    }
    Ei = !1, Ss = !1, vo = !1, clearInterval(this._loopId), clearTimeout(this._dragStartTimer), ff(this.cloneId), ff(this._dragStartId), this.nativeDraggable && (yt(document, "drop", this), yt(i, "dragstart", this._onDragStart)), this._offMoveEvents(), this._offUpEvents(), co && Q(document.body, "user-select", ""), Q(Y, "transform", ""), r && (ro && (r.cancelable && r.preventDefault(), !o.dropBubble && r.stopPropagation()), ct && ct.parentNode && ct.parentNode.removeChild(ct), (Xt === Zt || Ee && Ee.lastPutMode !== "clone") && kt && kt.parentNode && kt.parentNode.removeChild(kt), Y && (this.nativeDraggable && yt(Y, "dragend", this), Gu(Y), Y.style["will-change"] = "", ro && !Ei && oe(Y, Ee ? Ee.options.ghostClass : this.options.ghostClass, !1), oe(Y, this.options.chosenClass, !1), Be({
      sortable: this,
      name: "unchoose",
      toEl: Zt,
      newIndex: null,
      newDraggableIndex: null,
      originalEvent: r
    }), Xt !== Zt ? (on >= 0 && (Be({
      rootEl: Zt,
      name: "add",
      toEl: Zt,
      fromEl: Xt,
      originalEvent: r
    }), Be({
      sortable: this,
      name: "remove",
      toEl: Zt,
      originalEvent: r
    }), Be({
      rootEl: Zt,
      name: "sort",
      toEl: Zt,
      fromEl: Xt,
      originalEvent: r
    }), Be({
      sortable: this,
      name: "sort",
      toEl: Zt,
      originalEvent: r
    })), Ee && Ee.save()) : on !== Si && on >= 0 && (Be({
      sortable: this,
      name: "update",
      toEl: Zt,
      originalEvent: r
    }), Be({
      sortable: this,
      name: "sort",
      toEl: Zt,
      originalEvent: r
    })), st.active && ((on == null || on === -1) && (on = Si, Er = po), Be({
      sortable: this,
      name: "end",
      toEl: Zt,
      originalEvent: r
    }), this.save()))), this._nulling();
  },
  _nulling: function() {
    Xe("nulling", this), Xt = Y = Zt = ct = qr = kt = Ns = Tr = Yr = Tn = ro = on = Er = Si = po = yi = go = Ee = Es = st.dragged = st.ghost = st.clone = st.active = null, ks.forEach(function(r) {
      r.checked = !0;
    }), ks.length = Hu = $u = 0;
  },
  handleEvent: function(r) {
    switch (r.type) {
      case "drop":
      case "dragend":
        this._onDrop(r);
        break;
      case "dragenter":
      case "dragover":
        Y && (this._onDragOver(r), AE(r));
        break;
      case "selectstart":
        r.preventDefault();
        break;
    }
  },
  /**
   * Serializes the item into an array of string.
   * @returns {String[]}
   */
  toArray: function() {
    for (var r = [], i, o = this.el.children, l = 0, f = o.length, a = this.options; l < f; l++)
      i = o[l], _n(i, a.draggable, this.el, !1) && r.push(i.getAttribute(a.dataIdAttr) || PE(i));
    return r;
  },
  /**
   * Sorts the elements according to the array.
   * @param  {String[]}  order  order of the items
   */
  sort: function(r, i) {
    var o = {}, l = this.el;
    this.toArray().forEach(function(f, a) {
      var h = l.children[a];
      _n(h, this.options.draggable, l, !1) && (o[f] = h);
    }, this), i && this.captureAnimationState(), r.forEach(function(f) {
      o[f] && (l.removeChild(o[f]), l.appendChild(o[f]));
    }), i && this.animateAll();
  },
  /**
   * Save the current sorting
   */
  save: function() {
    var r = this.options.store;
    r && r.set && r.set(this);
  },
  /**
   * For each element in the set, get the first element that matches the selector by testing the element itself and traversing up through its ancestors in the DOM tree.
   * @param   {HTMLElement}  el
   * @param   {String}       [selector]  default: `options.draggable`
   * @returns {HTMLElement|null}
   */
  closest: function(r, i) {
    return _n(r, i || this.options.draggable, this.el, !1);
  },
  /**
   * Set/get option
   * @param   {string} name
   * @param   {*}      [value]
   * @returns {*}
   */
  option: function(r, i) {
    var o = this.options;
    if (i === void 0)
      return o[r];
    var l = Ao.modifyOption(this, r, i);
    typeof l < "u" ? o[r] = l : o[r] = i, r === "group" && Ap(o);
  },
  /**
   * Destroy
   */
  destroy: function() {
    Xe("destroy", this);
    var r = this.el;
    r[De] = null, yt(r, "mousedown", this._onTapStart), yt(r, "touchstart", this._onTapStart), yt(r, "pointerdown", this._onTapStart), this.nativeDraggable && (yt(r, "dragover", this), yt(r, "dragenter", this)), Array.prototype.forEach.call(r.querySelectorAll("[draggable]"), function(i) {
      i.removeAttribute("draggable");
    }), this._onDrop(), this._disableDelayedDragEvents(), Vs.splice(Vs.indexOf(this.el), 1), this.el = r = null;
  },
  _hideClone: function() {
    if (!Tr) {
      if (Xe("hideClone", this), st.eventCanceled)
        return;
      Q(kt, "display", "none"), this.options.removeCloneOnHide && kt.parentNode && kt.parentNode.removeChild(kt), Tr = !0;
    }
  },
  _showClone: function(r) {
    if (r.lastPutMode !== "clone") {
      this._hideClone();
      return;
    }
    if (Tr) {
      if (Xe("showClone", this), st.eventCanceled)
        return;
      Y.parentNode == Xt && !this.options.group.revertClone ? Xt.insertBefore(kt, Y) : qr ? Xt.insertBefore(kt, qr) : Xt.appendChild(kt), this.options.group.revertClone && this.animate(Y, kt), Q(kt, "display", ""), Tr = !1;
    }
  }
};
function AE(e) {
  e.dataTransfer && (e.dataTransfer.dropEffect = "move"), e.cancelable && e.preventDefault();
}
function As(e, r, i, o, l, f, a, h) {
  var g, v = e[De], b = v.options.onMove, _;
  return window.CustomEvent && !sr && !To ? g = new CustomEvent("move", {
    bubbles: !0,
    cancelable: !0
  }) : (g = document.createEvent("Event"), g.initEvent("move", !0, !0)), g.to = r, g.from = e, g.dragged = i, g.draggedRect = o, g.related = l || r, g.relatedRect = f || $t(r), g.willInsertAfter = h, g.originalEvent = a, e.dispatchEvent(g), b && (_ = b.call(v, g, a)), _;
}
function Gu(e) {
  e.draggable = !1;
}
function CE() {
  uf = !1;
}
function IE(e, r, i) {
  var o = $t(Mi(i.el, 0, i.options, !0)), l = Ep(i.el, i.options, ct), f = 10;
  return r ? e.clientX < l.left - f || e.clientY < o.top && e.clientX < o.right : e.clientY < l.top - f || e.clientY < o.bottom && e.clientX < o.left;
}
function OE(e, r, i) {
  var o = $t(Nf(i.el, i.options.draggable)), l = Ep(i.el, i.options, ct), f = 10;
  return r ? e.clientX > l.right + f || e.clientY > o.bottom && e.clientX > o.left : e.clientY > l.bottom + f || e.clientX > o.right && e.clientY > o.top;
}
function DE(e, r, i, o, l, f, a, h) {
  var g = o ? e.clientY : e.clientX, v = o ? i.height : i.width, b = o ? i.top : i.left, _ = o ? i.bottom : i.right, T = !1;
  if (!a) {
    if (h && Ls < v * l) {
      if (!vo && (go === 1 ? g > b + v * f / 2 : g < _ - v * f / 2) && (vo = !0), vo)
        T = !0;
      else if (go === 1 ? g < b + Ls : g > _ - Ls)
        return -go;
    } else if (g > b + v * (1 - l) / 2 && g < _ - v * (1 - l) / 2)
      return RE(r);
  }
  return T = T || a, T && (g < b + v * f / 2 || g > _ - v * f / 2) ? g > b + v / 2 ? 1 : -1 : 0;
}
function RE(e) {
  return se(Y) < se(e) ? 1 : -1;
}
function PE(e) {
  for (var r = e.tagName + e.className + e.src + e.href + e.textContent, i = r.length, o = 0; i--; )
    o += r.charCodeAt(i);
  return o.toString(36);
}
function ME(e) {
  ks.length = 0;
  for (var r = e.getElementsByTagName("input"), i = r.length; i--; ) {
    var o = r[i];
    o.checked && ks.push(o);
  }
}
function Bs(e) {
  return setTimeout(e, 0);
}
function ff(e) {
  return clearTimeout(e);
}
fl && Et(document, "touchmove", function(e) {
  (st.active || Ei) && e.cancelable && e.preventDefault();
});
st.utils = {
  on: Et,
  off: yt,
  css: Q,
  find: yp,
  is: function(r, i) {
    return !!_n(r, i, r, !1);
  },
  extend: vE,
  throttle: wp,
  closest: _n,
  toggleClass: oe,
  clone: Lf,
  index: se,
  nextTick: Bs,
  cancelNextTick: ff,
  detectDirection: Tp,
  getChild: Mi
};
st.get = function(e) {
  return e[De];
};
st.mount = function() {
  for (var e = arguments.length, r = new Array(e), i = 0; i < e; i++)
    r[i] = arguments[i];
  r[0].constructor === Array && (r = r[0]), r.forEach(function(o) {
    if (!o.prototype || !o.prototype.constructor)
      throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(o));
    o.utils && (st.utils = Gn(Gn({}, st.utils), o.utils)), Ao.mount(o);
  });
};
st.create = function(e, r) {
  return new st(e, r);
};
st.version = dE;
var ie = [], io, af, cf = !1, Yu, zu, Js, oo;
function FE() {
  function e() {
    this.defaults = {
      scroll: !0,
      forceAutoScrollFallback: !1,
      scrollSensitivity: 30,
      scrollSpeed: 10,
      bubbleScroll: !0
    };
    for (var r in this)
      r.charAt(0) === "_" && typeof this[r] == "function" && (this[r] = this[r].bind(this));
  }
  return e.prototype = {
    dragStarted: function(i) {
      var o = i.originalEvent;
      this.sortable.nativeDraggable ? Et(document, "dragover", this._handleAutoScroll) : this.options.supportPointer ? Et(document, "pointermove", this._handleFallbackAutoScroll) : o.touches ? Et(document, "touchmove", this._handleFallbackAutoScroll) : Et(document, "mousemove", this._handleFallbackAutoScroll);
    },
    dragOverCompleted: function(i) {
      var o = i.originalEvent;
      !this.options.dragOverBubble && !o.rootEl && this._handleAutoScroll(o);
    },
    drop: function() {
      this.sortable.nativeDraggable ? yt(document, "dragover", this._handleAutoScroll) : (yt(document, "pointermove", this._handleFallbackAutoScroll), yt(document, "touchmove", this._handleFallbackAutoScroll), yt(document, "mousemove", this._handleFallbackAutoScroll)), rd(), Ws(), _E();
    },
    nulling: function() {
      Js = af = io = cf = oo = Yu = zu = null, ie.length = 0;
    },
    _handleFallbackAutoScroll: function(i) {
      this._handleAutoScroll(i, !0);
    },
    _handleAutoScroll: function(i, o) {
      var l = this, f = (i.touches ? i.touches[0] : i).clientX, a = (i.touches ? i.touches[0] : i).clientY, h = document.elementFromPoint(f, a);
      if (Js = i, o || this.options.forceAutoScrollFallback || To || sr || co) {
        Xu(i, this.options, h, o);
        var g = Ar(h, !0);
        cf && (!oo || f !== Yu || a !== zu) && (oo && rd(), oo = setInterval(function() {
          var v = Ar(document.elementFromPoint(f, a), !0);
          v !== g && (g = v, Ws()), Xu(i, l.options, v, o);
        }, 10), Yu = f, zu = a);
      } else {
        if (!this.options.bubbleScroll || Ar(h, !0) === $n()) {
          Ws();
          return;
        }
        Xu(i, this.options, Ar(h, !1), !1);
      }
    }
  }, On(e, {
    pluginName: "scroll",
    initializeByDefault: !0
  });
}
function Ws() {
  ie.forEach(function(e) {
    clearInterval(e.pid);
  }), ie = [];
}
function rd() {
  clearInterval(oo);
}
var Xu = wp(function(e, r, i, o) {
  if (r.scroll) {
    var l = (e.touches ? e.touches[0] : e).clientX, f = (e.touches ? e.touches[0] : e).clientY, a = r.scrollSensitivity, h = r.scrollSpeed, g = $n(), v = !1, b;
    af !== i && (af = i, Ws(), io = r.scroll, b = r.scrollFn, io === !0 && (io = Ar(i, !0)));
    var _ = 0, T = io;
    do {
      var C = T, O = $t(C), F = O.top, N = O.bottom, K = O.left, J = O.right, V = O.width, U = O.height, nt = void 0, Tt = void 0, It = C.scrollWidth, Kt = C.scrollHeight, Lt = Q(C), te = C.scrollLeft, fe = C.scrollTop;
      C === g ? (nt = V < It && (Lt.overflowX === "auto" || Lt.overflowX === "scroll" || Lt.overflowX === "visible"), Tt = U < Kt && (Lt.overflowY === "auto" || Lt.overflowY === "scroll" || Lt.overflowY === "visible")) : (nt = V < It && (Lt.overflowX === "auto" || Lt.overflowX === "scroll"), Tt = U < Kt && (Lt.overflowY === "auto" || Lt.overflowY === "scroll"));
      var ge = nt && (Math.abs(J - l) <= a && te + V < It) - (Math.abs(K - l) <= a && !!te), Gt = Tt && (Math.abs(N - f) <= a && fe + U < Kt) - (Math.abs(F - f) <= a && !!fe);
      if (!ie[_])
        for (var he = 0; he <= _; he++)
          ie[he] || (ie[he] = {});
      (ie[_].vx != ge || ie[_].vy != Gt || ie[_].el !== C) && (ie[_].el = C, ie[_].vx = ge, ie[_].vy = Gt, clearInterval(ie[_].pid), (ge != 0 || Gt != 0) && (v = !0, ie[_].pid = setInterval((function() {
        o && this.layer === 0 && st.active._onTouchMove(Js);
        var Te = ie[this.layer].vy ? ie[this.layer].vy * h : 0, gt = ie[this.layer].vx ? ie[this.layer].vx * h : 0;
        typeof b == "function" && b.call(st.dragged.parentNode[De], gt, Te, e, Js, ie[this.layer].el) !== "continue" || xp(ie[this.layer].el, gt, Te);
      }).bind({
        layer: _
      }), 24))), _++;
    } while (r.bubbleScroll && T !== g && (T = Ar(T, !1)));
    cf = v;
  }
}, 30), Op = function(r) {
  var i = r.originalEvent, o = r.putSortable, l = r.dragEl, f = r.activeSortable, a = r.dispatchSortableEvent, h = r.hideGhostForTarget, g = r.unhideGhostForTarget;
  if (i) {
    var v = o || f;
    h();
    var b = i.changedTouches && i.changedTouches.length ? i.changedTouches[0] : i, _ = document.elementFromPoint(b.clientX, b.clientY);
    g(), v && !v.el.contains(_) && (a("spill"), this.onSpill({
      dragEl: l,
      putSortable: o
    }));
  }
};
function Bf() {
}
Bf.prototype = {
  startIndex: null,
  dragStart: function(r) {
    var i = r.oldDraggableIndex;
    this.startIndex = i;
  },
  onSpill: function(r) {
    var i = r.dragEl, o = r.putSortable;
    this.sortable.captureAnimationState(), o && o.captureAnimationState();
    var l = Mi(this.sortable.el, this.startIndex - (ft.length ? ft.indexOf(i) : 0), this.options);
    l ? this.sortable.el.insertBefore(i, l) : this.sortable.el.appendChild(i), this.sortable.animateAll(), o && o.animateAll();
  },
  drop: Op
};
On(Bf, {
  pluginName: "revertOnSpill"
});
function Wf() {
}
Wf.prototype = {
  onSpill: function(r) {
    var i = r.dragEl, o = r.putSortable, l = o || this.sortable;
    l.captureAnimationState(), i.parentNode && i.parentNode.removeChild(i), l.animateAll();
  },
  drop: Op
};
On(Wf, {
  pluginName: "removeOnSpill"
});
var ft = [], nn = [], Qi, An, ji = !1, qe = !1, wi = !1, Wt, to, Cs;
function NE() {
  function e(r) {
    for (var i in this)
      i.charAt(0) === "_" && typeof this[i] == "function" && (this[i] = this[i].bind(this));
    r.options.avoidImplicitDeselect || (r.options.supportPointer ? Et(document, "pointerup", this._deselectMultiDrag) : (Et(document, "mouseup", this._deselectMultiDrag), Et(document, "touchend", this._deselectMultiDrag))), Et(document, "keydown", this._checkKeyDown), Et(document, "keyup", this._checkKeyUp), this.defaults = {
      selectedClass: "sortable-selected",
      multiDragKey: null,
      avoidImplicitDeselect: !1,
      setData: function(l, f) {
        var a = "";
        ft.length && An === r ? ft.forEach(function(h, g) {
          a += (g ? ", " : "") + h.textContent;
        }) : a = f.textContent, l.setData("Text", a);
      }
    };
  }
  return e.prototype = {
    multiDragKeyDown: !1,
    isMultiDrag: !1,
    delayStartGlobal: function(i) {
      var o = i.dragEl;
      Wt = o;
    },
    delayEnded: function() {
      this.isMultiDrag = ~ft.indexOf(Wt);
    },
    setupClone: function(i) {
      var o = i.sortable, l = i.cancel;
      if (this.isMultiDrag) {
        for (var f = 0; f < ft.length; f++)
          nn.push(Lf(ft[f])), nn[f].sortableIndex = ft[f].sortableIndex, nn[f].draggable = !1, nn[f].style["will-change"] = "", oe(nn[f], this.options.selectedClass, !1), ft[f] === Wt && oe(nn[f], this.options.chosenClass, !1);
        o._hideClone(), l();
      }
    },
    clone: function(i) {
      var o = i.sortable, l = i.rootEl, f = i.dispatchSortableEvent, a = i.cancel;
      this.isMultiDrag && (this.options.removeCloneOnHide || ft.length && An === o && (id(!0, l), f("clone"), a()));
    },
    showClone: function(i) {
      var o = i.cloneNowShown, l = i.rootEl, f = i.cancel;
      this.isMultiDrag && (id(!1, l), nn.forEach(function(a) {
        Q(a, "display", "");
      }), o(), Cs = !1, f());
    },
    hideClone: function(i) {
      var o = this;
      i.sortable;
      var l = i.cloneNowHidden, f = i.cancel;
      this.isMultiDrag && (nn.forEach(function(a) {
        Q(a, "display", "none"), o.options.removeCloneOnHide && a.parentNode && a.parentNode.removeChild(a);
      }), l(), Cs = !0, f());
    },
    dragStartGlobal: function(i) {
      i.sortable, !this.isMultiDrag && An && An.multiDrag._deselectMultiDrag(), ft.forEach(function(o) {
        o.sortableIndex = se(o);
      }), ft = ft.sort(function(o, l) {
        return o.sortableIndex - l.sortableIndex;
      }), wi = !0;
    },
    dragStarted: function(i) {
      var o = this, l = i.sortable;
      if (this.isMultiDrag) {
        if (this.options.sort && (l.captureAnimationState(), this.options.animation)) {
          ft.forEach(function(a) {
            a !== Wt && Q(a, "position", "absolute");
          });
          var f = $t(Wt, !1, !0, !0);
          ft.forEach(function(a) {
            a !== Wt && jh(a, f);
          }), qe = !0, ji = !0;
        }
        l.animateAll(function() {
          qe = !1, ji = !1, o.options.animation && ft.forEach(function(a) {
            Wu(a);
          }), o.options.sort && Is();
        });
      }
    },
    dragOver: function(i) {
      var o = i.target, l = i.completed, f = i.cancel;
      qe && ~ft.indexOf(o) && (l(!1), f());
    },
    revert: function(i) {
      var o = i.fromSortable, l = i.rootEl, f = i.sortable, a = i.dragRect;
      ft.length > 1 && (ft.forEach(function(h) {
        f.addAnimationState({
          target: h,
          rect: qe ? $t(h) : a
        }), Wu(h), h.fromRect = a, o.removeAnimationState(h);
      }), qe = !1, LE(!this.options.removeCloneOnHide, l));
    },
    dragOverCompleted: function(i) {
      var o = i.sortable, l = i.isOwner, f = i.insertion, a = i.activeSortable, h = i.parentEl, g = i.putSortable, v = this.options;
      if (f) {
        if (l && a._hideClone(), ji = !1, v.animation && ft.length > 1 && (qe || !l && !a.options.sort && !g)) {
          var b = $t(Wt, !1, !0, !0);
          ft.forEach(function(T) {
            T !== Wt && (jh(T, b), h.appendChild(T));
          }), qe = !0;
        }
        if (!l)
          if (qe || Is(), ft.length > 1) {
            var _ = Cs;
            a._showClone(o), a.options.animation && !Cs && _ && nn.forEach(function(T) {
              a.addAnimationState({
                target: T,
                rect: to
              }), T.fromRect = to, T.thisAnimationDuration = null;
            });
          } else
            a._showClone(o);
      }
    },
    dragOverAnimationCapture: function(i) {
      var o = i.dragRect, l = i.isOwner, f = i.activeSortable;
      if (ft.forEach(function(h) {
        h.thisAnimationDuration = null;
      }), f.options.animation && !l && f.multiDrag.isMultiDrag) {
        to = On({}, o);
        var a = Zr(Wt, !0);
        to.top -= a.f, to.left -= a.e;
      }
    },
    dragOverAnimationComplete: function() {
      qe && (qe = !1, Is());
    },
    drop: function(i) {
      var o = i.originalEvent, l = i.rootEl, f = i.parentEl, a = i.sortable, h = i.dispatchSortableEvent, g = i.oldIndex, v = i.putSortable, b = v || this.sortable;
      if (o) {
        var _ = this.options, T = f.children;
        if (!wi)
          if (_.multiDragKey && !this.multiDragKeyDown && this._deselectMultiDrag(), oe(Wt, _.selectedClass, !~ft.indexOf(Wt)), ~ft.indexOf(Wt))
            ft.splice(ft.indexOf(Wt), 1), Qi = null, no({
              sortable: a,
              rootEl: l,
              name: "deselect",
              targetEl: Wt,
              originalEvent: o
            });
          else {
            if (ft.push(Wt), no({
              sortable: a,
              rootEl: l,
              name: "select",
              targetEl: Wt,
              originalEvent: o
            }), o.shiftKey && Qi && a.el.contains(Qi)) {
              var C = se(Qi), O = se(Wt);
              if (~C && ~O && C !== O) {
                var F, N;
                for (O > C ? (N = C, F = O) : (N = O, F = C + 1); N < F; N++)
                  ~ft.indexOf(T[N]) || (oe(T[N], _.selectedClass, !0), ft.push(T[N]), no({
                    sortable: a,
                    rootEl: l,
                    name: "select",
                    targetEl: T[N],
                    originalEvent: o
                  }));
              }
            } else
              Qi = Wt;
            An = b;
          }
        if (wi && this.isMultiDrag) {
          if (qe = !1, (f[De].options.sort || f !== l) && ft.length > 1) {
            var K = $t(Wt), J = se(Wt, ":not(." + this.options.selectedClass + ")");
            if (!ji && _.animation && (Wt.thisAnimationDuration = null), b.captureAnimationState(), !ji && (_.animation && (Wt.fromRect = K, ft.forEach(function(U) {
              if (U.thisAnimationDuration = null, U !== Wt) {
                var nt = qe ? $t(U) : K;
                U.fromRect = nt, b.addAnimationState({
                  target: U,
                  rect: nt
                });
              }
            })), Is(), ft.forEach(function(U) {
              T[J] ? f.insertBefore(U, T[J]) : f.appendChild(U), J++;
            }), g === se(Wt))) {
              var V = !1;
              ft.forEach(function(U) {
                if (U.sortableIndex !== se(U)) {
                  V = !0;
                  return;
                }
              }), V && (h("update"), h("sort"));
            }
            ft.forEach(function(U) {
              Wu(U);
            }), b.animateAll();
          }
          An = b;
        }
        (l === f || v && v.lastPutMode !== "clone") && nn.forEach(function(U) {
          U.parentNode && U.parentNode.removeChild(U);
        });
      }
    },
    nullingGlobal: function() {
      this.isMultiDrag = wi = !1, nn.length = 0;
    },
    destroyGlobal: function() {
      this._deselectMultiDrag(), yt(document, "pointerup", this._deselectMultiDrag), yt(document, "mouseup", this._deselectMultiDrag), yt(document, "touchend", this._deselectMultiDrag), yt(document, "keydown", this._checkKeyDown), yt(document, "keyup", this._checkKeyUp);
    },
    _deselectMultiDrag: function(i) {
      if (!(typeof wi < "u" && wi) && An === this.sortable && !(i && _n(i.target, this.options.draggable, this.sortable.el, !1)) && !(i && i.button !== 0))
        for (; ft.length; ) {
          var o = ft[0];
          oe(o, this.options.selectedClass, !1), ft.shift(), no({
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
  }, On(e, {
    // Static methods & properties
    pluginName: "multiDrag",
    utils: {
      /**
       * Selects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be selected
       */
      select: function(i) {
        var o = i.parentNode[De];
        !o || !o.options.multiDrag || ~ft.indexOf(i) || (An && An !== o && (An.multiDrag._deselectMultiDrag(), An = o), oe(i, o.options.selectedClass, !0), ft.push(i));
      },
      /**
       * Deselects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be deselected
       */
      deselect: function(i) {
        var o = i.parentNode[De], l = ft.indexOf(i);
        !o || !o.options.multiDrag || !~l || (oe(i, o.options.selectedClass, !1), ft.splice(l, 1));
      }
    },
    eventProperties: function() {
      var i = this, o = [], l = [];
      return ft.forEach(function(f) {
        o.push({
          multiDragElement: f,
          index: f.sortableIndex
        });
        var a;
        qe && f !== Wt ? a = -1 : qe ? a = se(f, ":not(." + i.options.selectedClass + ")") : a = se(f), l.push({
          multiDragElement: f,
          index: a
        });
      }), {
        items: uE(ft),
        clones: [].concat(nn),
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
function LE(e, r) {
  ft.forEach(function(i, o) {
    var l = r.children[i.sortableIndex + (e ? Number(o) : 0)];
    l ? r.insertBefore(i, l) : r.appendChild(i);
  });
}
function id(e, r) {
  nn.forEach(function(i, o) {
    var l = r.children[i.sortableIndex + (e ? Number(o) : 0)];
    l ? r.insertBefore(i, l) : r.appendChild(i);
  });
}
function Is() {
  ft.forEach(function(e) {
    e !== Wt && e.parentNode && e.parentNode.removeChild(e);
  });
}
st.mount(new FE());
st.mount(Wf, Bf);
st.mount(new NE());
function Os(e) {
  return typeof e == "number" && !isNaN(e) ? e : typeof e == "string" && !isNaN(Number(e.trim())) ? parseFloat(e) : String(e);
}
let Ds;
function BE() {
  return {
    draggingEntityName: Ds,
    setDraggingEntityName: (o) => {
      Ds = o;
    },
    getDraggingEntityName: () => Ds,
    clearDraggingEntityName: () => {
      Ds = void 0;
    }
  };
}
const WE = ["data-id"], UE = ["data-id"], HE = /* @__PURE__ */ Of({
  __name: "SortableList",
  props: {
    items: {},
    sortableListId: {},
    sortableGroupName: {}
  },
  emits: ["sort"],
  setup(e, { emit: r }) {
    const i = r, o = e, l = mn(null), { setDraggingEntityName: f, clearDraggingEntityName: a } = BE();
    let h;
    In(() => o.items, async () => {
      await yo(), h && h.destroy(), g();
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
        setData(v, b) {
          if (l.value) {
            o.sortableGroupName && f(o.sortableGroupName);
            const _ = [...l.value.querySelectorAll(`.${h.option("selectedClass")}`)];
            v.setData("text/plain", JSON.stringify({
              taskIds: (_.length ? _ : [b]).map((T) => Os(T.dataset.id))
            }));
          }
        },
        onEnd: (v) => {
          if (a(), v.oldIndex === v.newIndex && v.from.dataset.listId === v.to.dataset.listId)
            return;
          const b = Os(v.to.dataset.id), _ = v.items.length ? v.items : [v.item], T = _[0].previousElementSibling ? Os(_[0].previousElementSibling.dataset.id) ?? null : null, C = _.map((O) => Os(O.dataset.id)).filter((O) => O);
          b && i("sort", b, T, C);
        }
      }));
    }
    return (v, b) => (Hn(), Jr("div", {
      ref_key: "sortableRef",
      ref: l,
      class: "tw-flex tw-flex-col",
      "data-id": o.sortableListId
    }, [
      (Hn(!0), Jr(sn, null, E1(o.items, (_) => (Hn(), Jr("div", {
        key: _.uuid || _.id,
        "data-id": _.id
      }, [
        S1(v.$slots, "default", { item: _ })
      ], 8, UE))), 128))
    ], 8, WE));
  }
}), od = (e) => {
  const r = Object.entries(e).filter(([i, o]) => o).reduce((i, o) => {
    const l = qw(o[1]);
    if (Array.isArray(l))
      for (const f of l)
        i.push([o[0], f.toString()]);
    else
      i.push([o[0], String(o[1])]);
    return i;
  }, []);
  return r.length ? `?${new URLSearchParams(r).toString()}` : "";
}, $E = { class: "tw-p-8" }, KE = /* @__PURE__ */ Of({
  __name: "App",
  setup(e) {
    const r = kr("useFetch"), i = kr("options"), o = mn([]), l = mn(), f = Zx(l, [o]);
    Xd("listNavigation", f), a();
    async function a() {
      const { data: _ } = await r(`pocketlists.items.get${od({ external_app_id: i.externalAppId, external_entity_type: i.externalEntityType, external_entity_id: i.externalEntityId })}`).get().json();
      o.value = _.value.data;
    }
    const h = async () => {
      const _ = crypto.randomUUID();
      o.value = [{
        id: _
      }, ...o.value], await yo(), f.focusTaskById(_);
    }, g = async (_, T) => {
      let C = null;
      if (typeof _.id == "string") {
        const { data: O } = await r("pocketlists.items.add", {
          method: "PUT",
          body: JSON.stringify([
            {
              ...T,
              uuid: _.id,
              external_links: [
                {
                  app_id: i.externalAppId,
                  entity_type: i.externalEntityType,
                  entity_id: i.externalEntityId
                }
              ]
            }
          ])
        }).json();
        C = O;
      } else {
        const { data: O } = await r("pocketlists.items.update", {
          method: "PATCH",
          body: JSON.stringify([{
            id: _.id,
            ...T
          }])
        }).json();
        C = O;
      }
      if (C.value.status_code === "ok" && Array.isArray(C.value.data)) {
        const O = C.value.data.filter(({ success: N }) => N).map((N) => N.data), F = O.reduce((N, K) => (N.push(K.id), N.push(K.uuid), N), []);
        o.value = [...o.value.filter((N) => !F.includes(N.id)), ...O];
      }
    }, v = async (_) => {
      o.value = [...o.value.filter((C) => C.id !== _.id)];
      const { data: T } = await r(`pocketlists.items.delete${od({ "id[]": _.id })}`).delete().json();
      T.value.status_code === "ok" && Array.isArray(T.value.data) && (T.value.data.filter(({ success: O }) => O).map((O) => O.data.id).includes(_.id) || console.log("error"));
    }, b = (_) => {
      typeof _.id == "string" && !_.name && (o.value = [...o.value.filter((T) => T.id !== _.id)]);
    };
    return (_, T) => (Hn(), Jr("div", $E, [
      Ln("div", {
        class: "tw-mb-4",
        onClick: h
      }, " + New To-Do "),
      Ln("div", {
        ref_key: "navigatableRef",
        ref: l
      }, [
        yn(HE, {
          "sortable-list-id": 666,
          "sortable-group-name": "tasks",
          items: o.value,
          onSort: () => {
          }
        }, {
          default: Wd(({ item: C }) => [
            yn(iE, {
              task: C,
              sortable: !0,
              editable: !0,
              "addable-props": {
                external_app_id: Ve(i).externalAppId,
                external_entity_type: Ve(i).externalEntityType,
                external_entity_id: Ve(i).externalEntityId
              },
              onInsert: () => {
              },
              onUpdate: g,
              onDelete: v,
              onBlur: b
            }, null, 8, ["task", "addable-props"])
          ]),
          _: 1
        }, 8, ["items"])
      ], 512)
    ]));
  }
}), GE = {
  apiBaseUrl: "",
  apiToken: ""
}, YE = (e = {}) => {
  const r = {
    ...GE,
    ...e
  }, i = Lx(KE);
  i.provide("options", r), i.provide("useFetch", qx({
    baseUrl: r.apiBaseUrl,
    options: {
      beforeFetch({ options: o }) {
        return o.headers = {
          ...o.headers,
          Authorization: `Bearer ${r.apiToken}`,
          "X-PL-API-Client": crypto.randomUUID()
        }, {
          options: o
        };
      }
    }
  })), i.mount("#app");
};
export {
  YE as init
};
