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
}, fw = Object.prototype.hasOwnProperty, Pt = (e, r) => fw.call(e, r), pt = Array.isArray, Ai = (e) => Qs(e) === "[object Map]", sd = (e) => Qs(e) === "[object Set]", dt = (e) => typeof e == "function", ue = (e) => typeof e == "string", Or = (e) => typeof e == "symbol", qt = (e) => e !== null && typeof e == "object", ld = (e) => (qt(e) || dt(e)) && dt(e.then) && dt(e.catch), ud = Object.prototype.toString, Qs = (e) => ud.call(e), aw = (e) => Qs(e).slice(8, -1), fd = (e) => Qs(e) === "[object Object]", gf = (e) => ue(e) && e !== "NaN" && e[0] !== "-" && "" + parseInt(e, 10) === e, lo = /* @__PURE__ */ hf(
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
let md = 0, uo;
function bd(e) {
  e.flags |= 8, e.next = uo, uo = e;
}
function _f() {
  md++;
}
function mf() {
  if (--md > 0)
    return;
  let e;
  for (; uo; ) {
    let r = uo;
    for (uo = void 0; r; ) {
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
  if (e.flags & 4 && !(e.flags & 16) || (e.flags &= -17, e.globalVersion === bo))
    return;
  e.globalVersion = bo;
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
let bo = 0;
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
    this.version++, bo++, this.notify(r);
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
), yo = Symbol(
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
    bo++;
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
      const m = Number(o);
      a.forEach((b, T) => {
        (T === "length" || T === yo || !Or(T) && T >= m) && h(b);
      });
    } else
      switch (i !== void 0 && h(a.get(i)), v && h(a.get(yo)), r) {
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
  return r === e ? r : (Re(r, "iterate", yo), bn(e) ? r : r.map(Oe));
}
function nl(e) {
  return Re(e = Dt(e), "iterate", yo), e;
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
    return Ji(this, "pop");
  },
  push(...e) {
    return Ji(this, "push", e);
  },
  reduce(e, ...r) {
    return vh(this, "reduce", e, r);
  },
  reduceRight(e, ...r) {
    return vh(this, "reduceRight", e, r);
  },
  shift() {
    return Ji(this, "shift");
  },
  // slice could use ARRAY_ITERATE but also seems to beg for range tracking
  some(e, r) {
    return er(this, "some", e, r, void 0, arguments);
  },
  splice(...e) {
    return Ji(this, "splice", e);
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
    return Ji(this, "unshift", e);
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
    const b = g.apply(e, f);
    return h ? Oe(b) : b;
  }
  let v = i;
  a !== e && (h ? v = function(b, T) {
    return i.call(this, Oe(b), T, e);
  } : i.length > 2 && (v = function(b, T) {
    return i.call(this, b, T, e);
  }));
  const m = g.call(a, v, o);
  return h && l ? l(m) : m;
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
  Re(o, "iterate", yo);
  const l = o[r](...i);
  return (l === -1 || l === !1) && Sf(i[0]) ? (i[0] = Dt(i[0]), o[r](...i)) : l;
}
function Ji(e, r, i = []) {
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
function _s(e, r, i = !1, o = !1) {
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
function ms(e, r = !1) {
  const i = this.__v_raw, o = Dt(i), l = Dt(e);
  return r || (Cr(e, l) && Re(o, "has", e), Re(o, "has", l)), e === l ? i.has(e) : i.has(e) || i.has(l);
}
function bs(e, r = !1) {
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
function ys(e, r) {
  return function(o, l) {
    const f = this, a = f.__v_raw, h = Dt(a), g = r ? yf : e ? Tf : Oe;
    return !e && Re(h, "iterate", Vr), a.forEach((v, m) => o.call(l, g(v), g(m), f));
  };
}
function ws(e, r, i) {
  return function(...o) {
    const l = this.__v_raw, f = Dt(l), a = Ai(f), h = e === "entries" || e === Symbol.iterator && a, g = e === "keys" && a, v = l[e](...o), m = i ? yf : r ? Tf : Oe;
    return !r && Re(
      f,
      "iterate",
      g ? ku : Vr
    ), {
      // iterator protocol
      next() {
        const { value: b, done: T } = v.next();
        return T ? { value: b, done: T } : {
          value: h ? [m(b[0]), m(b[1])] : m(b),
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
      return _s(this, f);
    },
    get size() {
      return bs(this);
    },
    has: ms,
    add: _h,
    set: mh,
    delete: bh,
    clear: yh,
    forEach: ys(!1, !1)
  }, r = {
    get(f) {
      return _s(this, f, !1, !0);
    },
    get size() {
      return bs(this);
    },
    has: ms,
    add(f) {
      return _h.call(this, f, !0);
    },
    set(f, a) {
      return mh.call(this, f, a, !0);
    },
    delete: bh,
    clear: yh,
    forEach: ys(!1, !0)
  }, i = {
    get(f) {
      return _s(this, f, !0);
    },
    get size() {
      return bs(this, !0);
    },
    has(f) {
      return ms.call(this, f, !0);
    },
    add: yr("add"),
    set: yr("set"),
    delete: yr("delete"),
    clear: yr("clear"),
    forEach: ys(!0, !1)
  }, o = {
    get(f) {
      return _s(this, f, !0, !0);
    },
    get size() {
      return bs(this, !0);
    },
    has(f) {
      return ms.call(this, f, !0);
    },
    add: yr("add"),
    set: yr("set"),
    delete: yr("delete"),
    clear: yr("clear"),
    forEach: ys(!0, !0)
  };
  return [
    "keys",
    "values",
    "entries",
    Symbol.iterator
  ].forEach((f) => {
    e[f] = ws(f, !1, !1), i[f] = ws(f, !0, !1), r[f] = ws(f, !1, !0), o[f] = ws(
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
    this.fn = r, this.setter = i, this._value = void 0, this.dep = new el(this), this.__v_isRef = !0, this.deps = void 0, this.depsTail = void 0, this.flags = 16, this.globalVersion = bo - 1, this.effect = this, this.__v_isReadonly = !i, this.isSSR = o;
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
const xs = {}, Hs = /* @__PURE__ */ new WeakMap();
let Xr;
function r1(e, r = !1, i = Xr) {
  if (i) {
    let o = Hs.get(i);
    o || Hs.set(i, o = []), o.push(e);
  }
}
function i1(e, r, i = Ut) {
  const { immediate: o, deep: l, once: f, scheduler: a, augmentJob: h, call: g } = i, v = (B) => l ? B : bn(B) || l === !1 || l === 0 ? Sr(B, 1) : Sr(B);
  let m, b, T, A, I = !1, R = !1;
  if (le(e) ? (b = () => e.value, I = bn(e)) : Ci(e) ? (b = () => v(e), I = !0) : pt(e) ? (R = !0, I = e.some((B) => Ci(B) || bn(B)), b = () => e.map((B) => {
    if (le(B))
      return B.value;
    if (Ci(B))
      return v(B);
    if (dt(B))
      return g ? g(B, 2) : B();
  })) : dt(e) ? r ? b = g ? () => g(e, 2) : e : b = () => {
    if (T) {
      Rr();
      try {
        T();
      } finally {
        Pr();
      }
    }
    const B = Xr;
    Xr = m;
    try {
      return g ? g(e, 3, [A]) : e(A);
    } finally {
      Xr = B;
    }
  } : b = Un, r && l) {
    const B = b, nt = l === !0 ? 1 / 0 : l;
    b = () => Sr(B(), nt);
  }
  const N = vd(), W = () => {
    m.stop(), N && pf(N.effects, m);
  };
  if (f && r) {
    const B = r;
    r = (...nt) => {
      B(...nt), W();
    };
  }
  let k = R ? new Array(e.length).fill(xs) : xs;
  const q = (B) => {
    if (!(!(m.flags & 1) || !m.dirty && !B))
      if (r) {
        const nt = m.run();
        if (l || I || (R ? nt.some((Tt, It) => Cr(Tt, k[It])) : Cr(nt, k))) {
          T && T();
          const Tt = Xr;
          Xr = m;
          try {
            const It = [
              nt,
              // pass undefined as the old value when it's changed for the first time
              k === xs ? void 0 : R && k[0] === xs ? [] : k,
              A
            ];
            g ? g(r, 3, It) : (
              // @ts-expect-error
              r(...It)
            ), k = nt;
          } finally {
            Xr = Tt;
          }
        }
      } else
        m.run();
  };
  return h && h(q), m = new _d(b), m.scheduler = a ? () => a(q, !1) : q, A = (B) => r1(B, !1, m), T = m.onStop = () => {
    const B = Hs.get(m);
    if (B) {
      if (g)
        g(B, 4);
      else
        for (const nt of B)
          nt();
      Hs.delete(m);
    }
  }, r ? o ? q(!0) : k = m.run() : a ? a(q.bind(null, !0), !0) : m.run(), W.pause = m.pause.bind(m), W.resume = m.resume.bind(m), W.stop = W, W;
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
function So(e, r, i, o) {
  try {
    return o ? e(...o) : e();
  } catch (l) {
    il(l, r, i);
  }
}
function Kn(e, r, i, o) {
  if (dt(e)) {
    const l = So(e, r, i, o);
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
      const m = h.ec;
      if (m) {
        for (let b = 0; b < m.length; b++)
          if (m[b](e, g, v) === !1)
            return;
      }
      h = h.parent;
    }
    if (f) {
      Rr(), So(f, null, 10, [
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
let wo = !1, Ju = !1;
const Ue = [];
let Nn = 0;
const Ii = [];
let wr = null, xi = 0;
const Md = /* @__PURE__ */ Promise.resolve();
let Af = null;
function Pi(e) {
  const r = Af || Md;
  return e ? r.then(this ? e.bind(this) : e) : r;
}
function s1(e) {
  let r = wo ? Nn + 1 : 0, i = Ue.length;
  for (; r < i; ) {
    const o = r + i >>> 1, l = Ue[o], f = xo(l);
    f < e || f === e && l.flags & 2 ? r = o + 1 : i = o;
  }
  return r;
}
function Cf(e) {
  if (!(e.flags & 1)) {
    const r = xo(e), i = Ue[Ue.length - 1];
    !i || // fast path when the job id is larger than the tail
    !(e.flags & 2) && r >= xo(i) ? Ue.push(e) : Ue.splice(s1(r), 0, e), e.flags |= 1, Fd();
  }
}
function Fd() {
  !wo && !Ju && (Ju = !0, Af = Md.then(Ld));
}
function l1(e) {
  pt(e) ? Ii.push(...e) : wr && e.id === -1 ? wr.splice(xi + 1, 0, e) : e.flags & 1 || (Ii.push(e), e.flags |= 1), Fd();
}
function wh(e, r, i = wo ? Nn + 1 : 0) {
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
      (i, o) => xo(i) - xo(o)
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
const xo = (e) => e.id == null ? e.flags & 2 ? -1 : 1 / 0 : e.id;
function Ld(e) {
  Ju = !1, wo = !0;
  try {
    for (Nn = 0; Nn < Ue.length; Nn++) {
      const r = Ue[Nn];
      r && !(r.flags & 8) && (r.flags & 4 && (r.flags &= -2), So(
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
    Nn = 0, Ue.length = 0, Nd(), wo = !1, Af = null, (Ue.length || Ii.length) && Ld();
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
      (I, R) => Zu(
        I,
        r && (pt(r) ? r[R] : r),
        i,
        o,
        l
      )
    );
    return;
  }
  if (Oi(o) && !l)
    return;
  const f = o.shapeFlag & 4 ? Mf(o.component) : o.el, a = l ? null : f, { i: h, r: g } = e, v = r && r.r, m = h.refs === Ut ? h.refs = {} : h.refs, b = h.setupState, T = Dt(b), A = b === Ut ? () => !1 : (I) => Pt(T, I);
  if (v != null && v !== g && (ue(v) ? (m[v] = null, A(v) && (b[v] = null)) : le(v) && (v.value = null)), dt(g))
    So(g, h, 12, [a, m]);
  else {
    const I = ue(g), R = le(g);
    if (I || R) {
      const N = () => {
        if (e.f) {
          const W = I ? A(g) ? b[g] : m[g] : g.value;
          l ? pt(W) && pf(W, f) : pt(W) ? W.includes(f) || W.push(f) : I ? (m[g] = [f], A(g) && (b[g] = m[g])) : (g.value = [f], e.k && (m[e.k] = g.value));
        } else
          I ? (m[g] = a, A(g) && (b[g] = a)) : R && (g.value = a, e.k && (m[e.k] = a));
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
      const h = To(i), g = Kn(r, i, e, a);
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
    for (let v = 0, m = e.length; v < m; v++)
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
        const m = h[g];
        l[g] = r(e[m], m, g, f && f[g]);
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
const Qu = (e) => e ? hp(e) ? Mf(e) : Qu(e.parent) : null, fo = (
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
    $nextTick: (e) => e.n || (e.n = Pi.bind(e.proxy)),
    $watch: (e) => z1.bind(e)
  })
), Ou = (e, r) => e !== Ut && !e.__isScriptSetup && Pt(e, r), T1 = {
  get({ _: e }, r) {
    if (r === "__v_skip")
      return !0;
    const { ctx: i, setupState: o, data: l, props: f, accessCache: a, type: h, appContext: g } = e;
    let v;
    if (r[0] !== "$") {
      const A = a[r];
      if (A !== void 0)
        switch (A) {
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
    const m = fo[r];
    let b, T;
    if (m)
      return r === "$attrs" && Re(e.attrs, "get", ""), m(e);
    if (
      // css module (injected by vue-loader)
      (b = h.__cssModules) && (b = b[r])
    )
      return b;
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
    return !!i[a] || e !== Ut && Pt(e, a) || Ou(r, a) || (h = f[0]) && Pt(h, a) || Pt(o, a) || Pt(fo, a) || Pt(l.config.globalProperties, a);
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
    created: m,
    beforeMount: b,
    mounted: T,
    beforeUpdate: A,
    updated: I,
    activated: R,
    deactivated: N,
    beforeDestroy: W,
    beforeUnmount: k,
    destroyed: q,
    unmounted: B,
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
  m && Eh(m, e, "c");
  function gt(lt, rt) {
    pt(rt) ? rt.forEach((ve) => lt(ve.bind(i))) : rt && lt(rt.bind(i));
  }
  if (gt(d1, b), gt(p1, T), gt(g1, A), gt(v1, I), gt(a1, R), gt(c1, N), gt(w1, Kt), gt(y1, Tt), gt(b1, It), gt(_1, k), gt(Kd, B), gt(m1, Lt), pt(te))
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
  methods: no,
  computed: no,
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
  components: no,
  directives: no,
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
  return no(tf(e), tf(r));
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
function no(e, r) {
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
      set config(m) {
      },
      use(m, ...b) {
        return a.has(m) || (m && dt(m.install) ? (a.add(m), m.install(v, ...b)) : dt(m) && (a.add(m), m(v, ...b))), v;
      },
      mixin(m) {
        return f.mixins.includes(m) || f.mixins.push(m), v;
      },
      component(m, b) {
        return b ? (f.components[m] = b, v) : f.components[m];
      },
      directive(m, b) {
        return b ? (f.directives[m] = b, v) : f.directives[m];
      },
      mount(m, b, T) {
        if (!g) {
          const A = v._ceVNode || yn(o, l);
          return A.appContext = f, T === !0 ? T = "svg" : T === !1 && (T = void 0), b && r ? r(A, m) : e(A, m, T), g = !0, v._container = m, m.__vue_app__ = v, Mf(A.component);
        }
      },
      onUnmount(m) {
        h.push(m);
      },
      unmount() {
        g && (Kn(
          h,
          v._instance,
          16
        ), e(null, v._container), delete v._container.__vue_app__);
      },
      provide(m, b) {
        return f.provides[m] = b, v;
      },
      runWithContext(m) {
        const b = Di;
        Di = v;
        try {
          return m();
        } finally {
          Di = b;
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
      const m = e.vnode.dynamicProps;
      for (let b = 0; b < m.length; b++) {
        let T = m[b];
        if (sl(e.emitsOptions, T))
          continue;
        const A = r[T];
        if (g)
          if (Pt(f, T))
            A !== f[T] && (f[T] = A, v = !0);
          else {
            const I = Qr(T);
            l[I] = ef(
              g,
              h,
              I,
              A,
              e,
              !1
            );
          }
        else
          A !== f[T] && (f[T] = A, v = !0);
      }
    }
  } else {
    Jd(e, r, l, f) && (v = !0);
    let m;
    for (const b in h)
      (!r || // for camelCase
      !Pt(r, b) && // it's possible the original props was passed in as kebab-case
      // and converted to camelCase (#955)
      ((m = Dr(b)) === b || !Pt(r, m))) && (g ? i && // for camelCase
      (i[b] !== void 0 || // for kebab-case
      i[m] !== void 0) && (l[b] = ef(
        g,
        h,
        b,
        void 0,
        e,
        !0
      )) : delete l[b]);
    if (f !== h)
      for (const b in f)
        (!r || !Pt(r, b)) && (delete f[b], v = !0);
  }
  v && rr(e.attrs, "set", "");
}
function Jd(e, r, i, o) {
  const [l, f] = e.propsOptions;
  let a = !1, h;
  if (r)
    for (let g in r) {
      if (lo(g))
        continue;
      const v = r[g];
      let m;
      l && Pt(l, m = Qr(g)) ? !f || !f.includes(m) ? i[m] = v : (h || (h = {}))[m] = v : sl(e.emitsOptions, g) || (!(g in o) || v !== o[g]) && (o[g] = v, a = !0);
    }
  if (f) {
    const g = Dt(i), v = h || Ut;
    for (let m = 0; m < f.length; m++) {
      const b = f[m];
      i[b] = ef(
        l,
        g,
        b,
        v[b],
        e,
        !Pt(v, b)
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
          const m = To(l);
          o = v[i] = g.call(
            null,
            r
          ), m();
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
    const m = (b) => {
      g = !0;
      const [T, A] = Zd(b, r, !0);
      Se(a, T), A && h.push(...A);
    };
    !i && r.mixins.length && r.mixins.forEach(m), e.extends && m(e.extends), e.mixins && e.mixins.forEach(m);
  }
  if (!f && !g)
    return qt(e) && o.set(e, Ti), Ti;
  if (pt(f))
    for (let m = 0; m < f.length; m++) {
      const b = Qr(f[m]);
      Ah(b) && (a[b] = Ut);
    }
  else if (f)
    for (const m in f) {
      const b = Qr(m);
      if (Ah(b)) {
        const T = f[m], A = a[b] = pt(T) || dt(T) ? { type: T } : Se({}, T), I = A.type;
        let R = !1, N = !0;
        if (pt(I))
          for (let W = 0; W < I.length; ++W) {
            const k = I[W], q = dt(k) && k.name;
            if (q === "Boolean") {
              R = !0;
              break;
            } else
              q === "String" && (N = !1);
          }
        else
          R = dt(I) && I.name === "Boolean";
        A[
          0
          /* shouldCast */
        ] = R, A[
          1
          /* shouldCastTrue */
        ] = N, (R || Pt(A, "default")) && h.push(b);
      }
    }
  const v = [a, h];
  return qt(e) && o.set(e, v), v;
}
function Ah(e) {
  return e[0] !== "$" && !lo(e);
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
    setElementText: m,
    parentNode: b,
    nextSibling: T,
    setScopeId: A = Un,
    insertStaticContent: I
  } = e, R = (y, x, O, $ = null, F = null, H = null, z = void 0, G = null, K = !!x.dynamicChildren) => {
    if (y === x)
      return;
    y && !Zi(y, x) && ($ = ti(y), ee(y, F, H, !0), y = null), x.patchFlag === -2 && (K = !1, x.dynamicChildren = null);
    const { type: U, ref: tt, shapeFlag: V } = x;
    switch (U) {
      case ll:
        N(y, x, O, $);
        break;
      case Ir:
        W(y, x, O, $);
        break;
      case Pu:
        y == null && k(x, O, $, z);
        break;
      case sn:
        ge(
          y,
          x,
          O,
          $,
          F,
          H,
          z,
          G,
          K
        );
        break;
      default:
        V & 1 ? nt(
          y,
          x,
          O,
          $,
          F,
          H,
          z,
          G,
          K
        ) : V & 6 ? Gt(
          y,
          x,
          O,
          $,
          F,
          H,
          z,
          G,
          K
        ) : (V & 64 || V & 128) && U.process(
          y,
          x,
          O,
          $,
          F,
          H,
          z,
          G,
          K,
          ur
        );
    }
    tt != null && F && Zu(tt, y && y.ref, H, x || y, !x);
  }, N = (y, x, O, $) => {
    if (y == null)
      o(
        x.el = h(x.children),
        O,
        $
      );
    else {
      const F = x.el = y.el;
      x.children !== y.children && v(F, x.children);
    }
  }, W = (y, x, O, $) => {
    y == null ? o(
      x.el = g(x.children || ""),
      O,
      $
    ) : x.el = y.el;
  }, k = (y, x, O, $) => {
    [y.el, y.anchor] = I(
      y.children,
      x,
      O,
      $,
      y.el,
      y.anchor
    );
  }, q = ({ el: y, anchor: x }, O, $) => {
    let F;
    for (; y && y !== x; )
      F = T(y), o(y, O, $), y = F;
    o(x, O, $);
  }, B = ({ el: y, anchor: x }) => {
    let O;
    for (; y && y !== x; )
      O = T(y), l(y), y = O;
    l(x);
  }, nt = (y, x, O, $, F, H, z, G, K) => {
    x.type === "svg" ? z = "svg" : x.type === "math" && (z = "mathml"), y == null ? Tt(
      x,
      O,
      $,
      F,
      H,
      z,
      G,
      K
    ) : Lt(
      y,
      x,
      F,
      H,
      z,
      G,
      K
    );
  }, Tt = (y, x, O, $, F, H, z, G) => {
    let K, U;
    const { props: tt, shapeFlag: V, transition: Z, dirs: ut } = y;
    if (K = y.el = a(
      y.type,
      H,
      tt && tt.is,
      tt
    ), V & 8 ? m(K, y.children) : V & 16 && Kt(
      y.children,
      K,
      null,
      $,
      F,
      Du(y, H),
      z,
      G
    ), ut && Kr(y, null, $, "created"), It(K, y, y.scopeId, z, $), tt) {
      for (const At in tt)
        At !== "value" && !lo(At) && f(K, At, null, tt[At], H, $);
      "value" in tt && f(K, "value", null, tt.value, H), (U = tt.onVnodeBeforeMount) && Fn(U, $, y);
    }
    ut && Kr(y, null, $, "beforeMount");
    const _t = $1(F, Z);
    _t && Z.beforeEnter(K), o(K, x, O), ((U = tt && tt.onVnodeMounted) || _t || ut) && rn(() => {
      U && Fn(U, $, y), _t && Z.enter(K), ut && Kr(y, null, $, "mounted");
    }, F);
  }, It = (y, x, O, $, F) => {
    if (O && A(y, O), $)
      for (let H = 0; H < $.length; H++)
        A(y, $[H]);
    if (F) {
      let H = F.subTree;
      if (x === H || lp(H.type) && (H.ssContent === x || H.ssFallback === x)) {
        const z = F.vnode;
        It(
          y,
          z,
          z.scopeId,
          z.slotScopeIds,
          F.parent
        );
      }
    }
  }, Kt = (y, x, O, $, F, H, z, G, K = 0) => {
    for (let U = K; U < y.length; U++) {
      const tt = y[U] = G ? xr(y[U]) : Bn(y[U]);
      R(
        null,
        tt,
        x,
        O,
        $,
        F,
        H,
        z,
        G
      );
    }
  }, Lt = (y, x, O, $, F, H, z) => {
    const G = x.el = y.el;
    let { patchFlag: K, dynamicChildren: U, dirs: tt } = x;
    K |= y.patchFlag & 16;
    const V = y.props || Ut, Z = x.props || Ut;
    let ut;
    if (O && Gr(O, !1), (ut = Z.onVnodeBeforeUpdate) && Fn(ut, O, x, y), tt && Kr(x, y, O, "beforeUpdate"), O && Gr(O, !0), (V.innerHTML && Z.innerHTML == null || V.textContent && Z.textContent == null) && m(G, ""), U ? te(
      y.dynamicChildren,
      U,
      G,
      O,
      $,
      Du(x, F),
      H
    ) : z || rt(
      y,
      x,
      G,
      null,
      O,
      $,
      Du(x, F),
      H,
      !1
    ), K > 0) {
      if (K & 16)
        fe(G, V, Z, O, F);
      else if (K & 2 && V.class !== Z.class && f(G, "class", null, Z.class, F), K & 4 && f(G, "style", V.style, Z.style, F), K & 8) {
        const _t = x.dynamicProps;
        for (let At = 0; At < _t.length; At++) {
          const Ct = _t[At], me = V[Ct], re = Z[Ct];
          (re !== me || Ct === "value") && f(G, Ct, me, re, F, O);
        }
      }
      K & 1 && y.children !== x.children && m(G, x.children);
    } else
      !z && U == null && fe(G, V, Z, O, F);
    ((ut = Z.onVnodeUpdated) || tt) && rn(() => {
      ut && Fn(ut, O, x, y), tt && Kr(x, y, O, "updated");
    }, $);
  }, te = (y, x, O, $, F, H, z) => {
    for (let G = 0; G < x.length; G++) {
      const K = y[G], U = x[G], tt = (
        // oldVNode may be an errored async setup() component inside Suspense
        // which will not have a mounted element
        K.el && // - In the case of a Fragment, we need to provide the actual parent
        // of the Fragment itself so it can move its children.
        (K.type === sn || // - In the case of different nodes, there is going to be a replacement
        // which also requires the correct parent container
        !Zi(K, U) || // - In the case of a component, it could contain anything.
        K.shapeFlag & 70) ? b(K.el) : (
          // In other cases, the parent container is not actually used so we
          // just pass the block element here to avoid a DOM parentNode call.
          O
        )
      );
      R(
        K,
        U,
        tt,
        null,
        $,
        F,
        H,
        z,
        !0
      );
    }
  }, fe = (y, x, O, $, F) => {
    if (x !== O) {
      if (x !== Ut)
        for (const H in x)
          !lo(H) && !(H in O) && f(
            y,
            H,
            x[H],
            null,
            F,
            $
          );
      for (const H in O) {
        if (lo(H))
          continue;
        const z = O[H], G = x[H];
        z !== G && H !== "value" && f(y, H, G, z, F, $);
      }
      "value" in O && f(y, "value", x.value, O.value, F);
    }
  }, ge = (y, x, O, $, F, H, z, G, K) => {
    const U = x.el = y ? y.el : h(""), tt = x.anchor = y ? y.anchor : h("");
    let { patchFlag: V, dynamicChildren: Z, slotScopeIds: ut } = x;
    ut && (G = G ? G.concat(ut) : ut), y == null ? (o(U, O, $), o(tt, O, $), Kt(
      // #10007
      // such fragment like `<></>` will be compiled into
      // a fragment which doesn't have a children.
      // In this case fallback to an empty array
      x.children || [],
      O,
      tt,
      F,
      H,
      z,
      G,
      K
    )) : V > 0 && V & 64 && Z && // #2715 the previous fragment could've been a BAILed one as a result
    // of renderSlot() with no valid children
    y.dynamicChildren ? (te(
      y.dynamicChildren,
      Z,
      O,
      F,
      H,
      z,
      G
    ), // #2080 if the stable fragment has a key, it's a <template v-for> that may
    //  get moved around. Make sure all root level vnodes inherit el.
    // #2134 or if it's a component root, it may also get moved around
    // as the component is being moved.
    (x.key != null || F && x === F.subTree) && np(
      y,
      x,
      !0
      /* shallow */
    )) : rt(
      y,
      x,
      O,
      tt,
      F,
      H,
      z,
      G,
      K
    );
  }, Gt = (y, x, O, $, F, H, z, G, K) => {
    x.slotScopeIds = G, y == null ? x.shapeFlag & 512 ? F.ctx.activate(
      x,
      O,
      $,
      z,
      K
    ) : he(
      x,
      O,
      $,
      F,
      H,
      z,
      K
    ) : Te(y, x, K);
  }, he = (y, x, O, $, F, H, z) => {
    const G = y.component = ox(
      y,
      $,
      F
    );
    if (Hd(y) && (G.ctx.renderer = ur), sx(G, !1, z), G.asyncDep) {
      if (F && F.registerDep(G, gt, z), !y.el) {
        const K = G.subTree = yn(Ir);
        W(null, K, x, O);
      }
    } else
      gt(
        G,
        y,
        x,
        O,
        F,
        H,
        z
      );
  }, Te = (y, x, O) => {
    const $ = x.component = y.component;
    if (J1(y, x, O))
      if ($.asyncDep && !$.asyncResolved) {
        lt($, x, O);
        return;
      } else
        $.next = x, $.update();
    else
      x.el = y.el, $.vnode = x;
  }, gt = (y, x, O, $, F, H, z) => {
    const G = () => {
      if (y.isMounted) {
        let { next: V, bu: Z, u: ut, parent: _t, vnode: At } = y;
        {
          const be = rp(y);
          if (be) {
            V && (V.el = At.el, lt(y, V, z)), be.asyncDep.then(() => {
              y.isUnmounted || G();
            });
            return;
          }
        }
        let Ct = V, me;
        Gr(y, !1), V ? (V.el = At.el, lt(y, V, z)) : V = At, Z && Eu(Z), (me = V.props && V.props.onVnodeBeforeUpdate) && Fn(me, _t, V, At), Gr(y, !0);
        const re = Ru(y), Pe = y.subTree;
        y.subTree = re, R(
          Pe,
          re,
          // parent may have changed if it's in a teleport
          b(Pe.el),
          // anchor may have changed if it's in a fragment
          ti(Pe),
          y,
          F,
          H
        ), V.el = re.el, Ct === null && Z1(y, re.el), ut && rn(ut, F), (me = V.props && V.props.onVnodeUpdated) && rn(
          () => Fn(me, _t, V, At),
          F
        );
      } else {
        let V;
        const { el: Z, props: ut } = x, { bm: _t, m: At, parent: Ct, root: me, type: re } = y, Pe = Oi(x);
        if (Gr(y, !1), _t && Eu(_t), !Pe && (V = ut && ut.onVnodeBeforeMount) && Fn(V, Ct, x), Gr(y, !0), Z && ar) {
          const be = () => {
            y.subTree = Ru(y), ar(
              Z,
              y.subTree,
              y,
              F,
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
          R(
            null,
            be,
            O,
            $,
            y,
            F,
            H
          ), x.el = be.el;
        }
        if (At && rn(At, F), !Pe && (V = ut && ut.onVnodeMounted)) {
          const be = x;
          rn(
            () => Fn(V, Ct, be),
            F
          );
        }
        (x.shapeFlag & 256 || Ct && Oi(Ct.vnode) && Ct.vnode.shapeFlag & 256) && y.a && rn(y.a, F), y.isMounted = !0, x = O = $ = null;
      }
    };
    y.scope.on();
    const K = y.effect = new _d(G);
    y.scope.off();
    const U = y.update = K.run.bind(K), tt = y.job = K.runIfDirty.bind(K);
    tt.i = y, tt.id = y.uid, K.scheduler = () => Cf(tt), Gr(y, !0), U();
  }, lt = (y, x, O) => {
    x.component = y;
    const $ = y.vnode.props;
    y.vnode = x, y.next = null, F1(y, x.props, $, O), W1(y, x.children, O), Rr(), wh(y), Pr();
  }, rt = (y, x, O, $, F, H, z, G, K = !1) => {
    const U = y && y.children, tt = y ? y.shapeFlag : 0, V = x.children, { patchFlag: Z, shapeFlag: ut } = x;
    if (Z > 0) {
      if (Z & 128) {
        de(
          U,
          V,
          O,
          $,
          F,
          H,
          z,
          G,
          K
        );
        return;
      } else if (Z & 256) {
        ve(
          U,
          V,
          O,
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
    ut & 8 ? (tt & 16 && Dn(U, F, H), V !== U && m(O, V)) : tt & 16 ? ut & 16 ? de(
      U,
      V,
      O,
      $,
      F,
      H,
      z,
      G,
      K
    ) : Dn(U, F, H, !0) : (tt & 8 && m(O, ""), ut & 16 && Kt(
      V,
      O,
      $,
      F,
      H,
      z,
      G,
      K
    ));
  }, ve = (y, x, O, $, F, H, z, G, K) => {
    y = y || Ti, x = x || Ti;
    const U = y.length, tt = x.length, V = Math.min(U, tt);
    let Z;
    for (Z = 0; Z < V; Z++) {
      const ut = x[Z] = K ? xr(x[Z]) : Bn(x[Z]);
      R(
        y[Z],
        ut,
        O,
        null,
        F,
        H,
        z,
        G,
        K
      );
    }
    U > tt ? Dn(
      y,
      F,
      H,
      !0,
      !1,
      V
    ) : Kt(
      x,
      O,
      $,
      F,
      H,
      z,
      G,
      K,
      V
    );
  }, de = (y, x, O, $, F, H, z, G, K) => {
    let U = 0;
    const tt = x.length;
    let V = y.length - 1, Z = tt - 1;
    for (; U <= V && U <= Z; ) {
      const ut = y[U], _t = x[U] = K ? xr(x[U]) : Bn(x[U]);
      if (Zi(ut, _t))
        R(
          ut,
          _t,
          O,
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
    for (; U <= V && U <= Z; ) {
      const ut = y[V], _t = x[Z] = K ? xr(x[Z]) : Bn(x[Z]);
      if (Zi(ut, _t))
        R(
          ut,
          _t,
          O,
          null,
          F,
          H,
          z,
          G,
          K
        );
      else
        break;
      V--, Z--;
    }
    if (U > V) {
      if (U <= Z) {
        const ut = Z + 1, _t = ut < tt ? x[ut].el : $;
        for (; U <= Z; )
          R(
            null,
            x[U] = K ? xr(x[U]) : Bn(x[U]),
            O,
            _t,
            F,
            H,
            z,
            G,
            K
          ), U++;
      }
    } else if (U > Z)
      for (; U <= V; )
        ee(y[U], F, H, !0), U++;
    else {
      const ut = U, _t = U, At = /* @__PURE__ */ new Map();
      for (U = _t; U <= Z; U++) {
        const ye = x[U] = K ? xr(x[U]) : Bn(x[U]);
        ye.key != null && At.set(ye.key, U);
      }
      let Ct, me = 0;
      const re = Z - _t + 1;
      let Pe = !1, be = 0;
      const zn = new Array(re);
      for (U = 0; U < re; U++)
        zn[U] = 0;
      for (U = ut; U <= V; U++) {
        const ye = y[U];
        if (me >= re) {
          ee(ye, F, H, !0);
          continue;
        }
        let $e;
        if (ye.key != null)
          $e = At.get(ye.key);
        else
          for (Ct = _t; Ct <= Z; Ct++)
            if (zn[Ct - _t] === 0 && Zi(ye, x[Ct])) {
              $e = Ct;
              break;
            }
        $e === void 0 ? ee(ye, F, H, !0) : (zn[$e - _t] = U + 1, $e >= be ? be = $e : Pe = !0, R(
          ye,
          x[$e],
          O,
          null,
          F,
          H,
          z,
          G,
          K
        ), me++);
      }
      const ei = Pe ? K1(zn) : Ti;
      for (Ct = ei.length - 1, U = re - 1; U >= 0; U--) {
        const ye = _t + U, $e = x[ye], Io = ye + 1 < tt ? x[ye + 1].el : $;
        zn[U] === 0 ? R(
          null,
          $e,
          O,
          Io,
          F,
          H,
          z,
          G,
          K
        ) : Pe && (Ct < 0 || U !== ei[Ct] ? Yt($e, O, Io, 2) : Ct--);
      }
    }
  }, Yt = (y, x, O, $, F = null) => {
    const { el: H, type: z, transition: G, children: K, shapeFlag: U } = y;
    if (U & 6) {
      Yt(y.component.subTree, x, O, $);
      return;
    }
    if (U & 128) {
      y.suspense.move(x, O, $);
      return;
    }
    if (U & 64) {
      z.move(y, x, O, ur);
      return;
    }
    if (z === sn) {
      o(H, x, O);
      for (let V = 0; V < K.length; V++)
        Yt(K[V], x, O, $);
      o(y.anchor, x, O);
      return;
    }
    if (z === Pu) {
      q(y, x, O);
      return;
    }
    if ($ !== 2 && U & 1 && G)
      if ($ === 0)
        G.beforeEnter(H), o(H, x, O), rn(() => G.enter(H), F);
      else {
        const { leave: V, delayLeave: Z, afterLeave: ut } = G, _t = () => o(H, x, O), At = () => {
          V(H, () => {
            _t(), ut && ut();
          });
        };
        Z ? Z(H, _t, At) : At();
      }
    else
      o(H, x, O);
  }, ee = (y, x, O, $ = !1, F = !1) => {
    const {
      type: H,
      props: z,
      ref: G,
      children: K,
      dynamicChildren: U,
      shapeFlag: tt,
      patchFlag: V,
      dirs: Z,
      cacheIndex: ut
    } = y;
    if (V === -2 && (F = !1), G != null && Zu(G, null, O, y, !0), ut != null && (x.renderCache[ut] = void 0), tt & 256) {
      x.ctx.deactivate(y);
      return;
    }
    const _t = tt & 1 && Z, At = !Oi(y);
    let Ct;
    if (At && (Ct = z && z.onVnodeBeforeUnmount) && Fn(Ct, x, y), tt & 6)
      _e(y.component, O, $);
    else {
      if (tt & 128) {
        y.suspense.unmount(O, $);
        return;
      }
      _t && Kr(y, null, x, "beforeUnmount"), tt & 64 ? y.type.remove(
        y,
        x,
        O,
        ur,
        $
      ) : U && // #5154
      // when v-once is used inside a block, setBlockTracking(-1) marks the
      // parent block with hasOnce: true
      // so that it doesn't take the fast path during unmount - otherwise
      // components nested in v-once are never unmounted.
      !U.hasOnce && // #1153: fast path should not be taken for non-stable (v-for) fragments
      (H !== sn || V > 0 && V & 64) ? Dn(
        U,
        x,
        O,
        !1,
        !0
      ) : (H === sn && V & 384 || !F && tt & 16) && Dn(K, x, O), $ && un(y);
    }
    (At && (Ct = z && z.onVnodeUnmounted) || _t) && rn(() => {
      Ct && Fn(Ct, x, y), _t && Kr(y, null, x, "unmounted");
    }, O);
  }, un = (y) => {
    const { type: x, el: O, anchor: $, transition: F } = y;
    if (x === sn) {
      ne(O, $);
      return;
    }
    if (x === Pu) {
      B(y);
      return;
    }
    const H = () => {
      l(O), F && !F.persisted && F.afterLeave && F.afterLeave();
    };
    if (y.shapeFlag & 1 && F && !F.persisted) {
      const { leave: z, delayLeave: G } = F, K = () => z(O, H);
      G ? G(y.el, H, K) : K();
    } else
      H();
  }, ne = (y, x) => {
    let O;
    for (; y !== x; )
      O = T(y), l(y), y = O;
    l(x);
  }, _e = (y, x, O) => {
    const { bum: $, scope: F, job: H, subTree: z, um: G, m: K, a: U } = y;
    Ch(K), Ch(U), $ && Eu($), F.stop(), H && (H.flags |= 8, ee(z, y, x, O)), G && rn(G, x), rn(() => {
      y.isUnmounted = !0;
    }, x), x && x.pendingBranch && !x.isUnmounted && y.asyncDep && !y.asyncResolved && y.suspenseId === x.pendingId && (x.deps--, x.deps === 0 && x.resolve());
  }, Dn = (y, x, O, $ = !1, F = !1, H = 0) => {
    for (let z = H; z < y.length; z++)
      ee(y[z], x, O, $, F);
  }, ti = (y) => {
    if (y.shapeFlag & 6)
      return ti(y.component.subTree);
    if (y.shapeFlag & 128)
      return y.suspense.next();
    const x = T(y.anchor || y.el), O = x && x[u1];
    return O ? T(O) : x;
  };
  let Yn = !1;
  const lr = (y, x, O) => {
    y == null ? x._vnode && ee(x._vnode, null, null, !0) : R(
      x._vnode || null,
      y,
      x,
      null,
      null,
      null,
      O
    ), x._vnode = y, Yn || (Yn = !0, wh(), Nd(), Yn = !1);
  }, ur = {
    p: R,
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
  h.call = (T, A, I) => Kn(T, v, A, I);
  let m = !1;
  f === "post" ? h.scheduler = (T) => {
    rn(T, v && v.suspense);
  } : f !== "sync" && (m = !0, h.scheduler = (T, A) => {
    A ? T() : Cf(T);
  }), h.augmentJob = (T) => {
    r && (T.flags |= 4), m && (T.flags |= 2, v && (T.id = v.uid, T.i = v));
  };
  const b = i1(e, r, h);
  return g && g.push(b), b;
}
function z1(e, r, i) {
  const o = this.proxy, l = ue(e) ? e.includes(".") ? op(o, e) : () => o[e] : e.bind(o, o);
  let f;
  dt(r) ? f = r : (f = r.handler, i = r);
  const a = To(this), h = ip(l, f.bind(o), i);
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
  a && (a.trim && (l = i.map((m) => ue(m) ? m.trim() : m)), a.number && (l = i.map(dw)));
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
      const m = sp(v, r, !0);
      m && (h = !0, Se(a, m));
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
    renderCache: m,
    props: b,
    data: T,
    setupState: A,
    ctx: I,
    inheritAttrs: R
  } = e, N = $s(e);
  let W, k;
  try {
    if (i.shapeFlag & 4) {
      const B = l || o, nt = B;
      W = Bn(
        v.call(
          nt,
          B,
          m,
          b,
          A,
          T,
          I
        )
      ), k = h;
    } else {
      const B = r;
      W = Bn(
        B.length > 1 ? B(
          b,
          { attrs: h, slots: a, emit: g }
        ) : B(
          b,
          null
        )
      ), k = r.props ? h : V1(h);
    }
  } catch (B) {
    ao.length = 0, il(B, e, 1), W = yn(Ir);
  }
  let q = W;
  if (k && R !== !1) {
    const B = Object.keys(k), { shapeFlag: nt } = q;
    B.length && nt & 7 && (f && B.some(df) && (k = k1(
      k,
      f
    )), q = Mi(q, k, !1, !0));
  }
  return i.dirs && (q = Mi(q, null, !1, !0), q.dirs = q.dirs ? q.dirs.concat(i.dirs) : i.dirs), i.transition && If(q, i.transition), W = q, $s(N), W;
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
      const m = r.dynamicProps;
      for (let b = 0; b < m.length; b++) {
        const T = m[b];
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
const sn = Symbol.for("v-fgt"), ll = Symbol.for("v-txt"), Ir = Symbol.for("v-cmt"), Pu = Symbol.for("v-stc"), ao = [];
let ln = null;
function Hn(e = !1) {
  ao.push(ln = e ? null : []);
}
function j1() {
  ao.pop(), ln = ao[ao.length - 1] || null;
}
let Eo = 1;
function Oh(e) {
  Eo += e, e < 0 && ln && (ln.hasOnce = !0);
}
function up(e) {
  return e.dynamicChildren = Eo > 0 ? ln || Ti : null, j1(), Eo > 0 && ln && ln.push(e), e;
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
function Zi(e, r) {
  return e.type === r.type && e.key === r.key;
}
const ap = ({ key: e }) => e ?? null, Ps = ({
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
    ref: r && Ps(r),
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
  return h ? (Pf(g, i), f & 128 && e.normalize(g)) : i && (g.shapeFlag |= ue(i) ? 8 : 16), Eo > 0 && // avoid a block node from tracking itself
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
    const h = Mi(
      e,
      r,
      !0
      /* mergeRef: true */
    );
    return i && Pf(h, i), Eo > 0 && !f && ln && (h.shapeFlag & 6 ? ln[ln.indexOf(e)] = h : ln.push(h)), h.patchFlag = -2, h;
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
function Mi(e, r, i = !1, o = !1) {
  const { props: l, ref: f, patchFlag: a, children: h, transition: g } = e, v = r ? nx(l || {}, r) : l, m = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: e.type,
    props: v,
    key: v && ap(v),
    ref: r && r.ref ? (
      // #2078 in the case of <component :is="vnode" ref="extra"/>
      // if the vnode itself already has a ref, cloneVNode will need to merge
      // the refs so the single vnode can be set on multiple refs
      i && f ? pt(f) ? f.concat(Ps(r)) : [f, Ps(r)] : Ps(r)
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
    ssContent: e.ssContent && Mi(e.ssContent),
    ssFallback: e.ssFallback && Mi(e.ssFallback),
    el: e.el,
    anchor: e.anchor,
    ctx: e.ctx,
    ce: e.ce
  };
  return g && o && If(
    m,
    g.clone(m)
  ), m;
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
  return e.el === null && e.patchFlag !== -1 || e.memo ? e : Mi(e);
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
const To = (e) => {
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
    const l = e.setupContext = o.length > 1 ? fx(e) : null, f = To(e);
    Rr();
    const a = So(
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
    const l = To(e);
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
      if (i in fo)
        return fo[i](e);
    },
    has(r, i) {
      return i in r || i in fo;
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
          i[h] == null && Ms(o, h, "");
        }
      else
        for (const a in r)
          i[a] == null && Ms(o, a, "");
    for (const a in i)
      a === "display" && (f = !0), Ms(o, a, i[a]);
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
function Ms(e, r, i) {
  if (pt(i))
    i.forEach((o) => Ms(e, r, o));
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
  function i(b, { flush: T = "sync", deep: A = !1, timeout: I, throwOnTimeout: R } = {}) {
    let N = null;
    const k = [new Promise((q) => {
      N = In(
        e,
        (B) => {
          b(B) !== r && (N ? N() : Pi(() => N == null ? void 0 : N()), q(B));
        },
        {
          flush: T,
          deep: A,
          immediate: !0
        }
      );
    })];
    return I != null && k.push(
      zh(I, R).then(() => Wn(e)).finally(() => N == null ? void 0 : N())
    ), Promise.race(k);
  }
  function o(b, T) {
    if (!le(b))
      return i((B) => B === b, T);
    const { flush: A = "sync", deep: I = !1, timeout: R, throwOnTimeout: N } = T ?? {};
    let W = null;
    const q = [new Promise((B) => {
      W = In(
        [e, b],
        ([nt, Tt]) => {
          r !== (nt === Tt) && (W ? W() : Pi(() => W == null ? void 0 : W()), B(nt));
        },
        {
          flush: A,
          deep: I,
          immediate: !0
        }
      );
    })];
    return R != null && q.push(
      zh(R, N).then(() => Wn(e)).finally(() => (W == null || W(), Wn(e)))
    ), Promise.race(q);
  }
  function l(b) {
    return i((T) => !!T, b);
  }
  function f(b) {
    return o(null, b);
  }
  function a(b) {
    return o(void 0, b);
  }
  function h(b) {
    return i(Number.isNaN, b);
  }
  function g(b, T) {
    return i((A) => {
      const I = Array.from(A);
      return I.includes(b) || I.includes(Wn(b));
    }, T);
  }
  function v(b) {
    return m(1, b);
  }
  function m(b = 1, T) {
    let A = -1;
    return i(() => (A += 1, A >= b), T);
  }
  return Array.isArray(Wn(e)) ? {
    toMatch: i,
    toContains: g,
    changed: v,
    changedTimes: m,
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
    changedTimes: m,
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
function co(e) {
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
      const m = Wn(e.baseUrl), b = Wn(f);
      return m && !Xx(b) ? kx(m, b) : b;
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
        ...co(v.headers) || {},
        ...co(a[0].headers) || {}
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
  } = f, m = Nu(), b = Nu(), T = Nu(), A = mn(!1), I = mn(!1), R = mn(!1), N = mn(null), W = Iu(null), k = Iu(null), q = Iu(g || null), B = Ff(() => o && I.value);
  let nt, Tt;
  const It = () => {
    o && (nt == null || nt.abort(), nt = new AbortController(), nt.signal.onabort = () => R.value = !0, l = {
      ...l,
      signal: nt.signal
    });
  }, Kt = (gt) => {
    I.value = gt, A.value = !gt;
  };
  v && (Tt = Kx(It, v, { immediate: !1 }));
  let Lt = 0;
  const te = async (gt = !1) => {
    var lt, rt;
    It(), Kt(!0), k.value = null, N.value = null, R.value = !1, Lt += 1;
    const ve = Lt, de = {
      method: a.method,
      headers: {}
    };
    if (a.payload) {
      const ne = co(de.headers), _e = Wn(a.payload);
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
          ...co(de.headers),
          ...co((rt = ee.options) == null ? void 0 : rt.headers)
        }
      }
    ).then(async (ne) => {
      if (W.value = ne, N.value = ne.status, un = await ne.clone()[a.type](), !ne.ok)
        throw q.value = g || null, new Error(ne.statusText);
      return f.afterFetch && ({ data: un } = await f.afterFetch({
        data: un,
        response: ne
      })), q.value = un, m.trigger(ne), ne;
    }).catch(async (ne) => {
      let _e = ne.message || ne.name;
      if (f.onFetchError && ({ error: _e, data: un } = await f.onFetchError({
        data: un,
        error: ne,
        response: W.value
      })), k.value = _e, f.updateDataOnError && (q.value = un), b.trigger(ne), gt)
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
    isFinished: Ri(A),
    isFetching: Ri(I),
    statusCode: N,
    response: W,
    error: k,
    data: q,
    canAbort: B,
    aborted: R,
    abort: It,
    execute: te,
    onFetchResponse: m.on,
    onFetchError: b.on,
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
      if (!I.value)
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
      $x(A).toBe(!0).then(() => gt(ge)).catch((rt) => lt(rt));
    });
  }
  function Te(gt) {
    return () => {
      if (!I.value)
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
var Qi = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, zs = { exports: {} };
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
    var i, o = "4.17.21", l = 200, f = "Unsupported core-js use. Try https://npms.io/search?q=ponyfill.", a = "Expected a function", h = "Invalid `variable` option passed into `_.template`", g = "__lodash_hash_undefined__", v = 500, m = "__lodash_placeholder__", b = 1, T = 2, A = 4, I = 1, R = 2, N = 1, W = 2, k = 4, q = 8, B = 16, nt = 32, Tt = 64, It = 128, Kt = 256, Lt = 512, te = 30, fe = "...", ge = 800, Gt = 16, he = 1, Te = 2, gt = 3, lt = 1 / 0, rt = 9007199254740991, ve = 17976931348623157e292, de = NaN, Yt = 4294967295, ee = Yt - 1, un = Yt >>> 1, ne = [
      ["ary", It],
      ["bind", N],
      ["bindKey", W],
      ["curry", q],
      ["curryRight", B],
      ["flip", Lt],
      ["partial", nt],
      ["partialRight", Tt],
      ["rearg", Kt]
    ], _e = "[object Arguments]", Dn = "[object Array]", ti = "[object AsyncFunction]", Yn = "[object Boolean]", lr = "[object Date]", ur = "[object DOMException]", fr = "[object Error]", ar = "[object Function]", y = "[object GeneratorFunction]", x = "[object Map]", O = "[object Number]", $ = "[object Null]", F = "[object Object]", H = "[object Promise]", z = "[object Proxy]", G = "[object RegExp]", K = "[object Set]", U = "[object String]", tt = "[object Symbol]", V = "[object Undefined]", Z = "[object WeakMap]", ut = "[object WeakSet]", _t = "[object ArrayBuffer]", At = "[object DataView]", Ct = "[object Float32Array]", me = "[object Float64Array]", re = "[object Int8Array]", Pe = "[object Int16Array]", be = "[object Int32Array]", zn = "[object Uint8Array]", ei = "[object Uint8ClampedArray]", ye = "[object Uint16Array]", $e = "[object Uint32Array]", Io = /\b__p \+= '';/g, Dp = /\b(__p \+=) '' \+/g, Rp = /(__e\(.*?\)|\b__t\)) \+\n'';/g, Uf = /&(?:amp|lt|gt|quot|#39);/g, Hf = /[&<>"']/g, Pp = RegExp(Uf.source), Mp = RegExp(Hf.source), Fp = /<%-([\s\S]+?)%>/g, Np = /<%([\s\S]+?)%>/g, $f = /<%=([\s\S]+?)%>/g, Lp = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/, Bp = /^\w*$/, Wp = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g, al = /[\\^$.*+?()[\]{}|]/g, Up = RegExp(al.source), cl = /^\s+/, Hp = /\s/, $p = /\{(?:\n\/\* \[wrapped with .+\] \*\/)?\n?/, Kp = /\{\n\/\* \[wrapped with (.+)\] \*/, Gp = /,? & /, Yp = /[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g, zp = /[()=,{}\[\]\/\s]/, Xp = /\\(\\)?/g, qp = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g, Kf = /\w*$/, Vp = /^[-+]0x[0-9a-f]+$/i, kp = /^0b[01]+$/i, Jp = /^\[object .+?Constructor\]$/, Zp = /^0o[0-7]+$/i, Qp = /^(?:0|[1-9]\d*)$/, jp = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g, Oo = /($^)/, tg = /['\n\r\u2028\u2029\\]/g, Do = "\\ud800-\\udfff", eg = "\\u0300-\\u036f", ng = "\\ufe20-\\ufe2f", rg = "\\u20d0-\\u20ff", Gf = eg + ng + rg, Yf = "\\u2700-\\u27bf", zf = "a-z\\xdf-\\xf6\\xf8-\\xff", ig = "\\xac\\xb1\\xd7\\xf7", og = "\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf", sg = "\\u2000-\\u206f", lg = " \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000", Xf = "A-Z\\xc0-\\xd6\\xd8-\\xde", qf = "\\ufe0e\\ufe0f", Vf = ig + og + sg + lg, hl = "['’]", ug = "[" + Do + "]", kf = "[" + Vf + "]", Ro = "[" + Gf + "]", Jf = "\\d+", fg = "[" + Yf + "]", Zf = "[" + zf + "]", Qf = "[^" + Do + Vf + Jf + Yf + zf + Xf + "]", dl = "\\ud83c[\\udffb-\\udfff]", ag = "(?:" + Ro + "|" + dl + ")", jf = "[^" + Do + "]", pl = "(?:\\ud83c[\\udde6-\\uddff]){2}", gl = "[\\ud800-\\udbff][\\udc00-\\udfff]", ni = "[" + Xf + "]", ta = "\\u200d", ea = "(?:" + Zf + "|" + Qf + ")", cg = "(?:" + ni + "|" + Qf + ")", na = "(?:" + hl + "(?:d|ll|m|re|s|t|ve))?", ra = "(?:" + hl + "(?:D|LL|M|RE|S|T|VE))?", ia = ag + "?", oa = "[" + qf + "]?", hg = "(?:" + ta + "(?:" + [jf, pl, gl].join("|") + ")" + oa + ia + ")*", dg = "\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])", pg = "\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])", sa = oa + ia + hg, gg = "(?:" + [fg, pl, gl].join("|") + ")" + sa, vg = "(?:" + [jf + Ro + "?", Ro, pl, gl, ug].join("|") + ")", _g = RegExp(hl, "g"), mg = RegExp(Ro, "g"), vl = RegExp(dl + "(?=" + dl + ")|" + vg + sa, "g"), bg = RegExp([
      ni + "?" + Zf + "+" + na + "(?=" + [kf, ni, "$"].join("|") + ")",
      cg + "+" + ra + "(?=" + [kf, ni + ea, "$"].join("|") + ")",
      ni + "?" + ea + "+" + na,
      ni + "+" + ra,
      pg,
      dg,
      Jf,
      gg
    ].join("|"), "g"), yg = RegExp("[" + ta + Do + Gf + qf + "]"), wg = /[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/, xg = [
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
    Ht[Ct] = Ht[me] = Ht[re] = Ht[Pe] = Ht[be] = Ht[zn] = Ht[ei] = Ht[ye] = Ht[$e] = !0, Ht[_e] = Ht[Dn] = Ht[_t] = Ht[Yn] = Ht[At] = Ht[lr] = Ht[fr] = Ht[ar] = Ht[x] = Ht[O] = Ht[F] = Ht[G] = Ht[K] = Ht[U] = Ht[Z] = !1;
    var Bt = {};
    Bt[_e] = Bt[Dn] = Bt[_t] = Bt[At] = Bt[Yn] = Bt[lr] = Bt[Ct] = Bt[me] = Bt[re] = Bt[Pe] = Bt[be] = Bt[x] = Bt[O] = Bt[F] = Bt[G] = Bt[K] = Bt[U] = Bt[tt] = Bt[zn] = Bt[ei] = Bt[ye] = Bt[$e] = !0, Bt[fr] = Bt[ar] = Bt[Z] = !1;
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
    }, Ig = parseFloat, Og = parseInt, la = typeof Qi == "object" && Qi && Qi.Object === Object && Qi, Dg = typeof self == "object" && self && self.Object === Object && self, we = la || Dg || Function("return this")(), _l = r && !r.nodeType && r, Mr = _l && !0 && e && !e.nodeType && e, ua = Mr && Mr.exports === _l, ml = ua && la.process, fn = function() {
      try {
        var E = Mr && Mr.require && Mr.require("util").types;
        return E || ml && ml.binding && ml.binding("util");
      } catch {
      }
    }(), fa = fn && fn.isArrayBuffer, aa = fn && fn.isDate, ca = fn && fn.isMap, ha = fn && fn.isRegExp, da = fn && fn.isSet, pa = fn && fn.isTypedArray;
    function Je(E, D, C) {
      switch (C.length) {
        case 0:
          return E.call(D);
        case 1:
          return E.call(D, C[0]);
        case 2:
          return E.call(D, C[0], C[1]);
        case 3:
          return E.call(D, C[0], C[1], C[2]);
      }
      return E.apply(D, C);
    }
    function Rg(E, D, C, J) {
      for (var at = -1, Ot = E == null ? 0 : E.length; ++at < Ot; ) {
        var ae = E[at];
        D(J, ae, C(ae), E);
      }
      return J;
    }
    function an(E, D) {
      for (var C = -1, J = E == null ? 0 : E.length; ++C < J && D(E[C], C, E) !== !1; )
        ;
      return E;
    }
    function Pg(E, D) {
      for (var C = E == null ? 0 : E.length; C-- && D(E[C], C, E) !== !1; )
        ;
      return E;
    }
    function ga(E, D) {
      for (var C = -1, J = E == null ? 0 : E.length; ++C < J; )
        if (!D(E[C], C, E))
          return !1;
      return !0;
    }
    function cr(E, D) {
      for (var C = -1, J = E == null ? 0 : E.length, at = 0, Ot = []; ++C < J; ) {
        var ae = E[C];
        D(ae, C, E) && (Ot[at++] = ae);
      }
      return Ot;
    }
    function Po(E, D) {
      var C = E == null ? 0 : E.length;
      return !!C && ri(E, D, 0) > -1;
    }
    function bl(E, D, C) {
      for (var J = -1, at = E == null ? 0 : E.length; ++J < at; )
        if (C(D, E[J]))
          return !0;
      return !1;
    }
    function zt(E, D) {
      for (var C = -1, J = E == null ? 0 : E.length, at = Array(J); ++C < J; )
        at[C] = D(E[C], C, E);
      return at;
    }
    function hr(E, D) {
      for (var C = -1, J = D.length, at = E.length; ++C < J; )
        E[at + C] = D[C];
      return E;
    }
    function yl(E, D, C, J) {
      var at = -1, Ot = E == null ? 0 : E.length;
      for (J && Ot && (C = E[++at]); ++at < Ot; )
        C = D(C, E[at], at, E);
      return C;
    }
    function Mg(E, D, C, J) {
      var at = E == null ? 0 : E.length;
      for (J && at && (C = E[--at]); at--; )
        C = D(C, E[at], at, E);
      return C;
    }
    function wl(E, D) {
      for (var C = -1, J = E == null ? 0 : E.length; ++C < J; )
        if (D(E[C], C, E))
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
    function va(E, D, C) {
      var J;
      return C(E, function(at, Ot, ae) {
        if (D(at, Ot, ae))
          return J = Ot, !1;
      }), J;
    }
    function Mo(E, D, C, J) {
      for (var at = E.length, Ot = C + (J ? 1 : -1); J ? Ot-- : ++Ot < at; )
        if (D(E[Ot], Ot, E))
          return Ot;
      return -1;
    }
    function ri(E, D, C) {
      return D === D ? Vg(E, D, C) : Mo(E, _a, C);
    }
    function Bg(E, D, C, J) {
      for (var at = C - 1, Ot = E.length; ++at < Ot; )
        if (J(E[at], D))
          return at;
      return -1;
    }
    function _a(E) {
      return E !== E;
    }
    function ma(E, D) {
      var C = E == null ? 0 : E.length;
      return C ? Sl(E, D) / C : de;
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
    function ba(E, D, C, J, at) {
      return at(E, function(Ot, ae, Ft) {
        C = J ? (J = !1, Ot) : D(C, Ot, ae, Ft);
      }), C;
    }
    function Wg(E, D) {
      var C = E.length;
      for (E.sort(D); C--; )
        E[C] = E[C].value;
      return E;
    }
    function Sl(E, D) {
      for (var C, J = -1, at = E.length; ++J < at; ) {
        var Ot = D(E[J]);
        Ot !== i && (C = C === i ? Ot : C + Ot);
      }
      return C;
    }
    function Tl(E, D) {
      for (var C = -1, J = Array(E); ++C < E; )
        J[C] = D(C);
      return J;
    }
    function Ug(E, D) {
      return zt(D, function(C) {
        return [C, E[C]];
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
      return zt(D, function(C) {
        return E[C];
      });
    }
    function Ni(E, D) {
      return E.has(D);
    }
    function wa(E, D) {
      for (var C = -1, J = E.length; ++C < J && ri(D, E[C], 0) > -1; )
        ;
      return C;
    }
    function xa(E, D) {
      for (var C = E.length; C-- && ri(D, E[C], 0) > -1; )
        ;
      return C;
    }
    function Hg(E, D) {
      for (var C = E.length, J = 0; C--; )
        E[C] === D && ++J;
      return J;
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
      for (var D, C = []; !(D = E.next()).done; )
        C.push(D.value);
      return C;
    }
    function Cl(E) {
      var D = -1, C = Array(E.size);
      return E.forEach(function(J, at) {
        C[++D] = [at, J];
      }), C;
    }
    function Ea(E, D) {
      return function(C) {
        return E(D(C));
      };
    }
    function dr(E, D) {
      for (var C = -1, J = E.length, at = 0, Ot = []; ++C < J; ) {
        var ae = E[C];
        (ae === D || ae === m) && (E[C] = m, Ot[at++] = C);
      }
      return Ot;
    }
    function Fo(E) {
      var D = -1, C = Array(E.size);
      return E.forEach(function(J) {
        C[++D] = J;
      }), C;
    }
    function qg(E) {
      var D = -1, C = Array(E.size);
      return E.forEach(function(J) {
        C[++D] = [J, J];
      }), C;
    }
    function Vg(E, D, C) {
      for (var J = C - 1, at = E.length; ++J < at; )
        if (E[J] === D)
          return J;
      return -1;
    }
    function kg(E, D, C) {
      for (var J = C + 1; J--; )
        if (E[J] === D)
          return J;
      return J;
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
      var C = D.Array, J = D.Date, at = D.Error, Ot = D.Function, ae = D.Math, Ft = D.Object, Il = D.RegExp, ev = D.String, cn = D.TypeError, No = C.prototype, nv = Ot.prototype, li = Ft.prototype, Lo = D["__core-js_shared__"], Bo = nv.toString, Mt = li.hasOwnProperty, rv = 0, Ta = function() {
        var t = /[^.]+$/.exec(Lo && Lo.keys && Lo.keys.IE_PROTO || "");
        return t ? "Symbol(src)_1." + t : "";
      }(), Wo = li.toString, iv = Bo.call(Ft), ov = we._, sv = Il(
        "^" + Bo.call(Mt).replace(al, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"
      ), Uo = ua ? D.Buffer : i, pr = D.Symbol, Ho = D.Uint8Array, Aa = Uo ? Uo.allocUnsafe : i, $o = Ea(Ft.getPrototypeOf, Ft), Ca = Ft.create, Ia = li.propertyIsEnumerable, Ko = No.splice, Oa = pr ? pr.isConcatSpreadable : i, Li = pr ? pr.iterator : i, Fr = pr ? pr.toStringTag : i, Go = function() {
        try {
          var t = Ur(Ft, "defineProperty");
          return t({}, "", {}), t;
        } catch {
        }
      }(), lv = D.clearTimeout !== we.clearTimeout && D.clearTimeout, uv = J && J.now !== we.Date.now && J.now, fv = D.setTimeout !== we.setTimeout && D.setTimeout, Yo = ae.ceil, zo = ae.floor, Ol = Ft.getOwnPropertySymbols, av = Uo ? Uo.isBuffer : i, Da = D.isFinite, cv = No.join, hv = Ea(Ft.keys, Ft), ce = ae.max, Ae = ae.min, dv = J.now, pv = D.parseInt, Ra = ae.random, gv = No.reverse, Dl = Ur(D, "DataView"), Bi = Ur(D, "Map"), Rl = Ur(D, "Promise"), ui = Ur(D, "Set"), Wi = Ur(D, "WeakMap"), Ui = Ur(Ft, "create"), Xo = Wi && new Wi(), fi = {}, vv = Hr(Dl), _v = Hr(Bi), mv = Hr(Rl), bv = Hr(ui), yv = Hr(Wi), qo = pr ? pr.prototype : i, Hi = qo ? qo.valueOf : i, Pa = qo ? qo.toString : i;
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
      function Vo() {
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
      }, d.prototype = Vo.prototype, d.prototype.constructor = d, hn.prototype = ai(Vo.prototype), hn.prototype.constructor = hn;
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
        var t = this.__wrapped__.value(), n = this.__dir__, s = ht(t), u = n < 0, c = s ? t.length : 0, p = N_(0, c, this.__views__), _ = p.start, w = p.end, S = w - _, P = u ? w : _ - 1, M = this.__iteratees__, L = M.length, X = 0, j = Ae(S, this.__takeCount__);
        if (!s || !u && c == S && j == S)
          return nc(t, this.__actions__);
        var it = [];
        t:
          for (; S-- && X < j; ) {
            P += n;
            for (var mt = -1, ot = t[P]; ++mt < L; ) {
              var wt = M[mt], St = wt.iteratee, tn = wt.type, Ne = St(ot);
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
      xt.prototype = ai(Vo.prototype), xt.prototype.constructor = xt;
      function Nr(t) {
        var n = -1, s = t == null ? 0 : t.length;
        for (this.clear(); ++n < s; ) {
          var u = t[n];
          this.set(u[0], u[1]);
        }
      }
      function Sv() {
        this.__data__ = Ui ? Ui(null) : {}, this.size = 0;
      }
      function Tv(t) {
        var n = this.has(t) && delete this.__data__[t];
        return this.size -= n ? 1 : 0, n;
      }
      function Av(t) {
        var n = this.__data__;
        if (Ui) {
          var s = n[t];
          return s === g ? i : s;
        }
        return Mt.call(n, t) ? n[t] : i;
      }
      function Cv(t) {
        var n = this.__data__;
        return Ui ? n[t] !== i : Mt.call(n, t);
      }
      function Iv(t, n) {
        var s = this.__data__;
        return this.size += this.has(t) ? 0 : 1, s[t] = Ui && n === i ? g : n, this;
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
        var n = this.__data__, s = ko(n, t);
        if (s < 0)
          return !1;
        var u = n.length - 1;
        return s == u ? n.pop() : Ko.call(n, s, 1), --this.size, !0;
      }
      function Rv(t) {
        var n = this.__data__, s = ko(n, t);
        return s < 0 ? i : n[s][1];
      }
      function Pv(t) {
        return ko(this.__data__, t) > -1;
      }
      function Mv(t, n) {
        var s = this.__data__, u = ko(s, t);
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
          map: new (Bi || Xn)(),
          string: new Nr()
        };
      }
      function Nv(t) {
        var n = ls(this, t).delete(t);
        return this.size -= n ? 1 : 0, n;
      }
      function Lv(t) {
        return ls(this, t).get(t);
      }
      function Bv(t) {
        return ls(this, t).has(t);
      }
      function Wv(t, n) {
        var s = ls(this, t), u = s.size;
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
          if (!Bi || u.length < l - 1)
            return u.push([t, n]), this.size = ++s.size, this;
          s = this.__data__ = new qn(u);
        }
        return s.set(t, n), this.size = s.size, this;
      }
      xn.prototype.clear = $v, xn.prototype.delete = Kv, xn.prototype.get = Gv, xn.prototype.has = Yv, xn.prototype.set = zv;
      function Ma(t, n) {
        var s = ht(t), u = !s && $r(t), c = !s && !u && br(t), p = !s && !u && !c && pi(t), _ = s || u || c || p, w = _ ? Tl(t.length, ev) : [], S = w.length;
        for (var P in t)
          (n || Mt.call(t, P)) && !(_ && // Safari 9 has enumerable `arguments.length` in strict mode.
          (P == "length" || // Node.js 0.10 has enumerable non-index properties on buffers.
          c && (P == "offset" || P == "parent") || // PhantomJS 2 has enumerable non-index properties on typed arrays.
          p && (P == "buffer" || P == "byteLength" || P == "byteOffset") || // Skip index properties.
          Zn(P, S))) && w.push(P);
        return w;
      }
      function Fa(t) {
        var n = t.length;
        return n ? t[Kl(0, n - 1)] : i;
      }
      function Xv(t, n) {
        return us(Ke(t), Br(n, 0, t.length));
      }
      function qv(t) {
        return us(Ke(t));
      }
      function Pl(t, n, s) {
        (s !== i && !En(t[n], s) || s === i && !(n in t)) && Vn(t, n, s);
      }
      function $i(t, n, s) {
        var u = t[n];
        (!(Mt.call(t, n) && En(u, s)) || s === i && !(n in t)) && Vn(t, n, s);
      }
      function ko(t, n) {
        for (var s = t.length; s--; )
          if (En(t[s][0], n))
            return s;
        return -1;
      }
      function Vv(t, n, s, u) {
        return gr(t, function(c, p, _) {
          n(u, c, s(c), _);
        }), u;
      }
      function Na(t, n) {
        return t && Pn(n, pe(n), t);
      }
      function kv(t, n) {
        return t && Pn(n, Ye(n), t);
      }
      function Vn(t, n, s) {
        n == "__proto__" && Go ? Go(t, n, {
          configurable: !0,
          enumerable: !0,
          value: s,
          writable: !0
        }) : t[n] = s;
      }
      function Ml(t, n) {
        for (var s = -1, u = n.length, c = C(u), p = t == null; ++s < u; )
          c[s] = p ? i : du(t, n[s]);
        return c;
      }
      function Br(t, n, s) {
        return t === t && (s !== i && (t = t <= s ? t : s), n !== i && (t = t >= n ? t : n)), t;
      }
      function dn(t, n, s, u, c, p) {
        var _, w = n & b, S = n & T, P = n & A;
        if (s && (_ = c ? s(t, u, c, p) : s(t)), _ !== i)
          return _;
        if (!Vt(t))
          return t;
        var M = ht(t);
        if (M) {
          if (_ = B_(t), !w)
            return Ke(t, _);
        } else {
          var L = Ce(t), X = L == ar || L == y;
          if (br(t))
            return oc(t, w);
          if (L == F || L == _e || X && !c) {
            if (_ = S || X ? {} : Sc(t), !w)
              return S ? A_(t, kv(_, t)) : T_(t, Na(_, t));
          } else {
            if (!Bt[L])
              return c ? t : {};
            _ = W_(t, L, w);
          }
        }
        p || (p = new xn());
        var j = p.get(t);
        if (j)
          return j;
        p.set(t, _), jc(t) ? t.forEach(function(ot) {
          _.add(dn(ot, n, s, ot, t, p));
        }) : Zc(t) && t.forEach(function(ot, wt) {
          _.set(wt, dn(ot, n, s, wt, t, p));
        });
        var it = P ? S ? jl : Ql : S ? Ye : pe, mt = M ? i : it(t);
        return an(mt || t, function(ot, wt) {
          mt && (wt = ot, ot = t[wt]), $i(_, wt, dn(ot, n, s, wt, t, p));
        }), _;
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
          var c = s[u], p = n[c], _ = t[c];
          if (_ === i && !(c in t) || !p(_))
            return !1;
        }
        return !0;
      }
      function Ba(t, n, s) {
        if (typeof t != "function")
          throw new cn(a);
        return Vi(function() {
          t.apply(i, s);
        }, n);
      }
      function Ki(t, n, s, u) {
        var c = -1, p = Po, _ = !0, w = t.length, S = [], P = n.length;
        if (!w)
          return S;
        s && (n = zt(n, Ze(s))), u ? (p = bl, _ = !1) : n.length >= l && (p = Ni, _ = !1, n = new Lr(n));
        t:
          for (; ++c < w; ) {
            var M = t[c], L = s == null ? M : s(M);
            if (M = u || M !== 0 ? M : 0, _ && L === L) {
              for (var X = P; X--; )
                if (n[X] === L)
                  continue t;
              S.push(M);
            } else
              p(n, L, u) || S.push(M);
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
      function Jo(t, n, s) {
        for (var u = -1, c = t.length; ++u < c; ) {
          var p = t[u], _ = n(p);
          if (_ != null && (w === i ? _ === _ && !je(_) : s(_, w)))
            var w = _, S = p;
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
        var p = -1, _ = t.length;
        for (s || (s = H_), c || (c = []); ++p < _; ) {
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
      function Zo(t, n) {
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
        return t == null ? t === i ? V : $ : Fr && Fr in Ft(t) ? F_(t) : q_(t);
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
        for (var u = s ? bl : Po, c = t[0].length, p = t.length, _ = p, w = C(p), S = 1 / 0, P = []; _--; ) {
          var M = t[_];
          _ && n && (M = zt(M, Ze(n))), S = Ae(M.length, S), w[_] = !s && (n || c >= 120 && M.length >= 120) ? new Lr(_ && M) : i;
        }
        M = t[0];
        var L = -1, X = w[0];
        t:
          for (; ++L < c && P.length < S; ) {
            var j = M[L], it = n ? n(j) : j;
            if (j = s || j !== 0 ? j : 0, !(X ? Ni(X, it) : u(P, it, s))) {
              for (_ = p; --_; ) {
                var mt = w[_];
                if (!(mt ? Ni(mt, it) : u(t[_], it, s)))
                  continue t;
              }
              X && X.push(it), P.push(j);
            }
          }
        return P;
      }
      function n_(t, n, s, u) {
        return Rn(t, function(c, p, _) {
          n(u, s(c), p, _);
        }), u;
      }
      function Gi(t, n, s) {
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
      function Yi(t, n, s, u, c) {
        return t === n ? !0 : t == null || n == null || !Jt(t) && !Jt(n) ? t !== t && n !== n : o_(t, n, s, u, Yi, c);
      }
      function o_(t, n, s, u, c, p) {
        var _ = ht(t), w = ht(n), S = _ ? Dn : Ce(t), P = w ? Dn : Ce(n);
        S = S == _e ? F : S, P = P == _e ? F : P;
        var M = S == F, L = P == F, X = S == P;
        if (X && br(t)) {
          if (!br(n))
            return !1;
          _ = !0, M = !1;
        }
        if (X && !M)
          return p || (p = new xn()), _ || pi(t) ? wc(t, n, s, u, c, p) : P_(t, n, S, s, u, c, p);
        if (!(s & I)) {
          var j = M && Mt.call(t, "__wrapped__"), it = L && Mt.call(n, "__wrapped__");
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
        var c = s.length, p = c, _ = !u;
        if (t == null)
          return !p;
        for (t = Ft(t); c--; ) {
          var w = s[c];
          if (_ && w[2] ? w[1] !== t[w[0]] : !(w[0] in t))
            return !1;
        }
        for (; ++c < p; ) {
          w = s[c];
          var S = w[0], P = t[S], M = w[1];
          if (_ && w[2]) {
            if (P === i && !(S in t))
              return !1;
          } else {
            var L = new xn();
            if (u)
              var X = u(P, M, S, t, n, L);
            if (!(X === i ? Yi(M, P, I | R, u, L) : X))
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
        return Jt(t) && Ce(t) == K;
      }
      function f_(t) {
        return Jt(t) && ps(t.length) && !!Ht[Me(t)];
      }
      function Ya(t) {
        return typeof t == "function" ? t : t == null ? ze : typeof t == "object" ? ht(t) ? qa(t[0], t[1]) : Xa(t) : hh(t);
      }
      function Ul(t) {
        if (!qi(t))
          return hv(t);
        var n = [];
        for (var s in Ft(t))
          Mt.call(t, s) && s != "constructor" && n.push(s);
        return n;
      }
      function a_(t) {
        if (!Vt(t))
          return X_(t);
        var n = qi(t), s = [];
        for (var u in t)
          u == "constructor" && (n || !Mt.call(t, u)) || s.push(u);
        return s;
      }
      function Hl(t, n) {
        return t < n;
      }
      function za(t, n) {
        var s = -1, u = Ge(t) ? C(t.length) : [];
        return gr(t, function(c, p, _) {
          u[++s] = n(c, p, _);
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
          return u === i && u === n ? pu(s, t) : Yi(n, u, I | R);
        };
      }
      function Qo(t, n, s, u, c) {
        t !== n && Fl(n, function(p, _) {
          if (c || (c = new xn()), Vt(p))
            c_(t, n, _, s, Qo, u, c);
          else {
            var w = u ? u(ou(t, _), p, _ + "", t, n, c) : i;
            w === i && (w = p), Pl(t, _, w);
          }
        }, Ye);
      }
      function c_(t, n, s, u, c, p, _) {
        var w = ou(t, s), S = ou(n, s), P = _.get(S);
        if (P) {
          Pl(t, s, P);
          return;
        }
        var M = p ? p(w, S, s + "", t, n, _) : i, L = M === i;
        if (L) {
          var X = ht(S), j = !X && br(S), it = !X && !j && pi(S);
          M = S, X || j || it ? ht(w) ? M = w : Qt(w) ? M = Ke(w) : j ? (L = !1, M = oc(S, !0)) : it ? (L = !1, M = sc(S, !0)) : M = [] : ki(S) || $r(S) ? (M = w, $r(w) ? M = nh(w) : (!Vt(w) || Qn(w)) && (M = Sc(S))) : L = !1;
        }
        L && (_.set(S, M), c(M, S, u, p, _), _.delete(S)), Pl(t, s, M);
      }
      function Va(t, n) {
        var s = t.length;
        if (s)
          return n += n < 0 ? s : 0, Zn(n, s) ? t[n] : i;
      }
      function ka(t, n, s) {
        n.length ? n = zt(n, function(p) {
          return ht(p) ? function(_) {
            return Wr(_, p.length === 1 ? p[0] : p);
          } : p;
        }) : n = [ze];
        var u = -1;
        n = zt(n, Ze(et()));
        var c = za(t, function(p, _, w) {
          var S = zt(n, function(P) {
            return P(p);
          });
          return { criteria: S, index: ++u, value: p };
        });
        return Wg(c, function(p, _) {
          return S_(p, _, s);
        });
      }
      function h_(t, n) {
        return Ja(t, n, function(s, u) {
          return pu(t, u);
        });
      }
      function Ja(t, n, s) {
        for (var u = -1, c = n.length, p = {}; ++u < c; ) {
          var _ = n[u], w = Wr(t, _);
          s(w, _) && zi(p, _r(_, t), w);
        }
        return p;
      }
      function d_(t) {
        return function(n) {
          return Wr(n, t);
        };
      }
      function $l(t, n, s, u) {
        var c = u ? Bg : ri, p = -1, _ = n.length, w = t;
        for (t === n && (n = Ke(n)), s && (w = zt(t, Ze(s))); ++p < _; )
          for (var S = 0, P = n[p], M = s ? s(P) : P; (S = c(w, M, S, u)) > -1; )
            w !== t && Ko.call(w, S, 1), Ko.call(t, S, 1);
        return t;
      }
      function Za(t, n) {
        for (var s = t ? n.length : 0, u = s - 1; s--; ) {
          var c = n[s];
          if (s == u || c !== p) {
            var p = c;
            Zn(c) ? Ko.call(t, c, 1) : zl(t, c);
          }
        }
        return t;
      }
      function Kl(t, n) {
        return t + zo(Ra() * (n - t + 1));
      }
      function p_(t, n, s, u) {
        for (var c = -1, p = ce(Yo((n - t) / (s || 1)), 0), _ = C(p); p--; )
          _[u ? p : ++c] = t, t += s;
        return _;
      }
      function Gl(t, n) {
        var s = "";
        if (!t || n < 1 || n > rt)
          return s;
        do
          n % 2 && (s += t), n = zo(n / 2), n && (t += t);
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
        return us(s, Br(n, 0, s.length));
      }
      function zi(t, n, s, u) {
        if (!Vt(t))
          return t;
        n = _r(n, t);
        for (var c = -1, p = n.length, _ = p - 1, w = t; w != null && ++c < p; ) {
          var S = Mn(n[c]), P = s;
          if (S === "__proto__" || S === "constructor" || S === "prototype")
            return t;
          if (c != _) {
            var M = w[S];
            P = u ? u(M, S, w) : i, P === i && (P = Vt(M) ? M : Zn(n[c + 1]) ? [] : {});
          }
          $i(w, S, P), w = w[S];
        }
        return t;
      }
      var Qa = Xo ? function(t, n) {
        return Xo.set(t, n), t;
      } : ze, __ = Go ? function(t, n) {
        return Go(t, "toString", {
          configurable: !0,
          enumerable: !1,
          value: vu(n),
          writable: !0
        });
      } : ze;
      function m_(t) {
        return us(gi(t));
      }
      function pn(t, n, s) {
        var u = -1, c = t.length;
        n < 0 && (n = -n > c ? 0 : c + n), s = s > c ? c : s, s < 0 && (s += c), c = n > s ? 0 : s - n >>> 0, n >>>= 0;
        for (var p = C(c); ++u < c; )
          p[u] = t[u + n];
        return p;
      }
      function b_(t, n) {
        var s;
        return gr(t, function(u, c, p) {
          return s = n(u, c, p), !s;
        }), !!s;
      }
      function jo(t, n, s) {
        var u = 0, c = t == null ? u : t.length;
        if (typeof n == "number" && n === n && c <= un) {
          for (; u < c; ) {
            var p = u + c >>> 1, _ = t[p];
            _ !== null && !je(_) && (s ? _ <= n : _ < n) ? u = p + 1 : c = p;
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
        for (var _ = n !== n, w = n === null, S = je(n), P = n === i; c < p; ) {
          var M = zo((c + p) / 2), L = s(t[M]), X = L !== i, j = L === null, it = L === L, mt = je(L);
          if (_)
            var ot = u || it;
          else
            P ? ot = it && (u || X) : w ? ot = it && X && (u || !j) : S ? ot = it && X && !j && (u || !mt) : j || mt ? ot = !1 : ot = u ? L <= n : L < n;
          ot ? c = M + 1 : p = M;
        }
        return Ae(p, ee);
      }
      function ja(t, n) {
        for (var s = -1, u = t.length, c = 0, p = []; ++s < u; ) {
          var _ = t[s], w = n ? n(_) : _;
          if (!s || !En(w, S)) {
            var S = w;
            p[c++] = _ === 0 ? 0 : _;
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
        var u = -1, c = Po, p = t.length, _ = !0, w = [], S = w;
        if (s)
          _ = !1, c = bl;
        else if (p >= l) {
          var P = n ? null : D_(t);
          if (P)
            return Fo(P);
          _ = !1, c = Ni, S = new Lr();
        } else
          S = n ? [] : w;
        t:
          for (; ++u < p; ) {
            var M = t[u], L = n ? n(M) : M;
            if (M = s || M !== 0 ? M : 0, _ && L === L) {
              for (var X = S.length; X--; )
                if (S[X] === L)
                  continue t;
              n && S.push(L), w.push(M);
            } else
              c(S, L, s) || (S !== w && S.push(L), w.push(M));
          }
        return w;
      }
      function zl(t, n) {
        return n = _r(n, t), t = Ic(t, n), t == null || delete t[Mn(gn(n))];
      }
      function ec(t, n, s, u) {
        return zi(t, n, s(Wr(t, n)), u);
      }
      function ts(t, n, s, u) {
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
        for (var c = -1, p = C(u); ++c < u; )
          for (var _ = t[c], w = -1; ++w < u; )
            w != c && (p[c] = Ki(p[c] || _, t[w], n, s));
        return vr(xe(p, 1), n, s);
      }
      function rc(t, n, s) {
        for (var u = -1, c = t.length, p = n.length, _ = {}; ++u < c; ) {
          var w = u < p ? n[u] : i;
          s(_, t[u], w);
        }
        return _;
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
        return new Ho(n).set(new Ho(t)), n;
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
        return Hi ? Ft(Hi.call(t)) : {};
      }
      function sc(t, n) {
        var s = n ? kl(t.buffer) : t.buffer;
        return new t.constructor(s, t.byteOffset, t.length);
      }
      function lc(t, n) {
        if (t !== n) {
          var s = t !== i, u = t === null, c = t === t, p = je(t), _ = n !== i, w = n === null, S = n === n, P = je(n);
          if (!w && !P && !p && t > n || p && _ && S && !w && !P || u && _ && S || !s && S || !c)
            return 1;
          if (!u && !p && !P && t < n || P && s && c && !u && !p || w && s && c || !_ && c || !S)
            return -1;
        }
        return 0;
      }
      function S_(t, n, s) {
        for (var u = -1, c = t.criteria, p = n.criteria, _ = c.length, w = s.length; ++u < _; ) {
          var S = lc(c[u], p[u]);
          if (S) {
            if (u >= w)
              return S;
            var P = s[u];
            return S * (P == "desc" ? -1 : 1);
          }
        }
        return t.index - n.index;
      }
      function uc(t, n, s, u) {
        for (var c = -1, p = t.length, _ = s.length, w = -1, S = n.length, P = ce(p - _, 0), M = C(S + P), L = !u; ++w < S; )
          M[w] = n[w];
        for (; ++c < _; )
          (L || c < p) && (M[s[c]] = t[c]);
        for (; P--; )
          M[w++] = t[c++];
        return M;
      }
      function fc(t, n, s, u) {
        for (var c = -1, p = t.length, _ = -1, w = s.length, S = -1, P = n.length, M = ce(p - w, 0), L = C(M + P), X = !u; ++c < M; )
          L[c] = t[c];
        for (var j = c; ++S < P; )
          L[j + S] = n[S];
        for (; ++_ < w; )
          (X || c < p) && (L[j + s[_]] = t[c++]);
        return L;
      }
      function Ke(t, n) {
        var s = -1, u = t.length;
        for (n || (n = C(u)); ++s < u; )
          n[s] = t[s];
        return n;
      }
      function Pn(t, n, s, u) {
        var c = !s;
        s || (s = {});
        for (var p = -1, _ = n.length; ++p < _; ) {
          var w = n[p], S = u ? u(s[w], t[w], w, s, t) : i;
          S === i && (S = t[w]), c ? Vn(s, w, S) : $i(s, w, S);
        }
        return s;
      }
      function T_(t, n) {
        return Pn(t, nu(t), n);
      }
      function A_(t, n) {
        return Pn(t, xc(t), n);
      }
      function es(t, n) {
        return function(s, u) {
          var c = ht(s) ? Rg : Vv, p = n ? n() : {};
          return c(s, t, et(u, 2), p);
        };
      }
      function ci(t) {
        return bt(function(n, s) {
          var u = -1, c = s.length, p = c > 1 ? s[c - 1] : i, _ = c > 2 ? s[2] : i;
          for (p = t.length > 3 && typeof p == "function" ? (c--, p) : i, _ && Fe(s[0], s[1], _) && (p = c < 3 ? i : p, c = 1), n = Ft(n); ++u < c; ) {
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
          for (var c = s.length, p = n ? c : -1, _ = Ft(s); (n ? p-- : ++p < c) && u(_[p], p, _) !== !1; )
            ;
          return s;
        };
      }
      function cc(t) {
        return function(n, s, u) {
          for (var c = -1, p = Ft(n), _ = u(n), w = _.length; w--; ) {
            var S = _[t ? w : ++c];
            if (s(p[S], S, p) === !1)
              break;
          }
          return n;
        };
      }
      function C_(t, n, s) {
        var u = n & N, c = Xi(t);
        function p() {
          var _ = this && this !== we && this instanceof p ? c : t;
          return _.apply(u ? s : this, arguments);
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
      function Xi(t) {
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
        var u = Xi(t);
        function c() {
          for (var p = arguments.length, _ = C(p), w = p, S = di(c); w--; )
            _[w] = arguments[w];
          var P = p < 3 && _[0] !== S && _[p - 1] !== S ? [] : dr(_, S);
          if (p -= P.length, p < s)
            return _c(
              t,
              n,
              ns,
              c.placeholder,
              i,
              _,
              P,
              i,
              i,
              s - p
            );
          var M = this && this !== we && this instanceof c ? u : t;
          return Je(M, this, _);
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
          var _ = t(n, s, u);
          return _ > -1 ? c[p ? n[_] : _] : i;
        };
      }
      function pc(t) {
        return Jn(function(n) {
          var s = n.length, u = s, c = hn.prototype.thru;
          for (t && n.reverse(); u--; ) {
            var p = n[u];
            if (typeof p != "function")
              throw new cn(a);
            if (c && !_ && ss(p) == "wrapper")
              var _ = new hn([], !0);
          }
          for (u = _ ? u : s; ++u < s; ) {
            p = n[u];
            var w = ss(p), S = w == "wrapper" ? tu(p) : i;
            S && iu(S[0]) && S[1] == (It | q | nt | Kt) && !S[4].length && S[9] == 1 ? _ = _[ss(S[0])].apply(_, S[3]) : _ = p.length == 1 && iu(p) ? _[w]() : _.thru(p);
          }
          return function() {
            var P = arguments, M = P[0];
            if (_ && P.length == 1 && ht(M))
              return _.plant(M).value();
            for (var L = 0, X = s ? n[L].apply(this, P) : M; ++L < s; )
              X = n[L].call(this, X);
            return X;
          };
        });
      }
      function ns(t, n, s, u, c, p, _, w, S, P) {
        var M = n & It, L = n & N, X = n & W, j = n & (q | B), it = n & Lt, mt = X ? i : Xi(t);
        function ot() {
          for (var wt = arguments.length, St = C(wt), tn = wt; tn--; )
            St[tn] = arguments[tn];
          if (j)
            var Ne = di(ot), en = Hg(St, Ne);
          if (u && (St = uc(St, u, c, j)), p && (St = fc(St, p, _, j)), wt -= en, j && wt < P) {
            var jt = dr(St, Ne);
            return _c(
              t,
              n,
              ns,
              ot.placeholder,
              s,
              St,
              jt,
              w,
              S,
              P - wt
            );
          }
          var Sn = L ? s : this, tr = X ? Sn[t] : t;
          return wt = St.length, w ? St = V_(St, w) : it && wt > 1 && St.reverse(), M && S < wt && (St.length = S), this && this !== we && this instanceof ot && (tr = mt || Xi(tr)), tr.apply(Sn, St);
        }
        return ot;
      }
      function gc(t, n) {
        return function(s, u) {
          return n_(s, t, n(u), {});
        };
      }
      function rs(t, n) {
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
      function is(t, n) {
        n = n === i ? " " : Qe(n);
        var s = n.length;
        if (s < 2)
          return s ? Gl(n, t) : n;
        var u = Gl(n, Yo(t / oi(n)));
        return ii(n) ? mr(wn(u), 0, t).join("") : u.slice(0, t);
      }
      function O_(t, n, s, u) {
        var c = n & N, p = Xi(t);
        function _() {
          for (var w = -1, S = arguments.length, P = -1, M = u.length, L = C(M + S), X = this && this !== we && this instanceof _ ? p : t; ++P < M; )
            L[P] = u[P];
          for (; S--; )
            L[P++] = arguments[++w];
          return Je(X, c ? s : this, L);
        }
        return _;
      }
      function vc(t) {
        return function(n, s, u) {
          return u && typeof u != "number" && Fe(n, s, u) && (s = u = i), n = jn(n), s === i ? (s = n, n = 0) : s = jn(s), u = u === i ? n < s ? 1 : -1 : jn(u), p_(n, s, u, t);
        };
      }
      function os(t) {
        return function(n, s) {
          return typeof n == "string" && typeof s == "string" || (n = vn(n), s = vn(s)), t(n, s);
        };
      }
      function _c(t, n, s, u, c, p, _, w, S, P) {
        var M = n & q, L = M ? _ : i, X = M ? i : _, j = M ? p : i, it = M ? i : p;
        n |= M ? nt : Tt, n &= ~(M ? Tt : nt), n & k || (n &= ~(N | W));
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
          P
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
      var D_ = ui && 1 / Fo(new ui([, -0]))[1] == lt ? function(t) {
        return new ui(t);
      } : bu;
      function mc(t) {
        return function(n) {
          var s = Ce(n);
          return s == x ? Cl(n) : s == K ? qg(n) : Ug(n, t(n));
        };
      }
      function kn(t, n, s, u, c, p, _, w) {
        var S = n & W;
        if (!S && typeof t != "function")
          throw new cn(a);
        var P = u ? u.length : 0;
        if (P || (n &= ~(nt | Tt), u = c = i), _ = _ === i ? _ : ce(vt(_), 0), w = w === i ? w : vt(w), P -= c ? c.length : 0, n & Tt) {
          var M = u, L = c;
          u = c = i;
        }
        var X = S ? i : tu(t), j = [
          t,
          n,
          s,
          u,
          c,
          M,
          L,
          p,
          _,
          w
        ];
        if (X && z_(j, X), t = j[0], n = j[1], s = j[2], u = j[3], c = j[4], w = j[9] = j[9] === i ? S ? 0 : t.length : ce(j[9] - P, 0), !w && n & (q | B) && (n &= ~(q | B)), !n || n == N)
          var it = C_(t, n, s);
        else
          n == q || n == B ? it = I_(t, n, w) : (n == nt || n == (N | nt)) && !c.length ? it = O_(t, n, s, u) : it = ns.apply(i, j);
        var mt = X ? Qa : Oc;
        return Dc(mt(it, j), t, n);
      }
      function bc(t, n, s, u) {
        return t === i || En(t, li[s]) && !Mt.call(u, s) ? n : t;
      }
      function yc(t, n, s, u, c, p) {
        return Vt(t) && Vt(n) && (p.set(n, t), Qo(t, n, i, yc, p), p.delete(n)), t;
      }
      function R_(t) {
        return ki(t) ? i : t;
      }
      function wc(t, n, s, u, c, p) {
        var _ = s & I, w = t.length, S = n.length;
        if (w != S && !(_ && S > w))
          return !1;
        var P = p.get(t), M = p.get(n);
        if (P && M)
          return P == n && M == t;
        var L = -1, X = !0, j = s & R ? new Lr() : i;
        for (p.set(t, n), p.set(n, t); ++L < w; ) {
          var it = t[L], mt = n[L];
          if (u)
            var ot = _ ? u(mt, it, L, n, t, p) : u(it, mt, L, t, n, p);
          if (ot !== i) {
            if (ot)
              continue;
            X = !1;
            break;
          }
          if (j) {
            if (!wl(n, function(wt, St) {
              if (!Ni(j, St) && (it === wt || c(it, wt, s, u, p)))
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
      function P_(t, n, s, u, c, p, _) {
        switch (s) {
          case At:
            if (t.byteLength != n.byteLength || t.byteOffset != n.byteOffset)
              return !1;
            t = t.buffer, n = n.buffer;
          case _t:
            return !(t.byteLength != n.byteLength || !p(new Ho(t), new Ho(n)));
          case Yn:
          case lr:
          case O:
            return En(+t, +n);
          case fr:
            return t.name == n.name && t.message == n.message;
          case G:
          case U:
            return t == n + "";
          case x:
            var w = Cl;
          case K:
            var S = u & I;
            if (w || (w = Fo), t.size != n.size && !S)
              return !1;
            var P = _.get(t);
            if (P)
              return P == n;
            u |= R, _.set(t, n);
            var M = wc(w(t), w(n), u, c, p, _);
            return _.delete(t), M;
          case tt:
            if (Hi)
              return Hi.call(t) == Hi.call(n);
        }
        return !1;
      }
      function M_(t, n, s, u, c, p) {
        var _ = s & I, w = Ql(t), S = w.length, P = Ql(n), M = P.length;
        if (S != M && !_)
          return !1;
        for (var L = S; L--; ) {
          var X = w[L];
          if (!(_ ? X in n : Mt.call(n, X)))
            return !1;
        }
        var j = p.get(t), it = p.get(n);
        if (j && it)
          return j == n && it == t;
        var mt = !0;
        p.set(t, n), p.set(n, t);
        for (var ot = _; ++L < S; ) {
          X = w[L];
          var wt = t[X], St = n[X];
          if (u)
            var tn = _ ? u(St, wt, X, n, t, p) : u(wt, St, X, t, n, p);
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
      var tu = Xo ? function(t) {
        return Xo.get(t);
      } : bu;
      function ss(t) {
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
      function ls(t, n) {
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
        var c = Wo.call(t);
        return u && (n ? t[Fr] = s : delete t[Fr]), c;
      }
      var nu = Ol ? function(t) {
        return t == null ? [] : (t = Ft(t), cr(Ol(t), function(n) {
          return Ia.call(t, n);
        }));
      } : yu, xc = Ol ? function(t) {
        for (var n = []; t; )
          hr(n, nu(t)), t = $o(t);
        return n;
      } : yu, Ce = Me;
      (Dl && Ce(new Dl(new ArrayBuffer(1))) != At || Bi && Ce(new Bi()) != x || Rl && Ce(Rl.resolve()) != H || ui && Ce(new ui()) != K || Wi && Ce(new Wi()) != Z) && (Ce = function(t) {
        var n = Me(t), s = n == F ? t.constructor : i, u = s ? Hr(s) : "";
        if (u)
          switch (u) {
            case vv:
              return At;
            case _v:
              return x;
            case mv:
              return H;
            case bv:
              return K;
            case yv:
              return Z;
          }
        return n;
      });
      function N_(t, n, s) {
        for (var u = -1, c = s.length; ++u < c; ) {
          var p = s[u], _ = p.size;
          switch (p.type) {
            case "drop":
              t += _;
              break;
            case "dropRight":
              n -= _;
              break;
            case "take":
              n = Ae(n, t + _);
              break;
            case "takeRight":
              t = ce(t, n - _);
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
          var _ = Mn(n[u]);
          if (!(p = t != null && s(t, _)))
            break;
          t = t[_];
        }
        return p || ++u != c ? p : (c = t == null ? 0 : t.length, !!c && ps(c) && Zn(_, c) && (ht(t) || $r(t)));
      }
      function B_(t) {
        var n = t.length, s = new t.constructor(n);
        return n && typeof t[0] == "string" && Mt.call(t, "index") && (s.index = t.index, s.input = t.input), s;
      }
      function Sc(t) {
        return typeof t.constructor == "function" && !qi(t) ? ai($o(t)) : {};
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
          case O:
          case U:
            return new u(t);
          case G:
            return x_(t);
          case K:
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
        var n = ss(t), s = d[n];
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
      var G_ = Lo ? Qn : wu;
      function qi(t) {
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
        var n = hs(t, function(u) {
          return s.size === v && s.clear(), u;
        }), s = n.cache;
        return n;
      }
      function z_(t, n) {
        var s = t[1], u = n[1], c = s | u, p = c < (N | W | It), _ = u == It && s == q || u == It && s == Kt && t[7].length <= n[8] || u == (It | Kt) && n[7].length <= n[8] && s == q;
        if (!(p || _))
          return t;
        u & N && (t[2] = n[2], c |= s & N ? 0 : k);
        var w = n[3];
        if (w) {
          var S = t[3];
          t[3] = S ? uc(S, w, n[4]) : w, t[4] = S ? dr(t[3], m) : n[4];
        }
        return w = n[5], w && (S = t[5], t[5] = S ? fc(S, w, n[6]) : w, t[6] = S ? dr(t[5], m) : n[6]), w = n[7], w && (t[7] = w), u & It && (t[8] = t[8] == null ? n[8] : Ae(t[8], n[8])), t[9] == null && (t[9] = n[9]), t[0] = n[0], t[1] = c, t;
      }
      function X_(t) {
        var n = [];
        if (t != null)
          for (var s in Ft(t))
            n.push(s);
        return n;
      }
      function q_(t) {
        return Wo.call(t);
      }
      function Cc(t, n, s) {
        return n = ce(n === i ? t.length - 1 : n, 0), function() {
          for (var u = arguments, c = -1, p = ce(u.length - n, 0), _ = C(p); ++c < p; )
            _[c] = u[n + c];
          c = -1;
          for (var w = C(n + 1); ++c < n; )
            w[c] = u[c];
          return w[n] = s(_), Je(t, this, w);
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
      var Oc = Rc(Qa), Vi = fv || function(t, n) {
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
      function us(t, n) {
        var s = -1, u = t.length, c = u - 1;
        for (n = n === i ? u : n; ++s < n; ) {
          var p = Kl(s, c), _ = t[p];
          t[p] = t[s], t[s] = _;
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
            return Bo.call(t);
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
          n & s[1] && !Po(t, u) && t.push(u);
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
        for (var c = 0, p = 0, _ = C(Yo(u / n)); c < u; )
          _[p++] = pn(t, c, c += n);
        return _;
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
        for (var n = C(t - 1), s = arguments[0], u = t; u--; )
          n[u - 1] = arguments[u];
        return hr(ht(s) ? Ke(s) : [s], xe(n, 1));
      }
      var j_ = bt(function(t, n) {
        return Qt(t) ? Ki(t, xe(n, 1, Qt, !0)) : [];
      }), tm = bt(function(t, n) {
        var s = gn(n);
        return Qt(s) && (s = i), Qt(t) ? Ki(t, xe(n, 1, Qt, !0), et(s, 2)) : [];
      }), em = bt(function(t, n) {
        var s = gn(n);
        return Qt(s) && (s = i), Qt(t) ? Ki(t, xe(n, 1, Qt, !0), i, s) : [];
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
        return t && t.length ? ts(t, et(n, 3), !0, !0) : [];
      }
      function om(t, n) {
        return t && t.length ? ts(t, et(n, 3), !0) : [];
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
        return c < 0 && (c = ce(u + c, 0)), Mo(t, et(n, 3), c);
      }
      function Nc(t, n, s) {
        var u = t == null ? 0 : t.length;
        if (!u)
          return -1;
        var c = u - 1;
        return s !== i && (c = vt(s), c = s < 0 ? ce(u + c, 0) : Ae(c, u - 1)), Mo(t, et(n, 3), c, !0);
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
        return s !== i && (c = vt(s), c = c < 0 ? ce(u + c, 0) : Ae(c, u - 1)), n === n ? kg(t, n, c) : Mo(t, _a, c, !0);
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
          var _ = t[u];
          n(_, u, t) && (s.push(_), c.push(u));
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
        return jo(t, n);
      }
      function Tm(t, n, s) {
        return Yl(t, n, et(s, 2));
      }
      function Am(t, n) {
        var s = t == null ? 0 : t.length;
        if (s) {
          var u = jo(t, n);
          if (u < s && En(t[u], n))
            return u;
        }
        return -1;
      }
      function Cm(t, n) {
        return jo(t, n, !0);
      }
      function Im(t, n, s) {
        return Yl(t, n, et(s, 2), !0);
      }
      function Om(t, n) {
        var s = t == null ? 0 : t.length;
        if (s) {
          var u = jo(t, n, !0) - 1;
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
        return t && t.length ? ts(t, et(n, 3), !1, !0) : [];
      }
      function Lm(t, n) {
        return t && t.length ? ts(t, et(n, 3)) : [];
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
        return Qt(t) ? Ki(t, n) : [];
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
        return rc(t || [], n || [], $i);
      }
      function km(t, n) {
        return rc(t || [], n || [], zi);
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
      function fs(t, n) {
        return n(t);
      }
      var Qm = Jn(function(t) {
        var n = t.length, s = n ? t[0] : 0, u = this.__wrapped__, c = function(p) {
          return Ml(p, t);
        };
        return n > 1 || this.__actions__.length || !(u instanceof xt) || !Zn(s) ? this.thru(c) : (u = u.slice(s, +s + (n ? 1 : 0)), u.__actions__.push({
          func: fs,
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
        for (var n, s = this; s instanceof Vo; ) {
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
            func: fs,
            args: [lu],
            thisArg: i
          }), new hn(n, this.__chain__);
        }
        return this.thru(lu);
      }
      function o0() {
        return nc(this.__wrapped__, this.__actions__);
      }
      var s0 = es(function(t, n, s) {
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
        return xe(as(t, n), 1);
      }
      function h0(t, n) {
        return xe(as(t, n), lt);
      }
      function d0(t, n, s) {
        return s = s === i ? 1 : vt(s), xe(as(t, n), s);
      }
      function $c(t, n) {
        var s = ht(t) ? an : gr;
        return s(t, et(n, 3));
      }
      function Kc(t, n) {
        var s = ht(t) ? Pg : Wa;
        return s(t, et(n, 3));
      }
      var p0 = es(function(t, n, s) {
        Mt.call(t, s) ? t[s].push(n) : Vn(t, s, [n]);
      });
      function g0(t, n, s, u) {
        t = Ge(t) ? t : gi(t), s = s && !u ? vt(s) : 0;
        var c = t.length;
        return s < 0 && (s = ce(c + s, 0)), gs(t) ? s <= c && t.indexOf(n, s) > -1 : !!c && ri(t, n, s) > -1;
      }
      var v0 = bt(function(t, n, s) {
        var u = -1, c = typeof n == "function", p = Ge(t) ? C(t.length) : [];
        return gr(t, function(_) {
          p[++u] = c ? Je(n, _, s) : Gi(_, n, s);
        }), p;
      }), _0 = es(function(t, n, s) {
        Vn(t, s, n);
      });
      function as(t, n) {
        var s = ht(t) ? zt : za;
        return s(t, et(n, 3));
      }
      function m0(t, n, s, u) {
        return t == null ? [] : (ht(n) || (n = n == null ? [] : [n]), s = u ? i : s, ht(s) || (s = s == null ? [] : [s]), ka(t, n, s));
      }
      var b0 = es(function(t, n, s) {
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
        return s(t, ds(et(n, 3)));
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
          return gs(t) ? oi(t) : t.length;
        var n = Ce(t);
        return n == x || n == K ? t.size : Ul(t).length;
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
      }), cs = uv || function() {
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
        var u = N | W;
        if (s.length) {
          var c = dr(s, di(zc));
          u |= nt;
        }
        return kn(n, u, t, s, c);
      });
      function Xc(t, n, s) {
        n = s ? i : n;
        var u = kn(t, q, i, i, i, i, i, n);
        return u.placeholder = Xc.placeholder, u;
      }
      function qc(t, n, s) {
        n = s ? i : n;
        var u = kn(t, B, i, i, i, i, i, n);
        return u.placeholder = qc.placeholder, u;
      }
      function Vc(t, n, s) {
        var u, c, p, _, w, S, P = 0, M = !1, L = !1, X = !0;
        if (typeof t != "function")
          throw new cn(a);
        n = vn(n) || 0, Vt(s) && (M = !!s.leading, L = "maxWait" in s, p = L ? ce(vn(s.maxWait) || 0, n) : p, X = "trailing" in s ? !!s.trailing : X);
        function j(jt) {
          var Sn = u, tr = c;
          return u = c = i, P = jt, _ = t.apply(tr, Sn), _;
        }
        function it(jt) {
          return P = jt, w = Vi(wt, n), M ? j(jt) : _;
        }
        function mt(jt) {
          var Sn = jt - S, tr = jt - P, dh = n - Sn;
          return L ? Ae(dh, p - tr) : dh;
        }
        function ot(jt) {
          var Sn = jt - S, tr = jt - P;
          return S === i || Sn >= n || Sn < 0 || L && tr >= p;
        }
        function wt() {
          var jt = cs();
          if (ot(jt))
            return St(jt);
          w = Vi(wt, mt(jt));
        }
        function St(jt) {
          return w = i, X && u ? j(jt) : (u = c = i, _);
        }
        function tn() {
          w !== i && ic(w), P = 0, u = S = c = w = i;
        }
        function Ne() {
          return w === i ? _ : St(cs());
        }
        function en() {
          var jt = cs(), Sn = ot(jt);
          if (u = arguments, c = this, S = jt, Sn) {
            if (w === i)
              return it(S);
            if (L)
              return ic(w), w = Vi(wt, n), j(S);
          }
          return w === i && (w = Vi(wt, n)), _;
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
      function hs(t, n) {
        if (typeof t != "function" || n != null && typeof n != "function")
          throw new cn(a);
        var s = function() {
          var u = arguments, c = n ? n.apply(this, u) : u[0], p = s.cache;
          if (p.has(c))
            return p.get(c);
          var _ = t.apply(this, u);
          return s.cache = p.set(c, _) || p, _;
        };
        return s.cache = new (hs.Cache || qn)(), s;
      }
      hs.Cache = qn;
      function ds(t) {
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
        return dn(t, A);
      }
      function G0(t, n) {
        return n = typeof n == "function" ? n : i, dn(t, A, n);
      }
      function Y0(t) {
        return dn(t, b | A);
      }
      function z0(t, n) {
        return n = typeof n == "function" ? n : i, dn(t, b | A, n);
      }
      function X0(t, n) {
        return n == null || La(t, n, pe(n));
      }
      function En(t, n) {
        return t === n || t !== t && n !== n;
      }
      var q0 = os(Ll), V0 = os(function(t, n) {
        return t >= n;
      }), $r = Ka(/* @__PURE__ */ function() {
        return arguments;
      }()) ? Ka : function(t) {
        return Jt(t) && Mt.call(t, "callee") && !Ia.call(t, "callee");
      }, ht = C.isArray, k0 = fa ? Ze(fa) : r_;
      function Ge(t) {
        return t != null && ps(t.length) && !Qn(t);
      }
      function Qt(t) {
        return Jt(t) && Ge(t);
      }
      function J0(t) {
        return t === !0 || t === !1 || Jt(t) && Me(t) == Yn;
      }
      var br = av || wu, Z0 = aa ? Ze(aa) : i_;
      function Q0(t) {
        return Jt(t) && t.nodeType === 1 && !ki(t);
      }
      function j0(t) {
        if (t == null)
          return !0;
        if (Ge(t) && (ht(t) || typeof t == "string" || typeof t.splice == "function" || br(t) || pi(t) || $r(t)))
          return !t.length;
        var n = Ce(t);
        if (n == x || n == K)
          return !t.size;
        if (qi(t))
          return !Ul(t).length;
        for (var s in t)
          if (Mt.call(t, s))
            return !1;
        return !0;
      }
      function tb(t, n) {
        return Yi(t, n);
      }
      function eb(t, n, s) {
        s = typeof s == "function" ? s : i;
        var u = s ? s(t, n) : i;
        return u === i ? Yi(t, n, i, s) : !!u;
      }
      function cu(t) {
        if (!Jt(t))
          return !1;
        var n = Me(t);
        return n == fr || n == ur || typeof t.message == "string" && typeof t.name == "string" && !ki(t);
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
      function ps(t) {
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
        return typeof t == "number" || Jt(t) && Me(t) == O;
      }
      function ki(t) {
        if (!Jt(t) || Me(t) != F)
          return !1;
        var n = $o(t);
        if (n === null)
          return !0;
        var s = Mt.call(n, "constructor") && n.constructor;
        return typeof s == "function" && s instanceof s && Bo.call(s) == iv;
      }
      var hu = ha ? Ze(ha) : l_;
      function fb(t) {
        return Jc(t) && t >= -rt && t <= rt;
      }
      var jc = da ? Ze(da) : u_;
      function gs(t) {
        return typeof t == "string" || !ht(t) && Jt(t) && Me(t) == U;
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
      var db = os(Hl), pb = os(function(t, n) {
        return t <= n;
      });
      function th(t) {
        if (!t)
          return [];
        if (Ge(t))
          return gs(t) ? wn(t) : Ke(t);
        if (Li && t[Li])
          return Xg(t[Li]());
        var n = Ce(t), s = n == x ? Cl : n == K ? Fo : gi;
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
        if (qi(n) || Ge(n)) {
          Pn(n, pe(n), t);
          return;
        }
        for (var s in n)
          Mt.call(n, s) && $i(t, s, n[s]);
      }), rh = ci(function(t, n) {
        Pn(n, Ye(n), t);
      }), vs = ci(function(t, n, s, u) {
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
          for (var p = n[s], _ = Ye(p), w = -1, S = _.length; ++w < S; ) {
            var P = _[w], M = t[P];
            (M === i || En(M, li[P]) && !Mt.call(t, P)) && (t[P] = p[P]);
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
        return t == null ? [] : Zo(t, pe(t));
      }
      function Ob(t) {
        return t == null ? [] : Zo(t, Ye(t));
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
        n != null && typeof n.toString != "function" && (n = Wo.call(n)), t[n] = s;
      }, vu(ze)), Pb = gc(function(t, n, s) {
        n != null && typeof n.toString != "function" && (n = Wo.call(n)), Mt.call(t, n) ? t[n].push(s) : t[n] = [s];
      }, et), Mb = bt(Gi);
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
        Qo(t, n, s);
      }), ih = ci(function(t, n, s, u) {
        Qo(t, n, s, u);
      }), Bb = Jn(function(t, n) {
        var s = {};
        if (t == null)
          return s;
        var u = !1;
        n = zt(n, function(p) {
          return p = _r(p, t), u || (u = p.length > 1), p;
        }), Pn(t, jl(t), s), u && (s = dn(s, b | T | A, R_));
        for (var c = n.length; c--; )
          zl(s, n[c]);
        return s;
      });
      function Wb(t, n) {
        return oh(t, ds(et(n)));
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
        return t == null ? t : zi(t, n, s);
      }
      function Kb(t, n, s, u) {
        return u = typeof u == "function" ? u : i, t == null ? t : zi(t, n, s, u);
      }
      var sh = mc(pe), lh = mc(Ye);
      function Gb(t, n, s) {
        var u = ht(t), c = u || br(t) || pi(t);
        if (n = et(n, 4), s == null) {
          var p = t && t.constructor;
          c ? s = u ? new p() : [] : Vt(t) ? s = Qn(p) ? ai($o(t)) : {} : s = {};
        }
        return (c ? an : Rn)(t, function(_, w, S) {
          return n(s, _, w, S);
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
        return is(zo(c), s) + t + is(Yo(c), s);
      }
      function oy(t, n, s) {
        t = Rt(t), n = vt(n);
        var u = n ? oi(t) : 0;
        return n && u < n ? t + is(n - u, s) : t;
      }
      function sy(t, n, s) {
        t = Rt(t), n = vt(n);
        var u = n ? oi(t) : 0;
        return n && u < n ? is(n - u, s) + t : t;
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
        s && Fe(t, n, s) && (n = i), t = Rt(t), n = vs({}, n, u, bc);
        var c = vs({}, n.imports, u.imports, bc), p = pe(c), _ = Al(c, p), w, S, P = 0, M = n.interpolate || Oo, L = "__p += '", X = Il(
          (n.escape || Oo).source + "|" + M.source + "|" + (M === $f ? qp : Oo).source + "|" + (n.evaluate || Oo).source + "|$",
          "g"
        ), j = "//# sourceURL=" + (Mt.call(n, "sourceURL") ? (n.sourceURL + "").replace(/\s/g, " ") : "lodash.templateSources[" + ++Eg + "]") + `
`;
        t.replace(X, function(ot, wt, St, tn, Ne, en) {
          return St || (St = tn), L += t.slice(P, en).replace(tg, Gg), wt && (w = !0, L += `' +
__e(` + wt + `) +
'`), Ne && (S = !0, L += `';
` + Ne + `;
__p += '`), St && (L += `' +
((__t = (` + St + `)) == null ? '' : __t) +
'`), P = en + ot.length, ot;
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
        L = (S ? L.replace(Io, "") : L).replace(Dp, "$1").replace(Rp, "$1;"), L = "function(" + (it || "obj") + `) {
` + (it ? "" : `obj || (obj = {});
`) + "var __t, __p = ''" + (w ? ", __e = _.escape" : "") + (S ? `, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
` : `;
`) + L + `return __p
}`;
        var mt = ch(function() {
          return Ot(p, j + "return " + L).apply(i, _);
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
        var u = wn(t), c = wn(n), p = wa(u, c), _ = xa(u, c) + 1;
        return mr(u, p, _).join("");
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
          var _ = wn(t);
          p = _.length;
        }
        if (s >= p)
          return t;
        var w = s - oi(u);
        if (w < 1)
          return u;
        var S = _ ? mr(_, 0, w).join("") : t.slice(0, w);
        if (c === i)
          return S + u;
        if (_ && (w += S.length - w), hu(c)) {
          if (t.slice(w).search(c)) {
            var P, M = S;
            for (c.global || (c = Il(c.source, Rt(Kf.exec(c)) + "g")), c.lastIndex = 0; P = c.exec(M); )
              var L = P.index;
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
        return Jv(dn(t, b));
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
        return Ya(typeof t == "function" ? t : dn(t, b));
      }
      function Oy(t) {
        return Xa(dn(t, b));
      }
      function Dy(t, n) {
        return qa(t, dn(n, b));
      }
      var Ry = bt(function(t, n) {
        return function(s) {
          return Gi(s, t, n);
        };
      }), Py = bt(function(t, n) {
        return function(s) {
          return Gi(t, s, n);
        };
      });
      function mu(t, n, s) {
        var u = pe(n), c = Zo(n, u);
        s == null && !(Vt(n) && (c.length || !u.length)) && (s = n, n = t, t = this, c = Zo(n, pe(n)));
        var p = !(Vt(s) && "chain" in s) || !!s.chain, _ = Qn(t);
        return an(c, function(w) {
          var S = n[w];
          t[w] = S, _ && (t.prototype[w] = function() {
            var P = this.__chain__;
            if (p || P) {
              var M = t(this.__wrapped__), L = M.__actions__ = Ke(this.__actions__);
              return L.push({ func: S, args: arguments, thisArg: t }), M.__chain__ = P, M;
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
      var qy = rs(function(t, n) {
        return t + n;
      }, 0), Vy = Zl("ceil"), ky = rs(function(t, n) {
        return t / n;
      }, 1), Jy = Zl("floor");
      function Zy(t) {
        return t && t.length ? Jo(t, ze, Ll) : i;
      }
      function Qy(t, n) {
        return t && t.length ? Jo(t, et(n, 2), Ll) : i;
      }
      function jy(t) {
        return ma(t, ze);
      }
      function tw(t, n) {
        return ma(t, et(n, 2));
      }
      function ew(t) {
        return t && t.length ? Jo(t, ze, Hl) : i;
      }
      function nw(t, n) {
        return t && t.length ? Jo(t, et(n, 2), Hl) : i;
      }
      var rw = rs(function(t, n) {
        return t * n;
      }, 1), iw = Zl("round"), ow = rs(function(t, n) {
        return t - n;
      }, 0);
      function sw(t) {
        return t && t.length ? Sl(t, ze) : 0;
      }
      function lw(t, n) {
        return t && t.length ? Sl(t, et(n, 2)) : 0;
      }
      return d.after = O0, d.ary = Gc, d.assign = vb, d.assignIn = rh, d.assignInWith = vs, d.assignWith = _b, d.at = mb, d.before = Yc, d.bind = fu, d.bindAll = Ey, d.bindKey = zc, d.castArray = $0, d.chain = Hc, d.chunk = J_, d.compact = Z_, d.concat = Q_, d.cond = Sy, d.conforms = Ty, d.constant = vu, d.countBy = s0, d.create = bb, d.curry = Xc, d.curryRight = qc, d.debounce = Vc, d.defaults = yb, d.defaultsDeep = wb, d.defer = D0, d.delay = R0, d.difference = j_, d.differenceBy = tm, d.differenceWith = em, d.drop = nm, d.dropRight = rm, d.dropRightWhile = im, d.dropWhile = om, d.fill = sm, d.filter = u0, d.flatMap = c0, d.flatMapDeep = h0, d.flatMapDepth = d0, d.flatten = Lc, d.flattenDeep = lm, d.flattenDepth = um, d.flip = P0, d.flow = Cy, d.flowRight = Iy, d.fromPairs = fm, d.functions = Ib, d.functionsIn = Ob, d.groupBy = p0, d.initial = cm, d.intersection = hm, d.intersectionBy = dm, d.intersectionWith = pm, d.invert = Rb, d.invertBy = Pb, d.invokeMap = v0, d.iteratee = _u, d.keyBy = _0, d.keys = pe, d.keysIn = Ye, d.map = as, d.mapKeys = Fb, d.mapValues = Nb, d.matches = Oy, d.matchesProperty = Dy, d.memoize = hs, d.merge = Lb, d.mergeWith = ih, d.method = Ry, d.methodOf = Py, d.mixin = mu, d.negate = ds, d.nthArg = Fy, d.omit = Bb, d.omitBy = Wb, d.once = M0, d.orderBy = m0, d.over = Ny, d.overArgs = F0, d.overEvery = Ly, d.overSome = By, d.partial = au, d.partialRight = kc, d.partition = b0, d.pick = Ub, d.pickBy = oh, d.property = hh, d.propertyOf = Wy, d.pull = mm, d.pullAll = Wc, d.pullAllBy = bm, d.pullAllWith = ym, d.pullAt = wm, d.range = Uy, d.rangeRight = Hy, d.rearg = N0, d.reject = x0, d.remove = xm, d.rest = L0, d.reverse = lu, d.sampleSize = S0, d.set = $b, d.setWith = Kb, d.shuffle = T0, d.slice = Em, d.sortBy = I0, d.sortedUniq = Dm, d.sortedUniqBy = Rm, d.split = cy, d.spread = B0, d.tail = Pm, d.take = Mm, d.takeRight = Fm, d.takeRightWhile = Nm, d.takeWhile = Lm, d.tap = Zm, d.throttle = W0, d.thru = fs, d.toArray = th, d.toPairs = sh, d.toPairsIn = lh, d.toPath = zy, d.toPlainObject = nh, d.transform = Gb, d.unary = U0, d.union = Bm, d.unionBy = Wm, d.unionWith = Um, d.uniq = Hm, d.uniqBy = $m, d.uniqWith = Km, d.unset = Yb, d.unzip = uu, d.unzipWith = Uc, d.update = zb, d.updateWith = Xb, d.values = gi, d.valuesIn = qb, d.without = Gm, d.words = ah, d.wrap = H0, d.xor = Ym, d.xorBy = zm, d.xorWith = Xm, d.zip = qm, d.zipObject = Vm, d.zipObjectDeep = km, d.zipWith = Jm, d.entries = sh, d.entriesIn = lh, d.extend = rh, d.extendWith = vs, mu(d, d), d.add = qy, d.attempt = ch, d.camelCase = Zb, d.capitalize = uh, d.ceil = Vy, d.clamp = Vb, d.clone = K0, d.cloneDeep = Y0, d.cloneDeepWith = z0, d.cloneWith = G0, d.conformsTo = X0, d.deburr = fh, d.defaultTo = Ay, d.divide = ky, d.endsWith = Qb, d.eq = En, d.escape = jb, d.escapeRegExp = ty, d.every = l0, d.find = f0, d.findIndex = Fc, d.findKey = xb, d.findLast = a0, d.findLastIndex = Nc, d.findLastKey = Eb, d.floor = Jy, d.forEach = $c, d.forEachRight = Kc, d.forIn = Sb, d.forInRight = Tb, d.forOwn = Ab, d.forOwnRight = Cb, d.get = du, d.gt = q0, d.gte = V0, d.has = Db, d.hasIn = pu, d.head = Bc, d.identity = ze, d.includes = g0, d.indexOf = am, d.inRange = kb, d.invoke = Mb, d.isArguments = $r, d.isArray = ht, d.isArrayBuffer = k0, d.isArrayLike = Ge, d.isArrayLikeObject = Qt, d.isBoolean = J0, d.isBuffer = br, d.isDate = Z0, d.isElement = Q0, d.isEmpty = j0, d.isEqual = tb, d.isEqualWith = eb, d.isError = cu, d.isFinite = nb, d.isFunction = Qn, d.isInteger = Jc, d.isLength = ps, d.isMap = Zc, d.isMatch = rb, d.isMatchWith = ib, d.isNaN = ob, d.isNative = sb, d.isNil = ub, d.isNull = lb, d.isNumber = Qc, d.isObject = Vt, d.isObjectLike = Jt, d.isPlainObject = ki, d.isRegExp = hu, d.isSafeInteger = fb, d.isSet = jc, d.isString = gs, d.isSymbol = je, d.isTypedArray = pi, d.isUndefined = ab, d.isWeakMap = cb, d.isWeakSet = hb, d.join = gm, d.kebabCase = ey, d.last = gn, d.lastIndexOf = vm, d.lowerCase = ny, d.lowerFirst = ry, d.lt = db, d.lte = pb, d.max = Zy, d.maxBy = Qy, d.mean = jy, d.meanBy = tw, d.min = ew, d.minBy = nw, d.stubArray = yu, d.stubFalse = wu, d.stubObject = $y, d.stubString = Ky, d.stubTrue = Gy, d.multiply = rw, d.nth = _m, d.noConflict = My, d.noop = bu, d.now = cs, d.pad = iy, d.padEnd = oy, d.padStart = sy, d.parseInt = ly, d.random = Jb, d.reduce = y0, d.reduceRight = w0, d.repeat = uy, d.replace = fy, d.result = Hb, d.round = iw, d.runInContext = E, d.sample = E0, d.size = A0, d.snakeCase = ay, d.some = C0, d.sortedIndex = Sm, d.sortedIndexBy = Tm, d.sortedIndexOf = Am, d.sortedLastIndex = Cm, d.sortedLastIndexBy = Im, d.sortedLastIndexOf = Om, d.startCase = hy, d.startsWith = dy, d.subtract = ow, d.sum = sw, d.sumBy = lw, d.template = py, d.times = Yy, d.toFinite = jn, d.toInteger = vt, d.toLength = eh, d.toLower = gy, d.toNumber = vn, d.toSafeInteger = gb, d.toString = Rt, d.toUpper = vy, d.trim = _y, d.trimEnd = my, d.trimStart = by, d.truncate = yy, d.unescape = wy, d.uniqueId = Xy, d.upperCase = xy, d.upperFirst = gu, d.each = $c, d.eachRight = Kc, d.first = Bc, mu(d, function() {
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
          return Gi(s, t, n);
        });
      }), xt.prototype.reject = function(t) {
        return this.filter(ds(et(t)));
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
          var _ = this.__wrapped__, w = u ? [1] : arguments, S = _ instanceof xt, P = w[0], M = S || ht(_), L = function(wt) {
            var St = c.apply(d, hr([wt], w));
            return u && X ? St[0] : St;
          };
          M && s && typeof P == "function" && P.length != 1 && (S = M = !1);
          var X = this.__chain__, j = !!this.__actions__.length, it = p && !X, mt = S && !j;
          if (!p && M) {
            _ = mt ? _ : new xt(this);
            var ot = t.apply(_, w);
            return ot.__actions__.push({ func: fs, args: [L], thisArg: i }), new hn(ot, X);
          }
          return it && mt ? t.apply(this, w) : (ot = this.thru(L), it ? u ? ot.value()[0] : ot.value() : ot);
        });
      }), an(["pop", "push", "shift", "sort", "splice", "unshift"], function(t) {
        var n = No[t], s = /^(?:push|sort|unshift)$/.test(t) ? "tap" : "thru", u = /^(?:pop|shift)$/.test(t);
        d.prototype[t] = function() {
          var c = arguments;
          if (u && !this.__chain__) {
            var p = this.value();
            return n.apply(ht(p) ? p : [], c);
          }
          return this[s](function(_) {
            return n.apply(ht(_) ? _ : [], c);
          });
        };
      }), Rn(xt.prototype, function(t, n) {
        var s = d[n];
        if (s) {
          var u = s.name + "";
          Mt.call(fi, u) || (fi[u] = []), fi[u].push({ name: n, func: s });
        }
      }), fi[ns(i, W).name] = [{
        name: "wrapper",
        func: i
      }], xt.prototype.clone = wv, xt.prototype.reverse = xv, xt.prototype.value = Ev, d.prototype.at = Qm, d.prototype.chain = jm, d.prototype.commit = t0, d.prototype.next = e0, d.prototype.plant = r0, d.prototype.reverse = i0, d.prototype.toJSON = d.prototype.valueOf = d.prototype.value = o0, d.prototype.first = d.prototype.head, Li && (d.prototype[Li] = n0), d;
    }, si = tv();
    Mr ? ((Mr.exports = si)._ = si, _l._ = si) : we._ = si;
  }).call(Qi);
})(zs, zs.exports);
var Es = zs.exports;
function ho(e) {
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
  const o = (A) => i.findIndex((I) => I.isEqualNode(A)), l = (A) => i[o(A) - 1], f = (A) => i[o(A) + 1], a = (A, I) => {
    if (A) {
      const R = f(A);
      if (R instanceof HTMLElement)
        return R.focus(), I && qh(R), R;
    } else {
      const R = i[0];
      R instanceof HTMLElement && R.focus();
    }
  }, h = (A, I) => {
    if (A.target instanceof HTMLElement) {
      const R = A.target, N = ho(R), W = I === "up" ? l(R) : f(R);
      W instanceof HTMLElement && (A.preventDefault(), W.focus(), Xh(W, N));
    }
  };
  return {
    getPreviousElementSibling: l,
    getNextElementSibling: f,
    getCurrentPositionInNavigationList: o,
    focusNextTask: a,
    focusTaskById: (A) => {
      const I = i.find((R) => {
        var N;
        return R instanceof HTMLElement && ((N = R.dataset) == null ? void 0 : N.id) == A;
      });
      I instanceof HTMLElement && I.focus();
    },
    onUp: (A) => {
      h(A, "up");
    },
    onDown: (A) => {
      h(A, "down");
    },
    onLeft: (A, I) => {
      if (A.target instanceof HTMLElement) {
        const R = A.target;
        if (ho(R) === 0) {
          const N = l(R);
          N instanceof HTMLElement && (A.preventDefault(), I && (N.innerText += I), N.focus(), qh(N), I && Xh(N, N.innerText.length - I.length));
        }
      }
    },
    onRight: (A) => {
      if (A.target instanceof HTMLElement) {
        const I = A.target;
        if (ho(I) === I.innerText.length) {
          const R = f(I);
          R instanceof HTMLElement && (A.preventDefault(), R.focus());
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
      h && h(), f = Es.pick(N, Object.keys(f)), a.value = { ...f }, h = In(a, async () => {
        const W = Es.reduce(a.value, (k, q, B) => Es.isEqual(q, f[B]) ? k : [...k, B], []);
        W.length && o("update", i.task, Es.pick(a.value, W));
      }, {
        deep: !0
      });
    }, {
      immediate: !0
    });
    const g = async (N) => {
      N.target instanceof HTMLElement && (N.target.innerText = N.target.innerText.trim(), a.value.name = N.target.innerText), o("blur", i.task, a.value);
    }, v = (N) => {
      var k;
      N.preventDefault();
      const W = (k = N.clipboardData) == null ? void 0 : k.getData("text/plain");
      W && document.execCommand("insertText", !1, W);
    }, m = (N) => {
      N.target instanceof HTMLElement && ho(N.target) === 0 && (I(N, N.target.innerText.trim()), o("delete", i.task));
    }, b = async (N) => {
      if (N.preventDefault(), N.target instanceof HTMLElement) {
        const W = N.target, k = W.innerText.trim(), q = ho(W), B = {};
        q !== 0 && (B.currentName = k.substring(0, q).trim(), B.newName = k.substring(q, k.length).trim(), W.innerText = B.currentName), o("insert", i.task, B);
      }
    }, {
      onUp: T,
      onDown: A,
      onLeft: I,
      onRight: R
    } = kr("listNavigation");
    return (N, W) => {
      var k;
      return Hn(), Jr("div", Qx, [
        i.sortable ? (Hn(), Jr("div", jx, W[6] || (W[6] = [
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
              _i(b, ["enter"]),
              W[0] || (W[0] = _i(
                //@ts-ignore
                (...q) => Ve(I) && Ve(I)(...q),
                ["left"]
              )),
              W[1] || (W[1] = _i(
                //@ts-ignore
                (...q) => Ve(T) && Ve(T)(...q),
                ["up"]
              )),
              W[2] || (W[2] = _i(
                //@ts-ignore
                (...q) => Ve(R) && Ve(R)(...q),
                ["right"]
              )),
              W[3] || (W[3] = _i(
                //@ts-ignore
                (...q) => Ve(A) && Ve(A)(...q),
                ["down"]
              )),
              _i(m, ["delete"])
            ],
            onBlur: g,
            onFocus: W[4] || (W[4] = (q) => l.value = !0),
            onFocusout: W[5] || (W[5] = (q) => l.value = !1),
            onPaste: v,
            textContent: qu(i.task.name)
          }, null, 40, nE)
        ]),
        (k = i.task.extended_data) != null && k.comments_count ? (Hn(), Jr("div", rE, [
          W[7] || (W[7] = Ln("i", { class: "far fa-comment" }, null, -1)),
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
var sr = ir(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i), Ao = ir(/Edge/i), kh = ir(/firefox/i), po = ir(/safari/i) && !ir(/chrome/i) && !ir(/android/i), _p = ir(/iP(ad|od|hone)/i), mp = ir(/chrome/i) && ir(/android/i), bp = {
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
    var f, a, h, g, v, m, b;
    if (e !== window && e.parentNode && e !== $n() ? (f = e.getBoundingClientRect(), a = f.top, h = f.left, g = f.bottom, v = f.right, m = f.height, b = f.width) : (a = 0, h = 0, g = window.innerHeight, v = window.innerWidth, m = window.innerHeight, b = window.innerWidth), (r || i) && e !== window && (l = l || e.parentNode, !sr))
      do
        if (l && l.getBoundingClientRect && (Q(l, "transform") !== "none" || i && Q(l, "position") !== "static")) {
          var T = l.getBoundingClientRect();
          a -= T.top + parseInt(Q(l, "border-top-width")), h -= T.left + parseInt(Q(l, "border-left-width")), g = a + f.height, v = h + f.width;
          break;
        }
      while (l = l.parentNode);
    if (o && e !== window) {
      var A = Zr(l || e), I = A && A.a, R = A && A.d;
      A && (a /= R, h /= I, b /= I, m /= R, g = a + m, v = h + b);
    }
    return {
      top: a,
      left: h,
      bottom: g,
      right: v,
      width: b,
      height: m
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
function Fi(e, r, i, o) {
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
var go;
function wp(e, r) {
  return function() {
    if (!go) {
      var i = arguments, o = this;
      i.length === 1 ? e.call(o, i[0]) : e.apply(o, i), go = setTimeout(function() {
        go = void 0;
      }, r);
    }
  };
}
function _E() {
  clearTimeout(go), go = void 0;
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
        var g = 0, v = h.target, m = v.fromRect, b = $t(v), T = v.prevFromRect, A = v.prevToRect, I = h.rect, R = Zr(v, !0);
        R && (b.top -= R.f, b.left -= R.e), v.toRect = b, v.thisAnimationDuration && Bu(T, b) && !Bu(m, b) && // Make sure animatingRect is on line between toRect & fromRect
        (I.top - b.top) / (I.left - b.left) === (m.top - b.top) / (m.left - b.left) && (g = yE(I, T, A, l.options)), Bu(b, m) || (v.prevFromRect = m, v.prevToRect = b, g || (g = l.options.animation), l.animate(v, I, b, g)), g && (f = !0, a = Math.max(a, g), clearTimeout(v.animationResetTimer), v.animationResetTimer = setTimeout(function() {
          v.animationTime = 0, v.prevFromRect = null, v.fromRect = null, v.prevToRect = null, v.thisAnimationDuration = null;
        }, g), v.thisAnimationDuration = g);
      }), clearTimeout(r), f ? r = setTimeout(function() {
        typeof o == "function" && o();
      }, a) : typeof o == "function" && o(), e = [];
    },
    animate: function(o, l, f, a) {
      if (a) {
        Q(o, "transition", ""), Q(o, "transform", "");
        var h = Zr(this.el), g = h && h.a, v = h && h.d, m = (l.left - f.left) / (g || 1), b = (l.top - f.top) / (v || 1);
        o.animatingX = !!m, o.animatingY = !!b, Q(o, "transform", "translate3d(" + m + "px," + b + "px,0)"), this.forRepaintDummy = bE(o), Q(o, "transition", "transform " + a + "ms" + (this.options.easing ? " " + this.options.easing : "")), Q(o, "transform", "translate3d(0,0,0)"), typeof o.animated == "number" && clearTimeout(o.animated), o.animated = setTimeout(function() {
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
}, Co = {
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
function ro(e) {
  var r = e.sortable, i = e.rootEl, o = e.name, l = e.targetEl, f = e.cloneEl, a = e.toEl, h = e.fromEl, g = e.oldIndex, v = e.newIndex, m = e.oldDraggableIndex, b = e.newDraggableIndex, T = e.originalEvent, A = e.putSortable, I = e.extraEventProperties;
  if (r = r || i && i[De], !!r) {
    var R, N = r.options, W = "on" + o.charAt(0).toUpperCase() + o.substr(1);
    window.CustomEvent && !sr && !Ao ? R = new CustomEvent(o, {
      bubbles: !0,
      cancelable: !0
    }) : (R = document.createEvent("Event"), R.initEvent(o, !0, !0)), R.to = a || i, R.from = h || i, R.item = l || i, R.clone = f, R.oldIndex = g, R.newIndex = v, R.oldDraggableIndex = m, R.newDraggableIndex = b, R.originalEvent = T, R.pullMode = A ? A.lastPutMode : void 0;
    var k = Gn(Gn({}, I), Co.getEventProperties(o, r));
    for (var q in k)
      R[q] = k[q];
    i && i.dispatchEvent(R), N[W] && N[W].call(r, R);
  }
}
var wE = ["evt"], Xe = function(r, i) {
  var o = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, l = o.evt, f = lE(o, wE);
  Co.pluginEvent.bind(st)(r, i, Gn({
    dragEl: Y,
    parentEl: Zt,
    ghostEl: ct,
    rootEl: Xt,
    nextEl: qr,
    lastDownEl: Ns,
    cloneEl: kt,
    cloneHidden: Tr,
    dragStarted: io,
    putSortable: Ee,
    activeSortable: st.active,
    originalEvent: l,
    oldIndex: Si,
    oldDraggableIndex: vo,
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
  ro(Gn({
    putSortable: Ee,
    cloneEl: kt,
    targetEl: Y,
    rootEl: Xt,
    oldIndex: Si,
    oldDraggableIndex: vo,
    newIndex: on,
    newDraggableIndex: Er
  }, e));
}
var Y, Zt, ct, Xt, qr, Ns, kt, Tr, Si, on, vo, Er, Ss, Ee, Ei = !1, qs = !1, Vs = [], Yr, Tn, Hu, $u, td, ed, io, yi, _o, mo = !1, Ts = !1, Ls, Ie, Ku = [], uf = !1, ks = [], fl = typeof document < "u", As = _p, nd = Ao || sr ? "cssFloat" : "float", xE = fl && !mp && !_p && "draggable" in document.createElement("div"), Sp = function() {
  if (fl) {
    if (sr)
      return !1;
    var e = document.createElement("x");
    return e.style.cssText = "pointer-events:auto", e.style.pointerEvents === "auto";
  }
}(), Tp = function(r, i) {
  var o = Q(r), l = parseInt(o.width) - parseInt(o.paddingLeft) - parseInt(o.paddingRight) - parseInt(o.borderLeftWidth) - parseInt(o.borderRightWidth), f = Fi(r, 0, i), a = Fi(r, 1, i), h = f && Q(f), g = a && Q(a), v = h && parseInt(h.marginLeft) + parseInt(h.marginRight) + $t(f).width, m = g && parseInt(g.marginLeft) + parseInt(g.marginRight) + $t(a).width;
  if (o.display === "flex")
    return o.flexDirection === "column" || o.flexDirection === "column-reverse" ? "vertical" : "horizontal";
  if (o.display === "grid")
    return o.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
  if (f && h.float && h.float !== "none") {
    var b = h.float === "left" ? "left" : "right";
    return a && (g.clear === "both" || g.clear === b) ? "vertical" : "horizontal";
  }
  return f && (h.display === "block" || h.display === "flex" || h.display === "table" || h.display === "grid" || v >= l && o[nd] === "none" || a && o[nd] === "none" && v + m > l) ? "vertical" : "horizontal";
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
    return function(h, g, v, m) {
      var b = h.options.group.name && g.options.group.name && h.options.group.name === g.options.group.name;
      if (f == null && (a || b))
        return !0;
      if (f == null || f === !1)
        return !1;
      if (a && f === "clone")
        return f;
      if (typeof f == "function")
        return i(f(h, g, v, m), a)(h, g, v, m);
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
    supportPointer: st.supportPointer !== !1 && "PointerEvent" in window && !po,
    emptyInsertThreshold: 5
  };
  Co.initializePlugins(this, e, i);
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
      var i = this, o = this.el, l = this.options, f = l.preventOnFilter, a = r.type, h = r.touches && r.touches[0] || r.pointerType && r.pointerType === "touch" && r, g = (h || r).target, v = r.target.shadowRoot && (r.path && r.path[0] || r.composedPath && r.composedPath()[0]) || g, m = l.filter;
      if (ME(o), !Y && !(/mousedown|pointerdown/.test(a) && r.button !== 0 || l.disabled) && !v.isContentEditable && !(!this.nativeDraggable && po && g && g.tagName.toUpperCase() === "SELECT") && (g = _n(g, l.draggable, o, !1), !(g && g.animated) && Ns !== g)) {
        if (Si = se(g), vo = se(g, l.draggable), typeof m == "function") {
          if (m.call(this, r, g, this)) {
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
        } else if (m && (m = m.split(",").some(function(b) {
          if (b = _n(v, b.trim(), o, !1), b)
            return Be({
              sortable: i,
              rootEl: b,
              name: "filter",
              targetEl: g,
              fromEl: o,
              toEl: o
            }), Xe("filter", i, {
              evt: r
            }), !0;
        }), m)) {
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
      if (Xt = f, Y = o, Zt = Y.parentNode, qr = Y.nextSibling, Ns = o, Ss = a.group, st.dragged = Y, Yr = {
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
      }, a.ignore.split(",").forEach(function(m) {
        yp(Y, m.trim(), Gu);
      }), Et(h, "dragover", zr), Et(h, "mousemove", zr), Et(h, "touchmove", zr), Et(h, "mouseup", l._onDrop), Et(h, "touchend", l._onDrop), Et(h, "touchcancel", l._onDrop), kh && this.nativeDraggable && (this.options.touchStartThreshold = 4, Y.draggable = !0), Xe("delayStart", this, {
        evt: r
      }), a.delay && (!a.delayOnTouchOnly || i) && (!this.nativeDraggable || !(Ao || sr))) {
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
      var i = this.options, o = i.fallbackTolerance, l = i.fallbackOffset, f = r.touches ? r.touches[0] : r, a = ct && Zr(ct, !0), h = ct && a && a.a, g = ct && a && a.d, v = As && Ie && Qh(Ie), m = (f.clientX - Yr.clientX + l.x) / (h || 1) + (v ? v[0] - Ku[0] : 0) / (h || 1), b = (f.clientY - Yr.clientY + l.y) / (g || 1) + (v ? v[1] - Ku[1] : 0) / (g || 1);
      if (!st.active && !Ei) {
        if (o && Math.max(Math.abs(f.clientX - this._lastX), Math.abs(f.clientY - this._lastY)) < o)
          return;
        this._onDragStart(r, !0);
      }
      if (ct) {
        a ? (a.e += m - (Hu || 0), a.f += b - ($u || 0)) : a = {
          a: 1,
          b: 0,
          c: 0,
          d: 1,
          e: m,
          f: b
        };
        var T = "matrix(".concat(a.a, ",").concat(a.b, ",").concat(a.c, ",").concat(a.d, ",").concat(a.e, ",").concat(a.f, ")");
        Q(ct, "webkitTransform", T), Q(ct, "mozTransform", T), Q(ct, "msTransform", T), Q(ct, "transform", T), Hu = m, $u = b, Tn = f;
      }
      r.cancelable && r.preventDefault();
    }
  },
  _appendGhost: function() {
    if (!ct) {
      var r = this.options.fallbackOnBody ? document.body : Xt, i = $t(Y, !0, As, !0, r), o = this.options;
      if (As) {
        for (Ie = r; Q(Ie, "position") === "static" && Q(Ie, "transform") === "none" && Ie !== document; )
          Ie = Ie.parentNode;
        Ie !== document.body && Ie !== document.documentElement ? (Ie === document && (Ie = $n()), i.top += Ie.scrollTop, i.left += Ie.scrollLeft) : Ie = $n(), Ku = Qh(Ie);
      }
      ct = Y.cloneNode(!0), oe(ct, o.ghostClass, !1), oe(ct, o.fallbackClass, !0), oe(ct, o.dragClass, !0), Q(ct, "transition", ""), Q(ct, "transform", ""), Q(ct, "box-sizing", "border-box"), Q(ct, "margin", 0), Q(ct, "top", i.top), Q(ct, "left", i.left), Q(ct, "width", i.width), Q(ct, "height", i.height), Q(ct, "opacity", "0.8"), Q(ct, "position", As ? "absolute" : "fixed"), Q(ct, "zIndex", "100000"), Q(ct, "pointerEvents", "none"), st.ghost = ct, r.appendChild(ct), Q(ct, "transform-origin", td / parseInt(ct.style.width) * 100 + "% " + ed / parseInt(ct.style.height) * 100 + "%");
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
    }), !i && oe(Y, f.dragClass, !0), i ? (qs = !0, o._loopId = setInterval(o._emulateDragOver, 50)) : (yt(document, "mouseup", o._onDrop), yt(document, "touchend", o._onDrop), yt(document, "touchcancel", o._onDrop), l && (l.effectAllowed = "move", f.setData && f.setData.call(o, l, Y)), Et(document, "drop", o), Q(Y, "transform", "translateZ(0)")), Ei = !0, o._dragStartId = Bs(o._dragStarted.bind(o, i, r)), Et(document, "selectstart", o), io = !0, po && Q(document.body, "user-select", "none");
  },
  // Returns true - if no further action is needed (either inserted or another condition)
  _onDragOver: function(r) {
    var i = this.el, o = r.target, l, f, a, h = this.options, g = h.group, v = st.active, m = Ss === g, b = h.sort, T = Ee || v, A, I = this, R = !1;
    if (uf)
      return;
    function N(rt, ve) {
      Xe(rt, I, Gn({
        evt: r,
        isOwner: m,
        axis: A ? "vertical" : "horizontal",
        revert: a,
        dragRect: l,
        targetRect: f,
        canSort: b,
        fromSortable: T,
        target: o,
        completed: k,
        onMove: function(Yt, ee) {
          return Cs(Xt, i, Y, l, Yt, $t(Yt), r, ee);
        },
        changed: q
      }, ve));
    }
    function W() {
      N("dragOverAnimationCapture"), I.captureAnimationState(), I !== T && T.captureAnimationState();
    }
    function k(rt) {
      return N("dragOverCompleted", {
        insertion: rt
      }), rt && (m ? v._hideClone() : v._showClone(I), I !== T && (oe(Y, Ee ? Ee.options.ghostClass : v.options.ghostClass, !1), oe(Y, h.ghostClass, !0)), Ee !== I && I !== st.active ? Ee = I : I === st.active && Ee && (Ee = null), T === I && (I._ignoreWhileAnimating = o), I.animateAll(function() {
        N("dragOverAnimationComplete"), I._ignoreWhileAnimating = null;
      }), I !== T && (T.animateAll(), T._ignoreWhileAnimating = null)), (o === Y && !Y.animated || o === i && !o.animated) && (yi = null), !h.dragoverBubble && !r.rootEl && o !== document && (Y.parentNode[De]._isOutsideThisEl(r.target), !rt && zr(r)), !h.dragoverBubble && r.stopPropagation && r.stopPropagation(), R = !0;
    }
    function q() {
      on = se(Y), Er = se(Y, h.draggable), Be({
        sortable: I,
        name: "change",
        toEl: i,
        newIndex: on,
        newDraggableIndex: Er,
        originalEvent: r
      });
    }
    if (r.preventDefault !== void 0 && r.cancelable && r.preventDefault(), o = _n(o, h.draggable, i, !0), N("dragOver"), st.eventCanceled)
      return R;
    if (Y.contains(r.target) || o.animated && o.animatingX && o.animatingY || I._ignoreWhileAnimating === o)
      return k(!1);
    if (qs = !1, v && !h.disabled && (m ? b || (a = Zt !== Xt) : Ee === this || (this.lastPutMode = Ss.checkPull(this, v, Y, r)) && g.checkPut(this, v, Y, r))) {
      if (A = this._getDirection(r, o) === "vertical", l = $t(Y), N("dragOverValid"), st.eventCanceled)
        return R;
      if (a)
        return Zt = Xt, W(), this._hideClone(), N("revert"), st.eventCanceled || (qr ? Xt.insertBefore(Y, qr) : Xt.appendChild(Y)), k(!0);
      var B = Nf(i, h.draggable);
      if (!B || OE(r, A, this) && !B.animated) {
        if (B === Y)
          return k(!1);
        if (B && i === r.target && (o = B), o && (f = $t(o)), Cs(Xt, i, Y, l, o, f, r, !!o) !== !1)
          return W(), B && B.nextSibling ? i.insertBefore(Y, B.nextSibling) : i.appendChild(Y), Zt = i, q(), k(!0);
      } else if (B && IE(r, A, this)) {
        var nt = Fi(i, 0, h, !0);
        if (nt === Y)
          return k(!1);
        if (o = nt, f = $t(o), Cs(Xt, i, Y, l, o, f, r, !1) !== !1)
          return W(), i.insertBefore(Y, nt), Zt = i, q(), k(!0);
      } else if (o.parentNode === i) {
        f = $t(o);
        var Tt = 0, It, Kt = Y.parentNode !== i, Lt = !EE(Y.animated && Y.toRect || l, o.animated && o.toRect || f, A), te = A ? "top" : "left", fe = Zh(o, "top", "top") || Zh(Y, "top", "top"), ge = fe ? fe.scrollTop : void 0;
        yi !== o && (It = f[te], mo = !1, Ts = !Lt && h.invertSwap || Kt), Tt = DE(r, o, f, A, Lt ? 1 : h.swapThreshold, h.invertedSwapThreshold == null ? h.swapThreshold : h.invertedSwapThreshold, Ts, yi === o);
        var Gt;
        if (Tt !== 0) {
          var he = se(Y);
          do
            he -= Tt, Gt = Zt.children[he];
          while (Gt && (Q(Gt, "display") === "none" || Gt === ct));
        }
        if (Tt === 0 || Gt === o)
          return k(!1);
        yi = o, _o = Tt;
        var Te = o.nextElementSibling, gt = !1;
        gt = Tt === 1;
        var lt = Cs(Xt, i, Y, l, o, f, r, gt);
        if (lt !== !1)
          return (lt === 1 || lt === -1) && (gt = lt === 1), uf = !0, setTimeout(CE, 30), W(), gt && !Te ? i.appendChild(Y) : o.parentNode.insertBefore(Y, gt ? Te : o), fe && xp(fe, 0, ge - fe.scrollTop), Zt = Y.parentNode, It !== void 0 && !Ts && (Ls = Math.abs(It - $t(o)[te])), q(), k(!0);
      }
      if (i.contains(Y))
        return k(!1);
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
    Ei = !1, Ts = !1, mo = !1, clearInterval(this._loopId), clearTimeout(this._dragStartTimer), ff(this.cloneId), ff(this._dragStartId), this.nativeDraggable && (yt(document, "drop", this), yt(i, "dragstart", this._onDragStart)), this._offMoveEvents(), this._offUpEvents(), po && Q(document.body, "user-select", ""), Q(Y, "transform", ""), r && (io && (r.cancelable && r.preventDefault(), !o.dropBubble && r.stopPropagation()), ct && ct.parentNode && ct.parentNode.removeChild(ct), (Xt === Zt || Ee && Ee.lastPutMode !== "clone") && kt && kt.parentNode && kt.parentNode.removeChild(kt), Y && (this.nativeDraggable && yt(Y, "dragend", this), Gu(Y), Y.style["will-change"] = "", io && !Ei && oe(Y, Ee ? Ee.options.ghostClass : this.options.ghostClass, !1), oe(Y, this.options.chosenClass, !1), Be({
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
    })), st.active && ((on == null || on === -1) && (on = Si, Er = vo), Be({
      sortable: this,
      name: "end",
      toEl: Zt,
      originalEvent: r
    }), this.save()))), this._nulling();
  },
  _nulling: function() {
    Xe("nulling", this), Xt = Y = Zt = ct = qr = kt = Ns = Tr = Yr = Tn = io = on = Er = Si = vo = yi = _o = Ee = Ss = st.dragged = st.ghost = st.clone = st.active = null, ks.forEach(function(r) {
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
    var l = Co.modifyOption(this, r, i);
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
function Cs(e, r, i, o, l, f, a, h) {
  var g, v = e[De], m = v.options.onMove, b;
  return window.CustomEvent && !sr && !Ao ? g = new CustomEvent("move", {
    bubbles: !0,
    cancelable: !0
  }) : (g = document.createEvent("Event"), g.initEvent("move", !0, !0)), g.to = r, g.from = e, g.dragged = i, g.draggedRect = o, g.related = l || r, g.relatedRect = f || $t(r), g.willInsertAfter = h, g.originalEvent = a, e.dispatchEvent(g), m && (b = m.call(v, g, a)), b;
}
function Gu(e) {
  e.draggable = !1;
}
function CE() {
  uf = !1;
}
function IE(e, r, i) {
  var o = $t(Fi(i.el, 0, i.options, !0)), l = Ep(i.el, i.options, ct), f = 10;
  return r ? e.clientX < l.left - f || e.clientY < o.top && e.clientX < o.right : e.clientY < l.top - f || e.clientY < o.bottom && e.clientX < o.left;
}
function OE(e, r, i) {
  var o = $t(Nf(i.el, i.options.draggable)), l = Ep(i.el, i.options, ct), f = 10;
  return r ? e.clientX > l.right + f || e.clientY > o.bottom && e.clientX > o.left : e.clientY > l.bottom + f || e.clientX > o.right && e.clientY > o.top;
}
function DE(e, r, i, o, l, f, a, h) {
  var g = o ? e.clientY : e.clientX, v = o ? i.height : i.width, m = o ? i.top : i.left, b = o ? i.bottom : i.right, T = !1;
  if (!a) {
    if (h && Ls < v * l) {
      if (!mo && (_o === 1 ? g > m + v * f / 2 : g < b - v * f / 2) && (mo = !0), mo)
        T = !0;
      else if (_o === 1 ? g < m + Ls : g > b - Ls)
        return -_o;
    } else if (g > m + v * (1 - l) / 2 && g < b - v * (1 - l) / 2)
      return RE(r);
  }
  return T = T || a, T && (g < m + v * f / 2 || g > b - v * f / 2) ? g > m + v / 2 ? 1 : -1 : 0;
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
  getChild: Fi
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
    o.utils && (st.utils = Gn(Gn({}, st.utils), o.utils)), Co.mount(o);
  });
};
st.create = function(e, r) {
  return new st(e, r);
};
st.version = dE;
var ie = [], oo, af, cf = !1, Yu, zu, Js, so;
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
      Js = af = oo = cf = so = Yu = zu = null, ie.length = 0;
    },
    _handleFallbackAutoScroll: function(i) {
      this._handleAutoScroll(i, !0);
    },
    _handleAutoScroll: function(i, o) {
      var l = this, f = (i.touches ? i.touches[0] : i).clientX, a = (i.touches ? i.touches[0] : i).clientY, h = document.elementFromPoint(f, a);
      if (Js = i, o || this.options.forceAutoScrollFallback || Ao || sr || po) {
        Xu(i, this.options, h, o);
        var g = Ar(h, !0);
        cf && (!so || f !== Yu || a !== zu) && (so && rd(), so = setInterval(function() {
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
  clearInterval(so);
}
var Xu = wp(function(e, r, i, o) {
  if (r.scroll) {
    var l = (e.touches ? e.touches[0] : e).clientX, f = (e.touches ? e.touches[0] : e).clientY, a = r.scrollSensitivity, h = r.scrollSpeed, g = $n(), v = !1, m;
    af !== i && (af = i, Ws(), oo = r.scroll, m = r.scrollFn, oo === !0 && (oo = Ar(i, !0)));
    var b = 0, T = oo;
    do {
      var A = T, I = $t(A), R = I.top, N = I.bottom, W = I.left, k = I.right, q = I.width, B = I.height, nt = void 0, Tt = void 0, It = A.scrollWidth, Kt = A.scrollHeight, Lt = Q(A), te = A.scrollLeft, fe = A.scrollTop;
      A === g ? (nt = q < It && (Lt.overflowX === "auto" || Lt.overflowX === "scroll" || Lt.overflowX === "visible"), Tt = B < Kt && (Lt.overflowY === "auto" || Lt.overflowY === "scroll" || Lt.overflowY === "visible")) : (nt = q < It && (Lt.overflowX === "auto" || Lt.overflowX === "scroll"), Tt = B < Kt && (Lt.overflowY === "auto" || Lt.overflowY === "scroll"));
      var ge = nt && (Math.abs(k - l) <= a && te + q < It) - (Math.abs(W - l) <= a && !!te), Gt = Tt && (Math.abs(N - f) <= a && fe + B < Kt) - (Math.abs(R - f) <= a && !!fe);
      if (!ie[b])
        for (var he = 0; he <= b; he++)
          ie[he] || (ie[he] = {});
      (ie[b].vx != ge || ie[b].vy != Gt || ie[b].el !== A) && (ie[b].el = A, ie[b].vx = ge, ie[b].vy = Gt, clearInterval(ie[b].pid), (ge != 0 || Gt != 0) && (v = !0, ie[b].pid = setInterval((function() {
        o && this.layer === 0 && st.active._onTouchMove(Js);
        var Te = ie[this.layer].vy ? ie[this.layer].vy * h : 0, gt = ie[this.layer].vx ? ie[this.layer].vx * h : 0;
        typeof m == "function" && m.call(st.dragged.parentNode[De], gt, Te, e, Js, ie[this.layer].el) !== "continue" || xp(ie[this.layer].el, gt, Te);
      }).bind({
        layer: b
      }), 24))), b++;
    } while (r.bubbleScroll && T !== g && (T = Ar(T, !1)));
    cf = v;
  }
}, 30), Op = function(r) {
  var i = r.originalEvent, o = r.putSortable, l = r.dragEl, f = r.activeSortable, a = r.dispatchSortableEvent, h = r.hideGhostForTarget, g = r.unhideGhostForTarget;
  if (i) {
    var v = o || f;
    h();
    var m = i.changedTouches && i.changedTouches.length ? i.changedTouches[0] : i, b = document.elementFromPoint(m.clientX, m.clientY);
    g(), v && !v.el.contains(b) && (a("spill"), this.onSpill({
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
    var l = Fi(this.sortable.el, this.startIndex - (ft.length ? ft.indexOf(i) : 0), this.options);
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
var ft = [], nn = [], ji, An, to = !1, qe = !1, wi = !1, Wt, eo, Is;
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
      }), o(), Is = !1, f());
    },
    hideClone: function(i) {
      var o = this;
      i.sortable;
      var l = i.cloneNowHidden, f = i.cancel;
      this.isMultiDrag && (nn.forEach(function(a) {
        Q(a, "display", "none"), o.options.removeCloneOnHide && a.parentNode && a.parentNode.removeChild(a);
      }), l(), Is = !0, f());
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
          }), qe = !0, to = !0;
        }
        l.animateAll(function() {
          qe = !1, to = !1, o.options.animation && ft.forEach(function(a) {
            Wu(a);
          }), o.options.sort && Os();
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
        if (l && a._hideClone(), to = !1, v.animation && ft.length > 1 && (qe || !l && !a.options.sort && !g)) {
          var m = $t(Wt, !1, !0, !0);
          ft.forEach(function(T) {
            T !== Wt && (jh(T, m), h.appendChild(T));
          }), qe = !0;
        }
        if (!l)
          if (qe || Os(), ft.length > 1) {
            var b = Is;
            a._showClone(o), a.options.animation && !Is && b && nn.forEach(function(T) {
              a.addAnimationState({
                target: T,
                rect: eo
              }), T.fromRect = eo, T.thisAnimationDuration = null;
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
        eo = On({}, o);
        var a = Zr(Wt, !0);
        eo.top -= a.f, eo.left -= a.e;
      }
    },
    dragOverAnimationComplete: function() {
      qe && (qe = !1, Os());
    },
    drop: function(i) {
      var o = i.originalEvent, l = i.rootEl, f = i.parentEl, a = i.sortable, h = i.dispatchSortableEvent, g = i.oldIndex, v = i.putSortable, m = v || this.sortable;
      if (o) {
        var b = this.options, T = f.children;
        if (!wi)
          if (b.multiDragKey && !this.multiDragKeyDown && this._deselectMultiDrag(), oe(Wt, b.selectedClass, !~ft.indexOf(Wt)), ~ft.indexOf(Wt))
            ft.splice(ft.indexOf(Wt), 1), ji = null, ro({
              sortable: a,
              rootEl: l,
              name: "deselect",
              targetEl: Wt,
              originalEvent: o
            });
          else {
            if (ft.push(Wt), ro({
              sortable: a,
              rootEl: l,
              name: "select",
              targetEl: Wt,
              originalEvent: o
            }), o.shiftKey && ji && a.el.contains(ji)) {
              var A = se(ji), I = se(Wt);
              if (~A && ~I && A !== I) {
                var R, N;
                for (I > A ? (N = A, R = I) : (N = I, R = A + 1); N < R; N++)
                  ~ft.indexOf(T[N]) || (oe(T[N], b.selectedClass, !0), ft.push(T[N]), ro({
                    sortable: a,
                    rootEl: l,
                    name: "select",
                    targetEl: T[N],
                    originalEvent: o
                  }));
              }
            } else
              ji = Wt;
            An = m;
          }
        if (wi && this.isMultiDrag) {
          if (qe = !1, (f[De].options.sort || f !== l) && ft.length > 1) {
            var W = $t(Wt), k = se(Wt, ":not(." + this.options.selectedClass + ")");
            if (!to && b.animation && (Wt.thisAnimationDuration = null), m.captureAnimationState(), !to && (b.animation && (Wt.fromRect = W, ft.forEach(function(B) {
              if (B.thisAnimationDuration = null, B !== Wt) {
                var nt = qe ? $t(B) : W;
                B.fromRect = nt, m.addAnimationState({
                  target: B,
                  rect: nt
                });
              }
            })), Os(), ft.forEach(function(B) {
              T[k] ? f.insertBefore(B, T[k]) : f.appendChild(B), k++;
            }), g === se(Wt))) {
              var q = !1;
              ft.forEach(function(B) {
                if (B.sortableIndex !== se(B)) {
                  q = !0;
                  return;
                }
              }), q && (h("update"), h("sort"));
            }
            ft.forEach(function(B) {
              Wu(B);
            }), m.animateAll();
          }
          An = m;
        }
        (l === f || v && v.lastPutMode !== "clone") && nn.forEach(function(B) {
          B.parentNode && B.parentNode.removeChild(B);
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
          oe(o, this.options.selectedClass, !1), ft.shift(), ro({
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
function Os() {
  ft.forEach(function(e) {
    e !== Wt && e.parentNode && e.parentNode.removeChild(e);
  });
}
st.mount(new FE());
st.mount(Wf, Bf);
st.mount(new NE());
function Ds(e) {
  return typeof e == "number" && !isNaN(e) ? e : typeof e == "string" && !isNaN(Number(e.trim())) ? parseFloat(e) : String(e);
}
let Rs;
function BE() {
  return {
    draggingEntityName: Rs,
    setDraggingEntityName: (o) => {
      Rs = o;
    },
    getDraggingEntityName: () => Rs,
    clearDraggingEntityName: () => {
      Rs = void 0;
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
      await Pi(), h && h.destroy(), g();
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
        setData(v, m) {
          if (l.value) {
            o.sortableGroupName && f(o.sortableGroupName);
            const b = [...l.value.querySelectorAll(`.${h.option("selectedClass")}`)];
            v.setData("text/plain", JSON.stringify({
              taskIds: (b.length ? b : [m]).map((T) => Ds(T.dataset.id))
            }));
          }
        },
        onEnd: (v) => {
          if (a(), v.oldIndex === v.newIndex && v.from.dataset.listId === v.to.dataset.listId)
            return;
          const m = Ds(v.to.dataset.id), b = v.items.length ? v.items : [v.item], T = b[0].previousElementSibling ? Ds(b[0].previousElementSibling.dataset.id) ?? null : null, A = b.map((I) => Ds(I.dataset.id)).filter((I) => I);
          m && i("sort", m, T, A);
        }
      }));
    }
    return (v, m) => (Hn(), Jr("div", {
      ref_key: "sortableRef",
      ref: l,
      class: "tw-flex tw-flex-col",
      "data-id": o.sortableListId
    }, [
      (Hn(!0), Jr(sn, null, E1(o.items, (b) => (Hn(), Jr("div", {
        key: b.uuid || b.id,
        "data-id": b.id
      }, [
        S1(v.$slots, "default", { item: b })
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
    const r = kr("useFetch"), i = kr("options"), o = mn([]), l = mn(), f = Zx(l, [() => o.value.length]);
    Xd("listNavigation", f), a();
    async function a() {
      const { data: A } = await r(`pocketlists.items.get${od({ external_app_id: i.externalAppId, external_entity_type: i.externalEntityType, external_entity_id: i.externalEntityId })}`).get().json();
      o.value = A.value.data;
    }
    const h = async () => {
      const A = T();
      o.value.unshift(A), await Pi(), f.focusTaskById(A.id);
    }, g = async (A, I) => {
      const R = o.value.findIndex((N) => N.id === A.id);
      if (R > -1) {
        const N = I.newName ? T({ name: I.newName }) : T();
        o.value.splice(R + (I.currentName ? 1 : 0), 0, N), await Pi(), f.focusTaskById(N.id), I.newName && v(N);
      }
    }, v = async (A, I) => {
      if (typeof A.id == "string") {
        const { data: R } = await r("pocketlists.items.add", {
          method: "PUT",
          body: JSON.stringify([
            {
              ...A,
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
        if (R.value.status_code === "ok" && Array.isArray(R.value.data)) {
          const N = R.value.data.find((W) => W.success && W.data.uuid === A.id).data.id;
          if (N) {
            const W = o.value.findIndex((k) => k.id === A.id);
            W > -1 && o.value.splice(W, 1, {
              ...A,
              id: N
            });
          }
        }
      } else
        await r("pocketlists.items.update", {
          method: "PATCH",
          body: JSON.stringify([{
            id: A.id,
            ...I
          }])
        }).json();
    }, m = async (A, I) => {
      const R = o.value.findIndex((N) => N.id === A.id);
      R > -1 && (o.value.splice(R, 1), I != null && I.silently || await r(`pocketlists.items.delete${od({ "id[]": A.id })}`).delete().json());
    }, b = (A, I) => {
      typeof A.id == "string" && !I.name && m(A, { silently: !0 });
    };
    function T(A) {
      const I = crypto.randomUUID();
      return {
        id: I,
        uuid: I,
        name: "",
        ...A
      };
    }
    return (A, I) => (Hn(), Jr("div", $E, [
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
          default: Wd(({ item: R }) => [
            yn(iE, {
              task: R,
              sortable: !1,
              editable: !0,
              "addable-props": {
                external_app_id: Ve(i).externalAppId,
                external_entity_type: Ve(i).externalEntityType,
                external_entity_id: Ve(i).externalEntityId
              },
              onInsert: g,
              onUpdate: v,
              onDelete: m,
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
