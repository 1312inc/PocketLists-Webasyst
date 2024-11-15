/**
* @vue/shared v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function bf(t) {
  const n = /* @__PURE__ */ Object.create(null);
  for (const i of t.split(","))
    n[i] = 1;
  return (i) => i in n;
}
const Ye = {}, Oi = [], Un = () => {
}, yw = () => !1, il = (t) => t.charCodeAt(0) === 111 && t.charCodeAt(1) === 110 && // uppercase letter
(t.charCodeAt(2) > 122 || t.charCodeAt(2) < 97), yf = (t) => t.startsWith("onUpdate:"), At = Object.assign, wf = (t, n) => {
  const i = t.indexOf(n);
  i > -1 && t.splice(i, 1);
}, ww = Object.prototype.hasOwnProperty, Be = (t, n) => ww.call(t, n), _e = Array.isArray, Ii = (t) => ol(t) === "[object Map]", vd = (t) => ol(t) === "[object Set]", ve = (t) => typeof t == "function", ct = (t) => typeof t == "string", Rr = (t) => typeof t == "symbol", ke = (t) => t !== null && typeof t == "object", _d = (t) => (ke(t) || ve(t)) && ve(t.then) && ve(t.catch), md = Object.prototype.toString, ol = (t) => md.call(t), Ew = (t) => ol(t).slice(8, -1), bd = (t) => ol(t) === "[object Object]", Ef = (t) => ct(t) && t !== "NaN" && t[0] !== "-" && "" + parseInt(t, 10) === t, fo = /* @__PURE__ */ bf(
  // the leading comma is intentional so empty string "" is also included
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), sl = (t) => {
  const n = /* @__PURE__ */ Object.create(null);
  return (i) => n[i] || (n[i] = t(i));
}, xw = /-(\w)/g, ei = sl(
  (t) => t.replace(xw, (n, i) => i ? i.toUpperCase() : "")
), Sw = /\B([A-Z])/g, Pr = sl(
  (t) => t.replace(Sw, "-$1").toLowerCase()
), yd = sl((t) => t.charAt(0).toUpperCase() + t.slice(1)), Iu = sl(
  (t) => t ? `on${yd(t)}` : ""
), Or = (t, n) => !Object.is(t, n), Du = (t, ...n) => {
  for (let i = 0; i < t.length; i++)
    t[i](...n);
}, wd = (t, n, i, o = !1) => {
  Object.defineProperty(t, n, {
    configurable: !0,
    enumerable: !1,
    writable: o,
    value: i
  });
}, Tw = (t) => {
  const n = parseFloat(t);
  return isNaN(n) ? t : n;
};
let Eh;
const Ed = () => Eh || (Eh = typeof globalThis < "u" ? globalThis : typeof self < "u" ? self : typeof window < "u" ? window : typeof global < "u" ? global : {});
function xf(t) {
  if (_e(t)) {
    const n = {};
    for (let i = 0; i < t.length; i++) {
      const o = t[i], l = ct(o) ? Iw(o) : xf(o);
      if (l)
        for (const u in l)
          n[u] = l[u];
    }
    return n;
  } else if (ct(t) || ke(t))
    return t;
}
const Aw = /;(?![^(]*\))/g, Cw = /:([^]+)/, Ow = /\/\*[^]*?\*\//g;
function Iw(t) {
  const n = {};
  return t.replace(Ow, "").split(Aw).forEach((i) => {
    if (i) {
      const o = i.split(Cw);
      o.length > 1 && (n[o[0].trim()] = o[1].trim());
    }
  }), n;
}
function ll(t) {
  let n = "";
  if (ct(t))
    n = t;
  else if (_e(t))
    for (let i = 0; i < t.length; i++) {
      const o = ll(t[i]);
      o && (n += o + " ");
    }
  else if (ke(t))
    for (const i in t)
      t[i] && (n += i + " ");
  return n.trim();
}
const Dw = "itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly", Rw = /* @__PURE__ */ bf(Dw);
function xd(t) {
  return !!t || t === "";
}
const Sd = (t) => !!(t && t.__v_isRef === !0), ju = (t) => ct(t) ? t : t == null ? "" : _e(t) || ke(t) && (t.toString === md || !ve(t.toString)) ? Sd(t) ? ju(t.value) : JSON.stringify(t, Td, 2) : String(t), Td = (t, n) => Sd(n) ? Td(t, n.value) : Ii(n) ? {
  [`Map(${n.size})`]: [...n.entries()].reduce(
    (i, [o, l], u) => (i[Ru(o, u) + " =>"] = l, i),
    {}
  )
} : vd(n) ? {
  [`Set(${n.size})`]: [...n.values()].map((i) => Ru(i))
} : Rr(n) ? Ru(n) : ke(n) && !_e(n) && !bd(n) ? String(n) : n, Ru = (t, n = "") => {
  var i;
  return (
    // Symbol.description in es2019+ so we need to cast here to pass
    // the lib: es2016 check
    Rr(t) ? `Symbol(${(i = t.description) != null ? i : n})` : t
  );
};
/**
* @vue/reactivity v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let Kt;
class Pw {
  constructor(n = !1) {
    this.detached = n, this._active = !0, this.effects = [], this.cleanups = [], this._isPaused = !1, this.parent = Kt, !n && Kt && (this.index = (Kt.scopes || (Kt.scopes = [])).push(
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
      const i = Kt;
      try {
        return Kt = this, n();
      } finally {
        Kt = i;
      }
    }
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  on() {
    Kt = this;
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  off() {
    Kt = this.parent;
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
function Ad() {
  return Kt;
}
function Fw(t, n = !1) {
  Kt && Kt.cleanups.push(t);
}
let $e;
const Pu = /* @__PURE__ */ new WeakSet();
class Cd {
  constructor(n) {
    this.fn = n, this.deps = void 0, this.depsTail = void 0, this.flags = 5, this.next = void 0, this.cleanup = void 0, this.scheduler = void 0, Kt && Kt.active && Kt.effects.push(this);
  }
  pause() {
    this.flags |= 64;
  }
  resume() {
    this.flags & 64 && (this.flags &= -65, Pu.has(this) && (Pu.delete(this), this.trigger()));
  }
  /**
   * @internal
   */
  notify() {
    this.flags & 2 && !(this.flags & 32) || this.flags & 8 || Id(this);
  }
  run() {
    if (!(this.flags & 1))
      return this.fn();
    this.flags |= 2, xh(this), Dd(this);
    const n = $e, i = In;
    $e = this, In = !0;
    try {
      return this.fn();
    } finally {
      Rd(this), $e = n, In = i, this.flags &= -3;
    }
  }
  stop() {
    if (this.flags & 1) {
      for (let n = this.deps; n; n = n.nextDep)
        Af(n);
      this.deps = this.depsTail = void 0, xh(this), this.onStop && this.onStop(), this.flags &= -2;
    }
  }
  trigger() {
    this.flags & 64 ? Pu.add(this) : this.scheduler ? this.scheduler() : this.runIfDirty();
  }
  /**
   * @internal
   */
  runIfDirty() {
    ef(this) && this.run();
  }
  get dirty() {
    return ef(this);
  }
}
let Od = 0, ao;
function Id(t) {
  t.flags |= 8, t.next = ao, ao = t;
}
function Sf() {
  Od++;
}
function Tf() {
  if (--Od > 0)
    return;
  let t;
  for (; ao; ) {
    let n = ao;
    for (ao = void 0; n; ) {
      const i = n.next;
      if (n.next = void 0, n.flags &= -9, n.flags & 1)
        try {
          n.trigger();
        } catch (o) {
          t || (t = o);
        }
      n = i;
    }
  }
  if (t)
    throw t;
}
function Dd(t) {
  for (let n = t.deps; n; n = n.nextDep)
    n.version = -1, n.prevActiveLink = n.dep.activeLink, n.dep.activeLink = n;
}
function Rd(t) {
  let n, i = t.depsTail, o = i;
  for (; o; ) {
    const l = o.prevDep;
    o.version === -1 ? (o === i && (i = l), Af(o), Mw(o)) : n = o, o.dep.activeLink = o.prevActiveLink, o.prevActiveLink = void 0, o = l;
  }
  t.deps = n, t.depsTail = i;
}
function ef(t) {
  for (let n = t.deps; n; n = n.nextDep)
    if (n.dep.version !== n.version || n.dep.computed && (Pd(n.dep.computed) || n.dep.version !== n.version))
      return !0;
  return !!t._dirty;
}
function Pd(t) {
  if (t.flags & 4 && !(t.flags & 16) || (t.flags &= -17, t.globalVersion === wo))
    return;
  t.globalVersion = wo;
  const n = t.dep;
  if (t.flags |= 2, n.version > 0 && !t.isSSR && t.deps && !ef(t)) {
    t.flags &= -3;
    return;
  }
  const i = $e, o = In;
  $e = t, In = !0;
  try {
    Dd(t);
    const l = t.fn(t._value);
    (n.version === 0 || Or(l, t._value)) && (t._value = l, n.version++);
  } catch (l) {
    throw n.version++, l;
  } finally {
    $e = i, In = o, Rd(t), t.flags &= -3;
  }
}
function Af(t) {
  const { dep: n, prevSub: i, nextSub: o } = t;
  if (i && (i.nextSub = o, t.prevSub = void 0), o && (o.prevSub = i, t.nextSub = void 0), n.subs === t && (n.subs = i), !n.subs && n.computed) {
    n.computed.flags &= -5;
    for (let l = n.computed.deps; l; l = l.nextDep)
      Af(l);
  }
}
function Mw(t) {
  const { prevDep: n, nextDep: i } = t;
  n && (n.nextDep = i, t.prevDep = void 0), i && (i.prevDep = n, t.nextDep = void 0);
}
let In = !0;
const Fd = [];
function Fr() {
  Fd.push(In), In = !1;
}
function Mr() {
  const t = Fd.pop();
  In = t === void 0 ? !0 : t;
}
function xh(t) {
  const { cleanup: n } = t;
  if (t.cleanup = void 0, n) {
    const i = $e;
    $e = void 0;
    try {
      n();
    } finally {
      $e = i;
    }
  }
}
let wo = 0;
class Nw {
  constructor(n, i) {
    this.sub = n, this.dep = i, this.version = i.version, this.nextDep = this.prevDep = this.nextSub = this.prevSub = this.prevActiveLink = void 0;
  }
}
class ul {
  constructor(n) {
    this.computed = n, this.version = 0, this.activeLink = void 0, this.subs = void 0;
  }
  track(n) {
    if (!$e || !In || $e === this.computed)
      return;
    let i = this.activeLink;
    if (i === void 0 || i.sub !== $e)
      i = this.activeLink = new Nw($e, this), $e.deps ? (i.prevDep = $e.depsTail, $e.depsTail.nextDep = i, $e.depsTail = i) : $e.deps = $e.depsTail = i, $e.flags & 4 && Md(i);
    else if (i.version === -1 && (i.version = this.version, i.nextDep)) {
      const o = i.nextDep;
      o.prevDep = i.prevDep, i.prevDep && (i.prevDep.nextDep = o), i.prevDep = $e.depsTail, i.nextDep = void 0, $e.depsTail.nextDep = i, $e.depsTail = i, $e.deps === i && ($e.deps = o);
    }
    return i;
  }
  trigger(n) {
    this.version++, wo++, this.notify(n);
  }
  notify(n) {
    Sf();
    try {
      for (let i = this.subs; i; i = i.prevSub)
        i.sub.notify() && i.sub.dep.notify();
    } finally {
      Tf();
    }
  }
}
function Md(t) {
  const n = t.dep.computed;
  if (n && !t.dep.subs) {
    n.flags |= 20;
    for (let o = n.deps; o; o = o.nextDep)
      Md(o);
  }
  const i = t.dep.subs;
  i !== t && (t.prevSub = i, i && (i.nextSub = t)), t.dep.subs = t;
}
const zs = /* @__PURE__ */ new WeakMap(), kr = Symbol(
  ""
), tf = Symbol(
  ""
), Eo = Symbol(
  ""
);
function Nt(t, n, i) {
  if (In && $e) {
    let o = zs.get(t);
    o || zs.set(t, o = /* @__PURE__ */ new Map());
    let l = o.get(i);
    l || o.set(i, l = new ul()), l.track();
  }
}
function rr(t, n, i, o, l, u) {
  const a = zs.get(t);
  if (!a) {
    wo++;
    return;
  }
  const h = (g) => {
    g && g.trigger();
  };
  if (Sf(), n === "clear")
    a.forEach(h);
  else {
    const g = _e(t), v = g && Ef(i);
    if (g && i === "length") {
      const _ = Number(o);
      a.forEach((m, A) => {
        (A === "length" || A === Eo || !Rr(A) && A >= _) && h(m);
      });
    } else
      switch (i !== void 0 && h(a.get(i)), v && h(a.get(Eo)), n) {
        case "add":
          g ? v && h(a.get("length")) : (h(a.get(kr)), Ii(t) && h(a.get(tf)));
          break;
        case "delete":
          g || (h(a.get(kr)), Ii(t) && h(a.get(tf)));
          break;
        case "set":
          Ii(t) && h(a.get(kr));
          break;
      }
  }
  Tf();
}
function Lw(t, n) {
  var i;
  return (i = zs.get(t)) == null ? void 0 : i.get(n);
}
function bi(t) {
  const n = Ne(t);
  return n === t ? n : (Nt(n, "iterate", Eo), wn(t) ? n : n.map(Rt));
}
function fl(t) {
  return Nt(t = Ne(t), "iterate", Eo), t;
}
const Bw = {
  __proto__: null,
  [Symbol.iterator]() {
    return Fu(this, Symbol.iterator, Rt);
  },
  concat(...t) {
    return bi(this).concat(
      ...t.map((n) => _e(n) ? bi(n) : n)
    );
  },
  entries() {
    return Fu(this, "entries", (t) => (t[1] = Rt(t[1]), t));
  },
  every(t, n) {
    return tr(this, "every", t, n, void 0, arguments);
  },
  filter(t, n) {
    return tr(this, "filter", t, n, (i) => i.map(Rt), arguments);
  },
  find(t, n) {
    return tr(this, "find", t, n, Rt, arguments);
  },
  findIndex(t, n) {
    return tr(this, "findIndex", t, n, void 0, arguments);
  },
  findLast(t, n) {
    return tr(this, "findLast", t, n, Rt, arguments);
  },
  findLastIndex(t, n) {
    return tr(this, "findLastIndex", t, n, void 0, arguments);
  },
  // flat, flatMap could benefit from ARRAY_ITERATE but are not straight-forward to implement
  forEach(t, n) {
    return tr(this, "forEach", t, n, void 0, arguments);
  },
  includes(...t) {
    return Mu(this, "includes", t);
  },
  indexOf(...t) {
    return Mu(this, "indexOf", t);
  },
  join(t) {
    return bi(this).join(t);
  },
  // keys() iterator only reads `length`, no optimisation required
  lastIndexOf(...t) {
    return Mu(this, "lastIndexOf", t);
  },
  map(t, n) {
    return tr(this, "map", t, n, void 0, arguments);
  },
  pop() {
    return Qi(this, "pop");
  },
  push(...t) {
    return Qi(this, "push", t);
  },
  reduce(t, ...n) {
    return Sh(this, "reduce", t, n);
  },
  reduceRight(t, ...n) {
    return Sh(this, "reduceRight", t, n);
  },
  shift() {
    return Qi(this, "shift");
  },
  // slice could use ARRAY_ITERATE but also seems to beg for range tracking
  some(t, n) {
    return tr(this, "some", t, n, void 0, arguments);
  },
  splice(...t) {
    return Qi(this, "splice", t);
  },
  toReversed() {
    return bi(this).toReversed();
  },
  toSorted(t) {
    return bi(this).toSorted(t);
  },
  toSpliced(...t) {
    return bi(this).toSpliced(...t);
  },
  unshift(...t) {
    return Qi(this, "unshift", t);
  },
  values() {
    return Fu(this, "values", Rt);
  }
};
function Fu(t, n, i) {
  const o = fl(t), l = o[n]();
  return o !== t && !wn(t) && (l._next = l.next, l.next = () => {
    const u = l._next();
    return u.value && (u.value = i(u.value)), u;
  }), l;
}
const Ww = Array.prototype;
function tr(t, n, i, o, l, u) {
  const a = fl(t), h = a !== t && !wn(t), g = a[n];
  if (g !== Ww[n]) {
    const m = g.apply(t, u);
    return h ? Rt(m) : m;
  }
  let v = i;
  a !== t && (h ? v = function(m, A) {
    return i.call(this, Rt(m), A, t);
  } : i.length > 2 && (v = function(m, A) {
    return i.call(this, m, A, t);
  }));
  const _ = g.call(a, v, o);
  return h && l ? l(_) : _;
}
function Sh(t, n, i, o) {
  const l = fl(t);
  let u = i;
  return l !== t && (wn(t) ? i.length > 3 && (u = function(a, h, g) {
    return i.call(this, a, h, g, t);
  }) : u = function(a, h, g) {
    return i.call(this, a, Rt(h), g, t);
  }), l[n](u, ...o);
}
function Mu(t, n, i) {
  const o = Ne(t);
  Nt(o, "iterate", Eo);
  const l = o[n](...i);
  return (l === -1 || l === !1) && Rf(i[0]) ? (i[0] = Ne(i[0]), o[n](...i)) : l;
}
function Qi(t, n, i = []) {
  Fr(), Sf();
  const o = Ne(t)[n].apply(t, i);
  return Tf(), Mr(), o;
}
const Uw = /* @__PURE__ */ bf("__proto__,__v_isRef,__isVue"), Nd = new Set(
  /* @__PURE__ */ Object.getOwnPropertyNames(Symbol).filter((t) => t !== "arguments" && t !== "caller").map((t) => Symbol[t]).filter(Rr)
);
function Hw(t) {
  Rr(t) || (t = String(t));
  const n = Ne(this);
  return Nt(n, "has", t), n.hasOwnProperty(t);
}
class Ld {
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
      return o === (l ? u ? jw : Hd : u ? Ud : Wd).get(n) || // receiver is not the reactive proxy, but has the same prototype
      // this means the receiver is a user proxy of the reactive proxy
      Object.getPrototypeOf(n) === Object.getPrototypeOf(o) ? n : void 0;
    const a = _e(n);
    if (!l) {
      let g;
      if (a && (g = Bw[i]))
        return g;
      if (i === "hasOwnProperty")
        return Hw;
    }
    const h = Reflect.get(
      n,
      i,
      // if this is a proxy wrapping a ref, return methods using the raw ref
      // as receiver so that we don't have to call `toRaw` on the ref in all
      // its class methods
      it(n) ? n : o
    );
    return (Rr(i) ? Nd.has(i) : Uw(i)) || (l || Nt(n, "get", i), u) ? h : it(h) ? a && Ef(i) ? h : h.value : ke(h) ? l ? ti(h) : If(h) : h;
  }
}
class Bd extends Ld {
  constructor(n = !1) {
    super(!1, n);
  }
  set(n, i, o, l) {
    let u = n[i];
    if (!this._isShallow) {
      const g = ni(u);
      if (!wn(o) && !ni(o) && (u = Ne(u), o = Ne(o)), !_e(n) && it(u) && !it(o))
        return g ? !1 : (u.value = o, !0);
    }
    const a = _e(n) && Ef(i) ? Number(i) < n.length : Be(n, i), h = Reflect.set(
      n,
      i,
      o,
      it(n) ? n : l
    );
    return n === Ne(l) && (a ? Or(o, u) && rr(n, "set", i, o) : rr(n, "add", i, o)), h;
  }
  deleteProperty(n, i) {
    const o = Be(n, i);
    n[i];
    const l = Reflect.deleteProperty(n, i);
    return l && o && rr(n, "delete", i, void 0), l;
  }
  has(n, i) {
    const o = Reflect.has(n, i);
    return (!Rr(i) || !Nd.has(i)) && Nt(n, "has", i), o;
  }
  ownKeys(n) {
    return Nt(
      n,
      "iterate",
      _e(n) ? "length" : kr
    ), Reflect.ownKeys(n);
  }
}
class $w extends Ld {
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
const Kw = /* @__PURE__ */ new Bd(), Gw = /* @__PURE__ */ new $w(), Yw = /* @__PURE__ */ new Bd(!0);
const Cf = (t) => t, al = (t) => Reflect.getPrototypeOf(t);
function bs(t, n, i = !1, o = !1) {
  t = t.__v_raw;
  const l = Ne(t), u = Ne(n);
  i || (Or(n, u) && Nt(l, "get", n), Nt(l, "get", u));
  const { has: a } = al(l), h = o ? Cf : i ? Pf : Rt;
  if (a.call(l, n))
    return h(t.get(n));
  if (a.call(l, u))
    return h(t.get(u));
  t !== l && t.get(n);
}
function ys(t, n = !1) {
  const i = this.__v_raw, o = Ne(i), l = Ne(t);
  return n || (Or(t, l) && Nt(o, "has", t), Nt(o, "has", l)), t === l ? i.has(t) : i.has(t) || i.has(l);
}
function ws(t, n = !1) {
  return t = t.__v_raw, !n && Nt(Ne(t), "iterate", kr), Reflect.get(t, "size", t);
}
function Th(t, n = !1) {
  !n && !wn(t) && !ni(t) && (t = Ne(t));
  const i = Ne(this);
  return al(i).has.call(i, t) || (i.add(t), rr(i, "add", t, t)), this;
}
function Ah(t, n, i = !1) {
  !i && !wn(n) && !ni(n) && (n = Ne(n));
  const o = Ne(this), { has: l, get: u } = al(o);
  let a = l.call(o, t);
  a || (t = Ne(t), a = l.call(o, t));
  const h = u.call(o, t);
  return o.set(t, n), a ? Or(n, h) && rr(o, "set", t, n) : rr(o, "add", t, n), this;
}
function Ch(t) {
  const n = Ne(this), { has: i, get: o } = al(n);
  let l = i.call(n, t);
  l || (t = Ne(t), l = i.call(n, t)), o && o.call(n, t);
  const u = n.delete(t);
  return l && rr(n, "delete", t, void 0), u;
}
function Oh() {
  const t = Ne(this), n = t.size !== 0, i = t.clear();
  return n && rr(t, "clear", void 0, void 0), i;
}
function Es(t, n) {
  return function(o, l) {
    const u = this, a = u.__v_raw, h = Ne(a), g = n ? Cf : t ? Pf : Rt;
    return !t && Nt(h, "iterate", kr), a.forEach((v, _) => o.call(l, g(v), g(_), u));
  };
}
function xs(t, n, i) {
  return function(...o) {
    const l = this.__v_raw, u = Ne(l), a = Ii(u), h = t === "entries" || t === Symbol.iterator && a, g = t === "keys" && a, v = l[t](...o), _ = i ? Cf : n ? Pf : Rt;
    return !n && Nt(
      u,
      "iterate",
      g ? tf : kr
    ), {
      // iterator protocol
      next() {
        const { value: m, done: A } = v.next();
        return A ? { value: m, done: A } : {
          value: h ? [_(m[0]), _(m[1])] : _(m),
          done: A
        };
      },
      // iterable protocol
      [Symbol.iterator]() {
        return this;
      }
    };
  };
}
function wr(t) {
  return function(...n) {
    return t === "delete" ? !1 : t === "clear" ? void 0 : this;
  };
}
function zw() {
  const t = {
    get(u) {
      return bs(this, u);
    },
    get size() {
      return ws(this);
    },
    has: ys,
    add: Th,
    set: Ah,
    delete: Ch,
    clear: Oh,
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
      return Th.call(this, u, !0);
    },
    set(u, a) {
      return Ah.call(this, u, a, !0);
    },
    delete: Ch,
    clear: Oh,
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
    t[u] = xs(u, !1, !1), i[u] = xs(u, !0, !1), n[u] = xs(u, !1, !0), o[u] = xs(
      u,
      !0,
      !0
    );
  }), [
    t,
    i,
    n,
    o
  ];
}
const [
  Xw,
  qw,
  Vw,
  Jw
] = /* @__PURE__ */ zw();
function Of(t, n) {
  const i = n ? t ? Jw : Vw : t ? qw : Xw;
  return (o, l, u) => l === "__v_isReactive" ? !t : l === "__v_isReadonly" ? t : l === "__v_raw" ? o : Reflect.get(
    Be(i, l) && l in o ? i : o,
    l,
    u
  );
}
const kw = {
  get: /* @__PURE__ */ Of(!1, !1)
}, Zw = {
  get: /* @__PURE__ */ Of(!1, !0)
}, Qw = {
  get: /* @__PURE__ */ Of(!0, !1)
};
const Wd = /* @__PURE__ */ new WeakMap(), Ud = /* @__PURE__ */ new WeakMap(), Hd = /* @__PURE__ */ new WeakMap(), jw = /* @__PURE__ */ new WeakMap();
function e1(t) {
  switch (t) {
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
function t1(t) {
  return t.__v_skip || !Object.isExtensible(t) ? 0 : e1(Ew(t));
}
function If(t) {
  return ni(t) ? t : Df(
    t,
    !1,
    Kw,
    kw,
    Wd
  );
}
function n1(t) {
  return Df(
    t,
    !1,
    Yw,
    Zw,
    Ud
  );
}
function ti(t) {
  return Df(
    t,
    !0,
    Gw,
    Qw,
    Hd
  );
}
function Df(t, n, i, o, l) {
  if (!ke(t) || t.__v_raw && !(n && t.__v_isReactive))
    return t;
  const u = l.get(t);
  if (u)
    return u;
  const a = t1(t);
  if (a === 0)
    return t;
  const h = new Proxy(
    t,
    a === 2 ? o : i
  );
  return l.set(t, h), h;
}
function Di(t) {
  return ni(t) ? Di(t.__v_raw) : !!(t && t.__v_isReactive);
}
function ni(t) {
  return !!(t && t.__v_isReadonly);
}
function wn(t) {
  return !!(t && t.__v_isShallow);
}
function Rf(t) {
  return t ? !!t.__v_raw : !1;
}
function Ne(t) {
  const n = t && t.__v_raw;
  return n ? Ne(n) : t;
}
function r1(t) {
  return !Be(t, "__v_skip") && Object.isExtensible(t) && wd(t, "__v_skip", !0), t;
}
const Rt = (t) => ke(t) ? If(t) : t, Pf = (t) => ke(t) ? ti(t) : t;
function it(t) {
  return t ? t.__v_isRef === !0 : !1;
}
function _t(t) {
  return $d(t, !1);
}
function Ls(t) {
  return $d(t, !0);
}
function $d(t, n) {
  return it(t) ? t : new i1(t, n);
}
class i1 {
  constructor(n, i) {
    this.dep = new ul(), this.__v_isRef = !0, this.__v_isShallow = !1, this._rawValue = i ? n : Ne(n), this._value = i ? n : Rt(n), this.__v_isShallow = i;
  }
  get value() {
    return this.dep.track(), this._value;
  }
  set value(n) {
    const i = this._rawValue, o = this.__v_isShallow || wn(n) || ni(n);
    n = o ? n : Ne(n), Or(n, i) && (this._rawValue = n, this._value = o ? n : Rt(n), this.dep.trigger());
  }
}
function rt(t) {
  return it(t) ? t.value : t;
}
function o1(t) {
  return ve(t) ? t() : rt(t);
}
const s1 = {
  get: (t, n, i) => n === "__v_raw" ? t : rt(Reflect.get(t, n, i)),
  set: (t, n, i, o) => {
    const l = t[n];
    return it(l) && !it(i) ? (l.value = i, !0) : Reflect.set(t, n, i, o);
  }
};
function Kd(t) {
  return Di(t) ? t : new Proxy(t, s1);
}
class l1 {
  constructor(n) {
    this.__v_isRef = !0, this._value = void 0;
    const i = this.dep = new ul(), { get: o, set: l } = n(i.track.bind(i), i.trigger.bind(i));
    this._get = o, this._set = l;
  }
  get value() {
    return this._value = this._get();
  }
  set value(n) {
    this._set(n);
  }
}
function u1(t) {
  return new l1(t);
}
class f1 {
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
    return Lw(Ne(this._object), this._key);
  }
}
class a1 {
  constructor(n) {
    this._getter = n, this.__v_isRef = !0, this.__v_isReadonly = !0, this._value = void 0;
  }
  get value() {
    return this._value = this._getter();
  }
}
function c1(t, n, i) {
  return it(t) ? t : ve(t) ? new a1(t) : ke(t) && arguments.length > 1 ? h1(t, n, i) : _t(t);
}
function h1(t, n, i) {
  const o = t[n];
  return it(o) ? o : new f1(t, n, i);
}
class d1 {
  constructor(n, i, o) {
    this.fn = n, this.setter = i, this._value = void 0, this.dep = new ul(this), this.__v_isRef = !0, this.deps = void 0, this.depsTail = void 0, this.flags = 16, this.globalVersion = wo - 1, this.effect = this, this.__v_isReadonly = !i, this.isSSR = o;
  }
  /**
   * @internal
   */
  notify() {
    if (this.flags |= 16, !(this.flags & 8) && // avoid infinite self recursion
    $e !== this)
      return Id(this), !0;
  }
  get value() {
    const n = this.dep.track();
    return Pd(this), n && (n.version = this.dep.version), this._value;
  }
  set value(n) {
    this.setter && this.setter(n);
  }
}
function p1(t, n, i = !1) {
  let o, l;
  return ve(t) ? o = t : (o = t.get, l = t.set), new d1(o, l, i);
}
const Ss = {}, Xs = /* @__PURE__ */ new WeakMap();
let Vr;
function g1(t, n = !1, i = Vr) {
  if (i) {
    let o = Xs.get(i);
    o || Xs.set(i, o = []), o.push(t);
  }
}
function v1(t, n, i = Ye) {
  const { immediate: o, deep: l, once: u, scheduler: a, augmentJob: h, call: g } = i, v = (L) => l ? L : wn(L) || l === !1 || l === 0 ? Tr(L, 1) : Tr(L);
  let _, m, A, S, C = !1, I = !1;
  if (it(t) ? (m = () => t.value, C = wn(t)) : Di(t) ? (m = () => v(t), C = !0) : _e(t) ? (I = !0, C = t.some((L) => Di(L) || wn(L)), m = () => t.map((L) => {
    if (it(L))
      return L.value;
    if (Di(L))
      return v(L);
    if (ve(L))
      return g ? g(L, 2) : L();
  })) : ve(t) ? n ? m = g ? () => g(t, 2) : t : m = () => {
    if (A) {
      Fr();
      try {
        A();
      } finally {
        Mr();
      }
    }
    const L = Vr;
    Vr = _;
    try {
      return g ? g(t, 3, [S]) : t(S);
    } finally {
      Vr = L;
    }
  } : m = Un, n && l) {
    const L = m, te = l === !0 ? 1 / 0 : l;
    m = () => Tr(L(), te);
  }
  const P = Ad(), B = () => {
    _.stop(), P && wf(P.effects, _);
  };
  if (u && n) {
    const L = n;
    n = (...te) => {
      L(...te), B();
    };
  }
  let z = I ? new Array(t.length).fill(Ss) : Ss;
  const X = (L) => {
    if (!(!(_.flags & 1) || !_.dirty && !L))
      if (n) {
        const te = _.run();
        if (l || C || (I ? te.some((Ee, Se) => Or(Ee, z[Se])) : Or(te, z))) {
          A && A();
          const Ee = Vr;
          Vr = _;
          try {
            const Se = [
              te,
              // pass undefined as the old value when it's changed for the first time
              z === Ss ? void 0 : I && z[0] === Ss ? [] : z,
              S
            ];
            g ? g(n, 3, Se) : (
              // @ts-expect-error
              n(...Se)
            ), z = te;
          } finally {
            Vr = Ee;
          }
        }
      } else
        _.run();
  };
  return h && h(X), _ = new Cd(m), _.scheduler = a ? () => a(X, !1) : X, S = (L) => g1(L, !1, _), A = _.onStop = () => {
    const L = Xs.get(_);
    if (L) {
      if (g)
        g(L, 4);
      else
        for (const te of L)
          te();
      Xs.delete(_);
    }
  }, n ? o ? X(!0) : z = _.run() : a ? a(X.bind(null, !0), !0) : _.run(), B.pause = _.pause.bind(_), B.resume = _.resume.bind(_), B.stop = B, B;
}
function Tr(t, n = 1 / 0, i) {
  if (n <= 0 || !ke(t) || t.__v_skip || (i = i || /* @__PURE__ */ new Set(), i.has(t)))
    return t;
  if (i.add(t), n--, it(t))
    Tr(t.value, n, i);
  else if (_e(t))
    for (let o = 0; o < t.length; o++)
      Tr(t[o], n, i);
  else if (vd(t) || Ii(t))
    t.forEach((o) => {
      Tr(o, n, i);
    });
  else if (bd(t)) {
    for (const o in t)
      Tr(t[o], n, i);
    for (const o of Object.getOwnPropertySymbols(t))
      Object.prototype.propertyIsEnumerable.call(t, o) && Tr(t[o], n, i);
  }
  return t;
}
/**
* @vue/runtime-core v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
function Ao(t, n, i, o) {
  try {
    return o ? t(...o) : t();
  } catch (l) {
    cl(l, n, i);
  }
}
function Kn(t, n, i, o) {
  if (ve(t)) {
    const l = Ao(t, n, i, o);
    return l && _d(l) && l.catch((u) => {
      cl(u, n, i);
    }), l;
  }
  if (_e(t)) {
    const l = [];
    for (let u = 0; u < t.length; u++)
      l.push(Kn(t[u], n, i, o));
    return l;
  }
}
function cl(t, n, i, o = !0) {
  const l = n ? n.vnode : null, { errorHandler: u, throwUnhandledErrorInProduction: a } = n && n.appContext.config || Ye;
  if (n) {
    let h = n.parent;
    const g = n.proxy, v = `https://vuejs.org/error-reference/#runtime-${i}`;
    for (; h; ) {
      const _ = h.ec;
      if (_) {
        for (let m = 0; m < _.length; m++)
          if (_[m](t, g, v) === !1)
            return;
      }
      h = h.parent;
    }
    if (u) {
      Fr(), Ao(u, null, 10, [
        t,
        g,
        v
      ]), Mr();
      return;
    }
  }
  _1(t, i, l, o, a);
}
function _1(t, n, i, o = !0, l = !1) {
  if (l)
    throw t;
  console.error(t);
}
let xo = !1, nf = !1;
const Gt = [];
let Ln = 0;
const Ri = [];
let Er = null, Ti = 0;
const Gd = /* @__PURE__ */ Promise.resolve();
let Ff = null;
function Ir(t) {
  const n = Ff || Gd;
  return t ? n.then(this ? t.bind(this) : t) : n;
}
function m1(t) {
  let n = xo ? Ln + 1 : 0, i = Gt.length;
  for (; n < i; ) {
    const o = n + i >>> 1, l = Gt[o], u = So(l);
    u < t || u === t && l.flags & 2 ? n = o + 1 : i = o;
  }
  return n;
}
function Mf(t) {
  if (!(t.flags & 1)) {
    const n = So(t), i = Gt[Gt.length - 1];
    !i || // fast path when the job id is larger than the tail
    !(t.flags & 2) && n >= So(i) ? Gt.push(t) : Gt.splice(m1(n), 0, t), t.flags |= 1, Yd();
  }
}
function Yd() {
  !xo && !nf && (nf = !0, Ff = Gd.then(Xd));
}
function b1(t) {
  _e(t) ? Ri.push(...t) : Er && t.id === -1 ? Er.splice(Ti + 1, 0, t) : t.flags & 1 || (Ri.push(t), t.flags |= 1), Yd();
}
function Ih(t, n, i = xo ? Ln + 1 : 0) {
  for (; i < Gt.length; i++) {
    const o = Gt[i];
    if (o && o.flags & 2) {
      if (t && o.id !== t.uid)
        continue;
      Gt.splice(i, 1), i--, o.flags & 4 && (o.flags &= -2), o(), o.flags &= -2;
    }
  }
}
function zd(t) {
  if (Ri.length) {
    const n = [...new Set(Ri)].sort(
      (i, o) => So(i) - So(o)
    );
    if (Ri.length = 0, Er) {
      Er.push(...n);
      return;
    }
    for (Er = n, Ti = 0; Ti < Er.length; Ti++) {
      const i = Er[Ti];
      i.flags & 4 && (i.flags &= -2), i.flags & 8 || i(), i.flags &= -2;
    }
    Er = null, Ti = 0;
  }
}
const So = (t) => t.id == null ? t.flags & 2 ? -1 : 1 / 0 : t.id;
function Xd(t) {
  nf = !1, xo = !0;
  try {
    for (Ln = 0; Ln < Gt.length; Ln++) {
      const n = Gt[Ln];
      n && !(n.flags & 8) && (n.flags & 4 && (n.flags &= -2), Ao(
        n,
        n.i,
        n.i ? 15 : 14
      ), n.flags &= -2);
    }
  } finally {
    for (; Ln < Gt.length; Ln++) {
      const n = Gt[Ln];
      n && (n.flags &= -2);
    }
    Ln = 0, Gt.length = 0, zd(), xo = !1, Ff = null, (Gt.length || Ri.length) && Xd();
  }
}
let Yt = null, qd = null;
function qs(t) {
  const n = Yt;
  return Yt = t, qd = t && t.type.__scopeId || null, n;
}
function Vd(t, n = Yt, i) {
  if (!n || t._n)
    return t;
  const o = (...l) => {
    o._d && Bh(-1);
    const u = qs(n);
    let a;
    try {
      a = t(...l);
    } finally {
      qs(u), o._d && Bh(1);
    }
    return a;
  };
  return o._n = !0, o._c = !0, o._d = !0, o;
}
function Yr(t, n, i, o) {
  const l = t.dirs, u = n && n.dirs;
  for (let a = 0; a < l.length; a++) {
    const h = l[a];
    u && (h.oldValue = u[a].value);
    let g = h.dir[o];
    g && (Fr(), Kn(g, i, 8, [
      t.el,
      h,
      t,
      n
    ]), Mr());
  }
}
const y1 = Symbol("_vte"), w1 = (t) => t.__isTeleport;
function Nf(t, n) {
  t.shapeFlag & 6 && t.component ? (t.transition = n, Nf(t.component.subTree, n)) : t.shapeFlag & 128 ? (t.ssContent.transition = n.clone(t.ssContent), t.ssFallback.transition = n.clone(t.ssFallback)) : t.transition = n;
}
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function Lf(t, n) {
  return ve(t) ? (
    // #8236: extend call and options.name access are considered side-effects
    // by Rollup, so we have to wrap it in a pure-annotated IIFE.
    At({ name: t.name }, n, { setup: t })
  ) : t;
}
function Jd(t) {
  t.ids = [t.ids[0] + t.ids[2]++ + "-", 0, 0];
}
function rf(t, n, i, o, l = !1) {
  if (_e(t)) {
    t.forEach(
      (C, I) => rf(
        C,
        n && (_e(n) ? n[I] : n),
        i,
        o,
        l
      )
    );
    return;
  }
  if (Pi(o) && !l)
    return;
  const u = o.shapeFlag & 4 ? $f(o.component) : o.el, a = l ? null : u, { i: h, r: g } = t, v = n && n.r, _ = h.refs === Ye ? h.refs = {} : h.refs, m = h.setupState, A = Ne(m), S = m === Ye ? () => !1 : (C) => Be(A, C);
  if (v != null && v !== g && (ct(v) ? (_[v] = null, S(v) && (m[v] = null)) : it(v) && (v.value = null)), ve(g))
    Ao(g, h, 12, [a, _]);
  else {
    const C = ct(g), I = it(g);
    if (C || I) {
      const P = () => {
        if (t.f) {
          const B = C ? S(g) ? m[g] : _[g] : g.value;
          l ? _e(B) && wf(B, u) : _e(B) ? B.includes(u) || B.push(u) : C ? (_[g] = [u], S(g) && (m[g] = _[g])) : (g.value = [u], t.k && (_[t.k] = g.value));
        } else
          C ? (_[g] = a, S(g) && (m[g] = a)) : I && (g.value = a, t.k && (_[t.k] = a));
      };
      a ? (P.id = -1, ln(P, i)) : P();
    }
  }
}
const Pi = (t) => !!t.type.__asyncLoader, kd = (t) => t.type.__isKeepAlive;
function E1(t, n) {
  Zd(t, "a", n);
}
function x1(t, n) {
  Zd(t, "da", n);
}
function Zd(t, n, i = Pt) {
  const o = t.__wdc || (t.__wdc = () => {
    let l = i;
    for (; l; ) {
      if (l.isDeactivated)
        return;
      l = l.parent;
    }
    return t();
  });
  if (hl(n, o, i), i) {
    let l = i.parent;
    for (; l && l.parent; )
      kd(l.parent.vnode) && S1(o, n, i, l), l = l.parent;
  }
}
function S1(t, n, i, o) {
  const l = hl(
    n,
    t,
    o,
    !0
    /* prepend */
  );
  Qd(() => {
    wf(o[n], l);
  }, i);
}
function hl(t, n, i = Pt, o = !1) {
  if (i) {
    const l = i[t] || (i[t] = []), u = n.__weh || (n.__weh = (...a) => {
      Fr();
      const h = Co(i), g = Kn(n, i, t, a);
      return h(), Mr(), g;
    });
    return o ? l.unshift(u) : l.push(u), u;
  }
}
const sr = (t) => (n, i = Pt) => {
  (!gl || t === "sp") && hl(t, (...o) => n(...o), i);
}, T1 = sr("bm"), Bf = sr("m"), A1 = sr(
  "bu"
), C1 = sr("u"), O1 = sr(
  "bum"
), Qd = sr("um"), I1 = sr(
  "sp"
), D1 = sr("rtg"), R1 = sr("rtc");
function P1(t, n = Pt) {
  hl("ec", t, n);
}
const F1 = Symbol.for("v-ndc");
function M1(t, n, i, o) {
  let l;
  const u = i && i[o], a = _e(t);
  if (a || ct(t)) {
    const h = a && Di(t);
    let g = !1;
    h && (g = !wn(t), t = fl(t)), l = new Array(t.length);
    for (let v = 0, _ = t.length; v < _; v++)
      l[v] = n(
        g ? Rt(t[v]) : t[v],
        v,
        void 0,
        u && u[v]
      );
  } else if (typeof t == "number") {
    l = new Array(t);
    for (let h = 0; h < t; h++)
      l[h] = n(h + 1, h, void 0, u && u[h]);
  } else if (ke(t))
    if (t[Symbol.iterator])
      l = Array.from(
        t,
        (h, g) => n(h, g, void 0, u && u[g])
      );
    else {
      const h = Object.keys(t);
      l = new Array(h.length);
      for (let g = 0, v = h.length; g < v; g++) {
        const _ = h[g];
        l[g] = n(t[_], _, g, u && u[g]);
      }
    }
  else
    l = [];
  return i && (i[o] = l), l;
}
function N1(t, n, i = {}, o, l) {
  if (Yt.ce || Yt.parent && Pi(Yt.parent) && Yt.parent.ce)
    return n !== "default" && (i.name = n), Hn(), ff(
      fn,
      null,
      [En("slot", i, o && o())],
      64
    );
  let u = t[n];
  u && u._c && (u._d = !1), Hn();
  const a = u && jd(u(i)), h = ff(
    fn,
    {
      key: (i.key || // slot content array of a dynamic conditional slot may have a branch
      // key attached in the `createSlots` helper, respect that
      a && a.key || `_${n}`) + // #7256 force differentiate fallback content from actual content
      (!a && o ? "_fb" : "")
    },
    a || (o ? o() : []),
    a && t._ === 1 ? 64 : -2
  );
  return !l && h.scopeId && (h.slotScopeIds = [h.scopeId + "-s"]), u && u._c && (u._d = !0), h;
}
function jd(t) {
  return t.some((n) => bp(n) ? !(n.type === Dr || n.type === fn && !jd(n.children)) : !0) ? t : null;
}
const of = (t) => t ? Ep(t) ? $f(t) : of(t.parent) : null, co = (
  // Move PURE marker to new line to workaround compiler discarding it
  // due to type annotation
  /* @__PURE__ */ At(/* @__PURE__ */ Object.create(null), {
    $: (t) => t,
    $el: (t) => t.vnode.el,
    $data: (t) => t.data,
    $props: (t) => t.props,
    $attrs: (t) => t.attrs,
    $slots: (t) => t.slots,
    $refs: (t) => t.refs,
    $parent: (t) => of(t.parent),
    $root: (t) => of(t.root),
    $host: (t) => t.ce,
    $emit: (t) => t.emit,
    $options: (t) => Wf(t),
    $forceUpdate: (t) => t.f || (t.f = () => {
      Mf(t.update);
    }),
    $nextTick: (t) => t.n || (t.n = Ir.bind(t.proxy)),
    $watch: (t) => nE.bind(t)
  })
), Nu = (t, n) => t !== Ye && !t.__isScriptSetup && Be(t, n), L1 = {
  get({ _: t }, n) {
    if (n === "__v_skip")
      return !0;
    const { ctx: i, setupState: o, data: l, props: u, accessCache: a, type: h, appContext: g } = t;
    let v;
    if (n[0] !== "$") {
      const S = a[n];
      if (S !== void 0)
        switch (S) {
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
        if (Nu(o, n))
          return a[n] = 1, o[n];
        if (l !== Ye && Be(l, n))
          return a[n] = 2, l[n];
        if (
          // only cache other properties when instance has declared (thus stable)
          // props
          (v = t.propsOptions[0]) && Be(v, n)
        )
          return a[n] = 3, u[n];
        if (i !== Ye && Be(i, n))
          return a[n] = 4, i[n];
        sf && (a[n] = 0);
      }
    }
    const _ = co[n];
    let m, A;
    if (_)
      return n === "$attrs" && Nt(t.attrs, "get", ""), _(t);
    if (
      // css module (injected by vue-loader)
      (m = h.__cssModules) && (m = m[n])
    )
      return m;
    if (i !== Ye && Be(i, n))
      return a[n] = 4, i[n];
    if (
      // global properties
      A = g.config.globalProperties, Be(A, n)
    )
      return A[n];
  },
  set({ _: t }, n, i) {
    const { data: o, setupState: l, ctx: u } = t;
    return Nu(l, n) ? (l[n] = i, !0) : o !== Ye && Be(o, n) ? (o[n] = i, !0) : Be(t.props, n) || n[0] === "$" && n.slice(1) in t ? !1 : (u[n] = i, !0);
  },
  has({
    _: { data: t, setupState: n, accessCache: i, ctx: o, appContext: l, propsOptions: u }
  }, a) {
    let h;
    return !!i[a] || t !== Ye && Be(t, a) || Nu(n, a) || (h = u[0]) && Be(h, a) || Be(o, a) || Be(co, a) || Be(l.config.globalProperties, a);
  },
  defineProperty(t, n, i) {
    return i.get != null ? t._.accessCache[n] = 0 : Be(i, "value") && this.set(t, n, i.value, null), Reflect.defineProperty(t, n, i);
  }
};
function Dh(t) {
  return _e(t) ? t.reduce(
    (n, i) => (n[i] = null, n),
    {}
  ) : t;
}
let sf = !0;
function B1(t) {
  const n = Wf(t), i = t.proxy, o = t.ctx;
  sf = !1, n.beforeCreate && Rh(n.beforeCreate, t, "bc");
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
    mounted: A,
    beforeUpdate: S,
    updated: C,
    activated: I,
    deactivated: P,
    beforeDestroy: B,
    beforeUnmount: z,
    destroyed: X,
    unmounted: L,
    render: te,
    renderTracked: Ee,
    renderTriggered: Se,
    errorCaptured: Re,
    serverPrefetch: ue,
    // public API
    expose: Z,
    inheritAttrs: he,
    // assets
    components: Ce,
    directives: We,
    filters: pt
  } = n;
  if (v && W1(v, o, null), a)
    for (const fe in a) {
      const ie = a[fe];
      ve(ie) && (o[fe] = ie.bind(i));
    }
  if (l) {
    const fe = l.call(i, i);
    ke(fe) && (t.data = If(fe));
  }
  if (sf = !0, u)
    for (const fe in u) {
      const ie = u[fe], mt = ve(ie) ? ie.bind(i, i) : ve(ie.get) ? ie.get.bind(i, i) : Un, gt = !ve(ie) && ve(ie.set) ? ie.set.bind(i) : Un, qe = Kf({
        get: mt,
        set: gt
      });
      Object.defineProperty(o, fe, {
        enumerable: !0,
        configurable: !0,
        get: () => qe.value,
        set: (ot) => qe.value = ot
      });
    }
  if (h)
    for (const fe in h)
      ep(h[fe], o, i, fe);
  if (g) {
    const fe = ve(g) ? g.call(i) : g;
    Reflect.ownKeys(fe).forEach((ie) => {
      np(ie, fe[ie]);
    });
  }
  _ && Rh(_, t, "c");
  function me(fe, ie) {
    _e(ie) ? ie.forEach((mt) => fe(mt.bind(i))) : ie && fe(ie.bind(i));
  }
  if (me(T1, m), me(Bf, A), me(A1, S), me(C1, C), me(E1, I), me(x1, P), me(P1, Re), me(R1, Ee), me(D1, Se), me(O1, z), me(Qd, L), me(I1, ue), _e(Z))
    if (Z.length) {
      const fe = t.exposed || (t.exposed = {});
      Z.forEach((ie) => {
        Object.defineProperty(fe, ie, {
          get: () => i[ie],
          set: (mt) => i[ie] = mt
        });
      });
    } else
      t.exposed || (t.exposed = {});
  te && t.render === Un && (t.render = te), he != null && (t.inheritAttrs = he), Ce && (t.components = Ce), We && (t.directives = We), ue && Jd(t);
}
function W1(t, n, i = Un) {
  _e(t) && (t = lf(t));
  for (const o in t) {
    const l = t[o];
    let u;
    ke(l) ? "default" in l ? u = ir(
      l.from || o,
      l.default,
      !0
    ) : u = ir(l.from || o) : u = ir(l), it(u) ? Object.defineProperty(n, o, {
      enumerable: !0,
      configurable: !0,
      get: () => u.value,
      set: (a) => u.value = a
    }) : n[o] = u;
  }
}
function Rh(t, n, i) {
  Kn(
    _e(t) ? t.map((o) => o.bind(n.proxy)) : t.bind(n.proxy),
    n,
    i
  );
}
function ep(t, n, i, o) {
  let l = o.includes(".") ? gp(i, o) : () => i[o];
  if (ct(t)) {
    const u = n[t];
    ve(u) && Mt(l, u);
  } else if (ve(t))
    Mt(l, t.bind(i));
  else if (ke(t))
    if (_e(t))
      t.forEach((u) => ep(u, n, i, o));
    else {
      const u = ve(t.handler) ? t.handler.bind(i) : n[t.handler];
      ve(u) && Mt(l, u, t);
    }
}
function Wf(t) {
  const n = t.type, { mixins: i, extends: o } = n, {
    mixins: l,
    optionsCache: u,
    config: { optionMergeStrategies: a }
  } = t.appContext, h = u.get(n);
  let g;
  return h ? g = h : !l.length && !i && !o ? g = n : (g = {}, l.length && l.forEach(
    (v) => Vs(g, v, a, !0)
  ), Vs(g, n, a)), ke(n) && u.set(n, g), g;
}
function Vs(t, n, i, o = !1) {
  const { mixins: l, extends: u } = n;
  u && Vs(t, u, i, !0), l && l.forEach(
    (a) => Vs(t, a, i, !0)
  );
  for (const a in n)
    if (!(o && a === "expose")) {
      const h = U1[a] || i && i[a];
      t[a] = h ? h(t[a], n[a]) : n[a];
    }
  return t;
}
const U1 = {
  data: Ph,
  props: Fh,
  emits: Fh,
  // objects
  methods: io,
  computed: io,
  // lifecycle
  beforeCreate: Ht,
  created: Ht,
  beforeMount: Ht,
  mounted: Ht,
  beforeUpdate: Ht,
  updated: Ht,
  beforeDestroy: Ht,
  beforeUnmount: Ht,
  destroyed: Ht,
  unmounted: Ht,
  activated: Ht,
  deactivated: Ht,
  errorCaptured: Ht,
  serverPrefetch: Ht,
  // assets
  components: io,
  directives: io,
  // watch
  watch: $1,
  // provide / inject
  provide: Ph,
  inject: H1
};
function Ph(t, n) {
  return n ? t ? function() {
    return At(
      ve(t) ? t.call(this, this) : t,
      ve(n) ? n.call(this, this) : n
    );
  } : n : t;
}
function H1(t, n) {
  return io(lf(t), lf(n));
}
function lf(t) {
  if (_e(t)) {
    const n = {};
    for (let i = 0; i < t.length; i++)
      n[t[i]] = t[i];
    return n;
  }
  return t;
}
function Ht(t, n) {
  return t ? [...new Set([].concat(t, n))] : n;
}
function io(t, n) {
  return t ? At(/* @__PURE__ */ Object.create(null), t, n) : n;
}
function Fh(t, n) {
  return t ? _e(t) && _e(n) ? [.../* @__PURE__ */ new Set([...t, ...n])] : At(
    /* @__PURE__ */ Object.create(null),
    Dh(t),
    Dh(n ?? {})
  ) : n;
}
function $1(t, n) {
  if (!t)
    return n;
  if (!n)
    return t;
  const i = At(/* @__PURE__ */ Object.create(null), t);
  for (const o in n)
    i[o] = Ht(t[o], n[o]);
  return i;
}
function tp() {
  return {
    app: null,
    config: {
      isNativeTag: yw,
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
let K1 = 0;
function G1(t, n) {
  return function(o, l = null) {
    ve(o) || (o = At({}, o)), l != null && !ke(l) && (l = null);
    const u = tp(), a = /* @__PURE__ */ new WeakSet(), h = [];
    let g = !1;
    const v = u.app = {
      _uid: K1++,
      _component: o,
      _props: l,
      _container: null,
      _context: u,
      _instance: null,
      version: xE,
      get config() {
        return u.config;
      },
      set config(_) {
      },
      use(_, ...m) {
        return a.has(_) || (_ && ve(_.install) ? (a.add(_), _.install(v, ...m)) : ve(_) && (a.add(_), _(v, ...m))), v;
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
      mount(_, m, A) {
        if (!g) {
          const S = v._ceVNode || En(o, l);
          return S.appContext = u, A === !0 ? A = "svg" : A === !1 && (A = void 0), m && n ? n(S, _) : t(S, _, A), g = !0, v._container = _, _.__vue_app__ = v, $f(S.component);
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
        ), t(null, v._container), delete v._container.__vue_app__);
      },
      provide(_, m) {
        return u.provides[_] = m, v;
      },
      runWithContext(_) {
        const m = Fi;
        Fi = v;
        try {
          return _();
        } finally {
          Fi = m;
        }
      }
    };
    return v;
  };
}
let Fi = null;
function np(t, n) {
  if (Pt) {
    let i = Pt.provides;
    const o = Pt.parent && Pt.parent.provides;
    o === i && (i = Pt.provides = Object.create(o)), i[t] = n;
  }
}
function ir(t, n, i = !1) {
  const o = Pt || Yt;
  if (o || Fi) {
    const l = Fi ? Fi._context.provides : o ? o.parent == null ? o.vnode.appContext && o.vnode.appContext.provides : o.parent.provides : void 0;
    if (l && t in l)
      return l[t];
    if (arguments.length > 1)
      return i && ve(n) ? n.call(o && o.proxy) : n;
  }
}
const rp = {}, ip = () => Object.create(rp), op = (t) => Object.getPrototypeOf(t) === rp;
function Y1(t, n, i, o = !1) {
  const l = {}, u = ip();
  t.propsDefaults = /* @__PURE__ */ Object.create(null), sp(t, n, l, u);
  for (const a in t.propsOptions[0])
    a in l || (l[a] = void 0);
  i ? t.props = o ? l : n1(l) : t.type.props ? t.props = l : t.props = u, t.attrs = u;
}
function z1(t, n, i, o) {
  const {
    props: l,
    attrs: u,
    vnode: { patchFlag: a }
  } = t, h = Ne(l), [g] = t.propsOptions;
  let v = !1;
  if (
    // always force full diff in dev
    // - #1942 if hmr is enabled with sfc component
    // - vite#872 non-sfc component used by sfc component
    (o || a > 0) && !(a & 16)
  ) {
    if (a & 8) {
      const _ = t.vnode.dynamicProps;
      for (let m = 0; m < _.length; m++) {
        let A = _[m];
        if (dl(t.emitsOptions, A))
          continue;
        const S = n[A];
        if (g)
          if (Be(u, A))
            S !== u[A] && (u[A] = S, v = !0);
          else {
            const C = ei(A);
            l[C] = uf(
              g,
              h,
              C,
              S,
              t,
              !1
            );
          }
        else
          S !== u[A] && (u[A] = S, v = !0);
      }
    }
  } else {
    sp(t, n, l, u) && (v = !0);
    let _;
    for (const m in h)
      (!n || // for camelCase
      !Be(n, m) && // it's possible the original props was passed in as kebab-case
      // and converted to camelCase (#955)
      ((_ = Pr(m)) === m || !Be(n, _))) && (g ? i && // for camelCase
      (i[m] !== void 0 || // for kebab-case
      i[_] !== void 0) && (l[m] = uf(
        g,
        h,
        m,
        void 0,
        t,
        !0
      )) : delete l[m]);
    if (u !== h)
      for (const m in u)
        (!n || !Be(n, m)) && (delete u[m], v = !0);
  }
  v && rr(t.attrs, "set", "");
}
function sp(t, n, i, o) {
  const [l, u] = t.propsOptions;
  let a = !1, h;
  if (n)
    for (let g in n) {
      if (fo(g))
        continue;
      const v = n[g];
      let _;
      l && Be(l, _ = ei(g)) ? !u || !u.includes(_) ? i[_] = v : (h || (h = {}))[_] = v : dl(t.emitsOptions, g) || (!(g in o) || v !== o[g]) && (o[g] = v, a = !0);
    }
  if (u) {
    const g = Ne(i), v = h || Ye;
    for (let _ = 0; _ < u.length; _++) {
      const m = u[_];
      i[m] = uf(
        l,
        g,
        m,
        v[m],
        t,
        !Be(v, m)
      );
    }
  }
  return a;
}
function uf(t, n, i, o, l, u) {
  const a = t[i];
  if (a != null) {
    const h = Be(a, "default");
    if (h && o === void 0) {
      const g = a.default;
      if (a.type !== Function && !a.skipFactory && ve(g)) {
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
    ] && (o === "" || o === Pr(i)) && (o = !0));
  }
  return o;
}
const X1 = /* @__PURE__ */ new WeakMap();
function lp(t, n, i = !1) {
  const o = i ? X1 : n.propsCache, l = o.get(t);
  if (l)
    return l;
  const u = t.props, a = {}, h = [];
  let g = !1;
  if (!ve(t)) {
    const _ = (m) => {
      g = !0;
      const [A, S] = lp(m, n, !0);
      At(a, A), S && h.push(...S);
    };
    !i && n.mixins.length && n.mixins.forEach(_), t.extends && _(t.extends), t.mixins && t.mixins.forEach(_);
  }
  if (!u && !g)
    return ke(t) && o.set(t, Oi), Oi;
  if (_e(u))
    for (let _ = 0; _ < u.length; _++) {
      const m = ei(u[_]);
      Mh(m) && (a[m] = Ye);
    }
  else if (u)
    for (const _ in u) {
      const m = ei(_);
      if (Mh(m)) {
        const A = u[_], S = a[m] = _e(A) || ve(A) ? { type: A } : At({}, A), C = S.type;
        let I = !1, P = !0;
        if (_e(C))
          for (let B = 0; B < C.length; ++B) {
            const z = C[B], X = ve(z) && z.name;
            if (X === "Boolean") {
              I = !0;
              break;
            } else
              X === "String" && (P = !1);
          }
        else
          I = ve(C) && C.name === "Boolean";
        S[
          0
          /* shouldCast */
        ] = I, S[
          1
          /* shouldCastTrue */
        ] = P, (I || Be(S, "default")) && h.push(m);
      }
    }
  const v = [a, h];
  return ke(t) && o.set(t, v), v;
}
function Mh(t) {
  return t[0] !== "$" && !fo(t);
}
const up = (t) => t[0] === "_" || t === "$stable", Uf = (t) => _e(t) ? t.map(Wn) : [Wn(t)], q1 = (t, n, i) => {
  if (n._n)
    return n;
  const o = Vd((...l) => Uf(n(...l)), i);
  return o._c = !1, o;
}, fp = (t, n, i) => {
  const o = t._ctx;
  for (const l in t) {
    if (up(l))
      continue;
    const u = t[l];
    if (ve(u))
      n[l] = q1(l, u, o);
    else if (u != null) {
      const a = Uf(u);
      n[l] = () => a;
    }
  }
}, ap = (t, n) => {
  const i = Uf(n);
  t.slots.default = () => i;
}, cp = (t, n, i) => {
  for (const o in n)
    (i || o !== "_") && (t[o] = n[o]);
}, V1 = (t, n, i) => {
  const o = t.slots = ip();
  if (t.vnode.shapeFlag & 32) {
    const l = n._;
    l ? (cp(o, n, i), i && wd(o, "_", l, !0)) : fp(n, o);
  } else
    n && ap(t, n);
}, J1 = (t, n, i) => {
  const { vnode: o, slots: l } = t;
  let u = !0, a = Ye;
  if (o.shapeFlag & 32) {
    const h = n._;
    h ? i && h === 1 ? u = !1 : cp(l, n, i) : (u = !n.$stable, fp(n, l)), a = n;
  } else
    n && (ap(t, n), a = { default: 1 });
  if (u)
    for (const h in l)
      !up(h) && a[h] == null && delete l[h];
}, ln = fE;
function k1(t) {
  return Z1(t);
}
function Z1(t, n) {
  const i = Ed();
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
    nextSibling: A,
    setScopeId: S = Un,
    insertStaticContent: C
  } = t, I = (y, E, D, $ = null, N = null, H = null, q = void 0, G = null, K = !!E.dynamicChildren) => {
    if (y === E)
      return;
    y && !ji(y, E) && ($ = ri(y), ot(y, N, H, !0), y = null), E.patchFlag === -2 && (K = !1, E.dynamicChildren = null);
    const { type: U, ref: ne, shapeFlag: J } = E;
    switch (U) {
      case pl:
        P(y, E, D, $);
        break;
      case Dr:
        B(y, E, D, $);
        break;
      case Wu:
        y == null && z(E, D, $, q);
        break;
      case fn:
        Ce(
          y,
          E,
          D,
          $,
          N,
          H,
          q,
          G,
          K
        );
        break;
      default:
        J & 1 ? te(
          y,
          E,
          D,
          $,
          N,
          H,
          q,
          G,
          K
        ) : J & 6 ? We(
          y,
          E,
          D,
          $,
          N,
          H,
          q,
          G,
          K
        ) : (J & 64 || J & 128) && U.process(
          y,
          E,
          D,
          $,
          N,
          H,
          q,
          G,
          K,
          fr
        );
    }
    ne != null && N && rf(ne, y && y.ref, H, E || y, !E);
  }, P = (y, E, D, $) => {
    if (y == null)
      o(
        E.el = h(E.children),
        D,
        $
      );
    else {
      const N = E.el = y.el;
      E.children !== y.children && v(N, E.children);
    }
  }, B = (y, E, D, $) => {
    y == null ? o(
      E.el = g(E.children || ""),
      D,
      $
    ) : E.el = y.el;
  }, z = (y, E, D, $) => {
    [y.el, y.anchor] = C(
      y.children,
      E,
      D,
      $,
      y.el,
      y.anchor
    );
  }, X = ({ el: y, anchor: E }, D, $) => {
    let N;
    for (; y && y !== E; )
      N = A(y), o(y, D, $), y = N;
    o(E, D, $);
  }, L = ({ el: y, anchor: E }) => {
    let D;
    for (; y && y !== E; )
      D = A(y), l(y), y = D;
    l(E);
  }, te = (y, E, D, $, N, H, q, G, K) => {
    E.type === "svg" ? q = "svg" : E.type === "math" && (q = "mathml"), y == null ? Ee(
      E,
      D,
      $,
      N,
      H,
      q,
      G,
      K
    ) : ue(
      y,
      E,
      N,
      H,
      q,
      G,
      K
    );
  }, Ee = (y, E, D, $, N, H, q, G) => {
    let K, U;
    const { props: ne, shapeFlag: J, transition: Q, dirs: ae } = y;
    if (K = y.el = a(
      y.type,
      H,
      ne && ne.is,
      ne
    ), J & 8 ? _(K, y.children) : J & 16 && Re(
      y.children,
      K,
      null,
      $,
      N,
      Lu(y, H),
      q,
      G
    ), ae && Yr(y, null, $, "created"), Se(K, y, y.scopeId, q, $), ne) {
      for (const Pe in ne)
        Pe !== "value" && !fo(Pe) && u(K, Pe, null, ne[Pe], H, $);
      "value" in ne && u(K, "value", null, ne.value, H), (U = ne.onVnodeBeforeMount) && Nn(U, $, y);
    }
    ae && Yr(y, null, $, "beforeMount");
    const ye = Q1(N, Q);
    ye && Q.beforeEnter(K), o(K, E, D), ((U = ne && ne.onVnodeMounted) || ye || ae) && ln(() => {
      U && Nn(U, $, y), ye && Q.enter(K), ae && Yr(y, null, $, "mounted");
    }, N);
  }, Se = (y, E, D, $, N) => {
    if (D && S(y, D), $)
      for (let H = 0; H < $.length; H++)
        S(y, $[H]);
    if (N) {
      let H = N.subTree;
      if (E === H || _p(H.type) && (H.ssContent === E || H.ssFallback === E)) {
        const q = N.vnode;
        Se(
          y,
          q,
          q.scopeId,
          q.slotScopeIds,
          N.parent
        );
      }
    }
  }, Re = (y, E, D, $, N, H, q, G, K = 0) => {
    for (let U = K; U < y.length; U++) {
      const ne = y[U] = G ? xr(y[U]) : Wn(y[U]);
      I(
        null,
        ne,
        E,
        D,
        $,
        N,
        H,
        q,
        G
      );
    }
  }, ue = (y, E, D, $, N, H, q) => {
    const G = E.el = y.el;
    let { patchFlag: K, dynamicChildren: U, dirs: ne } = E;
    K |= y.patchFlag & 16;
    const J = y.props || Ye, Q = E.props || Ye;
    let ae;
    if (D && zr(D, !1), (ae = Q.onVnodeBeforeUpdate) && Nn(ae, D, E, y), ne && Yr(E, y, D, "beforeUpdate"), D && zr(D, !0), (J.innerHTML && Q.innerHTML == null || J.textContent && Q.textContent == null) && _(G, ""), U ? Z(
      y.dynamicChildren,
      U,
      G,
      D,
      $,
      Lu(E, N),
      H
    ) : q || ie(
      y,
      E,
      G,
      null,
      D,
      $,
      Lu(E, N),
      H,
      !1
    ), K > 0) {
      if (K & 16)
        he(G, J, Q, D, N);
      else if (K & 2 && J.class !== Q.class && u(G, "class", null, Q.class, N), K & 4 && u(G, "style", J.style, Q.style, N), K & 8) {
        const ye = E.dynamicProps;
        for (let Pe = 0; Pe < ye.length; Pe++) {
          const Fe = ye[Pe], yt = J[Fe], lt = Q[Fe];
          (lt !== yt || Fe === "value") && u(G, Fe, yt, lt, N, D);
        }
      }
      K & 1 && y.children !== E.children && _(G, E.children);
    } else
      !q && U == null && he(G, J, Q, D, N);
    ((ae = Q.onVnodeUpdated) || ne) && ln(() => {
      ae && Nn(ae, D, E, y), ne && Yr(E, y, D, "updated");
    }, $);
  }, Z = (y, E, D, $, N, H, q) => {
    for (let G = 0; G < E.length; G++) {
      const K = y[G], U = E[G], ne = (
        // oldVNode may be an errored async setup() component inside Suspense
        // which will not have a mounted element
        K.el && // - In the case of a Fragment, we need to provide the actual parent
        // of the Fragment itself so it can move its children.
        (K.type === fn || // - In the case of different nodes, there is going to be a replacement
        // which also requires the correct parent container
        !ji(K, U) || // - In the case of a component, it could contain anything.
        K.shapeFlag & 70) ? m(K.el) : (
          // In other cases, the parent container is not actually used so we
          // just pass the block element here to avoid a DOM parentNode call.
          D
        )
      );
      I(
        K,
        U,
        ne,
        null,
        $,
        N,
        H,
        q,
        !0
      );
    }
  }, he = (y, E, D, $, N) => {
    if (E !== D) {
      if (E !== Ye)
        for (const H in E)
          !fo(H) && !(H in D) && u(
            y,
            H,
            E[H],
            null,
            N,
            $
          );
      for (const H in D) {
        if (fo(H))
          continue;
        const q = D[H], G = E[H];
        q !== G && H !== "value" && u(y, H, G, q, N, $);
      }
      "value" in D && u(y, "value", E.value, D.value, N);
    }
  }, Ce = (y, E, D, $, N, H, q, G, K) => {
    const U = E.el = y ? y.el : h(""), ne = E.anchor = y ? y.anchor : h("");
    let { patchFlag: J, dynamicChildren: Q, slotScopeIds: ae } = E;
    ae && (G = G ? G.concat(ae) : ae), y == null ? (o(U, D, $), o(ne, D, $), Re(
      // #10007
      // such fragment like `<></>` will be compiled into
      // a fragment which doesn't have a children.
      // In this case fallback to an empty array
      E.children || [],
      D,
      ne,
      N,
      H,
      q,
      G,
      K
    )) : J > 0 && J & 64 && Q && // #2715 the previous fragment could've been a BAILed one as a result
    // of renderSlot() with no valid children
    y.dynamicChildren ? (Z(
      y.dynamicChildren,
      Q,
      D,
      N,
      H,
      q,
      G
    ), // #2080 if the stable fragment has a key, it's a <template v-for> that may
    //  get moved around. Make sure all root level vnodes inherit el.
    // #2134 or if it's a component root, it may also get moved around
    // as the component is being moved.
    (E.key != null || N && E === N.subTree) && hp(
      y,
      E,
      !0
      /* shallow */
    )) : ie(
      y,
      E,
      D,
      ne,
      N,
      H,
      q,
      G,
      K
    );
  }, We = (y, E, D, $, N, H, q, G, K) => {
    E.slotScopeIds = G, y == null ? E.shapeFlag & 512 ? N.ctx.activate(
      E,
      D,
      $,
      q,
      K
    ) : pt(
      E,
      D,
      $,
      N,
      H,
      q,
      K
    ) : Ct(y, E, K);
  }, pt = (y, E, D, $, N, H, q) => {
    const G = y.component = vE(
      y,
      $,
      N
    );
    if (kd(y) && (G.ctx.renderer = fr), mE(G, !1, q), G.asyncDep) {
      if (N && N.registerDep(G, me, q), !y.el) {
        const K = G.subTree = En(Dr);
        B(null, K, E, D);
      }
    } else
      me(
        G,
        y,
        E,
        D,
        N,
        H,
        q
      );
  }, Ct = (y, E, D) => {
    const $ = E.component = y.component;
    if (lE(y, E, D))
      if ($.asyncDep && !$.asyncResolved) {
        fe($, E, D);
        return;
      } else
        $.next = E, $.update();
    else
      E.el = y.el, $.vnode = E;
  }, me = (y, E, D, $, N, H, q) => {
    const G = () => {
      if (y.isMounted) {
        let { next: J, bu: Q, u: ae, parent: ye, vnode: Pe } = y;
        {
          const wt = dp(y);
          if (wt) {
            J && (J.el = Pe.el, fe(y, J, q)), wt.asyncDep.then(() => {
              y.isUnmounted || G();
            });
            return;
          }
        }
        let Fe = J, yt;
        zr(y, !1), J ? (J.el = Pe.el, fe(y, J, q)) : J = Pe, Q && Du(Q), (yt = J.props && J.props.onVnodeBeforeUpdate) && Nn(yt, ye, J, Pe), zr(y, !0);
        const lt = Bu(y), Lt = y.subTree;
        y.subTree = lt, I(
          Lt,
          lt,
          // parent may have changed if it's in a teleport
          m(Lt.el),
          // anchor may have changed if it's in a fragment
          ri(Lt),
          y,
          N,
          H
        ), J.el = lt.el, Fe === null && uE(y, lt.el), ae && ln(ae, N), (yt = J.props && J.props.onVnodeUpdated) && ln(
          () => Nn(yt, ye, J, Pe),
          N
        );
      } else {
        let J;
        const { el: Q, props: ae } = E, { bm: ye, m: Pe, parent: Fe, root: yt, type: lt } = y, Lt = Pi(E);
        if (zr(y, !1), ye && Du(ye), !Lt && (J = ae && ae.onVnodeBeforeMount) && Nn(J, Fe, E), zr(y, !0), Q && cr) {
          const wt = () => {
            y.subTree = Bu(y), cr(
              Q,
              y.subTree,
              y,
              N,
              null
            );
          };
          Lt && lt.__asyncHydrate ? lt.__asyncHydrate(
            Q,
            y,
            wt
          ) : wt();
        } else {
          yt.ce && yt.ce._injectChildStyle(lt);
          const wt = y.subTree = Bu(y);
          I(
            null,
            wt,
            D,
            $,
            y,
            N,
            H
          ), E.el = wt.el;
        }
        if (Pe && ln(Pe, N), !Lt && (J = ae && ae.onVnodeMounted)) {
          const wt = E;
          ln(
            () => Nn(J, Fe, wt),
            N
          );
        }
        (E.shapeFlag & 256 || Fe && Pi(Fe.vnode) && Fe.vnode.shapeFlag & 256) && y.a && ln(y.a, N), y.isMounted = !0, E = D = $ = null;
      }
    };
    y.scope.on();
    const K = y.effect = new Cd(G);
    y.scope.off();
    const U = y.update = K.run.bind(K), ne = y.job = K.runIfDirty.bind(K);
    ne.i = y, ne.id = y.uid, K.scheduler = () => Mf(ne), zr(y, !0), U();
  }, fe = (y, E, D) => {
    E.component = y;
    const $ = y.vnode.props;
    y.vnode = E, y.next = null, z1(y, E.props, $, D), J1(y, E.children, D), Fr(), Ih(y), Mr();
  }, ie = (y, E, D, $, N, H, q, G, K = !1) => {
    const U = y && y.children, ne = y ? y.shapeFlag : 0, J = E.children, { patchFlag: Q, shapeFlag: ae } = E;
    if (Q > 0) {
      if (Q & 128) {
        gt(
          U,
          J,
          D,
          $,
          N,
          H,
          q,
          G,
          K
        );
        return;
      } else if (Q & 256) {
        mt(
          U,
          J,
          D,
          $,
          N,
          H,
          q,
          G,
          K
        );
        return;
      }
    }
    ae & 8 ? (ne & 16 && Rn(U, N, H), J !== U && _(D, J)) : ne & 16 ? ae & 16 ? gt(
      U,
      J,
      D,
      $,
      N,
      H,
      q,
      G,
      K
    ) : Rn(U, N, H, !0) : (ne & 8 && _(D, ""), ae & 16 && Re(
      J,
      D,
      $,
      N,
      H,
      q,
      G,
      K
    ));
  }, mt = (y, E, D, $, N, H, q, G, K) => {
    y = y || Oi, E = E || Oi;
    const U = y.length, ne = E.length, J = Math.min(U, ne);
    let Q;
    for (Q = 0; Q < J; Q++) {
      const ae = E[Q] = K ? xr(E[Q]) : Wn(E[Q]);
      I(
        y[Q],
        ae,
        D,
        null,
        N,
        H,
        q,
        G,
        K
      );
    }
    U > ne ? Rn(
      y,
      N,
      H,
      !0,
      !1,
      J
    ) : Re(
      E,
      D,
      $,
      N,
      H,
      q,
      G,
      K,
      J
    );
  }, gt = (y, E, D, $, N, H, q, G, K) => {
    let U = 0;
    const ne = E.length;
    let J = y.length - 1, Q = ne - 1;
    for (; U <= J && U <= Q; ) {
      const ae = y[U], ye = E[U] = K ? xr(E[U]) : Wn(E[U]);
      if (ji(ae, ye))
        I(
          ae,
          ye,
          D,
          null,
          N,
          H,
          q,
          G,
          K
        );
      else
        break;
      U++;
    }
    for (; U <= J && U <= Q; ) {
      const ae = y[J], ye = E[Q] = K ? xr(E[Q]) : Wn(E[Q]);
      if (ji(ae, ye))
        I(
          ae,
          ye,
          D,
          null,
          N,
          H,
          q,
          G,
          K
        );
      else
        break;
      J--, Q--;
    }
    if (U > J) {
      if (U <= Q) {
        const ae = Q + 1, ye = ae < ne ? E[ae].el : $;
        for (; U <= Q; )
          I(
            null,
            E[U] = K ? xr(E[U]) : Wn(E[U]),
            D,
            ye,
            N,
            H,
            q,
            G,
            K
          ), U++;
      }
    } else if (U > Q)
      for (; U <= J; )
        ot(y[U], N, H, !0), U++;
    else {
      const ae = U, ye = U, Pe = /* @__PURE__ */ new Map();
      for (U = ye; U <= Q; U++) {
        const Et = E[U] = K ? xr(E[U]) : Wn(E[U]);
        Et.key != null && Pe.set(Et.key, U);
      }
      let Fe, yt = 0;
      const lt = Q - ye + 1;
      let Lt = !1, wt = 0;
      const zn = new Array(lt);
      for (U = 0; U < lt; U++)
        zn[U] = 0;
      for (U = ae; U <= J; U++) {
        const Et = y[U];
        if (yt >= lt) {
          ot(Et, N, H, !0);
          continue;
        }
        let zt;
        if (Et.key != null)
          zt = Pe.get(Et.key);
        else
          for (Fe = ye; Fe <= Q; Fe++)
            if (zn[Fe - ye] === 0 && ji(Et, E[Fe])) {
              zt = Fe;
              break;
            }
        zt === void 0 ? ot(Et, N, H, !0) : (zn[zt - ye] = U + 1, zt >= wt ? wt = zt : Lt = !0, I(
          Et,
          E[zt],
          D,
          null,
          N,
          H,
          q,
          G,
          K
        ), yt++);
      }
      const ii = Lt ? j1(zn) : Oi;
      for (Fe = ii.length - 1, U = lt - 1; U >= 0; U--) {
        const Et = ye + U, zt = E[Et], Do = Et + 1 < ne ? E[Et + 1].el : $;
        zn[U] === 0 ? I(
          null,
          zt,
          D,
          Do,
          N,
          H,
          q,
          G,
          K
        ) : Lt && (Fe < 0 || U !== ii[Fe] ? qe(zt, D, Do, 2) : Fe--);
      }
    }
  }, qe = (y, E, D, $, N = null) => {
    const { el: H, type: q, transition: G, children: K, shapeFlag: U } = y;
    if (U & 6) {
      qe(y.component.subTree, E, D, $);
      return;
    }
    if (U & 128) {
      y.suspense.move(E, D, $);
      return;
    }
    if (U & 64) {
      q.move(y, E, D, fr);
      return;
    }
    if (q === fn) {
      o(H, E, D);
      for (let J = 0; J < K.length; J++)
        qe(K[J], E, D, $);
      o(y.anchor, E, D);
      return;
    }
    if (q === Wu) {
      X(y, E, D);
      return;
    }
    if ($ !== 2 && U & 1 && G)
      if ($ === 0)
        G.beforeEnter(H), o(H, E, D), ln(() => G.enter(H), N);
      else {
        const { leave: J, delayLeave: Q, afterLeave: ae } = G, ye = () => o(H, E, D), Pe = () => {
          J(H, () => {
            ye(), ae && ae();
          });
        };
        Q ? Q(H, ye, Pe) : Pe();
      }
    else
      o(H, E, D);
  }, ot = (y, E, D, $ = !1, N = !1) => {
    const {
      type: H,
      props: q,
      ref: G,
      children: K,
      dynamicChildren: U,
      shapeFlag: ne,
      patchFlag: J,
      dirs: Q,
      cacheIndex: ae
    } = y;
    if (J === -2 && (N = !1), G != null && rf(G, null, D, y, !0), ae != null && (E.renderCache[ae] = void 0), ne & 256) {
      E.ctx.deactivate(y);
      return;
    }
    const ye = ne & 1 && Q, Pe = !Pi(y);
    let Fe;
    if (Pe && (Fe = q && q.onVnodeBeforeUnmount) && Nn(Fe, E, y), ne & 6)
      bt(y.component, D, $);
    else {
      if (ne & 128) {
        y.suspense.unmount(D, $);
        return;
      }
      ye && Yr(y, null, E, "beforeUnmount"), ne & 64 ? y.type.remove(
        y,
        E,
        D,
        fr,
        $
      ) : U && // #5154
      // when v-once is used inside a block, setBlockTracking(-1) marks the
      // parent block with hasOnce: true
      // so that it doesn't take the fast path during unmount - otherwise
      // components nested in v-once are never unmounted.
      !U.hasOnce && // #1153: fast path should not be taken for non-stable (v-for) fragments
      (H !== fn || J > 0 && J & 64) ? Rn(
        U,
        E,
        D,
        !1,
        !0
      ) : (H === fn && J & 384 || !N && ne & 16) && Rn(K, E, D), $ && cn(y);
    }
    (Pe && (Fe = q && q.onVnodeUnmounted) || ye) && ln(() => {
      Fe && Nn(Fe, E, y), ye && Yr(y, null, E, "unmounted");
    }, D);
  }, cn = (y) => {
    const { type: E, el: D, anchor: $, transition: N } = y;
    if (E === fn) {
      st(D, $);
      return;
    }
    if (E === Wu) {
      L(y);
      return;
    }
    const H = () => {
      l(D), N && !N.persisted && N.afterLeave && N.afterLeave();
    };
    if (y.shapeFlag & 1 && N && !N.persisted) {
      const { leave: q, delayLeave: G } = N, K = () => q(D, H);
      G ? G(y.el, H, K) : K();
    } else
      H();
  }, st = (y, E) => {
    let D;
    for (; y !== E; )
      D = A(y), l(y), y = D;
    l(E);
  }, bt = (y, E, D) => {
    const { bum: $, scope: N, job: H, subTree: q, um: G, m: K, a: U } = y;
    Nh(K), Nh(U), $ && Du($), N.stop(), H && (H.flags |= 8, ot(q, y, E, D)), G && ln(G, E), ln(() => {
      y.isUnmounted = !0;
    }, E), E && E.pendingBranch && !E.isUnmounted && y.asyncDep && !y.asyncResolved && y.suspenseId === E.pendingId && (E.deps--, E.deps === 0 && E.resolve());
  }, Rn = (y, E, D, $ = !1, N = !1, H = 0) => {
    for (let q = H; q < y.length; q++)
      ot(y[q], E, D, $, N);
  }, ri = (y) => {
    if (y.shapeFlag & 6)
      return ri(y.component.subTree);
    if (y.shapeFlag & 128)
      return y.suspense.next();
    const E = A(y.anchor || y.el), D = E && E[y1];
    return D ? A(D) : E;
  };
  let Yn = !1;
  const ur = (y, E, D) => {
    y == null ? E._vnode && ot(E._vnode, null, null, !0) : I(
      E._vnode || null,
      y,
      E,
      null,
      null,
      null,
      D
    ), E._vnode = y, Yn || (Yn = !0, Ih(), zd(), Yn = !1);
  }, fr = {
    p: I,
    um: ot,
    m: qe,
    r: cn,
    mt: pt,
    mc: Re,
    pc: ie,
    pbc: Z,
    n: ri,
    o: t
  };
  let ar, cr;
  return n && ([ar, cr] = n(
    fr
  )), {
    render: ur,
    hydrate: ar,
    createApp: G1(ur, ar)
  };
}
function Lu({ type: t, props: n }, i) {
  return i === "svg" && t === "foreignObject" || i === "mathml" && t === "annotation-xml" && n && n.encoding && n.encoding.includes("html") ? void 0 : i;
}
function zr({ effect: t, job: n }, i) {
  i ? (t.flags |= 32, n.flags |= 4) : (t.flags &= -33, n.flags &= -5);
}
function Q1(t, n) {
  return (!t || t && !t.pendingBranch) && n && !n.persisted;
}
function hp(t, n, i = !1) {
  const o = t.children, l = n.children;
  if (_e(o) && _e(l))
    for (let u = 0; u < o.length; u++) {
      const a = o[u];
      let h = l[u];
      h.shapeFlag & 1 && !h.dynamicChildren && ((h.patchFlag <= 0 || h.patchFlag === 32) && (h = l[u] = xr(l[u]), h.el = a.el), !i && h.patchFlag !== -2 && hp(a, h)), h.type === pl && (h.el = a.el);
    }
}
function j1(t) {
  const n = t.slice(), i = [0];
  let o, l, u, a, h;
  const g = t.length;
  for (o = 0; o < g; o++) {
    const v = t[o];
    if (v !== 0) {
      if (l = i[i.length - 1], t[l] < v) {
        n[o] = l, i.push(o);
        continue;
      }
      for (u = 0, a = i.length - 1; u < a; )
        h = u + a >> 1, t[i[h]] < v ? u = h + 1 : a = h;
      v < t[i[u]] && (u > 0 && (n[o] = i[u - 1]), i[u] = o);
    }
  }
  for (u = i.length, a = i[u - 1]; u-- > 0; )
    i[u] = a, a = n[a];
  return i;
}
function dp(t) {
  const n = t.subTree.component;
  if (n)
    return n.asyncDep && !n.asyncResolved ? n : dp(n);
}
function Nh(t) {
  if (t)
    for (let n = 0; n < t.length; n++)
      t[n].flags |= 8;
}
const eE = Symbol.for("v-scx"), tE = () => ir(eE);
function Mt(t, n, i) {
  return pp(t, n, i);
}
function pp(t, n, i = Ye) {
  const { immediate: o, deep: l, flush: u, once: a } = i, h = At({}, i);
  let g;
  if (gl)
    if (u === "sync") {
      const A = tE();
      g = A.__watcherHandles || (A.__watcherHandles = []);
    } else if (!n || o)
      h.once = !0;
    else {
      const A = () => {
      };
      return A.stop = Un, A.resume = Un, A.pause = Un, A;
    }
  const v = Pt;
  h.call = (A, S, C) => Kn(A, v, S, C);
  let _ = !1;
  u === "post" ? h.scheduler = (A) => {
    ln(A, v && v.suspense);
  } : u !== "sync" && (_ = !0, h.scheduler = (A, S) => {
    S ? A() : Mf(A);
  }), h.augmentJob = (A) => {
    n && (A.flags |= 4), _ && (A.flags |= 2, v && (A.id = v.uid, A.i = v));
  };
  const m = v1(t, n, h);
  return g && g.push(m), m;
}
function nE(t, n, i) {
  const o = this.proxy, l = ct(t) ? t.includes(".") ? gp(o, t) : () => o[t] : t.bind(o, o);
  let u;
  ve(n) ? u = n : (u = n.handler, i = n);
  const a = Co(this), h = pp(l, u.bind(o), i);
  return a(), h;
}
function gp(t, n) {
  const i = n.split(".");
  return () => {
    let o = t;
    for (let l = 0; l < i.length && o; l++)
      o = o[i[l]];
    return o;
  };
}
const rE = (t, n) => n === "modelValue" || n === "model-value" ? t.modelModifiers : t[`${n}Modifiers`] || t[`${ei(n)}Modifiers`] || t[`${Pr(n)}Modifiers`];
function iE(t, n, ...i) {
  if (t.isUnmounted)
    return;
  const o = t.vnode.props || Ye;
  let l = i;
  const u = n.startsWith("update:"), a = u && rE(o, n.slice(7));
  a && (a.trim && (l = i.map((_) => ct(_) ? _.trim() : _)), a.number && (l = i.map(Tw)));
  let h, g = o[h = Iu(n)] || // also try camelCase event handler (#2249)
  o[h = Iu(ei(n))];
  !g && u && (g = o[h = Iu(Pr(n))]), g && Kn(
    g,
    t,
    6,
    l
  );
  const v = o[h + "Once"];
  if (v) {
    if (!t.emitted)
      t.emitted = {};
    else if (t.emitted[h])
      return;
    t.emitted[h] = !0, Kn(
      v,
      t,
      6,
      l
    );
  }
}
function vp(t, n, i = !1) {
  const o = n.emitsCache, l = o.get(t);
  if (l !== void 0)
    return l;
  const u = t.emits;
  let a = {}, h = !1;
  if (!ve(t)) {
    const g = (v) => {
      const _ = vp(v, n, !0);
      _ && (h = !0, At(a, _));
    };
    !i && n.mixins.length && n.mixins.forEach(g), t.extends && g(t.extends), t.mixins && t.mixins.forEach(g);
  }
  return !u && !h ? (ke(t) && o.set(t, null), null) : (_e(u) ? u.forEach((g) => a[g] = null) : At(a, u), ke(t) && o.set(t, a), a);
}
function dl(t, n) {
  return !t || !il(n) ? !1 : (n = n.slice(2).replace(/Once$/, ""), Be(t, n[0].toLowerCase() + n.slice(1)) || Be(t, Pr(n)) || Be(t, n));
}
function Bu(t) {
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
    data: A,
    setupState: S,
    ctx: C,
    inheritAttrs: I
  } = t, P = qs(t);
  let B, z;
  try {
    if (i.shapeFlag & 4) {
      const L = l || o, te = L;
      B = Wn(
        v.call(
          te,
          L,
          _,
          m,
          S,
          A,
          C
        )
      ), z = h;
    } else {
      const L = n;
      B = Wn(
        L.length > 1 ? L(
          m,
          { attrs: h, slots: a, emit: g }
        ) : L(
          m,
          null
        )
      ), z = n.props ? h : oE(h);
    }
  } catch (L) {
    ho.length = 0, cl(L, t, 1), B = En(Dr);
  }
  let X = B;
  if (z && I !== !1) {
    const L = Object.keys(z), { shapeFlag: te } = X;
    L.length && te & 7 && (u && L.some(yf) && (z = sE(
      z,
      u
    )), X = Mi(X, z, !1, !0));
  }
  return i.dirs && (X = Mi(X, null, !1, !0), X.dirs = X.dirs ? X.dirs.concat(i.dirs) : i.dirs), i.transition && Nf(X, i.transition), B = X, qs(P), B;
}
const oE = (t) => {
  let n;
  for (const i in t)
    (i === "class" || i === "style" || il(i)) && ((n || (n = {}))[i] = t[i]);
  return n;
}, sE = (t, n) => {
  const i = {};
  for (const o in t)
    (!yf(o) || !(o.slice(9) in n)) && (i[o] = t[o]);
  return i;
};
function lE(t, n, i) {
  const { props: o, children: l, component: u } = t, { props: a, children: h, patchFlag: g } = n, v = u.emitsOptions;
  if (n.dirs || n.transition)
    return !0;
  if (i && g >= 0) {
    if (g & 1024)
      return !0;
    if (g & 16)
      return o ? Lh(o, a, v) : !!a;
    if (g & 8) {
      const _ = n.dynamicProps;
      for (let m = 0; m < _.length; m++) {
        const A = _[m];
        if (a[A] !== o[A] && !dl(v, A))
          return !0;
      }
    }
  } else
    return (l || h) && (!h || !h.$stable) ? !0 : o === a ? !1 : o ? a ? Lh(o, a, v) : !0 : !!a;
  return !1;
}
function Lh(t, n, i) {
  const o = Object.keys(n);
  if (o.length !== Object.keys(t).length)
    return !0;
  for (let l = 0; l < o.length; l++) {
    const u = o[l];
    if (n[u] !== t[u] && !dl(i, u))
      return !0;
  }
  return !1;
}
function uE({ vnode: t, parent: n }, i) {
  for (; n; ) {
    const o = n.subTree;
    if (o.suspense && o.suspense.activeBranch === t && (o.el = t.el), o === t)
      (t = n.vnode).el = i, n = n.parent;
    else
      break;
  }
}
const _p = (t) => t.__isSuspense;
function fE(t, n) {
  n && n.pendingBranch ? _e(t) ? n.effects.push(...t) : n.effects.push(t) : b1(t);
}
const fn = Symbol.for("v-fgt"), pl = Symbol.for("v-txt"), Dr = Symbol.for("v-cmt"), Wu = Symbol.for("v-stc"), ho = [];
let an = null;
function Hn(t = !1) {
  ho.push(an = t ? null : []);
}
function aE() {
  ho.pop(), an = ho[ho.length - 1] || null;
}
let To = 1;
function Bh(t) {
  To += t, t < 0 && an && (an.hasOnce = !0);
}
function mp(t) {
  return t.dynamicChildren = To > 0 ? an || Oi : null, aE(), To > 0 && an && an.push(t), t;
}
function Zr(t, n, i, o, l, u) {
  return mp(
    Bn(
      t,
      n,
      i,
      o,
      l,
      u,
      !0
    )
  );
}
function ff(t, n, i, o, l) {
  return mp(
    En(
      t,
      n,
      i,
      o,
      l,
      !0
    )
  );
}
function bp(t) {
  return t ? t.__v_isVNode === !0 : !1;
}
function ji(t, n) {
  return t.type === n.type && t.key === n.key;
}
const yp = ({ key: t }) => t ?? null, Bs = ({
  ref: t,
  ref_key: n,
  ref_for: i
}) => (typeof t == "number" && (t = "" + t), t != null ? ct(t) || it(t) || ve(t) ? { i: Yt, r: t, k: n, f: !!i } : t : null);
function Bn(t, n = null, i = null, o = 0, l = null, u = t === fn ? 0 : 1, a = !1, h = !1) {
  const g = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: t,
    props: n,
    key: n && yp(n),
    ref: n && Bs(n),
    scopeId: qd,
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
    ctx: Yt
  };
  return h ? (Hf(g, i), u & 128 && t.normalize(g)) : i && (g.shapeFlag |= ct(i) ? 8 : 16), To > 0 && // avoid a block node from tracking itself
  !a && // has current parent block
  an && // presence of a patch flag indicates this node needs patching on updates.
  // component nodes also should always be patched, because even if the
  // component doesn't need to update, it needs to persist the instance on to
  // the next vnode so that it can be properly unmounted later.
  (g.patchFlag > 0 || u & 6) && // the EVENTS flag is only for hydration and if it is the only flag, the
  // vnode should not be considered dynamic due to handler caching.
  g.patchFlag !== 32 && an.push(g), g;
}
const En = cE;
function cE(t, n = null, i = null, o = 0, l = null, u = !1) {
  if ((!t || t === F1) && (t = Dr), bp(t)) {
    const h = Mi(
      t,
      n,
      !0
      /* mergeRef: true */
    );
    return i && Hf(h, i), To > 0 && !u && an && (h.shapeFlag & 6 ? an[an.indexOf(t)] = h : an.push(h)), h.patchFlag = -2, h;
  }
  if (EE(t) && (t = t.__vccOpts), n) {
    n = hE(n);
    let { class: h, style: g } = n;
    h && !ct(h) && (n.class = ll(h)), ke(g) && (Rf(g) && !_e(g) && (g = At({}, g)), n.style = xf(g));
  }
  const a = ct(t) ? 1 : _p(t) ? 128 : w1(t) ? 64 : ke(t) ? 4 : ve(t) ? 2 : 0;
  return Bn(
    t,
    n,
    i,
    o,
    l,
    a,
    u,
    !0
  );
}
function hE(t) {
  return t ? Rf(t) || op(t) ? At({}, t) : t : null;
}
function Mi(t, n, i = !1, o = !1) {
  const { props: l, ref: u, patchFlag: a, children: h, transition: g } = t, v = n ? dE(l || {}, n) : l, _ = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: t.type,
    props: v,
    key: v && yp(v),
    ref: n && n.ref ? (
      // #2078 in the case of <component :is="vnode" ref="extra"/>
      // if the vnode itself already has a ref, cloneVNode will need to merge
      // the refs so the single vnode can be set on multiple refs
      i && u ? _e(u) ? u.concat(Bs(n)) : [u, Bs(n)] : Bs(n)
    ) : u,
    scopeId: t.scopeId,
    slotScopeIds: t.slotScopeIds,
    children: h,
    target: t.target,
    targetStart: t.targetStart,
    targetAnchor: t.targetAnchor,
    staticCount: t.staticCount,
    shapeFlag: t.shapeFlag,
    // if the vnode is cloned with extra props, we can no longer assume its
    // existing patch flag to be reliable and need to add the FULL_PROPS flag.
    // note: preserve flag for fragments since they use the flag for children
    // fast paths only.
    patchFlag: n && t.type !== fn ? a === -1 ? 16 : a | 16 : a,
    dynamicProps: t.dynamicProps,
    dynamicChildren: t.dynamicChildren,
    appContext: t.appContext,
    dirs: t.dirs,
    transition: g,
    // These should technically only be non-null on mounted VNodes. However,
    // they *should* be copied for kept-alive vnodes. So we just always copy
    // them since them being non-null during a mount doesn't affect the logic as
    // they will simply be overwritten.
    component: t.component,
    suspense: t.suspense,
    ssContent: t.ssContent && Mi(t.ssContent),
    ssFallback: t.ssFallback && Mi(t.ssFallback),
    el: t.el,
    anchor: t.anchor,
    ctx: t.ctx,
    ce: t.ce
  };
  return g && o && Nf(
    _,
    g.clone(_)
  ), _;
}
function wp(t = " ", n = 0) {
  return En(pl, null, t, n);
}
function Wh(t = "", n = !1) {
  return n ? (Hn(), ff(Dr, null, t)) : En(Dr, null, t);
}
function Wn(t) {
  return t == null || typeof t == "boolean" ? En(Dr) : _e(t) ? En(
    fn,
    null,
    // #3666, avoid reference pollution when reusing vnode
    t.slice()
  ) : typeof t == "object" ? xr(t) : En(pl, null, String(t));
}
function xr(t) {
  return t.el === null && t.patchFlag !== -1 || t.memo ? t : Mi(t);
}
function Hf(t, n) {
  let i = 0;
  const { shapeFlag: o } = t;
  if (n == null)
    n = null;
  else if (_e(n))
    i = 16;
  else if (typeof n == "object")
    if (o & 65) {
      const l = n.default;
      l && (l._c && (l._d = !1), Hf(t, l()), l._c && (l._d = !0));
      return;
    } else {
      i = 32;
      const l = n._;
      !l && !op(n) ? n._ctx = Yt : l === 3 && Yt && (Yt.slots._ === 1 ? n._ = 1 : (n._ = 2, t.patchFlag |= 1024));
    }
  else
    ve(n) ? (n = { default: n, _ctx: Yt }, i = 32) : (n = String(n), o & 64 ? (i = 16, n = [wp(n)]) : i = 8);
  t.children = n, t.shapeFlag |= i;
}
function dE(...t) {
  const n = {};
  for (let i = 0; i < t.length; i++) {
    const o = t[i];
    for (const l in o)
      if (l === "class")
        n.class !== o.class && (n.class = ll([n.class, o.class]));
      else if (l === "style")
        n.style = xf([n.style, o.style]);
      else if (il(l)) {
        const u = n[l], a = o[l];
        a && u !== a && !(_e(u) && u.includes(a)) && (n[l] = u ? [].concat(u, a) : a);
      } else
        l !== "" && (n[l] = o[l]);
  }
  return n;
}
function Nn(t, n, i, o = null) {
  Kn(t, n, 7, [
    i,
    o
  ]);
}
const pE = tp();
let gE = 0;
function vE(t, n, i) {
  const o = t.type, l = (n ? n.appContext : t.appContext) || pE, u = {
    uid: gE++,
    vnode: t,
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
    scope: new Pw(
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
    propsOptions: lp(o, l),
    emitsOptions: vp(o, l),
    // emit
    emit: null,
    // to be set immediately
    emitted: null,
    // props default value
    propsDefaults: Ye,
    // inheritAttrs
    inheritAttrs: o.inheritAttrs,
    // state
    ctx: Ye,
    data: Ye,
    props: Ye,
    attrs: Ye,
    slots: Ye,
    refs: Ye,
    setupState: Ye,
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
  return u.ctx = { _: u }, u.root = n ? n.root : u, u.emit = iE.bind(null, u), t.ce && t.ce(u), u;
}
let Pt = null;
const _E = () => Pt || Yt;
let Js, af;
{
  const t = Ed(), n = (i, o) => {
    let l;
    return (l = t[i]) || (l = t[i] = []), l.push(o), (u) => {
      l.length > 1 ? l.forEach((a) => a(u)) : l[0](u);
    };
  };
  Js = n(
    "__VUE_INSTANCE_SETTERS__",
    (i) => Pt = i
  ), af = n(
    "__VUE_SSR_SETTERS__",
    (i) => gl = i
  );
}
const Co = (t) => {
  const n = Pt;
  return Js(t), t.scope.on(), () => {
    t.scope.off(), Js(n);
  };
}, Uh = () => {
  Pt && Pt.scope.off(), Js(null);
};
function Ep(t) {
  return t.vnode.shapeFlag & 4;
}
let gl = !1;
function mE(t, n = !1, i = !1) {
  n && af(n);
  const { props: o, children: l } = t.vnode, u = Ep(t);
  Y1(t, o, u, n), V1(t, l, i);
  const a = u ? bE(t, n) : void 0;
  return n && af(!1), a;
}
function bE(t, n) {
  const i = t.type;
  t.accessCache = /* @__PURE__ */ Object.create(null), t.proxy = new Proxy(t.ctx, L1);
  const { setup: o } = i;
  if (o) {
    const l = t.setupContext = o.length > 1 ? wE(t) : null, u = Co(t);
    Fr();
    const a = Ao(
      o,
      t,
      0,
      [
        t.props,
        l
      ]
    );
    if (Mr(), u(), _d(a)) {
      if (Pi(t) || Jd(t), a.then(Uh, Uh), n)
        return a.then((h) => {
          Hh(t, h, n);
        }).catch((h) => {
          cl(h, t, 0);
        });
      t.asyncDep = a;
    } else
      Hh(t, a, n);
  } else
    xp(t, n);
}
function Hh(t, n, i) {
  ve(n) ? t.type.__ssrInlineRender ? t.ssrRender = n : t.render = n : ke(n) && (t.setupState = Kd(n)), xp(t, i);
}
let $h;
function xp(t, n, i) {
  const o = t.type;
  if (!t.render) {
    if (!n && $h && !o.render) {
      const l = o.template || Wf(t).template;
      if (l) {
        const { isCustomElement: u, compilerOptions: a } = t.appContext.config, { delimiters: h, compilerOptions: g } = o, v = At(
          At(
            {
              isCustomElement: u,
              delimiters: h
            },
            a
          ),
          g
        );
        o.render = $h(l, v);
      }
    }
    t.render = o.render || Un;
  }
  {
    const l = Co(t);
    Fr();
    try {
      B1(t);
    } finally {
      Mr(), l();
    }
  }
}
const yE = {
  get(t, n) {
    return Nt(t, "get", ""), t[n];
  }
};
function wE(t) {
  const n = (i) => {
    t.exposed = i || {};
  };
  return {
    attrs: new Proxy(t.attrs, yE),
    slots: t.slots,
    emit: t.emit,
    expose: n
  };
}
function $f(t) {
  return t.exposed ? t.exposeProxy || (t.exposeProxy = new Proxy(Kd(r1(t.exposed)), {
    get(n, i) {
      if (i in n)
        return n[i];
      if (i in co)
        return co[i](t);
    },
    has(n, i) {
      return i in n || i in co;
    }
  })) : t.proxy;
}
function EE(t) {
  return ve(t) && "__vccOpts" in t;
}
const Kf = (t, n) => p1(t, n, gl), xE = "3.5.6";
/**
* @vue/runtime-dom v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let cf;
const Kh = typeof window < "u" && window.trustedTypes;
if (Kh)
  try {
    cf = /* @__PURE__ */ Kh.createPolicy("vue", {
      createHTML: (t) => t
    });
  } catch {
  }
const Sp = cf ? (t) => cf.createHTML(t) : (t) => t, SE = "http://www.w3.org/2000/svg", TE = "http://www.w3.org/1998/Math/MathML", nr = typeof document < "u" ? document : null, Gh = nr && /* @__PURE__ */ nr.createElement("template"), AE = {
  insert: (t, n, i) => {
    n.insertBefore(t, i || null);
  },
  remove: (t) => {
    const n = t.parentNode;
    n && n.removeChild(t);
  },
  createElement: (t, n, i, o) => {
    const l = n === "svg" ? nr.createElementNS(SE, t) : n === "mathml" ? nr.createElementNS(TE, t) : i ? nr.createElement(t, { is: i }) : nr.createElement(t);
    return t === "select" && o && o.multiple != null && l.setAttribute("multiple", o.multiple), l;
  },
  createText: (t) => nr.createTextNode(t),
  createComment: (t) => nr.createComment(t),
  setText: (t, n) => {
    t.nodeValue = n;
  },
  setElementText: (t, n) => {
    t.textContent = n;
  },
  parentNode: (t) => t.parentNode,
  nextSibling: (t) => t.nextSibling,
  querySelector: (t) => nr.querySelector(t),
  setScopeId(t, n) {
    t.setAttribute(n, "");
  },
  // __UNSAFE__
  // Reason: innerHTML.
  // Static content here can only come from compiled templates.
  // As long as the user only uses trusted templates, this is safe.
  insertStaticContent(t, n, i, o, l, u) {
    const a = i ? i.previousSibling : n.lastChild;
    if (l && (l === u || l.nextSibling))
      for (; n.insertBefore(l.cloneNode(!0), i), !(l === u || !(l = l.nextSibling)); )
        ;
    else {
      Gh.innerHTML = Sp(
        o === "svg" ? `<svg>${t}</svg>` : o === "mathml" ? `<math>${t}</math>` : t
      );
      const h = Gh.content;
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
}, CE = Symbol("_vtc");
function OE(t, n, i) {
  const o = t[CE];
  o && (n = (n ? [n, ...o] : [...o]).join(" ")), n == null ? t.removeAttribute("class") : i ? t.setAttribute("class", n) : t.className = n;
}
const Yh = Symbol("_vod"), IE = Symbol("_vsh"), DE = Symbol(""), RE = /(^|;)\s*display\s*:/;
function PE(t, n, i) {
  const o = t.style, l = ct(i);
  let u = !1;
  if (i && !l) {
    if (n)
      if (ct(n))
        for (const a of n.split(";")) {
          const h = a.slice(0, a.indexOf(":")).trim();
          i[h] == null && Ws(o, h, "");
        }
      else
        for (const a in n)
          i[a] == null && Ws(o, a, "");
    for (const a in i)
      a === "display" && (u = !0), Ws(o, a, i[a]);
  } else if (l) {
    if (n !== i) {
      const a = o[DE];
      a && (i += ";" + a), o.cssText = i, u = RE.test(i);
    }
  } else
    n && t.removeAttribute("style");
  Yh in t && (t[Yh] = u ? o.display : "", t[IE] && (o.display = "none"));
}
const zh = /\s*!important$/;
function Ws(t, n, i) {
  if (_e(i))
    i.forEach((o) => Ws(t, n, o));
  else if (i == null && (i = ""), n.startsWith("--"))
    t.setProperty(n, i);
  else {
    const o = FE(t, n);
    zh.test(i) ? t.setProperty(
      Pr(o),
      i.replace(zh, ""),
      "important"
    ) : t[o] = i;
  }
}
const Xh = ["Webkit", "Moz", "ms"], Uu = {};
function FE(t, n) {
  const i = Uu[n];
  if (i)
    return i;
  let o = ei(n);
  if (o !== "filter" && o in t)
    return Uu[n] = o;
  o = yd(o);
  for (let l = 0; l < Xh.length; l++) {
    const u = Xh[l] + o;
    if (u in t)
      return Uu[n] = u;
  }
  return n;
}
const qh = "http://www.w3.org/1999/xlink";
function Vh(t, n, i, o, l, u = Rw(n)) {
  o && n.startsWith("xlink:") ? i == null ? t.removeAttributeNS(qh, n.slice(6, n.length)) : t.setAttributeNS(qh, n, i) : i == null || u && !xd(i) ? t.removeAttribute(n) : t.setAttribute(
    n,
    u ? "" : Rr(i) ? String(i) : i
  );
}
function ME(t, n, i, o) {
  if (n === "innerHTML" || n === "textContent") {
    i != null && (t[n] = n === "innerHTML" ? Sp(i) : i);
    return;
  }
  const l = t.tagName;
  if (n === "value" && l !== "PROGRESS" && // custom elements may use _value internally
  !l.includes("-")) {
    const a = l === "OPTION" ? t.getAttribute("value") || "" : t.value, h = i == null ? (
      // #11647: value should be set as empty string for null and undefined,
      // but <input type="checkbox"> should be set as 'on'.
      t.type === "checkbox" ? "on" : ""
    ) : String(i);
    (a !== h || !("_value" in t)) && (t.value = h), i == null && t.removeAttribute(n), t._value = i;
    return;
  }
  let u = !1;
  if (i === "" || i == null) {
    const a = typeof t[n];
    a === "boolean" ? i = xd(i) : i == null && a === "string" ? (i = "", u = !0) : a === "number" && (i = 0, u = !0);
  }
  try {
    t[n] = i;
  } catch {
  }
  u && t.removeAttribute(n);
}
function NE(t, n, i, o) {
  t.addEventListener(n, i, o);
}
function LE(t, n, i, o) {
  t.removeEventListener(n, i, o);
}
const Jh = Symbol("_vei");
function BE(t, n, i, o, l = null) {
  const u = t[Jh] || (t[Jh] = {}), a = u[n];
  if (o && a)
    a.value = o;
  else {
    const [h, g] = WE(n);
    if (o) {
      const v = u[n] = $E(
        o,
        l
      );
      NE(t, h, v, g);
    } else
      a && (LE(t, h, a, g), u[n] = void 0);
  }
}
const kh = /(?:Once|Passive|Capture)$/;
function WE(t) {
  let n;
  if (kh.test(t)) {
    n = {};
    let o;
    for (; o = t.match(kh); )
      t = t.slice(0, t.length - o[0].length), n[o[0].toLowerCase()] = !0;
  }
  return [t[2] === ":" ? t.slice(3) : Pr(t.slice(2)), n];
}
let Hu = 0;
const UE = /* @__PURE__ */ Promise.resolve(), HE = () => Hu || (UE.then(() => Hu = 0), Hu = Date.now());
function $E(t, n) {
  const i = (o) => {
    if (!o._vts)
      o._vts = Date.now();
    else if (o._vts <= i.attached)
      return;
    Kn(
      KE(o, i.value),
      n,
      5,
      [o]
    );
  };
  return i.value = t, i.attached = HE(), i;
}
function KE(t, n) {
  if (_e(n)) {
    const i = t.stopImmediatePropagation;
    return t.stopImmediatePropagation = () => {
      i.call(t), t._stopped = !0;
    }, n.map(
      (o) => (l) => !l._stopped && o && o(l)
    );
  } else
    return n;
}
const Zh = (t) => t.charCodeAt(0) === 111 && t.charCodeAt(1) === 110 && // lowercase letter
t.charCodeAt(2) > 96 && t.charCodeAt(2) < 123, GE = (t, n, i, o, l, u) => {
  const a = l === "svg";
  n === "class" ? OE(t, o, a) : n === "style" ? PE(t, i, o) : il(n) ? yf(n) || BE(t, n, i, o, u) : (n[0] === "." ? (n = n.slice(1), !0) : n[0] === "^" ? (n = n.slice(1), !1) : YE(t, n, o, a)) ? (ME(t, n, o), !t.tagName.includes("-") && (n === "value" || n === "checked" || n === "selected") && Vh(t, n, o, a, u, n !== "value")) : (n === "true-value" ? t._trueValue = o : n === "false-value" && (t._falseValue = o), Vh(t, n, o, a));
};
function YE(t, n, i, o) {
  if (o)
    return !!(n === "innerHTML" || n === "textContent" || n in t && Zh(n) && ve(i));
  if (n === "spellcheck" || n === "draggable" || n === "translate" || n === "form" || n === "list" && t.tagName === "INPUT" || n === "type" && t.tagName === "TEXTAREA")
    return !1;
  if (n === "width" || n === "height") {
    const l = t.tagName;
    if (l === "IMG" || l === "VIDEO" || l === "CANVAS" || l === "SOURCE")
      return !1;
  }
  return Zh(n) && ct(i) ? !1 : !!(n in t || t._isVueCE && (/[A-Z]/.test(n) || !ct(i)));
}
const zE = {
  esc: "escape",
  space: " ",
  up: "arrow-up",
  left: "arrow-left",
  right: "arrow-right",
  down: "arrow-down",
  delete: "backspace"
}, yi = (t, n) => {
  const i = t._withKeys || (t._withKeys = {}), o = n.join(".");
  return i[o] || (i[o] = (l) => {
    if (!("key" in l))
      return;
    const u = Pr(l.key);
    if (n.some(
      (a) => a === u || zE[a] === u
    ))
      return t(l);
  });
}, XE = /* @__PURE__ */ At({ patchProp: GE }, AE);
let Qh;
function qE() {
  return Qh || (Qh = k1(XE));
}
const VE = (...t) => {
  const n = qE().createApp(...t), { mount: i } = n;
  return n.mount = (o) => {
    const l = kE(o);
    if (!l)
      return;
    const u = n._component;
    !ve(u) && !u.render && !u.template && (u.template = l.innerHTML), l.nodeType === 1 && (l.textContent = "");
    const a = i(l, !1, JE(l));
    return l instanceof Element && (l.removeAttribute("v-cloak"), l.setAttribute("data-v-app", "")), a;
  }, n;
};
function JE(t) {
  if (t instanceof SVGElement)
    return "svg";
  if (typeof MathMLElement == "function" && t instanceof MathMLElement)
    return "mathml";
}
function kE(t) {
  return ct(t) ? document.querySelector(t) : t;
}
function Ni(t) {
  return Ad() ? (Fw(t), !0) : !1;
}
function $u() {
  const t = /* @__PURE__ */ new Set(), n = (l) => {
    t.delete(l);
  };
  return {
    on: (l) => {
      t.add(l);
      const u = () => n(l);
      return Ni(u), {
        off: u
      };
    },
    off: n,
    trigger: (...l) => Promise.all(Array.from(t).map((u) => u(...l)))
  };
}
function Qt(t) {
  return typeof t == "function" ? t() : rt(t);
}
const Qr = typeof window < "u" && typeof document < "u", ZE = typeof WorkerGlobalScope < "u" && globalThis instanceof WorkerGlobalScope, QE = Object.prototype.toString, jE = (t) => QE.call(t) === "[object Object]", Tp = () => {
};
function ex(t, n) {
  function i(...o) {
    return new Promise((l, u) => {
      Promise.resolve(t(() => n.apply(this, o), { fn: n, thisArg: this, args: o })).then(l).catch(u);
    });
  }
  return i;
}
const Ap = (t) => t();
function tx(t = Ap) {
  const n = _t(!0);
  function i() {
    n.value = !1;
  }
  function o() {
    n.value = !0;
  }
  const l = (...u) => {
    n.value && t(...u);
  };
  return { isActive: ti(n), pause: i, resume: o, eventFilter: l };
}
function jh(t, n = !1, i = "Timeout") {
  return new Promise((o, l) => {
    setTimeout(n ? () => l(i) : o, t);
  });
}
function nx(t, ...n) {
  return n.some((i) => i in t);
}
function rx(t) {
  return t || _E();
}
function Us(...t) {
  if (t.length !== 1)
    return c1(...t);
  const n = t[0];
  return typeof n == "function" ? ti(u1(() => ({ get: n, set: Tp }))) : _t(n);
}
function ix(t, n, i = {}) {
  const {
    eventFilter: o = Ap,
    ...l
  } = i;
  return Mt(
    t,
    ex(
      o,
      n
    ),
    l
  );
}
function ox(t, n, i = {}) {
  const {
    eventFilter: o,
    ...l
  } = i, { eventFilter: u, pause: a, resume: h, isActive: g } = tx(o);
  return { stop: ix(
    t,
    n,
    {
      ...l,
      eventFilter: u
    }
  ), pause: a, resume: h, isActive: g };
}
function sx(t, n = !0, i) {
  rx() ? Bf(t, i) : n ? t() : Ir(t);
}
function hf(t, n = !1) {
  function i(m, { flush: A = "sync", deep: S = !1, timeout: C, throwOnTimeout: I } = {}) {
    let P = null;
    const z = [new Promise((X) => {
      P = Mt(
        t,
        (L) => {
          m(L) !== n && (P ? P() : Ir(() => P == null ? void 0 : P()), X(L));
        },
        {
          flush: A,
          deep: S,
          immediate: !0
        }
      );
    })];
    return C != null && z.push(
      jh(C, I).then(() => Qt(t)).finally(() => P == null ? void 0 : P())
    ), Promise.race(z);
  }
  function o(m, A) {
    if (!it(m))
      return i((L) => L === m, A);
    const { flush: S = "sync", deep: C = !1, timeout: I, throwOnTimeout: P } = A ?? {};
    let B = null;
    const X = [new Promise((L) => {
      B = Mt(
        [t, m],
        ([te, Ee]) => {
          n !== (te === Ee) && (B ? B() : Ir(() => B == null ? void 0 : B()), L(te));
        },
        {
          flush: S,
          deep: C,
          immediate: !0
        }
      );
    })];
    return I != null && X.push(
      jh(I, P).then(() => Qt(t)).finally(() => (B == null || B(), Qt(t)))
    ), Promise.race(X);
  }
  function l(m) {
    return i((A) => !!A, m);
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
  function g(m, A) {
    return i((S) => {
      const C = Array.from(S);
      return C.includes(m) || C.includes(Qt(m));
    }, A);
  }
  function v(m) {
    return _(1, m);
  }
  function _(m = 1, A) {
    let S = -1;
    return i(() => (S += 1, S >= m), A);
  }
  return Array.isArray(Qt(t)) ? {
    toMatch: i,
    toContains: g,
    changed: v,
    changedTimes: _,
    get not() {
      return hf(t, !n);
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
      return hf(t, !n);
    }
  };
}
function lx(t) {
  return hf(t);
}
function ux(t, n = 1e3, i = {}) {
  const {
    immediate: o = !0,
    immediateCallback: l = !1
  } = i;
  let u = null;
  const a = _t(!1);
  function h() {
    u && (clearInterval(u), u = null);
  }
  function g() {
    a.value = !1, h();
  }
  function v() {
    const _ = Qt(n);
    _ <= 0 || (a.value = !0, l && t(), h(), u = setInterval(t, _));
  }
  if (o && Qr && v(), it(n) || typeof n == "function") {
    const _ = Mt(n, () => {
      a.value && Qr && v();
    });
    Ni(_);
  }
  return Ni(g), {
    isActive: a,
    pause: g,
    resume: v
  };
}
function fx(t, n, i = {}) {
  const {
    immediate: o = !0
  } = i, l = _t(!1);
  let u = null;
  function a() {
    u && (clearTimeout(u), u = null);
  }
  function h() {
    l.value = !1, a();
  }
  function g(...v) {
    a(), l.value = !0, u = setTimeout(() => {
      l.value = !1, u = null, t(...v);
    }, Qt(n));
  }
  return o && (l.value = !0, Qr && g()), Ni(h), {
    isPending: ti(l),
    start: g,
    stop: h
  };
}
const ks = Qr ? window : void 0;
function ax(t) {
  var n;
  const i = Qt(t);
  return (n = i == null ? void 0 : i.$el) != null ? n : i;
}
function df(...t) {
  let n, i, o, l;
  if (typeof t[0] == "string" || Array.isArray(t[0]) ? ([i, o, l] = t, n = ks) : [n, i, o, l] = t, !n)
    return Tp;
  Array.isArray(i) || (i = [i]), Array.isArray(o) || (o = [o]);
  const u = [], a = () => {
    u.forEach((_) => _()), u.length = 0;
  }, h = (_, m, A, S) => (_.addEventListener(m, A, S), () => _.removeEventListener(m, A, S)), g = Mt(
    () => [ax(n), Qt(l)],
    ([_, m]) => {
      if (a(), !_)
        return;
      const A = jE(m) ? { ...m } : m;
      u.push(
        ...i.flatMap((S) => o.map((C) => h(_, S, C, A)))
      );
    },
    { immediate: !0, flush: "post" }
  ), v = () => {
    g(), a();
  };
  return Ni(v), v;
}
const Ts = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, As = "__vueuse_ssr_handlers__", cx = /* @__PURE__ */ hx();
function hx() {
  return As in Ts || (Ts[As] = Ts[As] || {}), Ts[As];
}
function dx(t, n) {
  return cx[t] || n;
}
function px(t) {
  return t == null ? "any" : t instanceof Set ? "set" : t instanceof Map ? "map" : t instanceof Date ? "date" : typeof t == "boolean" ? "boolean" : typeof t == "string" ? "string" : typeof t == "object" ? "object" : Number.isNaN(t) ? "any" : "number";
}
const gx = {
  boolean: {
    read: (t) => t === "true",
    write: (t) => String(t)
  },
  object: {
    read: (t) => JSON.parse(t),
    write: (t) => JSON.stringify(t)
  },
  number: {
    read: (t) => Number.parseFloat(t),
    write: (t) => String(t)
  },
  any: {
    read: (t) => t,
    write: (t) => String(t)
  },
  string: {
    read: (t) => t,
    write: (t) => String(t)
  },
  map: {
    read: (t) => new Map(JSON.parse(t)),
    write: (t) => JSON.stringify(Array.from(t.entries()))
  },
  set: {
    read: (t) => new Set(JSON.parse(t)),
    write: (t) => JSON.stringify(Array.from(t))
  },
  date: {
    read: (t) => new Date(t),
    write: (t) => t.toISOString()
  }
}, ed = "vueuse-storage";
function vx(t, n, i, o = {}) {
  var l;
  const {
    flush: u = "pre",
    deep: a = !0,
    listenToStorageChanges: h = !0,
    writeDefaults: g = !0,
    mergeDefaults: v = !1,
    shallow: _,
    window: m = ks,
    eventFilter: A,
    onError: S = (Z) => {
      console.error(Z);
    },
    initOnMounted: C
  } = o, I = (_ ? Ls : _t)(typeof n == "function" ? n() : n);
  if (!i)
    try {
      i = dx("getDefaultStorage", () => {
        var Z;
        return (Z = ks) == null ? void 0 : Z.localStorage;
      })();
    } catch (Z) {
      S(Z);
    }
  if (!i)
    return I;
  const P = Qt(n), B = px(P), z = (l = o.serializer) != null ? l : gx[B], { pause: X, resume: L } = ox(
    I,
    () => Ee(I.value),
    { flush: u, deep: a, eventFilter: A }
  );
  m && h && sx(() => {
    i instanceof Storage ? df(m, "storage", Re) : df(m, ed, ue), C && Re();
  }), C || Re();
  function te(Z, he) {
    if (m) {
      const Ce = {
        key: t,
        oldValue: Z,
        newValue: he,
        storageArea: i
      };
      m.dispatchEvent(i instanceof Storage ? new StorageEvent("storage", Ce) : new CustomEvent(ed, {
        detail: Ce
      }));
    }
  }
  function Ee(Z) {
    try {
      const he = i.getItem(t);
      if (Z == null)
        te(he, null), i.removeItem(t);
      else {
        const Ce = z.write(Z);
        he !== Ce && (i.setItem(t, Ce), te(he, Ce));
      }
    } catch (he) {
      S(he);
    }
  }
  function Se(Z) {
    const he = Z ? Z.newValue : i.getItem(t);
    if (he == null)
      return g && P != null && i.setItem(t, z.write(P)), P;
    if (!Z && v) {
      const Ce = z.read(he);
      return typeof v == "function" ? v(Ce, P) : B === "object" && !Array.isArray(Ce) ? { ...P, ...Ce } : Ce;
    } else
      return typeof he != "string" ? he : z.read(he);
  }
  function Re(Z) {
    if (!(Z && Z.storageArea !== i)) {
      if (Z && Z.key == null) {
        I.value = P;
        return;
      }
      if (!(Z && Z.key !== t)) {
        X();
        try {
          (Z == null ? void 0 : Z.newValue) !== z.write(I.value) && (I.value = Se(Z));
        } catch (he) {
          S(he);
        } finally {
          Z ? Ir(L) : L();
        }
      }
    }
  }
  function ue(Z) {
    Re(Z.detail);
  }
  return I;
}
const _x = {
  json: "application/json",
  text: "text/plain"
};
function Zs(t) {
  return t && nx(t, "immediate", "refetch", "initialData", "timeout", "beforeFetch", "afterFetch", "onFetchError", "fetch", "updateDataOnError");
}
const mx = /^(?:[a-z][a-z\d+\-.]*:)?\/\//i;
function bx(t) {
  return mx.test(t);
}
function po(t) {
  return typeof Headers < "u" && t instanceof Headers ? Object.fromEntries(t.entries()) : t;
}
function wi(t, ...n) {
  return t === "overwrite" ? async (i) => {
    const o = n[n.length - 1];
    return o ? { ...i, ...await o(i) } : i;
  } : async (i) => {
    for (const o of n)
      o && (i = { ...i, ...await o(i) });
    return i;
  };
}
function yx(t = {}) {
  const n = t.combination || "chain", i = t.options || {}, o = t.fetchOptions || {};
  function l(u, ...a) {
    const h = Kf(() => {
      const _ = Qt(t.baseUrl), m = Qt(u);
      return _ && !bx(m) ? Ex(_, m) : m;
    });
    let g = i, v = o;
    return a.length > 0 && (Zs(a[0]) ? g = {
      ...g,
      ...a[0],
      beforeFetch: wi(n, i.beforeFetch, a[0].beforeFetch),
      afterFetch: wi(n, i.afterFetch, a[0].afterFetch),
      onFetchError: wi(n, i.onFetchError, a[0].onFetchError)
    } : v = {
      ...v,
      ...a[0],
      headers: {
        ...po(v.headers) || {},
        ...po(a[0].headers) || {}
      }
    }), a.length > 1 && Zs(a[1]) && (g = {
      ...g,
      ...a[1],
      beforeFetch: wi(n, i.beforeFetch, a[1].beforeFetch),
      afterFetch: wi(n, i.afterFetch, a[1].afterFetch),
      onFetchError: wi(n, i.onFetchError, a[1].onFetchError)
    }), wx(h, v, g);
  }
  return l;
}
function wx(t, ...n) {
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
  n.length > 0 && (Zs(n[0]) ? u = { ...u, ...n[0] } : l = n[0]), n.length > 1 && Zs(n[1]) && (u = { ...u, ...n[1] });
  const {
    fetch: h = (i = ks) == null ? void 0 : i.fetch,
    initialData: g,
    timeout: v
  } = u, _ = $u(), m = $u(), A = $u(), S = _t(!1), C = _t(!1), I = _t(!1), P = _t(null), B = Ls(null), z = Ls(null), X = Ls(g || null), L = Kf(() => o && C.value);
  let te, Ee;
  const Se = () => {
    o && (te == null || te.abort(), te = new AbortController(), te.signal.onabort = () => I.value = !0, l = {
      ...l,
      signal: te.signal
    });
  }, Re = (me) => {
    C.value = me, S.value = !me;
  };
  v && (Ee = fx(Se, v, { immediate: !1 }));
  let ue = 0;
  const Z = async (me = !1) => {
    var fe, ie;
    Se(), Re(!0), z.value = null, P.value = null, I.value = !1, ue += 1;
    const mt = ue, gt = {
      method: a.method,
      headers: {}
    };
    if (a.payload) {
      const st = po(gt.headers), bt = Qt(a.payload);
      !a.payloadType && bt && Object.getPrototypeOf(bt) === Object.prototype && !(bt instanceof FormData) && (a.payloadType = "json"), a.payloadType && (st["Content-Type"] = (fe = _x[a.payloadType]) != null ? fe : a.payloadType), gt.body = a.payloadType === "json" ? JSON.stringify(bt) : bt;
    }
    let qe = !1;
    const ot = {
      url: Qt(t),
      options: {
        ...gt,
        ...l
      },
      cancel: () => {
        qe = !0;
      }
    };
    if (u.beforeFetch && Object.assign(ot, await u.beforeFetch(ot)), qe || !h)
      return Re(!1), Promise.resolve(null);
    let cn = null;
    return Ee && Ee.start(), h(
      ot.url,
      {
        ...gt,
        ...ot.options,
        headers: {
          ...po(gt.headers),
          ...po((ie = ot.options) == null ? void 0 : ie.headers)
        }
      }
    ).then(async (st) => {
      if (B.value = st, P.value = st.status, cn = await st.clone()[a.type](), !st.ok)
        throw X.value = g || null, new Error(st.statusText);
      return u.afterFetch && ({ data: cn } = await u.afterFetch({
        data: cn,
        response: st
      })), X.value = cn, _.trigger(st), st;
    }).catch(async (st) => {
      let bt = st.message || st.name;
      if (u.onFetchError && ({ error: bt, data: cn } = await u.onFetchError({
        data: cn,
        error: st,
        response: B.value
      })), z.value = bt, u.updateDataOnError && (X.value = cn), m.trigger(st), me)
        throw st;
      return null;
    }).finally(() => {
      mt === ue && Re(!1), Ee && Ee.stop(), A.trigger(null);
    });
  }, he = Us(u.refetch);
  Mt(
    [
      he,
      Us(t)
    ],
    ([me]) => me && Z(),
    { deep: !0 }
  );
  const Ce = {
    isFinished: ti(S),
    isFetching: ti(C),
    statusCode: P,
    response: B,
    error: z,
    data: X,
    canAbort: L,
    aborted: I,
    abort: Se,
    execute: Z,
    onFetchResponse: _.on,
    onFetchError: m.on,
    onFetchFinally: A.on,
    // method
    get: We("GET"),
    put: We("PUT"),
    post: We("POST"),
    delete: We("DELETE"),
    patch: We("PATCH"),
    head: We("HEAD"),
    options: We("OPTIONS"),
    // type
    json: Ct("json"),
    text: Ct("text"),
    blob: Ct("blob"),
    arrayBuffer: Ct("arrayBuffer"),
    formData: Ct("formData")
  };
  function We(me) {
    return (fe, ie) => {
      if (!C.value)
        return a.method = me, a.payload = fe, a.payloadType = ie, it(a.payload) && Mt(
          [
            he,
            Us(a.payload)
          ],
          ([mt]) => mt && Z(),
          { deep: !0 }
        ), {
          ...Ce,
          then(mt, gt) {
            return pt().then(mt, gt);
          }
        };
    };
  }
  function pt() {
    return new Promise((me, fe) => {
      lx(S).toBe(!0).then(() => me(Ce)).catch((ie) => fe(ie));
    });
  }
  function Ct(me) {
    return () => {
      if (!C.value)
        return a.type = me, {
          ...Ce,
          then(fe, ie) {
            return pt().then(fe, ie);
          }
        };
    };
  }
  return u.immediate && Promise.resolve().then(() => Z()), {
    ...Ce,
    then(me, fe) {
      return pt().then(me, fe);
    }
  };
}
function Ex(t, n) {
  return !t.endsWith("/") && !n.startsWith("/") ? `${t}/${n}` : `${t}${n}`;
}
const td = "ping";
function Ku(t) {
  return t === !0 ? {} : t;
}
function xx(t, n = {}) {
  const {
    onConnected: i,
    onDisconnected: o,
    onError: l,
    onMessage: u,
    immediate: a = !0,
    autoClose: h = !0,
    protocols: g = []
  } = n, v = _t(null), _ = _t("CLOSED"), m = _t(), A = Us(t);
  let S, C, I = !1, P = 0, B = [], z;
  const X = () => {
    if (B.length && m.value && _.value === "OPEN") {
      for (const ue of B)
        m.value.send(ue);
      B = [];
    }
  }, L = () => {
    clearTimeout(z), z = void 0;
  }, te = (ue = 1e3, Z) => {
    !Qr || !m.value || (I = !0, L(), S == null || S(), m.value.close(ue, Z), m.value = void 0);
  }, Ee = (ue, Z = !0) => !m.value || _.value !== "OPEN" ? (Z && B.push(ue), !1) : (X(), m.value.send(ue), !0), Se = () => {
    if (I || typeof A.value > "u")
      return;
    const ue = new WebSocket(A.value, g);
    m.value = ue, _.value = "CONNECTING", ue.onopen = () => {
      _.value = "OPEN", P = 0, i == null || i(ue), C == null || C(), X();
    }, ue.onclose = (Z) => {
      if (_.value = "CLOSED", o == null || o(ue, Z), !I && n.autoReconnect && ue === m.value) {
        const {
          retries: he = -1,
          delay: Ce = 1e3,
          onFailed: We
        } = Ku(n.autoReconnect);
        typeof he == "number" && (he < 0 || P < he) ? (P += 1, setTimeout(Se, Ce)) : typeof he == "function" && he() ? setTimeout(Se, Ce) : We == null || We();
      }
    }, ue.onerror = (Z) => {
      l == null || l(ue, Z);
    }, ue.onmessage = (Z) => {
      if (n.heartbeat) {
        L();
        const {
          message: he = td,
          responseMessage: Ce = he
        } = Ku(n.heartbeat);
        if (Z.data === Ce)
          return;
      }
      v.value = Z.data, u == null || u(ue, Z);
    };
  };
  if (n.heartbeat) {
    const {
      message: ue = td,
      interval: Z = 1e3,
      pongTimeout: he = 1e3
    } = Ku(n.heartbeat), { pause: Ce, resume: We } = ux(
      () => {
        Ee(ue, !1), z == null && (z = setTimeout(() => {
          te(), I = !1;
        }, he));
      },
      Z,
      { immediate: !1 }
    );
    S = Ce, C = We;
  }
  h && (Qr && df("beforeunload", () => te()), Ni(te));
  const Re = () => {
    !Qr && !ZE || (te(), I = !1, P = 0, Se());
  };
  return a && Re(), Mt(A, Re), {
    data: v,
    status: _,
    close: te,
    send: Ee,
    open: Re,
    ws: m
  };
}
var eo = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, Qs = { exports: {} };
/**
 * @license
 * Lodash <https://lodash.com/>
 * Copyright OpenJS Foundation and other contributors <https://openjsf.org/>
 * Released under MIT license <https://lodash.com/license>
 * Based on Underscore.js 1.8.3 <http://underscorejs.org/LICENSE>
 * Copyright Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 */
Qs.exports;
(function(t, n) {
  (function() {
    var i, o = "4.17.21", l = 200, u = "Unsupported core-js use. Try https://npms.io/search?q=ponyfill.", a = "Expected a function", h = "Invalid `variable` option passed into `_.template`", g = "__lodash_hash_undefined__", v = 500, _ = "__lodash_placeholder__", m = 1, A = 2, S = 4, C = 1, I = 2, P = 1, B = 2, z = 4, X = 8, L = 16, te = 32, Ee = 64, Se = 128, Re = 256, ue = 512, Z = 30, he = "...", Ce = 800, We = 16, pt = 1, Ct = 2, me = 3, fe = 1 / 0, ie = 9007199254740991, mt = 17976931348623157e292, gt = NaN, qe = 4294967295, ot = qe - 1, cn = qe >>> 1, st = [
      ["ary", Se],
      ["bind", P],
      ["bindKey", B],
      ["curry", X],
      ["curryRight", L],
      ["flip", ue],
      ["partial", te],
      ["partialRight", Ee],
      ["rearg", Re]
    ], bt = "[object Arguments]", Rn = "[object Array]", ri = "[object AsyncFunction]", Yn = "[object Boolean]", ur = "[object Date]", fr = "[object DOMException]", ar = "[object Error]", cr = "[object Function]", y = "[object GeneratorFunction]", E = "[object Map]", D = "[object Number]", $ = "[object Null]", N = "[object Object]", H = "[object Promise]", q = "[object Proxy]", G = "[object RegExp]", K = "[object Set]", U = "[object String]", ne = "[object Symbol]", J = "[object Undefined]", Q = "[object WeakMap]", ae = "[object WeakSet]", ye = "[object ArrayBuffer]", Pe = "[object DataView]", Fe = "[object Float32Array]", yt = "[object Float64Array]", lt = "[object Int8Array]", Lt = "[object Int16Array]", wt = "[object Int32Array]", zn = "[object Uint8Array]", ii = "[object Uint8ClampedArray]", Et = "[object Uint16Array]", zt = "[object Uint32Array]", Do = /\b__p \+= '';/g, Kp = /\b(__p \+=) '' \+/g, Gp = /(__e\(.*?\)|\b__t\)) \+\n'';/g, qf = /&(?:amp|lt|gt|quot|#39);/g, Vf = /[&<>"']/g, Yp = RegExp(qf.source), zp = RegExp(Vf.source), Xp = /<%-([\s\S]+?)%>/g, qp = /<%([\s\S]+?)%>/g, Jf = /<%=([\s\S]+?)%>/g, Vp = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/, Jp = /^\w*$/, kp = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g, _l = /[\\^$.*+?()[\]{}|]/g, Zp = RegExp(_l.source), ml = /^\s+/, Qp = /\s/, jp = /\{(?:\n\/\* \[wrapped with .+\] \*\/)?\n?/, eg = /\{\n\/\* \[wrapped with (.+)\] \*/, tg = /,? & /, ng = /[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g, rg = /[()=,{}\[\]\/\s]/, ig = /\\(\\)?/g, og = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g, kf = /\w*$/, sg = /^[-+]0x[0-9a-f]+$/i, lg = /^0b[01]+$/i, ug = /^\[object .+?Constructor\]$/, fg = /^0o[0-7]+$/i, ag = /^(?:0|[1-9]\d*)$/, cg = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g, Ro = /($^)/, hg = /['\n\r\u2028\u2029\\]/g, Po = "\\ud800-\\udfff", dg = "\\u0300-\\u036f", pg = "\\ufe20-\\ufe2f", gg = "\\u20d0-\\u20ff", Zf = dg + pg + gg, Qf = "\\u2700-\\u27bf", jf = "a-z\\xdf-\\xf6\\xf8-\\xff", vg = "\\xac\\xb1\\xd7\\xf7", _g = "\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf", mg = "\\u2000-\\u206f", bg = " \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000", ea = "A-Z\\xc0-\\xd6\\xd8-\\xde", ta = "\\ufe0e\\ufe0f", na = vg + _g + mg + bg, bl = "['’]", yg = "[" + Po + "]", ra = "[" + na + "]", Fo = "[" + Zf + "]", ia = "\\d+", wg = "[" + Qf + "]", oa = "[" + jf + "]", sa = "[^" + Po + na + ia + Qf + jf + ea + "]", yl = "\\ud83c[\\udffb-\\udfff]", Eg = "(?:" + Fo + "|" + yl + ")", la = "[^" + Po + "]", wl = "(?:\\ud83c[\\udde6-\\uddff]){2}", El = "[\\ud800-\\udbff][\\udc00-\\udfff]", oi = "[" + ea + "]", ua = "\\u200d", fa = "(?:" + oa + "|" + sa + ")", xg = "(?:" + oi + "|" + sa + ")", aa = "(?:" + bl + "(?:d|ll|m|re|s|t|ve))?", ca = "(?:" + bl + "(?:D|LL|M|RE|S|T|VE))?", ha = Eg + "?", da = "[" + ta + "]?", Sg = "(?:" + ua + "(?:" + [la, wl, El].join("|") + ")" + da + ha + ")*", Tg = "\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])", Ag = "\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])", pa = da + ha + Sg, Cg = "(?:" + [wg, wl, El].join("|") + ")" + pa, Og = "(?:" + [la + Fo + "?", Fo, wl, El, yg].join("|") + ")", Ig = RegExp(bl, "g"), Dg = RegExp(Fo, "g"), xl = RegExp(yl + "(?=" + yl + ")|" + Og + pa, "g"), Rg = RegExp([
      oi + "?" + oa + "+" + aa + "(?=" + [ra, oi, "$"].join("|") + ")",
      xg + "+" + ca + "(?=" + [ra, oi + fa, "$"].join("|") + ")",
      oi + "?" + fa + "+" + aa,
      oi + "+" + ca,
      Ag,
      Tg,
      ia,
      Cg
    ].join("|"), "g"), Pg = RegExp("[" + ua + Po + Zf + ta + "]"), Fg = /[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/, Mg = [
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
    ], Ng = -1, ze = {};
    ze[Fe] = ze[yt] = ze[lt] = ze[Lt] = ze[wt] = ze[zn] = ze[ii] = ze[Et] = ze[zt] = !0, ze[bt] = ze[Rn] = ze[ye] = ze[Yn] = ze[Pe] = ze[ur] = ze[ar] = ze[cr] = ze[E] = ze[D] = ze[N] = ze[G] = ze[K] = ze[U] = ze[Q] = !1;
    var Ke = {};
    Ke[bt] = Ke[Rn] = Ke[ye] = Ke[Pe] = Ke[Yn] = Ke[ur] = Ke[Fe] = Ke[yt] = Ke[lt] = Ke[Lt] = Ke[wt] = Ke[E] = Ke[D] = Ke[N] = Ke[G] = Ke[K] = Ke[U] = Ke[ne] = Ke[zn] = Ke[ii] = Ke[Et] = Ke[zt] = !0, Ke[ar] = Ke[cr] = Ke[Q] = !1;
    var Lg = {
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
    }, Bg = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;"
    }, Wg = {
      "&amp;": "&",
      "&lt;": "<",
      "&gt;": ">",
      "&quot;": '"',
      "&#39;": "'"
    }, Ug = {
      "\\": "\\",
      "'": "'",
      "\n": "n",
      "\r": "r",
      "\u2028": "u2028",
      "\u2029": "u2029"
    }, Hg = parseFloat, $g = parseInt, ga = typeof eo == "object" && eo && eo.Object === Object && eo, Kg = typeof self == "object" && self && self.Object === Object && self, xt = ga || Kg || Function("return this")(), Sl = n && !n.nodeType && n, Nr = Sl && !0 && t && !t.nodeType && t, va = Nr && Nr.exports === Sl, Tl = va && ga.process, hn = function() {
      try {
        var x = Nr && Nr.require && Nr.require("util").types;
        return x || Tl && Tl.binding && Tl.binding("util");
      } catch {
      }
    }(), _a = hn && hn.isArrayBuffer, ma = hn && hn.isDate, ba = hn && hn.isMap, ya = hn && hn.isRegExp, wa = hn && hn.isSet, Ea = hn && hn.isTypedArray;
    function jt(x, R, O) {
      switch (O.length) {
        case 0:
          return x.call(R);
        case 1:
          return x.call(R, O[0]);
        case 2:
          return x.call(R, O[0], O[1]);
        case 3:
          return x.call(R, O[0], O[1], O[2]);
      }
      return x.apply(R, O);
    }
    function Gg(x, R, O, k) {
      for (var de = -1, Me = x == null ? 0 : x.length; ++de < Me; ) {
        var ht = x[de];
        R(k, ht, O(ht), x);
      }
      return k;
    }
    function dn(x, R) {
      for (var O = -1, k = x == null ? 0 : x.length; ++O < k && R(x[O], O, x) !== !1; )
        ;
      return x;
    }
    function Yg(x, R) {
      for (var O = x == null ? 0 : x.length; O-- && R(x[O], O, x) !== !1; )
        ;
      return x;
    }
    function xa(x, R) {
      for (var O = -1, k = x == null ? 0 : x.length; ++O < k; )
        if (!R(x[O], O, x))
          return !1;
      return !0;
    }
    function hr(x, R) {
      for (var O = -1, k = x == null ? 0 : x.length, de = 0, Me = []; ++O < k; ) {
        var ht = x[O];
        R(ht, O, x) && (Me[de++] = ht);
      }
      return Me;
    }
    function Mo(x, R) {
      var O = x == null ? 0 : x.length;
      return !!O && si(x, R, 0) > -1;
    }
    function Al(x, R, O) {
      for (var k = -1, de = x == null ? 0 : x.length; ++k < de; )
        if (O(R, x[k]))
          return !0;
      return !1;
    }
    function Ve(x, R) {
      for (var O = -1, k = x == null ? 0 : x.length, de = Array(k); ++O < k; )
        de[O] = R(x[O], O, x);
      return de;
    }
    function dr(x, R) {
      for (var O = -1, k = R.length, de = x.length; ++O < k; )
        x[de + O] = R[O];
      return x;
    }
    function Cl(x, R, O, k) {
      var de = -1, Me = x == null ? 0 : x.length;
      for (k && Me && (O = x[++de]); ++de < Me; )
        O = R(O, x[de], de, x);
      return O;
    }
    function zg(x, R, O, k) {
      var de = x == null ? 0 : x.length;
      for (k && de && (O = x[--de]); de--; )
        O = R(O, x[de], de, x);
      return O;
    }
    function Ol(x, R) {
      for (var O = -1, k = x == null ? 0 : x.length; ++O < k; )
        if (R(x[O], O, x))
          return !0;
      return !1;
    }
    var Xg = Il("length");
    function qg(x) {
      return x.split("");
    }
    function Vg(x) {
      return x.match(ng) || [];
    }
    function Sa(x, R, O) {
      var k;
      return O(x, function(de, Me, ht) {
        if (R(de, Me, ht))
          return k = Me, !1;
      }), k;
    }
    function No(x, R, O, k) {
      for (var de = x.length, Me = O + (k ? 1 : -1); k ? Me-- : ++Me < de; )
        if (R(x[Me], Me, x))
          return Me;
      return -1;
    }
    function si(x, R, O) {
      return R === R ? sv(x, R, O) : No(x, Ta, O);
    }
    function Jg(x, R, O, k) {
      for (var de = O - 1, Me = x.length; ++de < Me; )
        if (k(x[de], R))
          return de;
      return -1;
    }
    function Ta(x) {
      return x !== x;
    }
    function Aa(x, R) {
      var O = x == null ? 0 : x.length;
      return O ? Rl(x, R) / O : gt;
    }
    function Il(x) {
      return function(R) {
        return R == null ? i : R[x];
      };
    }
    function Dl(x) {
      return function(R) {
        return x == null ? i : x[R];
      };
    }
    function Ca(x, R, O, k, de) {
      return de(x, function(Me, ht, He) {
        O = k ? (k = !1, Me) : R(O, Me, ht, He);
      }), O;
    }
    function kg(x, R) {
      var O = x.length;
      for (x.sort(R); O--; )
        x[O] = x[O].value;
      return x;
    }
    function Rl(x, R) {
      for (var O, k = -1, de = x.length; ++k < de; ) {
        var Me = R(x[k]);
        Me !== i && (O = O === i ? Me : O + Me);
      }
      return O;
    }
    function Pl(x, R) {
      for (var O = -1, k = Array(x); ++O < x; )
        k[O] = R(O);
      return k;
    }
    function Zg(x, R) {
      return Ve(R, function(O) {
        return [O, x[O]];
      });
    }
    function Oa(x) {
      return x && x.slice(0, Pa(x) + 1).replace(ml, "");
    }
    function en(x) {
      return function(R) {
        return x(R);
      };
    }
    function Fl(x, R) {
      return Ve(R, function(O) {
        return x[O];
      });
    }
    function Bi(x, R) {
      return x.has(R);
    }
    function Ia(x, R) {
      for (var O = -1, k = x.length; ++O < k && si(R, x[O], 0) > -1; )
        ;
      return O;
    }
    function Da(x, R) {
      for (var O = x.length; O-- && si(R, x[O], 0) > -1; )
        ;
      return O;
    }
    function Qg(x, R) {
      for (var O = x.length, k = 0; O--; )
        x[O] === R && ++k;
      return k;
    }
    var jg = Dl(Lg), ev = Dl(Bg);
    function tv(x) {
      return "\\" + Ug[x];
    }
    function nv(x, R) {
      return x == null ? i : x[R];
    }
    function li(x) {
      return Pg.test(x);
    }
    function rv(x) {
      return Fg.test(x);
    }
    function iv(x) {
      for (var R, O = []; !(R = x.next()).done; )
        O.push(R.value);
      return O;
    }
    function Ml(x) {
      var R = -1, O = Array(x.size);
      return x.forEach(function(k, de) {
        O[++R] = [de, k];
      }), O;
    }
    function Ra(x, R) {
      return function(O) {
        return x(R(O));
      };
    }
    function pr(x, R) {
      for (var O = -1, k = x.length, de = 0, Me = []; ++O < k; ) {
        var ht = x[O];
        (ht === R || ht === _) && (x[O] = _, Me[de++] = O);
      }
      return Me;
    }
    function Lo(x) {
      var R = -1, O = Array(x.size);
      return x.forEach(function(k) {
        O[++R] = k;
      }), O;
    }
    function ov(x) {
      var R = -1, O = Array(x.size);
      return x.forEach(function(k) {
        O[++R] = [k, k];
      }), O;
    }
    function sv(x, R, O) {
      for (var k = O - 1, de = x.length; ++k < de; )
        if (x[k] === R)
          return k;
      return -1;
    }
    function lv(x, R, O) {
      for (var k = O + 1; k--; )
        if (x[k] === R)
          return k;
      return k;
    }
    function ui(x) {
      return li(x) ? fv(x) : Xg(x);
    }
    function xn(x) {
      return li(x) ? av(x) : qg(x);
    }
    function Pa(x) {
      for (var R = x.length; R-- && Qp.test(x.charAt(R)); )
        ;
      return R;
    }
    var uv = Dl(Wg);
    function fv(x) {
      for (var R = xl.lastIndex = 0; xl.test(x); )
        ++R;
      return R;
    }
    function av(x) {
      return x.match(xl) || [];
    }
    function cv(x) {
      return x.match(Rg) || [];
    }
    var hv = function x(R) {
      R = R == null ? xt : fi.defaults(xt.Object(), R, fi.pick(xt, Mg));
      var O = R.Array, k = R.Date, de = R.Error, Me = R.Function, ht = R.Math, He = R.Object, Nl = R.RegExp, dv = R.String, pn = R.TypeError, Bo = O.prototype, pv = Me.prototype, ai = He.prototype, Wo = R["__core-js_shared__"], Uo = pv.toString, Ue = ai.hasOwnProperty, gv = 0, Fa = function() {
        var e = /[^.]+$/.exec(Wo && Wo.keys && Wo.keys.IE_PROTO || "");
        return e ? "Symbol(src)_1." + e : "";
      }(), Ho = ai.toString, vv = Uo.call(He), _v = xt._, mv = Nl(
        "^" + Uo.call(Ue).replace(_l, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"
      ), $o = va ? R.Buffer : i, gr = R.Symbol, Ko = R.Uint8Array, Ma = $o ? $o.allocUnsafe : i, Go = Ra(He.getPrototypeOf, He), Na = He.create, La = ai.propertyIsEnumerable, Yo = Bo.splice, Ba = gr ? gr.isConcatSpreadable : i, Wi = gr ? gr.iterator : i, Lr = gr ? gr.toStringTag : i, zo = function() {
        try {
          var e = $r(He, "defineProperty");
          return e({}, "", {}), e;
        } catch {
        }
      }(), bv = R.clearTimeout !== xt.clearTimeout && R.clearTimeout, yv = k && k.now !== xt.Date.now && k.now, wv = R.setTimeout !== xt.setTimeout && R.setTimeout, Xo = ht.ceil, qo = ht.floor, Ll = He.getOwnPropertySymbols, Ev = $o ? $o.isBuffer : i, Wa = R.isFinite, xv = Bo.join, Sv = Ra(He.keys, He), dt = ht.max, Ot = ht.min, Tv = k.now, Av = R.parseInt, Ua = ht.random, Cv = Bo.reverse, Bl = $r(R, "DataView"), Ui = $r(R, "Map"), Wl = $r(R, "Promise"), ci = $r(R, "Set"), Hi = $r(R, "WeakMap"), $i = $r(He, "create"), Vo = Hi && new Hi(), hi = {}, Ov = Kr(Bl), Iv = Kr(Ui), Dv = Kr(Wl), Rv = Kr(ci), Pv = Kr(Hi), Jo = gr ? gr.prototype : i, Ki = Jo ? Jo.valueOf : i, Ha = Jo ? Jo.toString : i;
      function d(e) {
        if (je(e) && !ge(e) && !(e instanceof Oe)) {
          if (e instanceof gn)
            return e;
          if (Ue.call(e, "__wrapped__"))
            return $c(e);
        }
        return new gn(e);
      }
      var di = /* @__PURE__ */ function() {
        function e() {
        }
        return function(r) {
          if (!Ze(r))
            return {};
          if (Na)
            return Na(r);
          e.prototype = r;
          var s = new e();
          return e.prototype = i, s;
        };
      }();
      function ko() {
      }
      function gn(e, r) {
        this.__wrapped__ = e, this.__actions__ = [], this.__chain__ = !!r, this.__index__ = 0, this.__values__ = i;
      }
      d.templateSettings = {
        /**
         * Used to detect `data` property values to be HTML-escaped.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        escape: Xp,
        /**
         * Used to detect code to be evaluated.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        evaluate: qp,
        /**
         * Used to detect `data` property values to inject.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        interpolate: Jf,
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
      }, d.prototype = ko.prototype, d.prototype.constructor = d, gn.prototype = di(ko.prototype), gn.prototype.constructor = gn;
      function Oe(e) {
        this.__wrapped__ = e, this.__actions__ = [], this.__dir__ = 1, this.__filtered__ = !1, this.__iteratees__ = [], this.__takeCount__ = qe, this.__views__ = [];
      }
      function Fv() {
        var e = new Oe(this.__wrapped__);
        return e.__actions__ = Xt(this.__actions__), e.__dir__ = this.__dir__, e.__filtered__ = this.__filtered__, e.__iteratees__ = Xt(this.__iteratees__), e.__takeCount__ = this.__takeCount__, e.__views__ = Xt(this.__views__), e;
      }
      function Mv() {
        if (this.__filtered__) {
          var e = new Oe(this);
          e.__dir__ = -1, e.__filtered__ = !0;
        } else
          e = this.clone(), e.__dir__ *= -1;
        return e;
      }
      function Nv() {
        var e = this.__wrapped__.value(), r = this.__dir__, s = ge(e), f = r < 0, c = s ? e.length : 0, p = q_(0, c, this.__views__), b = p.start, w = p.end, T = w - b, F = f ? w : b - 1, M = this.__iteratees__, W = M.length, V = 0, ee = Ot(T, this.__takeCount__);
        if (!s || !f && c == T && ee == T)
          return ac(e, this.__actions__);
        var oe = [];
        e:
          for (; T-- && V < ee; ) {
            F += r;
            for (var we = -1, se = e[F]; ++we < W; ) {
              var Ae = M[we], De = Ae.iteratee, rn = Ae.type, Ut = De(se);
              if (rn == Ct)
                se = Ut;
              else if (!Ut) {
                if (rn == pt)
                  continue e;
                break e;
              }
            }
            oe[V++] = se;
          }
        return oe;
      }
      Oe.prototype = di(ko.prototype), Oe.prototype.constructor = Oe;
      function Br(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.clear(); ++r < s; ) {
          var f = e[r];
          this.set(f[0], f[1]);
        }
      }
      function Lv() {
        this.__data__ = $i ? $i(null) : {}, this.size = 0;
      }
      function Bv(e) {
        var r = this.has(e) && delete this.__data__[e];
        return this.size -= r ? 1 : 0, r;
      }
      function Wv(e) {
        var r = this.__data__;
        if ($i) {
          var s = r[e];
          return s === g ? i : s;
        }
        return Ue.call(r, e) ? r[e] : i;
      }
      function Uv(e) {
        var r = this.__data__;
        return $i ? r[e] !== i : Ue.call(r, e);
      }
      function Hv(e, r) {
        var s = this.__data__;
        return this.size += this.has(e) ? 0 : 1, s[e] = $i && r === i ? g : r, this;
      }
      Br.prototype.clear = Lv, Br.prototype.delete = Bv, Br.prototype.get = Wv, Br.prototype.has = Uv, Br.prototype.set = Hv;
      function Xn(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.clear(); ++r < s; ) {
          var f = e[r];
          this.set(f[0], f[1]);
        }
      }
      function $v() {
        this.__data__ = [], this.size = 0;
      }
      function Kv(e) {
        var r = this.__data__, s = Zo(r, e);
        if (s < 0)
          return !1;
        var f = r.length - 1;
        return s == f ? r.pop() : Yo.call(r, s, 1), --this.size, !0;
      }
      function Gv(e) {
        var r = this.__data__, s = Zo(r, e);
        return s < 0 ? i : r[s][1];
      }
      function Yv(e) {
        return Zo(this.__data__, e) > -1;
      }
      function zv(e, r) {
        var s = this.__data__, f = Zo(s, e);
        return f < 0 ? (++this.size, s.push([e, r])) : s[f][1] = r, this;
      }
      Xn.prototype.clear = $v, Xn.prototype.delete = Kv, Xn.prototype.get = Gv, Xn.prototype.has = Yv, Xn.prototype.set = zv;
      function qn(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.clear(); ++r < s; ) {
          var f = e[r];
          this.set(f[0], f[1]);
        }
      }
      function Xv() {
        this.size = 0, this.__data__ = {
          hash: new Br(),
          map: new (Ui || Xn)(),
          string: new Br()
        };
      }
      function qv(e) {
        var r = fs(this, e).delete(e);
        return this.size -= r ? 1 : 0, r;
      }
      function Vv(e) {
        return fs(this, e).get(e);
      }
      function Jv(e) {
        return fs(this, e).has(e);
      }
      function kv(e, r) {
        var s = fs(this, e), f = s.size;
        return s.set(e, r), this.size += s.size == f ? 0 : 1, this;
      }
      qn.prototype.clear = Xv, qn.prototype.delete = qv, qn.prototype.get = Vv, qn.prototype.has = Jv, qn.prototype.set = kv;
      function Wr(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.__data__ = new qn(); ++r < s; )
          this.add(e[r]);
      }
      function Zv(e) {
        return this.__data__.set(e, g), this;
      }
      function Qv(e) {
        return this.__data__.has(e);
      }
      Wr.prototype.add = Wr.prototype.push = Zv, Wr.prototype.has = Qv;
      function Sn(e) {
        var r = this.__data__ = new Xn(e);
        this.size = r.size;
      }
      function jv() {
        this.__data__ = new Xn(), this.size = 0;
      }
      function e_(e) {
        var r = this.__data__, s = r.delete(e);
        return this.size = r.size, s;
      }
      function t_(e) {
        return this.__data__.get(e);
      }
      function n_(e) {
        return this.__data__.has(e);
      }
      function r_(e, r) {
        var s = this.__data__;
        if (s instanceof Xn) {
          var f = s.__data__;
          if (!Ui || f.length < l - 1)
            return f.push([e, r]), this.size = ++s.size, this;
          s = this.__data__ = new qn(f);
        }
        return s.set(e, r), this.size = s.size, this;
      }
      Sn.prototype.clear = jv, Sn.prototype.delete = e_, Sn.prototype.get = t_, Sn.prototype.has = n_, Sn.prototype.set = r_;
      function $a(e, r) {
        var s = ge(e), f = !s && Gr(e), c = !s && !f && yr(e), p = !s && !f && !c && _i(e), b = s || f || c || p, w = b ? Pl(e.length, dv) : [], T = w.length;
        for (var F in e)
          (r || Ue.call(e, F)) && !(b && // Safari 9 has enumerable `arguments.length` in strict mode.
          (F == "length" || // Node.js 0.10 has enumerable non-index properties on buffers.
          c && (F == "offset" || F == "parent") || // PhantomJS 2 has enumerable non-index properties on typed arrays.
          p && (F == "buffer" || F == "byteLength" || F == "byteOffset") || // Skip index properties.
          Zn(F, T))) && w.push(F);
        return w;
      }
      function Ka(e) {
        var r = e.length;
        return r ? e[Jl(0, r - 1)] : i;
      }
      function i_(e, r) {
        return as(Xt(e), Ur(r, 0, e.length));
      }
      function o_(e) {
        return as(Xt(e));
      }
      function Ul(e, r, s) {
        (s !== i && !Tn(e[r], s) || s === i && !(r in e)) && Vn(e, r, s);
      }
      function Gi(e, r, s) {
        var f = e[r];
        (!(Ue.call(e, r) && Tn(f, s)) || s === i && !(r in e)) && Vn(e, r, s);
      }
      function Zo(e, r) {
        for (var s = e.length; s--; )
          if (Tn(e[s][0], r))
            return s;
        return -1;
      }
      function s_(e, r, s, f) {
        return vr(e, function(c, p, b) {
          r(f, c, s(c), b);
        }), f;
      }
      function Ga(e, r) {
        return e && Fn(r, vt(r), e);
      }
      function l_(e, r) {
        return e && Fn(r, Vt(r), e);
      }
      function Vn(e, r, s) {
        r == "__proto__" && zo ? zo(e, r, {
          configurable: !0,
          enumerable: !0,
          value: s,
          writable: !0
        }) : e[r] = s;
      }
      function Hl(e, r) {
        for (var s = -1, f = r.length, c = O(f), p = e == null; ++s < f; )
          c[s] = p ? i : yu(e, r[s]);
        return c;
      }
      function Ur(e, r, s) {
        return e === e && (s !== i && (e = e <= s ? e : s), r !== i && (e = e >= r ? e : r)), e;
      }
      function vn(e, r, s, f, c, p) {
        var b, w = r & m, T = r & A, F = r & S;
        if (s && (b = c ? s(e, f, c, p) : s(e)), b !== i)
          return b;
        if (!Ze(e))
          return e;
        var M = ge(e);
        if (M) {
          if (b = J_(e), !w)
            return Xt(e, b);
        } else {
          var W = It(e), V = W == cr || W == y;
          if (yr(e))
            return dc(e, w);
          if (W == N || W == bt || V && !c) {
            if (b = T || V ? {} : Pc(e), !w)
              return T ? W_(e, l_(b, e)) : B_(e, Ga(b, e));
          } else {
            if (!Ke[W])
              return c ? e : {};
            b = k_(e, W, w);
          }
        }
        p || (p = new Sn());
        var ee = p.get(e);
        if (ee)
          return ee;
        p.set(e, b), lh(e) ? e.forEach(function(se) {
          b.add(vn(se, r, s, se, e, p));
        }) : oh(e) && e.forEach(function(se, Ae) {
          b.set(Ae, vn(se, r, s, Ae, e, p));
        });
        var oe = F ? T ? su : ou : T ? Vt : vt, we = M ? i : oe(e);
        return dn(we || e, function(se, Ae) {
          we && (Ae = se, se = e[Ae]), Gi(b, Ae, vn(se, r, s, Ae, e, p));
        }), b;
      }
      function u_(e) {
        var r = vt(e);
        return function(s) {
          return Ya(s, e, r);
        };
      }
      function Ya(e, r, s) {
        var f = s.length;
        if (e == null)
          return !f;
        for (e = He(e); f--; ) {
          var c = s[f], p = r[c], b = e[c];
          if (b === i && !(c in e) || !p(b))
            return !1;
        }
        return !0;
      }
      function za(e, r, s) {
        if (typeof e != "function")
          throw new pn(a);
        return ki(function() {
          e.apply(i, s);
        }, r);
      }
      function Yi(e, r, s, f) {
        var c = -1, p = Mo, b = !0, w = e.length, T = [], F = r.length;
        if (!w)
          return T;
        s && (r = Ve(r, en(s))), f ? (p = Al, b = !1) : r.length >= l && (p = Bi, b = !1, r = new Wr(r));
        e:
          for (; ++c < w; ) {
            var M = e[c], W = s == null ? M : s(M);
            if (M = f || M !== 0 ? M : 0, b && W === W) {
              for (var V = F; V--; )
                if (r[V] === W)
                  continue e;
              T.push(M);
            } else
              p(r, W, f) || T.push(M);
          }
        return T;
      }
      var vr = mc(Pn), Xa = mc(Kl, !0);
      function f_(e, r) {
        var s = !0;
        return vr(e, function(f, c, p) {
          return s = !!r(f, c, p), s;
        }), s;
      }
      function Qo(e, r, s) {
        for (var f = -1, c = e.length; ++f < c; ) {
          var p = e[f], b = r(p);
          if (b != null && (w === i ? b === b && !nn(b) : s(b, w)))
            var w = b, T = p;
        }
        return T;
      }
      function a_(e, r, s, f) {
        var c = e.length;
        for (s = be(s), s < 0 && (s = -s > c ? 0 : c + s), f = f === i || f > c ? c : be(f), f < 0 && (f += c), f = s > f ? 0 : fh(f); s < f; )
          e[s++] = r;
        return e;
      }
      function qa(e, r) {
        var s = [];
        return vr(e, function(f, c, p) {
          r(f, c, p) && s.push(f);
        }), s;
      }
      function St(e, r, s, f, c) {
        var p = -1, b = e.length;
        for (s || (s = Q_), c || (c = []); ++p < b; ) {
          var w = e[p];
          r > 0 && s(w) ? r > 1 ? St(w, r - 1, s, f, c) : dr(c, w) : f || (c[c.length] = w);
        }
        return c;
      }
      var $l = bc(), Va = bc(!0);
      function Pn(e, r) {
        return e && $l(e, r, vt);
      }
      function Kl(e, r) {
        return e && Va(e, r, vt);
      }
      function jo(e, r) {
        return hr(r, function(s) {
          return Qn(e[s]);
        });
      }
      function Hr(e, r) {
        r = mr(r, e);
        for (var s = 0, f = r.length; e != null && s < f; )
          e = e[Mn(r[s++])];
        return s && s == f ? e : i;
      }
      function Ja(e, r, s) {
        var f = r(e);
        return ge(e) ? f : dr(f, s(e));
      }
      function Bt(e) {
        return e == null ? e === i ? J : $ : Lr && Lr in He(e) ? X_(e) : om(e);
      }
      function Gl(e, r) {
        return e > r;
      }
      function c_(e, r) {
        return e != null && Ue.call(e, r);
      }
      function h_(e, r) {
        return e != null && r in He(e);
      }
      function d_(e, r, s) {
        return e >= Ot(r, s) && e < dt(r, s);
      }
      function Yl(e, r, s) {
        for (var f = s ? Al : Mo, c = e[0].length, p = e.length, b = p, w = O(p), T = 1 / 0, F = []; b--; ) {
          var M = e[b];
          b && r && (M = Ve(M, en(r))), T = Ot(M.length, T), w[b] = !s && (r || c >= 120 && M.length >= 120) ? new Wr(b && M) : i;
        }
        M = e[0];
        var W = -1, V = w[0];
        e:
          for (; ++W < c && F.length < T; ) {
            var ee = M[W], oe = r ? r(ee) : ee;
            if (ee = s || ee !== 0 ? ee : 0, !(V ? Bi(V, oe) : f(F, oe, s))) {
              for (b = p; --b; ) {
                var we = w[b];
                if (!(we ? Bi(we, oe) : f(e[b], oe, s)))
                  continue e;
              }
              V && V.push(oe), F.push(ee);
            }
          }
        return F;
      }
      function p_(e, r, s, f) {
        return Pn(e, function(c, p, b) {
          r(f, s(c), p, b);
        }), f;
      }
      function zi(e, r, s) {
        r = mr(r, e), e = Lc(e, r);
        var f = e == null ? e : e[Mn(mn(r))];
        return f == null ? i : jt(f, e, s);
      }
      function ka(e) {
        return je(e) && Bt(e) == bt;
      }
      function g_(e) {
        return je(e) && Bt(e) == ye;
      }
      function v_(e) {
        return je(e) && Bt(e) == ur;
      }
      function Xi(e, r, s, f, c) {
        return e === r ? !0 : e == null || r == null || !je(e) && !je(r) ? e !== e && r !== r : __(e, r, s, f, Xi, c);
      }
      function __(e, r, s, f, c, p) {
        var b = ge(e), w = ge(r), T = b ? Rn : It(e), F = w ? Rn : It(r);
        T = T == bt ? N : T, F = F == bt ? N : F;
        var M = T == N, W = F == N, V = T == F;
        if (V && yr(e)) {
          if (!yr(r))
            return !1;
          b = !0, M = !1;
        }
        if (V && !M)
          return p || (p = new Sn()), b || _i(e) ? Ic(e, r, s, f, c, p) : Y_(e, r, T, s, f, c, p);
        if (!(s & C)) {
          var ee = M && Ue.call(e, "__wrapped__"), oe = W && Ue.call(r, "__wrapped__");
          if (ee || oe) {
            var we = ee ? e.value() : e, se = oe ? r.value() : r;
            return p || (p = new Sn()), c(we, se, s, f, p);
          }
        }
        return V ? (p || (p = new Sn()), z_(e, r, s, f, c, p)) : !1;
      }
      function m_(e) {
        return je(e) && It(e) == E;
      }
      function zl(e, r, s, f) {
        var c = s.length, p = c, b = !f;
        if (e == null)
          return !p;
        for (e = He(e); c--; ) {
          var w = s[c];
          if (b && w[2] ? w[1] !== e[w[0]] : !(w[0] in e))
            return !1;
        }
        for (; ++c < p; ) {
          w = s[c];
          var T = w[0], F = e[T], M = w[1];
          if (b && w[2]) {
            if (F === i && !(T in e))
              return !1;
          } else {
            var W = new Sn();
            if (f)
              var V = f(F, M, T, e, r, W);
            if (!(V === i ? Xi(M, F, C | I, f, W) : V))
              return !1;
          }
        }
        return !0;
      }
      function Za(e) {
        if (!Ze(e) || em(e))
          return !1;
        var r = Qn(e) ? mv : ug;
        return r.test(Kr(e));
      }
      function b_(e) {
        return je(e) && Bt(e) == G;
      }
      function y_(e) {
        return je(e) && It(e) == K;
      }
      function w_(e) {
        return je(e) && vs(e.length) && !!ze[Bt(e)];
      }
      function Qa(e) {
        return typeof e == "function" ? e : e == null ? Jt : typeof e == "object" ? ge(e) ? tc(e[0], e[1]) : ec(e) : yh(e);
      }
      function Xl(e) {
        if (!Ji(e))
          return Sv(e);
        var r = [];
        for (var s in He(e))
          Ue.call(e, s) && s != "constructor" && r.push(s);
        return r;
      }
      function E_(e) {
        if (!Ze(e))
          return im(e);
        var r = Ji(e), s = [];
        for (var f in e)
          f == "constructor" && (r || !Ue.call(e, f)) || s.push(f);
        return s;
      }
      function ql(e, r) {
        return e < r;
      }
      function ja(e, r) {
        var s = -1, f = qt(e) ? O(e.length) : [];
        return vr(e, function(c, p, b) {
          f[++s] = r(c, p, b);
        }), f;
      }
      function ec(e) {
        var r = uu(e);
        return r.length == 1 && r[0][2] ? Mc(r[0][0], r[0][1]) : function(s) {
          return s === e || zl(s, e, r);
        };
      }
      function tc(e, r) {
        return au(e) && Fc(r) ? Mc(Mn(e), r) : function(s) {
          var f = yu(s, e);
          return f === i && f === r ? wu(s, e) : Xi(r, f, C | I);
        };
      }
      function es(e, r, s, f, c) {
        e !== r && $l(r, function(p, b) {
          if (c || (c = new Sn()), Ze(p))
            x_(e, r, b, s, es, f, c);
          else {
            var w = f ? f(hu(e, b), p, b + "", e, r, c) : i;
            w === i && (w = p), Ul(e, b, w);
          }
        }, Vt);
      }
      function x_(e, r, s, f, c, p, b) {
        var w = hu(e, s), T = hu(r, s), F = b.get(T);
        if (F) {
          Ul(e, s, F);
          return;
        }
        var M = p ? p(w, T, s + "", e, r, b) : i, W = M === i;
        if (W) {
          var V = ge(T), ee = !V && yr(T), oe = !V && !ee && _i(T);
          M = T, V || ee || oe ? ge(w) ? M = w : tt(w) ? M = Xt(w) : ee ? (W = !1, M = dc(T, !0)) : oe ? (W = !1, M = pc(T, !0)) : M = [] : Zi(T) || Gr(T) ? (M = w, Gr(w) ? M = ah(w) : (!Ze(w) || Qn(w)) && (M = Pc(T))) : W = !1;
        }
        W && (b.set(T, M), c(M, T, f, p, b), b.delete(T)), Ul(e, s, M);
      }
      function nc(e, r) {
        var s = e.length;
        if (s)
          return r += r < 0 ? s : 0, Zn(r, s) ? e[r] : i;
      }
      function rc(e, r, s) {
        r.length ? r = Ve(r, function(p) {
          return ge(p) ? function(b) {
            return Hr(b, p.length === 1 ? p[0] : p);
          } : p;
        }) : r = [Jt];
        var f = -1;
        r = Ve(r, en(re()));
        var c = ja(e, function(p, b, w) {
          var T = Ve(r, function(F) {
            return F(p);
          });
          return { criteria: T, index: ++f, value: p };
        });
        return kg(c, function(p, b) {
          return L_(p, b, s);
        });
      }
      function S_(e, r) {
        return ic(e, r, function(s, f) {
          return wu(e, f);
        });
      }
      function ic(e, r, s) {
        for (var f = -1, c = r.length, p = {}; ++f < c; ) {
          var b = r[f], w = Hr(e, b);
          s(w, b) && qi(p, mr(b, e), w);
        }
        return p;
      }
      function T_(e) {
        return function(r) {
          return Hr(r, e);
        };
      }
      function Vl(e, r, s, f) {
        var c = f ? Jg : si, p = -1, b = r.length, w = e;
        for (e === r && (r = Xt(r)), s && (w = Ve(e, en(s))); ++p < b; )
          for (var T = 0, F = r[p], M = s ? s(F) : F; (T = c(w, M, T, f)) > -1; )
            w !== e && Yo.call(w, T, 1), Yo.call(e, T, 1);
        return e;
      }
      function oc(e, r) {
        for (var s = e ? r.length : 0, f = s - 1; s--; ) {
          var c = r[s];
          if (s == f || c !== p) {
            var p = c;
            Zn(c) ? Yo.call(e, c, 1) : Ql(e, c);
          }
        }
        return e;
      }
      function Jl(e, r) {
        return e + qo(Ua() * (r - e + 1));
      }
      function A_(e, r, s, f) {
        for (var c = -1, p = dt(Xo((r - e) / (s || 1)), 0), b = O(p); p--; )
          b[f ? p : ++c] = e, e += s;
        return b;
      }
      function kl(e, r) {
        var s = "";
        if (!e || r < 1 || r > ie)
          return s;
        do
          r % 2 && (s += e), r = qo(r / 2), r && (e += e);
        while (r);
        return s;
      }
      function xe(e, r) {
        return du(Nc(e, r, Jt), e + "");
      }
      function C_(e) {
        return Ka(mi(e));
      }
      function O_(e, r) {
        var s = mi(e);
        return as(s, Ur(r, 0, s.length));
      }
      function qi(e, r, s, f) {
        if (!Ze(e))
          return e;
        r = mr(r, e);
        for (var c = -1, p = r.length, b = p - 1, w = e; w != null && ++c < p; ) {
          var T = Mn(r[c]), F = s;
          if (T === "__proto__" || T === "constructor" || T === "prototype")
            return e;
          if (c != b) {
            var M = w[T];
            F = f ? f(M, T, w) : i, F === i && (F = Ze(M) ? M : Zn(r[c + 1]) ? [] : {});
          }
          Gi(w, T, F), w = w[T];
        }
        return e;
      }
      var sc = Vo ? function(e, r) {
        return Vo.set(e, r), e;
      } : Jt, I_ = zo ? function(e, r) {
        return zo(e, "toString", {
          configurable: !0,
          enumerable: !1,
          value: xu(r),
          writable: !0
        });
      } : Jt;
      function D_(e) {
        return as(mi(e));
      }
      function _n(e, r, s) {
        var f = -1, c = e.length;
        r < 0 && (r = -r > c ? 0 : c + r), s = s > c ? c : s, s < 0 && (s += c), c = r > s ? 0 : s - r >>> 0, r >>>= 0;
        for (var p = O(c); ++f < c; )
          p[f] = e[f + r];
        return p;
      }
      function R_(e, r) {
        var s;
        return vr(e, function(f, c, p) {
          return s = r(f, c, p), !s;
        }), !!s;
      }
      function ts(e, r, s) {
        var f = 0, c = e == null ? f : e.length;
        if (typeof r == "number" && r === r && c <= cn) {
          for (; f < c; ) {
            var p = f + c >>> 1, b = e[p];
            b !== null && !nn(b) && (s ? b <= r : b < r) ? f = p + 1 : c = p;
          }
          return c;
        }
        return Zl(e, r, Jt, s);
      }
      function Zl(e, r, s, f) {
        var c = 0, p = e == null ? 0 : e.length;
        if (p === 0)
          return 0;
        r = s(r);
        for (var b = r !== r, w = r === null, T = nn(r), F = r === i; c < p; ) {
          var M = qo((c + p) / 2), W = s(e[M]), V = W !== i, ee = W === null, oe = W === W, we = nn(W);
          if (b)
            var se = f || oe;
          else
            F ? se = oe && (f || V) : w ? se = oe && V && (f || !ee) : T ? se = oe && V && !ee && (f || !we) : ee || we ? se = !1 : se = f ? W <= r : W < r;
          se ? c = M + 1 : p = M;
        }
        return Ot(p, ot);
      }
      function lc(e, r) {
        for (var s = -1, f = e.length, c = 0, p = []; ++s < f; ) {
          var b = e[s], w = r ? r(b) : b;
          if (!s || !Tn(w, T)) {
            var T = w;
            p[c++] = b === 0 ? 0 : b;
          }
        }
        return p;
      }
      function uc(e) {
        return typeof e == "number" ? e : nn(e) ? gt : +e;
      }
      function tn(e) {
        if (typeof e == "string")
          return e;
        if (ge(e))
          return Ve(e, tn) + "";
        if (nn(e))
          return Ha ? Ha.call(e) : "";
        var r = e + "";
        return r == "0" && 1 / e == -fe ? "-0" : r;
      }
      function _r(e, r, s) {
        var f = -1, c = Mo, p = e.length, b = !0, w = [], T = w;
        if (s)
          b = !1, c = Al;
        else if (p >= l) {
          var F = r ? null : K_(e);
          if (F)
            return Lo(F);
          b = !1, c = Bi, T = new Wr();
        } else
          T = r ? [] : w;
        e:
          for (; ++f < p; ) {
            var M = e[f], W = r ? r(M) : M;
            if (M = s || M !== 0 ? M : 0, b && W === W) {
              for (var V = T.length; V--; )
                if (T[V] === W)
                  continue e;
              r && T.push(W), w.push(M);
            } else
              c(T, W, s) || (T !== w && T.push(W), w.push(M));
          }
        return w;
      }
      function Ql(e, r) {
        return r = mr(r, e), e = Lc(e, r), e == null || delete e[Mn(mn(r))];
      }
      function fc(e, r, s, f) {
        return qi(e, r, s(Hr(e, r)), f);
      }
      function ns(e, r, s, f) {
        for (var c = e.length, p = f ? c : -1; (f ? p-- : ++p < c) && r(e[p], p, e); )
          ;
        return s ? _n(e, f ? 0 : p, f ? p + 1 : c) : _n(e, f ? p + 1 : 0, f ? c : p);
      }
      function ac(e, r) {
        var s = e;
        return s instanceof Oe && (s = s.value()), Cl(r, function(f, c) {
          return c.func.apply(c.thisArg, dr([f], c.args));
        }, s);
      }
      function jl(e, r, s) {
        var f = e.length;
        if (f < 2)
          return f ? _r(e[0]) : [];
        for (var c = -1, p = O(f); ++c < f; )
          for (var b = e[c], w = -1; ++w < f; )
            w != c && (p[c] = Yi(p[c] || b, e[w], r, s));
        return _r(St(p, 1), r, s);
      }
      function cc(e, r, s) {
        for (var f = -1, c = e.length, p = r.length, b = {}; ++f < c; ) {
          var w = f < p ? r[f] : i;
          s(b, e[f], w);
        }
        return b;
      }
      function eu(e) {
        return tt(e) ? e : [];
      }
      function tu(e) {
        return typeof e == "function" ? e : Jt;
      }
      function mr(e, r) {
        return ge(e) ? e : au(e, r) ? [e] : Hc(Le(e));
      }
      var P_ = xe;
      function br(e, r, s) {
        var f = e.length;
        return s = s === i ? f : s, !r && s >= f ? e : _n(e, r, s);
      }
      var hc = bv || function(e) {
        return xt.clearTimeout(e);
      };
      function dc(e, r) {
        if (r)
          return e.slice();
        var s = e.length, f = Ma ? Ma(s) : new e.constructor(s);
        return e.copy(f), f;
      }
      function nu(e) {
        var r = new e.constructor(e.byteLength);
        return new Ko(r).set(new Ko(e)), r;
      }
      function F_(e, r) {
        var s = r ? nu(e.buffer) : e.buffer;
        return new e.constructor(s, e.byteOffset, e.byteLength);
      }
      function M_(e) {
        var r = new e.constructor(e.source, kf.exec(e));
        return r.lastIndex = e.lastIndex, r;
      }
      function N_(e) {
        return Ki ? He(Ki.call(e)) : {};
      }
      function pc(e, r) {
        var s = r ? nu(e.buffer) : e.buffer;
        return new e.constructor(s, e.byteOffset, e.length);
      }
      function gc(e, r) {
        if (e !== r) {
          var s = e !== i, f = e === null, c = e === e, p = nn(e), b = r !== i, w = r === null, T = r === r, F = nn(r);
          if (!w && !F && !p && e > r || p && b && T && !w && !F || f && b && T || !s && T || !c)
            return 1;
          if (!f && !p && !F && e < r || F && s && c && !f && !p || w && s && c || !b && c || !T)
            return -1;
        }
        return 0;
      }
      function L_(e, r, s) {
        for (var f = -1, c = e.criteria, p = r.criteria, b = c.length, w = s.length; ++f < b; ) {
          var T = gc(c[f], p[f]);
          if (T) {
            if (f >= w)
              return T;
            var F = s[f];
            return T * (F == "desc" ? -1 : 1);
          }
        }
        return e.index - r.index;
      }
      function vc(e, r, s, f) {
        for (var c = -1, p = e.length, b = s.length, w = -1, T = r.length, F = dt(p - b, 0), M = O(T + F), W = !f; ++w < T; )
          M[w] = r[w];
        for (; ++c < b; )
          (W || c < p) && (M[s[c]] = e[c]);
        for (; F--; )
          M[w++] = e[c++];
        return M;
      }
      function _c(e, r, s, f) {
        for (var c = -1, p = e.length, b = -1, w = s.length, T = -1, F = r.length, M = dt(p - w, 0), W = O(M + F), V = !f; ++c < M; )
          W[c] = e[c];
        for (var ee = c; ++T < F; )
          W[ee + T] = r[T];
        for (; ++b < w; )
          (V || c < p) && (W[ee + s[b]] = e[c++]);
        return W;
      }
      function Xt(e, r) {
        var s = -1, f = e.length;
        for (r || (r = O(f)); ++s < f; )
          r[s] = e[s];
        return r;
      }
      function Fn(e, r, s, f) {
        var c = !s;
        s || (s = {});
        for (var p = -1, b = r.length; ++p < b; ) {
          var w = r[p], T = f ? f(s[w], e[w], w, s, e) : i;
          T === i && (T = e[w]), c ? Vn(s, w, T) : Gi(s, w, T);
        }
        return s;
      }
      function B_(e, r) {
        return Fn(e, fu(e), r);
      }
      function W_(e, r) {
        return Fn(e, Dc(e), r);
      }
      function rs(e, r) {
        return function(s, f) {
          var c = ge(s) ? Gg : s_, p = r ? r() : {};
          return c(s, e, re(f, 2), p);
        };
      }
      function pi(e) {
        return xe(function(r, s) {
          var f = -1, c = s.length, p = c > 1 ? s[c - 1] : i, b = c > 2 ? s[2] : i;
          for (p = e.length > 3 && typeof p == "function" ? (c--, p) : i, b && Wt(s[0], s[1], b) && (p = c < 3 ? i : p, c = 1), r = He(r); ++f < c; ) {
            var w = s[f];
            w && e(r, w, f, p);
          }
          return r;
        });
      }
      function mc(e, r) {
        return function(s, f) {
          if (s == null)
            return s;
          if (!qt(s))
            return e(s, f);
          for (var c = s.length, p = r ? c : -1, b = He(s); (r ? p-- : ++p < c) && f(b[p], p, b) !== !1; )
            ;
          return s;
        };
      }
      function bc(e) {
        return function(r, s, f) {
          for (var c = -1, p = He(r), b = f(r), w = b.length; w--; ) {
            var T = b[e ? w : ++c];
            if (s(p[T], T, p) === !1)
              break;
          }
          return r;
        };
      }
      function U_(e, r, s) {
        var f = r & P, c = Vi(e);
        function p() {
          var b = this && this !== xt && this instanceof p ? c : e;
          return b.apply(f ? s : this, arguments);
        }
        return p;
      }
      function yc(e) {
        return function(r) {
          r = Le(r);
          var s = li(r) ? xn(r) : i, f = s ? s[0] : r.charAt(0), c = s ? br(s, 1).join("") : r.slice(1);
          return f[e]() + c;
        };
      }
      function gi(e) {
        return function(r) {
          return Cl(mh(_h(r).replace(Ig, "")), e, "");
        };
      }
      function Vi(e) {
        return function() {
          var r = arguments;
          switch (r.length) {
            case 0:
              return new e();
            case 1:
              return new e(r[0]);
            case 2:
              return new e(r[0], r[1]);
            case 3:
              return new e(r[0], r[1], r[2]);
            case 4:
              return new e(r[0], r[1], r[2], r[3]);
            case 5:
              return new e(r[0], r[1], r[2], r[3], r[4]);
            case 6:
              return new e(r[0], r[1], r[2], r[3], r[4], r[5]);
            case 7:
              return new e(r[0], r[1], r[2], r[3], r[4], r[5], r[6]);
          }
          var s = di(e.prototype), f = e.apply(s, r);
          return Ze(f) ? f : s;
        };
      }
      function H_(e, r, s) {
        var f = Vi(e);
        function c() {
          for (var p = arguments.length, b = O(p), w = p, T = vi(c); w--; )
            b[w] = arguments[w];
          var F = p < 3 && b[0] !== T && b[p - 1] !== T ? [] : pr(b, T);
          if (p -= F.length, p < s)
            return Tc(
              e,
              r,
              is,
              c.placeholder,
              i,
              b,
              F,
              i,
              i,
              s - p
            );
          var M = this && this !== xt && this instanceof c ? f : e;
          return jt(M, this, b);
        }
        return c;
      }
      function wc(e) {
        return function(r, s, f) {
          var c = He(r);
          if (!qt(r)) {
            var p = re(s, 3);
            r = vt(r), s = function(w) {
              return p(c[w], w, c);
            };
          }
          var b = e(r, s, f);
          return b > -1 ? c[p ? r[b] : b] : i;
        };
      }
      function Ec(e) {
        return kn(function(r) {
          var s = r.length, f = s, c = gn.prototype.thru;
          for (e && r.reverse(); f--; ) {
            var p = r[f];
            if (typeof p != "function")
              throw new pn(a);
            if (c && !b && us(p) == "wrapper")
              var b = new gn([], !0);
          }
          for (f = b ? f : s; ++f < s; ) {
            p = r[f];
            var w = us(p), T = w == "wrapper" ? lu(p) : i;
            T && cu(T[0]) && T[1] == (Se | X | te | Re) && !T[4].length && T[9] == 1 ? b = b[us(T[0])].apply(b, T[3]) : b = p.length == 1 && cu(p) ? b[w]() : b.thru(p);
          }
          return function() {
            var F = arguments, M = F[0];
            if (b && F.length == 1 && ge(M))
              return b.plant(M).value();
            for (var W = 0, V = s ? r[W].apply(this, F) : M; ++W < s; )
              V = r[W].call(this, V);
            return V;
          };
        });
      }
      function is(e, r, s, f, c, p, b, w, T, F) {
        var M = r & Se, W = r & P, V = r & B, ee = r & (X | L), oe = r & ue, we = V ? i : Vi(e);
        function se() {
          for (var Ae = arguments.length, De = O(Ae), rn = Ae; rn--; )
            De[rn] = arguments[rn];
          if (ee)
            var Ut = vi(se), on = Qg(De, Ut);
          if (f && (De = vc(De, f, c, ee)), p && (De = _c(De, p, b, ee)), Ae -= on, ee && Ae < F) {
            var nt = pr(De, Ut);
            return Tc(
              e,
              r,
              is,
              se.placeholder,
              s,
              De,
              nt,
              w,
              T,
              F - Ae
            );
          }
          var An = W ? s : this, er = V ? An[e] : e;
          return Ae = De.length, w ? De = sm(De, w) : oe && Ae > 1 && De.reverse(), M && T < Ae && (De.length = T), this && this !== xt && this instanceof se && (er = we || Vi(er)), er.apply(An, De);
        }
        return se;
      }
      function xc(e, r) {
        return function(s, f) {
          return p_(s, e, r(f), {});
        };
      }
      function os(e, r) {
        return function(s, f) {
          var c;
          if (s === i && f === i)
            return r;
          if (s !== i && (c = s), f !== i) {
            if (c === i)
              return f;
            typeof s == "string" || typeof f == "string" ? (s = tn(s), f = tn(f)) : (s = uc(s), f = uc(f)), c = e(s, f);
          }
          return c;
        };
      }
      function ru(e) {
        return kn(function(r) {
          return r = Ve(r, en(re())), xe(function(s) {
            var f = this;
            return e(r, function(c) {
              return jt(c, f, s);
            });
          });
        });
      }
      function ss(e, r) {
        r = r === i ? " " : tn(r);
        var s = r.length;
        if (s < 2)
          return s ? kl(r, e) : r;
        var f = kl(r, Xo(e / ui(r)));
        return li(r) ? br(xn(f), 0, e).join("") : f.slice(0, e);
      }
      function $_(e, r, s, f) {
        var c = r & P, p = Vi(e);
        function b() {
          for (var w = -1, T = arguments.length, F = -1, M = f.length, W = O(M + T), V = this && this !== xt && this instanceof b ? p : e; ++F < M; )
            W[F] = f[F];
          for (; T--; )
            W[F++] = arguments[++w];
          return jt(V, c ? s : this, W);
        }
        return b;
      }
      function Sc(e) {
        return function(r, s, f) {
          return f && typeof f != "number" && Wt(r, s, f) && (s = f = i), r = jn(r), s === i ? (s = r, r = 0) : s = jn(s), f = f === i ? r < s ? 1 : -1 : jn(f), A_(r, s, f, e);
        };
      }
      function ls(e) {
        return function(r, s) {
          return typeof r == "string" && typeof s == "string" || (r = bn(r), s = bn(s)), e(r, s);
        };
      }
      function Tc(e, r, s, f, c, p, b, w, T, F) {
        var M = r & X, W = M ? b : i, V = M ? i : b, ee = M ? p : i, oe = M ? i : p;
        r |= M ? te : Ee, r &= ~(M ? Ee : te), r & z || (r &= ~(P | B));
        var we = [
          e,
          r,
          c,
          ee,
          W,
          oe,
          V,
          w,
          T,
          F
        ], se = s.apply(i, we);
        return cu(e) && Bc(se, we), se.placeholder = f, Wc(se, e, r);
      }
      function iu(e) {
        var r = ht[e];
        return function(s, f) {
          if (s = bn(s), f = f == null ? 0 : Ot(be(f), 292), f && Wa(s)) {
            var c = (Le(s) + "e").split("e"), p = r(c[0] + "e" + (+c[1] + f));
            return c = (Le(p) + "e").split("e"), +(c[0] + "e" + (+c[1] - f));
          }
          return r(s);
        };
      }
      var K_ = ci && 1 / Lo(new ci([, -0]))[1] == fe ? function(e) {
        return new ci(e);
      } : Au;
      function Ac(e) {
        return function(r) {
          var s = It(r);
          return s == E ? Ml(r) : s == K ? ov(r) : Zg(r, e(r));
        };
      }
      function Jn(e, r, s, f, c, p, b, w) {
        var T = r & B;
        if (!T && typeof e != "function")
          throw new pn(a);
        var F = f ? f.length : 0;
        if (F || (r &= ~(te | Ee), f = c = i), b = b === i ? b : dt(be(b), 0), w = w === i ? w : be(w), F -= c ? c.length : 0, r & Ee) {
          var M = f, W = c;
          f = c = i;
        }
        var V = T ? i : lu(e), ee = [
          e,
          r,
          s,
          f,
          c,
          M,
          W,
          p,
          b,
          w
        ];
        if (V && rm(ee, V), e = ee[0], r = ee[1], s = ee[2], f = ee[3], c = ee[4], w = ee[9] = ee[9] === i ? T ? 0 : e.length : dt(ee[9] - F, 0), !w && r & (X | L) && (r &= ~(X | L)), !r || r == P)
          var oe = U_(e, r, s);
        else
          r == X || r == L ? oe = H_(e, r, w) : (r == te || r == (P | te)) && !c.length ? oe = $_(e, r, s, f) : oe = is.apply(i, ee);
        var we = V ? sc : Bc;
        return Wc(we(oe, ee), e, r);
      }
      function Cc(e, r, s, f) {
        return e === i || Tn(e, ai[s]) && !Ue.call(f, s) ? r : e;
      }
      function Oc(e, r, s, f, c, p) {
        return Ze(e) && Ze(r) && (p.set(r, e), es(e, r, i, Oc, p), p.delete(r)), e;
      }
      function G_(e) {
        return Zi(e) ? i : e;
      }
      function Ic(e, r, s, f, c, p) {
        var b = s & C, w = e.length, T = r.length;
        if (w != T && !(b && T > w))
          return !1;
        var F = p.get(e), M = p.get(r);
        if (F && M)
          return F == r && M == e;
        var W = -1, V = !0, ee = s & I ? new Wr() : i;
        for (p.set(e, r), p.set(r, e); ++W < w; ) {
          var oe = e[W], we = r[W];
          if (f)
            var se = b ? f(we, oe, W, r, e, p) : f(oe, we, W, e, r, p);
          if (se !== i) {
            if (se)
              continue;
            V = !1;
            break;
          }
          if (ee) {
            if (!Ol(r, function(Ae, De) {
              if (!Bi(ee, De) && (oe === Ae || c(oe, Ae, s, f, p)))
                return ee.push(De);
            })) {
              V = !1;
              break;
            }
          } else if (!(oe === we || c(oe, we, s, f, p))) {
            V = !1;
            break;
          }
        }
        return p.delete(e), p.delete(r), V;
      }
      function Y_(e, r, s, f, c, p, b) {
        switch (s) {
          case Pe:
            if (e.byteLength != r.byteLength || e.byteOffset != r.byteOffset)
              return !1;
            e = e.buffer, r = r.buffer;
          case ye:
            return !(e.byteLength != r.byteLength || !p(new Ko(e), new Ko(r)));
          case Yn:
          case ur:
          case D:
            return Tn(+e, +r);
          case ar:
            return e.name == r.name && e.message == r.message;
          case G:
          case U:
            return e == r + "";
          case E:
            var w = Ml;
          case K:
            var T = f & C;
            if (w || (w = Lo), e.size != r.size && !T)
              return !1;
            var F = b.get(e);
            if (F)
              return F == r;
            f |= I, b.set(e, r);
            var M = Ic(w(e), w(r), f, c, p, b);
            return b.delete(e), M;
          case ne:
            if (Ki)
              return Ki.call(e) == Ki.call(r);
        }
        return !1;
      }
      function z_(e, r, s, f, c, p) {
        var b = s & C, w = ou(e), T = w.length, F = ou(r), M = F.length;
        if (T != M && !b)
          return !1;
        for (var W = T; W--; ) {
          var V = w[W];
          if (!(b ? V in r : Ue.call(r, V)))
            return !1;
        }
        var ee = p.get(e), oe = p.get(r);
        if (ee && oe)
          return ee == r && oe == e;
        var we = !0;
        p.set(e, r), p.set(r, e);
        for (var se = b; ++W < T; ) {
          V = w[W];
          var Ae = e[V], De = r[V];
          if (f)
            var rn = b ? f(De, Ae, V, r, e, p) : f(Ae, De, V, e, r, p);
          if (!(rn === i ? Ae === De || c(Ae, De, s, f, p) : rn)) {
            we = !1;
            break;
          }
          se || (se = V == "constructor");
        }
        if (we && !se) {
          var Ut = e.constructor, on = r.constructor;
          Ut != on && "constructor" in e && "constructor" in r && !(typeof Ut == "function" && Ut instanceof Ut && typeof on == "function" && on instanceof on) && (we = !1);
        }
        return p.delete(e), p.delete(r), we;
      }
      function kn(e) {
        return du(Nc(e, i, Yc), e + "");
      }
      function ou(e) {
        return Ja(e, vt, fu);
      }
      function su(e) {
        return Ja(e, Vt, Dc);
      }
      var lu = Vo ? function(e) {
        return Vo.get(e);
      } : Au;
      function us(e) {
        for (var r = e.name + "", s = hi[r], f = Ue.call(hi, r) ? s.length : 0; f--; ) {
          var c = s[f], p = c.func;
          if (p == null || p == e)
            return c.name;
        }
        return r;
      }
      function vi(e) {
        var r = Ue.call(d, "placeholder") ? d : e;
        return r.placeholder;
      }
      function re() {
        var e = d.iteratee || Su;
        return e = e === Su ? Qa : e, arguments.length ? e(arguments[0], arguments[1]) : e;
      }
      function fs(e, r) {
        var s = e.__data__;
        return j_(r) ? s[typeof r == "string" ? "string" : "hash"] : s.map;
      }
      function uu(e) {
        for (var r = vt(e), s = r.length; s--; ) {
          var f = r[s], c = e[f];
          r[s] = [f, c, Fc(c)];
        }
        return r;
      }
      function $r(e, r) {
        var s = nv(e, r);
        return Za(s) ? s : i;
      }
      function X_(e) {
        var r = Ue.call(e, Lr), s = e[Lr];
        try {
          e[Lr] = i;
          var f = !0;
        } catch {
        }
        var c = Ho.call(e);
        return f && (r ? e[Lr] = s : delete e[Lr]), c;
      }
      var fu = Ll ? function(e) {
        return e == null ? [] : (e = He(e), hr(Ll(e), function(r) {
          return La.call(e, r);
        }));
      } : Cu, Dc = Ll ? function(e) {
        for (var r = []; e; )
          dr(r, fu(e)), e = Go(e);
        return r;
      } : Cu, It = Bt;
      (Bl && It(new Bl(new ArrayBuffer(1))) != Pe || Ui && It(new Ui()) != E || Wl && It(Wl.resolve()) != H || ci && It(new ci()) != K || Hi && It(new Hi()) != Q) && (It = function(e) {
        var r = Bt(e), s = r == N ? e.constructor : i, f = s ? Kr(s) : "";
        if (f)
          switch (f) {
            case Ov:
              return Pe;
            case Iv:
              return E;
            case Dv:
              return H;
            case Rv:
              return K;
            case Pv:
              return Q;
          }
        return r;
      });
      function q_(e, r, s) {
        for (var f = -1, c = s.length; ++f < c; ) {
          var p = s[f], b = p.size;
          switch (p.type) {
            case "drop":
              e += b;
              break;
            case "dropRight":
              r -= b;
              break;
            case "take":
              r = Ot(r, e + b);
              break;
            case "takeRight":
              e = dt(e, r - b);
              break;
          }
        }
        return { start: e, end: r };
      }
      function V_(e) {
        var r = e.match(eg);
        return r ? r[1].split(tg) : [];
      }
      function Rc(e, r, s) {
        r = mr(r, e);
        for (var f = -1, c = r.length, p = !1; ++f < c; ) {
          var b = Mn(r[f]);
          if (!(p = e != null && s(e, b)))
            break;
          e = e[b];
        }
        return p || ++f != c ? p : (c = e == null ? 0 : e.length, !!c && vs(c) && Zn(b, c) && (ge(e) || Gr(e)));
      }
      function J_(e) {
        var r = e.length, s = new e.constructor(r);
        return r && typeof e[0] == "string" && Ue.call(e, "index") && (s.index = e.index, s.input = e.input), s;
      }
      function Pc(e) {
        return typeof e.constructor == "function" && !Ji(e) ? di(Go(e)) : {};
      }
      function k_(e, r, s) {
        var f = e.constructor;
        switch (r) {
          case ye:
            return nu(e);
          case Yn:
          case ur:
            return new f(+e);
          case Pe:
            return F_(e, s);
          case Fe:
          case yt:
          case lt:
          case Lt:
          case wt:
          case zn:
          case ii:
          case Et:
          case zt:
            return pc(e, s);
          case E:
            return new f();
          case D:
          case U:
            return new f(e);
          case G:
            return M_(e);
          case K:
            return new f();
          case ne:
            return N_(e);
        }
      }
      function Z_(e, r) {
        var s = r.length;
        if (!s)
          return e;
        var f = s - 1;
        return r[f] = (s > 1 ? "& " : "") + r[f], r = r.join(s > 2 ? ", " : " "), e.replace(jp, `{
/* [wrapped with ` + r + `] */
`);
      }
      function Q_(e) {
        return ge(e) || Gr(e) || !!(Ba && e && e[Ba]);
      }
      function Zn(e, r) {
        var s = typeof e;
        return r = r ?? ie, !!r && (s == "number" || s != "symbol" && ag.test(e)) && e > -1 && e % 1 == 0 && e < r;
      }
      function Wt(e, r, s) {
        if (!Ze(s))
          return !1;
        var f = typeof r;
        return (f == "number" ? qt(s) && Zn(r, s.length) : f == "string" && r in s) ? Tn(s[r], e) : !1;
      }
      function au(e, r) {
        if (ge(e))
          return !1;
        var s = typeof e;
        return s == "number" || s == "symbol" || s == "boolean" || e == null || nn(e) ? !0 : Jp.test(e) || !Vp.test(e) || r != null && e in He(r);
      }
      function j_(e) {
        var r = typeof e;
        return r == "string" || r == "number" || r == "symbol" || r == "boolean" ? e !== "__proto__" : e === null;
      }
      function cu(e) {
        var r = us(e), s = d[r];
        if (typeof s != "function" || !(r in Oe.prototype))
          return !1;
        if (e === s)
          return !0;
        var f = lu(s);
        return !!f && e === f[0];
      }
      function em(e) {
        return !!Fa && Fa in e;
      }
      var tm = Wo ? Qn : Ou;
      function Ji(e) {
        var r = e && e.constructor, s = typeof r == "function" && r.prototype || ai;
        return e === s;
      }
      function Fc(e) {
        return e === e && !Ze(e);
      }
      function Mc(e, r) {
        return function(s) {
          return s == null ? !1 : s[e] === r && (r !== i || e in He(s));
        };
      }
      function nm(e) {
        var r = ps(e, function(f) {
          return s.size === v && s.clear(), f;
        }), s = r.cache;
        return r;
      }
      function rm(e, r) {
        var s = e[1], f = r[1], c = s | f, p = c < (P | B | Se), b = f == Se && s == X || f == Se && s == Re && e[7].length <= r[8] || f == (Se | Re) && r[7].length <= r[8] && s == X;
        if (!(p || b))
          return e;
        f & P && (e[2] = r[2], c |= s & P ? 0 : z);
        var w = r[3];
        if (w) {
          var T = e[3];
          e[3] = T ? vc(T, w, r[4]) : w, e[4] = T ? pr(e[3], _) : r[4];
        }
        return w = r[5], w && (T = e[5], e[5] = T ? _c(T, w, r[6]) : w, e[6] = T ? pr(e[5], _) : r[6]), w = r[7], w && (e[7] = w), f & Se && (e[8] = e[8] == null ? r[8] : Ot(e[8], r[8])), e[9] == null && (e[9] = r[9]), e[0] = r[0], e[1] = c, e;
      }
      function im(e) {
        var r = [];
        if (e != null)
          for (var s in He(e))
            r.push(s);
        return r;
      }
      function om(e) {
        return Ho.call(e);
      }
      function Nc(e, r, s) {
        return r = dt(r === i ? e.length - 1 : r, 0), function() {
          for (var f = arguments, c = -1, p = dt(f.length - r, 0), b = O(p); ++c < p; )
            b[c] = f[r + c];
          c = -1;
          for (var w = O(r + 1); ++c < r; )
            w[c] = f[c];
          return w[r] = s(b), jt(e, this, w);
        };
      }
      function Lc(e, r) {
        return r.length < 2 ? e : Hr(e, _n(r, 0, -1));
      }
      function sm(e, r) {
        for (var s = e.length, f = Ot(r.length, s), c = Xt(e); f--; ) {
          var p = r[f];
          e[f] = Zn(p, s) ? c[p] : i;
        }
        return e;
      }
      function hu(e, r) {
        if (!(r === "constructor" && typeof e[r] == "function") && r != "__proto__")
          return e[r];
      }
      var Bc = Uc(sc), ki = wv || function(e, r) {
        return xt.setTimeout(e, r);
      }, du = Uc(I_);
      function Wc(e, r, s) {
        var f = r + "";
        return du(e, Z_(f, lm(V_(f), s)));
      }
      function Uc(e) {
        var r = 0, s = 0;
        return function() {
          var f = Tv(), c = We - (f - s);
          if (s = f, c > 0) {
            if (++r >= Ce)
              return arguments[0];
          } else
            r = 0;
          return e.apply(i, arguments);
        };
      }
      function as(e, r) {
        var s = -1, f = e.length, c = f - 1;
        for (r = r === i ? f : r; ++s < r; ) {
          var p = Jl(s, c), b = e[p];
          e[p] = e[s], e[s] = b;
        }
        return e.length = r, e;
      }
      var Hc = nm(function(e) {
        var r = [];
        return e.charCodeAt(0) === 46 && r.push(""), e.replace(kp, function(s, f, c, p) {
          r.push(c ? p.replace(ig, "$1") : f || s);
        }), r;
      });
      function Mn(e) {
        if (typeof e == "string" || nn(e))
          return e;
        var r = e + "";
        return r == "0" && 1 / e == -fe ? "-0" : r;
      }
      function Kr(e) {
        if (e != null) {
          try {
            return Uo.call(e);
          } catch {
          }
          try {
            return e + "";
          } catch {
          }
        }
        return "";
      }
      function lm(e, r) {
        return dn(st, function(s) {
          var f = "_." + s[0];
          r & s[1] && !Mo(e, f) && e.push(f);
        }), e.sort();
      }
      function $c(e) {
        if (e instanceof Oe)
          return e.clone();
        var r = new gn(e.__wrapped__, e.__chain__);
        return r.__actions__ = Xt(e.__actions__), r.__index__ = e.__index__, r.__values__ = e.__values__, r;
      }
      function um(e, r, s) {
        (s ? Wt(e, r, s) : r === i) ? r = 1 : r = dt(be(r), 0);
        var f = e == null ? 0 : e.length;
        if (!f || r < 1)
          return [];
        for (var c = 0, p = 0, b = O(Xo(f / r)); c < f; )
          b[p++] = _n(e, c, c += r);
        return b;
      }
      function fm(e) {
        for (var r = -1, s = e == null ? 0 : e.length, f = 0, c = []; ++r < s; ) {
          var p = e[r];
          p && (c[f++] = p);
        }
        return c;
      }
      function am() {
        var e = arguments.length;
        if (!e)
          return [];
        for (var r = O(e - 1), s = arguments[0], f = e; f--; )
          r[f - 1] = arguments[f];
        return dr(ge(s) ? Xt(s) : [s], St(r, 1));
      }
      var cm = xe(function(e, r) {
        return tt(e) ? Yi(e, St(r, 1, tt, !0)) : [];
      }), hm = xe(function(e, r) {
        var s = mn(r);
        return tt(s) && (s = i), tt(e) ? Yi(e, St(r, 1, tt, !0), re(s, 2)) : [];
      }), dm = xe(function(e, r) {
        var s = mn(r);
        return tt(s) && (s = i), tt(e) ? Yi(e, St(r, 1, tt, !0), i, s) : [];
      });
      function pm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (r = s || r === i ? 1 : be(r), _n(e, r < 0 ? 0 : r, f)) : [];
      }
      function gm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (r = s || r === i ? 1 : be(r), r = f - r, _n(e, 0, r < 0 ? 0 : r)) : [];
      }
      function vm(e, r) {
        return e && e.length ? ns(e, re(r, 3), !0, !0) : [];
      }
      function _m(e, r) {
        return e && e.length ? ns(e, re(r, 3), !0) : [];
      }
      function mm(e, r, s, f) {
        var c = e == null ? 0 : e.length;
        return c ? (s && typeof s != "number" && Wt(e, r, s) && (s = 0, f = c), a_(e, r, s, f)) : [];
      }
      function Kc(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = s == null ? 0 : be(s);
        return c < 0 && (c = dt(f + c, 0)), No(e, re(r, 3), c);
      }
      function Gc(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = f - 1;
        return s !== i && (c = be(s), c = s < 0 ? dt(f + c, 0) : Ot(c, f - 1)), No(e, re(r, 3), c, !0);
      }
      function Yc(e) {
        var r = e == null ? 0 : e.length;
        return r ? St(e, 1) : [];
      }
      function bm(e) {
        var r = e == null ? 0 : e.length;
        return r ? St(e, fe) : [];
      }
      function ym(e, r) {
        var s = e == null ? 0 : e.length;
        return s ? (r = r === i ? 1 : be(r), St(e, r)) : [];
      }
      function wm(e) {
        for (var r = -1, s = e == null ? 0 : e.length, f = {}; ++r < s; ) {
          var c = e[r];
          f[c[0]] = c[1];
        }
        return f;
      }
      function zc(e) {
        return e && e.length ? e[0] : i;
      }
      function Em(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = s == null ? 0 : be(s);
        return c < 0 && (c = dt(f + c, 0)), si(e, r, c);
      }
      function xm(e) {
        var r = e == null ? 0 : e.length;
        return r ? _n(e, 0, -1) : [];
      }
      var Sm = xe(function(e) {
        var r = Ve(e, eu);
        return r.length && r[0] === e[0] ? Yl(r) : [];
      }), Tm = xe(function(e) {
        var r = mn(e), s = Ve(e, eu);
        return r === mn(s) ? r = i : s.pop(), s.length && s[0] === e[0] ? Yl(s, re(r, 2)) : [];
      }), Am = xe(function(e) {
        var r = mn(e), s = Ve(e, eu);
        return r = typeof r == "function" ? r : i, r && s.pop(), s.length && s[0] === e[0] ? Yl(s, i, r) : [];
      });
      function Cm(e, r) {
        return e == null ? "" : xv.call(e, r);
      }
      function mn(e) {
        var r = e == null ? 0 : e.length;
        return r ? e[r - 1] : i;
      }
      function Om(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = f;
        return s !== i && (c = be(s), c = c < 0 ? dt(f + c, 0) : Ot(c, f - 1)), r === r ? lv(e, r, c) : No(e, Ta, c, !0);
      }
      function Im(e, r) {
        return e && e.length ? nc(e, be(r)) : i;
      }
      var Dm = xe(Xc);
      function Xc(e, r) {
        return e && e.length && r && r.length ? Vl(e, r) : e;
      }
      function Rm(e, r, s) {
        return e && e.length && r && r.length ? Vl(e, r, re(s, 2)) : e;
      }
      function Pm(e, r, s) {
        return e && e.length && r && r.length ? Vl(e, r, i, s) : e;
      }
      var Fm = kn(function(e, r) {
        var s = e == null ? 0 : e.length, f = Hl(e, r);
        return oc(e, Ve(r, function(c) {
          return Zn(c, s) ? +c : c;
        }).sort(gc)), f;
      });
      function Mm(e, r) {
        var s = [];
        if (!(e && e.length))
          return s;
        var f = -1, c = [], p = e.length;
        for (r = re(r, 3); ++f < p; ) {
          var b = e[f];
          r(b, f, e) && (s.push(b), c.push(f));
        }
        return oc(e, c), s;
      }
      function pu(e) {
        return e == null ? e : Cv.call(e);
      }
      function Nm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (s && typeof s != "number" && Wt(e, r, s) ? (r = 0, s = f) : (r = r == null ? 0 : be(r), s = s === i ? f : be(s)), _n(e, r, s)) : [];
      }
      function Lm(e, r) {
        return ts(e, r);
      }
      function Bm(e, r, s) {
        return Zl(e, r, re(s, 2));
      }
      function Wm(e, r) {
        var s = e == null ? 0 : e.length;
        if (s) {
          var f = ts(e, r);
          if (f < s && Tn(e[f], r))
            return f;
        }
        return -1;
      }
      function Um(e, r) {
        return ts(e, r, !0);
      }
      function Hm(e, r, s) {
        return Zl(e, r, re(s, 2), !0);
      }
      function $m(e, r) {
        var s = e == null ? 0 : e.length;
        if (s) {
          var f = ts(e, r, !0) - 1;
          if (Tn(e[f], r))
            return f;
        }
        return -1;
      }
      function Km(e) {
        return e && e.length ? lc(e) : [];
      }
      function Gm(e, r) {
        return e && e.length ? lc(e, re(r, 2)) : [];
      }
      function Ym(e) {
        var r = e == null ? 0 : e.length;
        return r ? _n(e, 1, r) : [];
      }
      function zm(e, r, s) {
        return e && e.length ? (r = s || r === i ? 1 : be(r), _n(e, 0, r < 0 ? 0 : r)) : [];
      }
      function Xm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (r = s || r === i ? 1 : be(r), r = f - r, _n(e, r < 0 ? 0 : r, f)) : [];
      }
      function qm(e, r) {
        return e && e.length ? ns(e, re(r, 3), !1, !0) : [];
      }
      function Vm(e, r) {
        return e && e.length ? ns(e, re(r, 3)) : [];
      }
      var Jm = xe(function(e) {
        return _r(St(e, 1, tt, !0));
      }), km = xe(function(e) {
        var r = mn(e);
        return tt(r) && (r = i), _r(St(e, 1, tt, !0), re(r, 2));
      }), Zm = xe(function(e) {
        var r = mn(e);
        return r = typeof r == "function" ? r : i, _r(St(e, 1, tt, !0), i, r);
      });
      function Qm(e) {
        return e && e.length ? _r(e) : [];
      }
      function jm(e, r) {
        return e && e.length ? _r(e, re(r, 2)) : [];
      }
      function e0(e, r) {
        return r = typeof r == "function" ? r : i, e && e.length ? _r(e, i, r) : [];
      }
      function gu(e) {
        if (!(e && e.length))
          return [];
        var r = 0;
        return e = hr(e, function(s) {
          if (tt(s))
            return r = dt(s.length, r), !0;
        }), Pl(r, function(s) {
          return Ve(e, Il(s));
        });
      }
      function qc(e, r) {
        if (!(e && e.length))
          return [];
        var s = gu(e);
        return r == null ? s : Ve(s, function(f) {
          return jt(r, i, f);
        });
      }
      var t0 = xe(function(e, r) {
        return tt(e) ? Yi(e, r) : [];
      }), n0 = xe(function(e) {
        return jl(hr(e, tt));
      }), r0 = xe(function(e) {
        var r = mn(e);
        return tt(r) && (r = i), jl(hr(e, tt), re(r, 2));
      }), i0 = xe(function(e) {
        var r = mn(e);
        return r = typeof r == "function" ? r : i, jl(hr(e, tt), i, r);
      }), o0 = xe(gu);
      function s0(e, r) {
        return cc(e || [], r || [], Gi);
      }
      function l0(e, r) {
        return cc(e || [], r || [], qi);
      }
      var u0 = xe(function(e) {
        var r = e.length, s = r > 1 ? e[r - 1] : i;
        return s = typeof s == "function" ? (e.pop(), s) : i, qc(e, s);
      });
      function Vc(e) {
        var r = d(e);
        return r.__chain__ = !0, r;
      }
      function f0(e, r) {
        return r(e), e;
      }
      function cs(e, r) {
        return r(e);
      }
      var a0 = kn(function(e) {
        var r = e.length, s = r ? e[0] : 0, f = this.__wrapped__, c = function(p) {
          return Hl(p, e);
        };
        return r > 1 || this.__actions__.length || !(f instanceof Oe) || !Zn(s) ? this.thru(c) : (f = f.slice(s, +s + (r ? 1 : 0)), f.__actions__.push({
          func: cs,
          args: [c],
          thisArg: i
        }), new gn(f, this.__chain__).thru(function(p) {
          return r && !p.length && p.push(i), p;
        }));
      });
      function c0() {
        return Vc(this);
      }
      function h0() {
        return new gn(this.value(), this.__chain__);
      }
      function d0() {
        this.__values__ === i && (this.__values__ = uh(this.value()));
        var e = this.__index__ >= this.__values__.length, r = e ? i : this.__values__[this.__index__++];
        return { done: e, value: r };
      }
      function p0() {
        return this;
      }
      function g0(e) {
        for (var r, s = this; s instanceof ko; ) {
          var f = $c(s);
          f.__index__ = 0, f.__values__ = i, r ? c.__wrapped__ = f : r = f;
          var c = f;
          s = s.__wrapped__;
        }
        return c.__wrapped__ = e, r;
      }
      function v0() {
        var e = this.__wrapped__;
        if (e instanceof Oe) {
          var r = e;
          return this.__actions__.length && (r = new Oe(this)), r = r.reverse(), r.__actions__.push({
            func: cs,
            args: [pu],
            thisArg: i
          }), new gn(r, this.__chain__);
        }
        return this.thru(pu);
      }
      function _0() {
        return ac(this.__wrapped__, this.__actions__);
      }
      var m0 = rs(function(e, r, s) {
        Ue.call(e, s) ? ++e[s] : Vn(e, s, 1);
      });
      function b0(e, r, s) {
        var f = ge(e) ? xa : f_;
        return s && Wt(e, r, s) && (r = i), f(e, re(r, 3));
      }
      function y0(e, r) {
        var s = ge(e) ? hr : qa;
        return s(e, re(r, 3));
      }
      var w0 = wc(Kc), E0 = wc(Gc);
      function x0(e, r) {
        return St(hs(e, r), 1);
      }
      function S0(e, r) {
        return St(hs(e, r), fe);
      }
      function T0(e, r, s) {
        return s = s === i ? 1 : be(s), St(hs(e, r), s);
      }
      function Jc(e, r) {
        var s = ge(e) ? dn : vr;
        return s(e, re(r, 3));
      }
      function kc(e, r) {
        var s = ge(e) ? Yg : Xa;
        return s(e, re(r, 3));
      }
      var A0 = rs(function(e, r, s) {
        Ue.call(e, s) ? e[s].push(r) : Vn(e, s, [r]);
      });
      function C0(e, r, s, f) {
        e = qt(e) ? e : mi(e), s = s && !f ? be(s) : 0;
        var c = e.length;
        return s < 0 && (s = dt(c + s, 0)), _s(e) ? s <= c && e.indexOf(r, s) > -1 : !!c && si(e, r, s) > -1;
      }
      var O0 = xe(function(e, r, s) {
        var f = -1, c = typeof r == "function", p = qt(e) ? O(e.length) : [];
        return vr(e, function(b) {
          p[++f] = c ? jt(r, b, s) : zi(b, r, s);
        }), p;
      }), I0 = rs(function(e, r, s) {
        Vn(e, s, r);
      });
      function hs(e, r) {
        var s = ge(e) ? Ve : ja;
        return s(e, re(r, 3));
      }
      function D0(e, r, s, f) {
        return e == null ? [] : (ge(r) || (r = r == null ? [] : [r]), s = f ? i : s, ge(s) || (s = s == null ? [] : [s]), rc(e, r, s));
      }
      var R0 = rs(function(e, r, s) {
        e[s ? 0 : 1].push(r);
      }, function() {
        return [[], []];
      });
      function P0(e, r, s) {
        var f = ge(e) ? Cl : Ca, c = arguments.length < 3;
        return f(e, re(r, 4), s, c, vr);
      }
      function F0(e, r, s) {
        var f = ge(e) ? zg : Ca, c = arguments.length < 3;
        return f(e, re(r, 4), s, c, Xa);
      }
      function M0(e, r) {
        var s = ge(e) ? hr : qa;
        return s(e, gs(re(r, 3)));
      }
      function N0(e) {
        var r = ge(e) ? Ka : C_;
        return r(e);
      }
      function L0(e, r, s) {
        (s ? Wt(e, r, s) : r === i) ? r = 1 : r = be(r);
        var f = ge(e) ? i_ : O_;
        return f(e, r);
      }
      function B0(e) {
        var r = ge(e) ? o_ : D_;
        return r(e);
      }
      function W0(e) {
        if (e == null)
          return 0;
        if (qt(e))
          return _s(e) ? ui(e) : e.length;
        var r = It(e);
        return r == E || r == K ? e.size : Xl(e).length;
      }
      function U0(e, r, s) {
        var f = ge(e) ? Ol : R_;
        return s && Wt(e, r, s) && (r = i), f(e, re(r, 3));
      }
      var H0 = xe(function(e, r) {
        if (e == null)
          return [];
        var s = r.length;
        return s > 1 && Wt(e, r[0], r[1]) ? r = [] : s > 2 && Wt(r[0], r[1], r[2]) && (r = [r[0]]), rc(e, St(r, 1), []);
      }), ds = yv || function() {
        return xt.Date.now();
      };
      function $0(e, r) {
        if (typeof r != "function")
          throw new pn(a);
        return e = be(e), function() {
          if (--e < 1)
            return r.apply(this, arguments);
        };
      }
      function Zc(e, r, s) {
        return r = s ? i : r, r = e && r == null ? e.length : r, Jn(e, Se, i, i, i, i, r);
      }
      function Qc(e, r) {
        var s;
        if (typeof r != "function")
          throw new pn(a);
        return e = be(e), function() {
          return --e > 0 && (s = r.apply(this, arguments)), e <= 1 && (r = i), s;
        };
      }
      var vu = xe(function(e, r, s) {
        var f = P;
        if (s.length) {
          var c = pr(s, vi(vu));
          f |= te;
        }
        return Jn(e, f, r, s, c);
      }), jc = xe(function(e, r, s) {
        var f = P | B;
        if (s.length) {
          var c = pr(s, vi(jc));
          f |= te;
        }
        return Jn(r, f, e, s, c);
      });
      function eh(e, r, s) {
        r = s ? i : r;
        var f = Jn(e, X, i, i, i, i, i, r);
        return f.placeholder = eh.placeholder, f;
      }
      function th(e, r, s) {
        r = s ? i : r;
        var f = Jn(e, L, i, i, i, i, i, r);
        return f.placeholder = th.placeholder, f;
      }
      function nh(e, r, s) {
        var f, c, p, b, w, T, F = 0, M = !1, W = !1, V = !0;
        if (typeof e != "function")
          throw new pn(a);
        r = bn(r) || 0, Ze(s) && (M = !!s.leading, W = "maxWait" in s, p = W ? dt(bn(s.maxWait) || 0, r) : p, V = "trailing" in s ? !!s.trailing : V);
        function ee(nt) {
          var An = f, er = c;
          return f = c = i, F = nt, b = e.apply(er, An), b;
        }
        function oe(nt) {
          return F = nt, w = ki(Ae, r), M ? ee(nt) : b;
        }
        function we(nt) {
          var An = nt - T, er = nt - F, wh = r - An;
          return W ? Ot(wh, p - er) : wh;
        }
        function se(nt) {
          var An = nt - T, er = nt - F;
          return T === i || An >= r || An < 0 || W && er >= p;
        }
        function Ae() {
          var nt = ds();
          if (se(nt))
            return De(nt);
          w = ki(Ae, we(nt));
        }
        function De(nt) {
          return w = i, V && f ? ee(nt) : (f = c = i, b);
        }
        function rn() {
          w !== i && hc(w), F = 0, f = T = c = w = i;
        }
        function Ut() {
          return w === i ? b : De(ds());
        }
        function on() {
          var nt = ds(), An = se(nt);
          if (f = arguments, c = this, T = nt, An) {
            if (w === i)
              return oe(T);
            if (W)
              return hc(w), w = ki(Ae, r), ee(T);
          }
          return w === i && (w = ki(Ae, r)), b;
        }
        return on.cancel = rn, on.flush = Ut, on;
      }
      var K0 = xe(function(e, r) {
        return za(e, 1, r);
      }), G0 = xe(function(e, r, s) {
        return za(e, bn(r) || 0, s);
      });
      function Y0(e) {
        return Jn(e, ue);
      }
      function ps(e, r) {
        if (typeof e != "function" || r != null && typeof r != "function")
          throw new pn(a);
        var s = function() {
          var f = arguments, c = r ? r.apply(this, f) : f[0], p = s.cache;
          if (p.has(c))
            return p.get(c);
          var b = e.apply(this, f);
          return s.cache = p.set(c, b) || p, b;
        };
        return s.cache = new (ps.Cache || qn)(), s;
      }
      ps.Cache = qn;
      function gs(e) {
        if (typeof e != "function")
          throw new pn(a);
        return function() {
          var r = arguments;
          switch (r.length) {
            case 0:
              return !e.call(this);
            case 1:
              return !e.call(this, r[0]);
            case 2:
              return !e.call(this, r[0], r[1]);
            case 3:
              return !e.call(this, r[0], r[1], r[2]);
          }
          return !e.apply(this, r);
        };
      }
      function z0(e) {
        return Qc(2, e);
      }
      var X0 = P_(function(e, r) {
        r = r.length == 1 && ge(r[0]) ? Ve(r[0], en(re())) : Ve(St(r, 1), en(re()));
        var s = r.length;
        return xe(function(f) {
          for (var c = -1, p = Ot(f.length, s); ++c < p; )
            f[c] = r[c].call(this, f[c]);
          return jt(e, this, f);
        });
      }), _u = xe(function(e, r) {
        var s = pr(r, vi(_u));
        return Jn(e, te, i, r, s);
      }), rh = xe(function(e, r) {
        var s = pr(r, vi(rh));
        return Jn(e, Ee, i, r, s);
      }), q0 = kn(function(e, r) {
        return Jn(e, Re, i, i, i, r);
      });
      function V0(e, r) {
        if (typeof e != "function")
          throw new pn(a);
        return r = r === i ? r : be(r), xe(e, r);
      }
      function J0(e, r) {
        if (typeof e != "function")
          throw new pn(a);
        return r = r == null ? 0 : dt(be(r), 0), xe(function(s) {
          var f = s[r], c = br(s, 0, r);
          return f && dr(c, f), jt(e, this, c);
        });
      }
      function k0(e, r, s) {
        var f = !0, c = !0;
        if (typeof e != "function")
          throw new pn(a);
        return Ze(s) && (f = "leading" in s ? !!s.leading : f, c = "trailing" in s ? !!s.trailing : c), nh(e, r, {
          leading: f,
          maxWait: r,
          trailing: c
        });
      }
      function Z0(e) {
        return Zc(e, 1);
      }
      function Q0(e, r) {
        return _u(tu(r), e);
      }
      function j0() {
        if (!arguments.length)
          return [];
        var e = arguments[0];
        return ge(e) ? e : [e];
      }
      function eb(e) {
        return vn(e, S);
      }
      function tb(e, r) {
        return r = typeof r == "function" ? r : i, vn(e, S, r);
      }
      function nb(e) {
        return vn(e, m | S);
      }
      function rb(e, r) {
        return r = typeof r == "function" ? r : i, vn(e, m | S, r);
      }
      function ib(e, r) {
        return r == null || Ya(e, r, vt(r));
      }
      function Tn(e, r) {
        return e === r || e !== e && r !== r;
      }
      var ob = ls(Gl), sb = ls(function(e, r) {
        return e >= r;
      }), Gr = ka(/* @__PURE__ */ function() {
        return arguments;
      }()) ? ka : function(e) {
        return je(e) && Ue.call(e, "callee") && !La.call(e, "callee");
      }, ge = O.isArray, lb = _a ? en(_a) : g_;
      function qt(e) {
        return e != null && vs(e.length) && !Qn(e);
      }
      function tt(e) {
        return je(e) && qt(e);
      }
      function ub(e) {
        return e === !0 || e === !1 || je(e) && Bt(e) == Yn;
      }
      var yr = Ev || Ou, fb = ma ? en(ma) : v_;
      function ab(e) {
        return je(e) && e.nodeType === 1 && !Zi(e);
      }
      function cb(e) {
        if (e == null)
          return !0;
        if (qt(e) && (ge(e) || typeof e == "string" || typeof e.splice == "function" || yr(e) || _i(e) || Gr(e)))
          return !e.length;
        var r = It(e);
        if (r == E || r == K)
          return !e.size;
        if (Ji(e))
          return !Xl(e).length;
        for (var s in e)
          if (Ue.call(e, s))
            return !1;
        return !0;
      }
      function hb(e, r) {
        return Xi(e, r);
      }
      function db(e, r, s) {
        s = typeof s == "function" ? s : i;
        var f = s ? s(e, r) : i;
        return f === i ? Xi(e, r, i, s) : !!f;
      }
      function mu(e) {
        if (!je(e))
          return !1;
        var r = Bt(e);
        return r == ar || r == fr || typeof e.message == "string" && typeof e.name == "string" && !Zi(e);
      }
      function pb(e) {
        return typeof e == "number" && Wa(e);
      }
      function Qn(e) {
        if (!Ze(e))
          return !1;
        var r = Bt(e);
        return r == cr || r == y || r == ri || r == q;
      }
      function ih(e) {
        return typeof e == "number" && e == be(e);
      }
      function vs(e) {
        return typeof e == "number" && e > -1 && e % 1 == 0 && e <= ie;
      }
      function Ze(e) {
        var r = typeof e;
        return e != null && (r == "object" || r == "function");
      }
      function je(e) {
        return e != null && typeof e == "object";
      }
      var oh = ba ? en(ba) : m_;
      function gb(e, r) {
        return e === r || zl(e, r, uu(r));
      }
      function vb(e, r, s) {
        return s = typeof s == "function" ? s : i, zl(e, r, uu(r), s);
      }
      function _b(e) {
        return sh(e) && e != +e;
      }
      function mb(e) {
        if (tm(e))
          throw new de(u);
        return Za(e);
      }
      function bb(e) {
        return e === null;
      }
      function yb(e) {
        return e == null;
      }
      function sh(e) {
        return typeof e == "number" || je(e) && Bt(e) == D;
      }
      function Zi(e) {
        if (!je(e) || Bt(e) != N)
          return !1;
        var r = Go(e);
        if (r === null)
          return !0;
        var s = Ue.call(r, "constructor") && r.constructor;
        return typeof s == "function" && s instanceof s && Uo.call(s) == vv;
      }
      var bu = ya ? en(ya) : b_;
      function wb(e) {
        return ih(e) && e >= -ie && e <= ie;
      }
      var lh = wa ? en(wa) : y_;
      function _s(e) {
        return typeof e == "string" || !ge(e) && je(e) && Bt(e) == U;
      }
      function nn(e) {
        return typeof e == "symbol" || je(e) && Bt(e) == ne;
      }
      var _i = Ea ? en(Ea) : w_;
      function Eb(e) {
        return e === i;
      }
      function xb(e) {
        return je(e) && It(e) == Q;
      }
      function Sb(e) {
        return je(e) && Bt(e) == ae;
      }
      var Tb = ls(ql), Ab = ls(function(e, r) {
        return e <= r;
      });
      function uh(e) {
        if (!e)
          return [];
        if (qt(e))
          return _s(e) ? xn(e) : Xt(e);
        if (Wi && e[Wi])
          return iv(e[Wi]());
        var r = It(e), s = r == E ? Ml : r == K ? Lo : mi;
        return s(e);
      }
      function jn(e) {
        if (!e)
          return e === 0 ? e : 0;
        if (e = bn(e), e === fe || e === -fe) {
          var r = e < 0 ? -1 : 1;
          return r * mt;
        }
        return e === e ? e : 0;
      }
      function be(e) {
        var r = jn(e), s = r % 1;
        return r === r ? s ? r - s : r : 0;
      }
      function fh(e) {
        return e ? Ur(be(e), 0, qe) : 0;
      }
      function bn(e) {
        if (typeof e == "number")
          return e;
        if (nn(e))
          return gt;
        if (Ze(e)) {
          var r = typeof e.valueOf == "function" ? e.valueOf() : e;
          e = Ze(r) ? r + "" : r;
        }
        if (typeof e != "string")
          return e === 0 ? e : +e;
        e = Oa(e);
        var s = lg.test(e);
        return s || fg.test(e) ? $g(e.slice(2), s ? 2 : 8) : sg.test(e) ? gt : +e;
      }
      function ah(e) {
        return Fn(e, Vt(e));
      }
      function Cb(e) {
        return e ? Ur(be(e), -ie, ie) : e === 0 ? e : 0;
      }
      function Le(e) {
        return e == null ? "" : tn(e);
      }
      var Ob = pi(function(e, r) {
        if (Ji(r) || qt(r)) {
          Fn(r, vt(r), e);
          return;
        }
        for (var s in r)
          Ue.call(r, s) && Gi(e, s, r[s]);
      }), ch = pi(function(e, r) {
        Fn(r, Vt(r), e);
      }), ms = pi(function(e, r, s, f) {
        Fn(r, Vt(r), e, f);
      }), Ib = pi(function(e, r, s, f) {
        Fn(r, vt(r), e, f);
      }), Db = kn(Hl);
      function Rb(e, r) {
        var s = di(e);
        return r == null ? s : Ga(s, r);
      }
      var Pb = xe(function(e, r) {
        e = He(e);
        var s = -1, f = r.length, c = f > 2 ? r[2] : i;
        for (c && Wt(r[0], r[1], c) && (f = 1); ++s < f; )
          for (var p = r[s], b = Vt(p), w = -1, T = b.length; ++w < T; ) {
            var F = b[w], M = e[F];
            (M === i || Tn(M, ai[F]) && !Ue.call(e, F)) && (e[F] = p[F]);
          }
        return e;
      }), Fb = xe(function(e) {
        return e.push(i, Oc), jt(hh, i, e);
      });
      function Mb(e, r) {
        return Sa(e, re(r, 3), Pn);
      }
      function Nb(e, r) {
        return Sa(e, re(r, 3), Kl);
      }
      function Lb(e, r) {
        return e == null ? e : $l(e, re(r, 3), Vt);
      }
      function Bb(e, r) {
        return e == null ? e : Va(e, re(r, 3), Vt);
      }
      function Wb(e, r) {
        return e && Pn(e, re(r, 3));
      }
      function Ub(e, r) {
        return e && Kl(e, re(r, 3));
      }
      function Hb(e) {
        return e == null ? [] : jo(e, vt(e));
      }
      function $b(e) {
        return e == null ? [] : jo(e, Vt(e));
      }
      function yu(e, r, s) {
        var f = e == null ? i : Hr(e, r);
        return f === i ? s : f;
      }
      function Kb(e, r) {
        return e != null && Rc(e, r, c_);
      }
      function wu(e, r) {
        return e != null && Rc(e, r, h_);
      }
      var Gb = xc(function(e, r, s) {
        r != null && typeof r.toString != "function" && (r = Ho.call(r)), e[r] = s;
      }, xu(Jt)), Yb = xc(function(e, r, s) {
        r != null && typeof r.toString != "function" && (r = Ho.call(r)), Ue.call(e, r) ? e[r].push(s) : e[r] = [s];
      }, re), zb = xe(zi);
      function vt(e) {
        return qt(e) ? $a(e) : Xl(e);
      }
      function Vt(e) {
        return qt(e) ? $a(e, !0) : E_(e);
      }
      function Xb(e, r) {
        var s = {};
        return r = re(r, 3), Pn(e, function(f, c, p) {
          Vn(s, r(f, c, p), f);
        }), s;
      }
      function qb(e, r) {
        var s = {};
        return r = re(r, 3), Pn(e, function(f, c, p) {
          Vn(s, c, r(f, c, p));
        }), s;
      }
      var Vb = pi(function(e, r, s) {
        es(e, r, s);
      }), hh = pi(function(e, r, s, f) {
        es(e, r, s, f);
      }), Jb = kn(function(e, r) {
        var s = {};
        if (e == null)
          return s;
        var f = !1;
        r = Ve(r, function(p) {
          return p = mr(p, e), f || (f = p.length > 1), p;
        }), Fn(e, su(e), s), f && (s = vn(s, m | A | S, G_));
        for (var c = r.length; c--; )
          Ql(s, r[c]);
        return s;
      });
      function kb(e, r) {
        return dh(e, gs(re(r)));
      }
      var Zb = kn(function(e, r) {
        return e == null ? {} : S_(e, r);
      });
      function dh(e, r) {
        if (e == null)
          return {};
        var s = Ve(su(e), function(f) {
          return [f];
        });
        return r = re(r), ic(e, s, function(f, c) {
          return r(f, c[0]);
        });
      }
      function Qb(e, r, s) {
        r = mr(r, e);
        var f = -1, c = r.length;
        for (c || (c = 1, e = i); ++f < c; ) {
          var p = e == null ? i : e[Mn(r[f])];
          p === i && (f = c, p = s), e = Qn(p) ? p.call(e) : p;
        }
        return e;
      }
      function jb(e, r, s) {
        return e == null ? e : qi(e, r, s);
      }
      function ey(e, r, s, f) {
        return f = typeof f == "function" ? f : i, e == null ? e : qi(e, r, s, f);
      }
      var ph = Ac(vt), gh = Ac(Vt);
      function ty(e, r, s) {
        var f = ge(e), c = f || yr(e) || _i(e);
        if (r = re(r, 4), s == null) {
          var p = e && e.constructor;
          c ? s = f ? new p() : [] : Ze(e) ? s = Qn(p) ? di(Go(e)) : {} : s = {};
        }
        return (c ? dn : Pn)(e, function(b, w, T) {
          return r(s, b, w, T);
        }), s;
      }
      function ny(e, r) {
        return e == null ? !0 : Ql(e, r);
      }
      function ry(e, r, s) {
        return e == null ? e : fc(e, r, tu(s));
      }
      function iy(e, r, s, f) {
        return f = typeof f == "function" ? f : i, e == null ? e : fc(e, r, tu(s), f);
      }
      function mi(e) {
        return e == null ? [] : Fl(e, vt(e));
      }
      function oy(e) {
        return e == null ? [] : Fl(e, Vt(e));
      }
      function sy(e, r, s) {
        return s === i && (s = r, r = i), s !== i && (s = bn(s), s = s === s ? s : 0), r !== i && (r = bn(r), r = r === r ? r : 0), Ur(bn(e), r, s);
      }
      function ly(e, r, s) {
        return r = jn(r), s === i ? (s = r, r = 0) : s = jn(s), e = bn(e), d_(e, r, s);
      }
      function uy(e, r, s) {
        if (s && typeof s != "boolean" && Wt(e, r, s) && (r = s = i), s === i && (typeof r == "boolean" ? (s = r, r = i) : typeof e == "boolean" && (s = e, e = i)), e === i && r === i ? (e = 0, r = 1) : (e = jn(e), r === i ? (r = e, e = 0) : r = jn(r)), e > r) {
          var f = e;
          e = r, r = f;
        }
        if (s || e % 1 || r % 1) {
          var c = Ua();
          return Ot(e + c * (r - e + Hg("1e-" + ((c + "").length - 1))), r);
        }
        return Jl(e, r);
      }
      var fy = gi(function(e, r, s) {
        return r = r.toLowerCase(), e + (s ? vh(r) : r);
      });
      function vh(e) {
        return Eu(Le(e).toLowerCase());
      }
      function _h(e) {
        return e = Le(e), e && e.replace(cg, jg).replace(Dg, "");
      }
      function ay(e, r, s) {
        e = Le(e), r = tn(r);
        var f = e.length;
        s = s === i ? f : Ur(be(s), 0, f);
        var c = s;
        return s -= r.length, s >= 0 && e.slice(s, c) == r;
      }
      function cy(e) {
        return e = Le(e), e && zp.test(e) ? e.replace(Vf, ev) : e;
      }
      function hy(e) {
        return e = Le(e), e && Zp.test(e) ? e.replace(_l, "\\$&") : e;
      }
      var dy = gi(function(e, r, s) {
        return e + (s ? "-" : "") + r.toLowerCase();
      }), py = gi(function(e, r, s) {
        return e + (s ? " " : "") + r.toLowerCase();
      }), gy = yc("toLowerCase");
      function vy(e, r, s) {
        e = Le(e), r = be(r);
        var f = r ? ui(e) : 0;
        if (!r || f >= r)
          return e;
        var c = (r - f) / 2;
        return ss(qo(c), s) + e + ss(Xo(c), s);
      }
      function _y(e, r, s) {
        e = Le(e), r = be(r);
        var f = r ? ui(e) : 0;
        return r && f < r ? e + ss(r - f, s) : e;
      }
      function my(e, r, s) {
        e = Le(e), r = be(r);
        var f = r ? ui(e) : 0;
        return r && f < r ? ss(r - f, s) + e : e;
      }
      function by(e, r, s) {
        return s || r == null ? r = 0 : r && (r = +r), Av(Le(e).replace(ml, ""), r || 0);
      }
      function yy(e, r, s) {
        return (s ? Wt(e, r, s) : r === i) ? r = 1 : r = be(r), kl(Le(e), r);
      }
      function wy() {
        var e = arguments, r = Le(e[0]);
        return e.length < 3 ? r : r.replace(e[1], e[2]);
      }
      var Ey = gi(function(e, r, s) {
        return e + (s ? "_" : "") + r.toLowerCase();
      });
      function xy(e, r, s) {
        return s && typeof s != "number" && Wt(e, r, s) && (r = s = i), s = s === i ? qe : s >>> 0, s ? (e = Le(e), e && (typeof r == "string" || r != null && !bu(r)) && (r = tn(r), !r && li(e)) ? br(xn(e), 0, s) : e.split(r, s)) : [];
      }
      var Sy = gi(function(e, r, s) {
        return e + (s ? " " : "") + Eu(r);
      });
      function Ty(e, r, s) {
        return e = Le(e), s = s == null ? 0 : Ur(be(s), 0, e.length), r = tn(r), e.slice(s, s + r.length) == r;
      }
      function Ay(e, r, s) {
        var f = d.templateSettings;
        s && Wt(e, r, s) && (r = i), e = Le(e), r = ms({}, r, f, Cc);
        var c = ms({}, r.imports, f.imports, Cc), p = vt(c), b = Fl(c, p), w, T, F = 0, M = r.interpolate || Ro, W = "__p += '", V = Nl(
          (r.escape || Ro).source + "|" + M.source + "|" + (M === Jf ? og : Ro).source + "|" + (r.evaluate || Ro).source + "|$",
          "g"
        ), ee = "//# sourceURL=" + (Ue.call(r, "sourceURL") ? (r.sourceURL + "").replace(/\s/g, " ") : "lodash.templateSources[" + ++Ng + "]") + `
`;
        e.replace(V, function(se, Ae, De, rn, Ut, on) {
          return De || (De = rn), W += e.slice(F, on).replace(hg, tv), Ae && (w = !0, W += `' +
__e(` + Ae + `) +
'`), Ut && (T = !0, W += `';
` + Ut + `;
__p += '`), De && (W += `' +
((__t = (` + De + `)) == null ? '' : __t) +
'`), F = on + se.length, se;
        }), W += `';
`;
        var oe = Ue.call(r, "variable") && r.variable;
        if (!oe)
          W = `with (obj) {
` + W + `
}
`;
        else if (rg.test(oe))
          throw new de(h);
        W = (T ? W.replace(Do, "") : W).replace(Kp, "$1").replace(Gp, "$1;"), W = "function(" + (oe || "obj") + `) {
` + (oe ? "" : `obj || (obj = {});
`) + "var __t, __p = ''" + (w ? ", __e = _.escape" : "") + (T ? `, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
` : `;
`) + W + `return __p
}`;
        var we = bh(function() {
          return Me(p, ee + "return " + W).apply(i, b);
        });
        if (we.source = W, mu(we))
          throw we;
        return we;
      }
      function Cy(e) {
        return Le(e).toLowerCase();
      }
      function Oy(e) {
        return Le(e).toUpperCase();
      }
      function Iy(e, r, s) {
        if (e = Le(e), e && (s || r === i))
          return Oa(e);
        if (!e || !(r = tn(r)))
          return e;
        var f = xn(e), c = xn(r), p = Ia(f, c), b = Da(f, c) + 1;
        return br(f, p, b).join("");
      }
      function Dy(e, r, s) {
        if (e = Le(e), e && (s || r === i))
          return e.slice(0, Pa(e) + 1);
        if (!e || !(r = tn(r)))
          return e;
        var f = xn(e), c = Da(f, xn(r)) + 1;
        return br(f, 0, c).join("");
      }
      function Ry(e, r, s) {
        if (e = Le(e), e && (s || r === i))
          return e.replace(ml, "");
        if (!e || !(r = tn(r)))
          return e;
        var f = xn(e), c = Ia(f, xn(r));
        return br(f, c).join("");
      }
      function Py(e, r) {
        var s = Z, f = he;
        if (Ze(r)) {
          var c = "separator" in r ? r.separator : c;
          s = "length" in r ? be(r.length) : s, f = "omission" in r ? tn(r.omission) : f;
        }
        e = Le(e);
        var p = e.length;
        if (li(e)) {
          var b = xn(e);
          p = b.length;
        }
        if (s >= p)
          return e;
        var w = s - ui(f);
        if (w < 1)
          return f;
        var T = b ? br(b, 0, w).join("") : e.slice(0, w);
        if (c === i)
          return T + f;
        if (b && (w += T.length - w), bu(c)) {
          if (e.slice(w).search(c)) {
            var F, M = T;
            for (c.global || (c = Nl(c.source, Le(kf.exec(c)) + "g")), c.lastIndex = 0; F = c.exec(M); )
              var W = F.index;
            T = T.slice(0, W === i ? w : W);
          }
        } else if (e.indexOf(tn(c), w) != w) {
          var V = T.lastIndexOf(c);
          V > -1 && (T = T.slice(0, V));
        }
        return T + f;
      }
      function Fy(e) {
        return e = Le(e), e && Yp.test(e) ? e.replace(qf, uv) : e;
      }
      var My = gi(function(e, r, s) {
        return e + (s ? " " : "") + r.toUpperCase();
      }), Eu = yc("toUpperCase");
      function mh(e, r, s) {
        return e = Le(e), r = s ? i : r, r === i ? rv(e) ? cv(e) : Vg(e) : e.match(r) || [];
      }
      var bh = xe(function(e, r) {
        try {
          return jt(e, i, r);
        } catch (s) {
          return mu(s) ? s : new de(s);
        }
      }), Ny = kn(function(e, r) {
        return dn(r, function(s) {
          s = Mn(s), Vn(e, s, vu(e[s], e));
        }), e;
      });
      function Ly(e) {
        var r = e == null ? 0 : e.length, s = re();
        return e = r ? Ve(e, function(f) {
          if (typeof f[1] != "function")
            throw new pn(a);
          return [s(f[0]), f[1]];
        }) : [], xe(function(f) {
          for (var c = -1; ++c < r; ) {
            var p = e[c];
            if (jt(p[0], this, f))
              return jt(p[1], this, f);
          }
        });
      }
      function By(e) {
        return u_(vn(e, m));
      }
      function xu(e) {
        return function() {
          return e;
        };
      }
      function Wy(e, r) {
        return e == null || e !== e ? r : e;
      }
      var Uy = Ec(), Hy = Ec(!0);
      function Jt(e) {
        return e;
      }
      function Su(e) {
        return Qa(typeof e == "function" ? e : vn(e, m));
      }
      function $y(e) {
        return ec(vn(e, m));
      }
      function Ky(e, r) {
        return tc(e, vn(r, m));
      }
      var Gy = xe(function(e, r) {
        return function(s) {
          return zi(s, e, r);
        };
      }), Yy = xe(function(e, r) {
        return function(s) {
          return zi(e, s, r);
        };
      });
      function Tu(e, r, s) {
        var f = vt(r), c = jo(r, f);
        s == null && !(Ze(r) && (c.length || !f.length)) && (s = r, r = e, e = this, c = jo(r, vt(r)));
        var p = !(Ze(s) && "chain" in s) || !!s.chain, b = Qn(e);
        return dn(c, function(w) {
          var T = r[w];
          e[w] = T, b && (e.prototype[w] = function() {
            var F = this.__chain__;
            if (p || F) {
              var M = e(this.__wrapped__), W = M.__actions__ = Xt(this.__actions__);
              return W.push({ func: T, args: arguments, thisArg: e }), M.__chain__ = F, M;
            }
            return T.apply(e, dr([this.value()], arguments));
          });
        }), e;
      }
      function zy() {
        return xt._ === this && (xt._ = _v), this;
      }
      function Au() {
      }
      function Xy(e) {
        return e = be(e), xe(function(r) {
          return nc(r, e);
        });
      }
      var qy = ru(Ve), Vy = ru(xa), Jy = ru(Ol);
      function yh(e) {
        return au(e) ? Il(Mn(e)) : T_(e);
      }
      function ky(e) {
        return function(r) {
          return e == null ? i : Hr(e, r);
        };
      }
      var Zy = Sc(), Qy = Sc(!0);
      function Cu() {
        return [];
      }
      function Ou() {
        return !1;
      }
      function jy() {
        return {};
      }
      function ew() {
        return "";
      }
      function tw() {
        return !0;
      }
      function nw(e, r) {
        if (e = be(e), e < 1 || e > ie)
          return [];
        var s = qe, f = Ot(e, qe);
        r = re(r), e -= qe;
        for (var c = Pl(f, r); ++s < e; )
          r(s);
        return c;
      }
      function rw(e) {
        return ge(e) ? Ve(e, Mn) : nn(e) ? [e] : Xt(Hc(Le(e)));
      }
      function iw(e) {
        var r = ++gv;
        return Le(e) + r;
      }
      var ow = os(function(e, r) {
        return e + r;
      }, 0), sw = iu("ceil"), lw = os(function(e, r) {
        return e / r;
      }, 1), uw = iu("floor");
      function fw(e) {
        return e && e.length ? Qo(e, Jt, Gl) : i;
      }
      function aw(e, r) {
        return e && e.length ? Qo(e, re(r, 2), Gl) : i;
      }
      function cw(e) {
        return Aa(e, Jt);
      }
      function hw(e, r) {
        return Aa(e, re(r, 2));
      }
      function dw(e) {
        return e && e.length ? Qo(e, Jt, ql) : i;
      }
      function pw(e, r) {
        return e && e.length ? Qo(e, re(r, 2), ql) : i;
      }
      var gw = os(function(e, r) {
        return e * r;
      }, 1), vw = iu("round"), _w = os(function(e, r) {
        return e - r;
      }, 0);
      function mw(e) {
        return e && e.length ? Rl(e, Jt) : 0;
      }
      function bw(e, r) {
        return e && e.length ? Rl(e, re(r, 2)) : 0;
      }
      return d.after = $0, d.ary = Zc, d.assign = Ob, d.assignIn = ch, d.assignInWith = ms, d.assignWith = Ib, d.at = Db, d.before = Qc, d.bind = vu, d.bindAll = Ny, d.bindKey = jc, d.castArray = j0, d.chain = Vc, d.chunk = um, d.compact = fm, d.concat = am, d.cond = Ly, d.conforms = By, d.constant = xu, d.countBy = m0, d.create = Rb, d.curry = eh, d.curryRight = th, d.debounce = nh, d.defaults = Pb, d.defaultsDeep = Fb, d.defer = K0, d.delay = G0, d.difference = cm, d.differenceBy = hm, d.differenceWith = dm, d.drop = pm, d.dropRight = gm, d.dropRightWhile = vm, d.dropWhile = _m, d.fill = mm, d.filter = y0, d.flatMap = x0, d.flatMapDeep = S0, d.flatMapDepth = T0, d.flatten = Yc, d.flattenDeep = bm, d.flattenDepth = ym, d.flip = Y0, d.flow = Uy, d.flowRight = Hy, d.fromPairs = wm, d.functions = Hb, d.functionsIn = $b, d.groupBy = A0, d.initial = xm, d.intersection = Sm, d.intersectionBy = Tm, d.intersectionWith = Am, d.invert = Gb, d.invertBy = Yb, d.invokeMap = O0, d.iteratee = Su, d.keyBy = I0, d.keys = vt, d.keysIn = Vt, d.map = hs, d.mapKeys = Xb, d.mapValues = qb, d.matches = $y, d.matchesProperty = Ky, d.memoize = ps, d.merge = Vb, d.mergeWith = hh, d.method = Gy, d.methodOf = Yy, d.mixin = Tu, d.negate = gs, d.nthArg = Xy, d.omit = Jb, d.omitBy = kb, d.once = z0, d.orderBy = D0, d.over = qy, d.overArgs = X0, d.overEvery = Vy, d.overSome = Jy, d.partial = _u, d.partialRight = rh, d.partition = R0, d.pick = Zb, d.pickBy = dh, d.property = yh, d.propertyOf = ky, d.pull = Dm, d.pullAll = Xc, d.pullAllBy = Rm, d.pullAllWith = Pm, d.pullAt = Fm, d.range = Zy, d.rangeRight = Qy, d.rearg = q0, d.reject = M0, d.remove = Mm, d.rest = V0, d.reverse = pu, d.sampleSize = L0, d.set = jb, d.setWith = ey, d.shuffle = B0, d.slice = Nm, d.sortBy = H0, d.sortedUniq = Km, d.sortedUniqBy = Gm, d.split = xy, d.spread = J0, d.tail = Ym, d.take = zm, d.takeRight = Xm, d.takeRightWhile = qm, d.takeWhile = Vm, d.tap = f0, d.throttle = k0, d.thru = cs, d.toArray = uh, d.toPairs = ph, d.toPairsIn = gh, d.toPath = rw, d.toPlainObject = ah, d.transform = ty, d.unary = Z0, d.union = Jm, d.unionBy = km, d.unionWith = Zm, d.uniq = Qm, d.uniqBy = jm, d.uniqWith = e0, d.unset = ny, d.unzip = gu, d.unzipWith = qc, d.update = ry, d.updateWith = iy, d.values = mi, d.valuesIn = oy, d.without = t0, d.words = mh, d.wrap = Q0, d.xor = n0, d.xorBy = r0, d.xorWith = i0, d.zip = o0, d.zipObject = s0, d.zipObjectDeep = l0, d.zipWith = u0, d.entries = ph, d.entriesIn = gh, d.extend = ch, d.extendWith = ms, Tu(d, d), d.add = ow, d.attempt = bh, d.camelCase = fy, d.capitalize = vh, d.ceil = sw, d.clamp = sy, d.clone = eb, d.cloneDeep = nb, d.cloneDeepWith = rb, d.cloneWith = tb, d.conformsTo = ib, d.deburr = _h, d.defaultTo = Wy, d.divide = lw, d.endsWith = ay, d.eq = Tn, d.escape = cy, d.escapeRegExp = hy, d.every = b0, d.find = w0, d.findIndex = Kc, d.findKey = Mb, d.findLast = E0, d.findLastIndex = Gc, d.findLastKey = Nb, d.floor = uw, d.forEach = Jc, d.forEachRight = kc, d.forIn = Lb, d.forInRight = Bb, d.forOwn = Wb, d.forOwnRight = Ub, d.get = yu, d.gt = ob, d.gte = sb, d.has = Kb, d.hasIn = wu, d.head = zc, d.identity = Jt, d.includes = C0, d.indexOf = Em, d.inRange = ly, d.invoke = zb, d.isArguments = Gr, d.isArray = ge, d.isArrayBuffer = lb, d.isArrayLike = qt, d.isArrayLikeObject = tt, d.isBoolean = ub, d.isBuffer = yr, d.isDate = fb, d.isElement = ab, d.isEmpty = cb, d.isEqual = hb, d.isEqualWith = db, d.isError = mu, d.isFinite = pb, d.isFunction = Qn, d.isInteger = ih, d.isLength = vs, d.isMap = oh, d.isMatch = gb, d.isMatchWith = vb, d.isNaN = _b, d.isNative = mb, d.isNil = yb, d.isNull = bb, d.isNumber = sh, d.isObject = Ze, d.isObjectLike = je, d.isPlainObject = Zi, d.isRegExp = bu, d.isSafeInteger = wb, d.isSet = lh, d.isString = _s, d.isSymbol = nn, d.isTypedArray = _i, d.isUndefined = Eb, d.isWeakMap = xb, d.isWeakSet = Sb, d.join = Cm, d.kebabCase = dy, d.last = mn, d.lastIndexOf = Om, d.lowerCase = py, d.lowerFirst = gy, d.lt = Tb, d.lte = Ab, d.max = fw, d.maxBy = aw, d.mean = cw, d.meanBy = hw, d.min = dw, d.minBy = pw, d.stubArray = Cu, d.stubFalse = Ou, d.stubObject = jy, d.stubString = ew, d.stubTrue = tw, d.multiply = gw, d.nth = Im, d.noConflict = zy, d.noop = Au, d.now = ds, d.pad = vy, d.padEnd = _y, d.padStart = my, d.parseInt = by, d.random = uy, d.reduce = P0, d.reduceRight = F0, d.repeat = yy, d.replace = wy, d.result = Qb, d.round = vw, d.runInContext = x, d.sample = N0, d.size = W0, d.snakeCase = Ey, d.some = U0, d.sortedIndex = Lm, d.sortedIndexBy = Bm, d.sortedIndexOf = Wm, d.sortedLastIndex = Um, d.sortedLastIndexBy = Hm, d.sortedLastIndexOf = $m, d.startCase = Sy, d.startsWith = Ty, d.subtract = _w, d.sum = mw, d.sumBy = bw, d.template = Ay, d.times = nw, d.toFinite = jn, d.toInteger = be, d.toLength = fh, d.toLower = Cy, d.toNumber = bn, d.toSafeInteger = Cb, d.toString = Le, d.toUpper = Oy, d.trim = Iy, d.trimEnd = Dy, d.trimStart = Ry, d.truncate = Py, d.unescape = Fy, d.uniqueId = iw, d.upperCase = My, d.upperFirst = Eu, d.each = Jc, d.eachRight = kc, d.first = zc, Tu(d, function() {
        var e = {};
        return Pn(d, function(r, s) {
          Ue.call(d.prototype, s) || (e[s] = r);
        }), e;
      }(), { chain: !1 }), d.VERSION = o, dn(["bind", "bindKey", "curry", "curryRight", "partial", "partialRight"], function(e) {
        d[e].placeholder = d;
      }), dn(["drop", "take"], function(e, r) {
        Oe.prototype[e] = function(s) {
          s = s === i ? 1 : dt(be(s), 0);
          var f = this.__filtered__ && !r ? new Oe(this) : this.clone();
          return f.__filtered__ ? f.__takeCount__ = Ot(s, f.__takeCount__) : f.__views__.push({
            size: Ot(s, qe),
            type: e + (f.__dir__ < 0 ? "Right" : "")
          }), f;
        }, Oe.prototype[e + "Right"] = function(s) {
          return this.reverse()[e](s).reverse();
        };
      }), dn(["filter", "map", "takeWhile"], function(e, r) {
        var s = r + 1, f = s == pt || s == me;
        Oe.prototype[e] = function(c) {
          var p = this.clone();
          return p.__iteratees__.push({
            iteratee: re(c, 3),
            type: s
          }), p.__filtered__ = p.__filtered__ || f, p;
        };
      }), dn(["head", "last"], function(e, r) {
        var s = "take" + (r ? "Right" : "");
        Oe.prototype[e] = function() {
          return this[s](1).value()[0];
        };
      }), dn(["initial", "tail"], function(e, r) {
        var s = "drop" + (r ? "" : "Right");
        Oe.prototype[e] = function() {
          return this.__filtered__ ? new Oe(this) : this[s](1);
        };
      }), Oe.prototype.compact = function() {
        return this.filter(Jt);
      }, Oe.prototype.find = function(e) {
        return this.filter(e).head();
      }, Oe.prototype.findLast = function(e) {
        return this.reverse().find(e);
      }, Oe.prototype.invokeMap = xe(function(e, r) {
        return typeof e == "function" ? new Oe(this) : this.map(function(s) {
          return zi(s, e, r);
        });
      }), Oe.prototype.reject = function(e) {
        return this.filter(gs(re(e)));
      }, Oe.prototype.slice = function(e, r) {
        e = be(e);
        var s = this;
        return s.__filtered__ && (e > 0 || r < 0) ? new Oe(s) : (e < 0 ? s = s.takeRight(-e) : e && (s = s.drop(e)), r !== i && (r = be(r), s = r < 0 ? s.dropRight(-r) : s.take(r - e)), s);
      }, Oe.prototype.takeRightWhile = function(e) {
        return this.reverse().takeWhile(e).reverse();
      }, Oe.prototype.toArray = function() {
        return this.take(qe);
      }, Pn(Oe.prototype, function(e, r) {
        var s = /^(?:filter|find|map|reject)|While$/.test(r), f = /^(?:head|last)$/.test(r), c = d[f ? "take" + (r == "last" ? "Right" : "") : r], p = f || /^find/.test(r);
        c && (d.prototype[r] = function() {
          var b = this.__wrapped__, w = f ? [1] : arguments, T = b instanceof Oe, F = w[0], M = T || ge(b), W = function(Ae) {
            var De = c.apply(d, dr([Ae], w));
            return f && V ? De[0] : De;
          };
          M && s && typeof F == "function" && F.length != 1 && (T = M = !1);
          var V = this.__chain__, ee = !!this.__actions__.length, oe = p && !V, we = T && !ee;
          if (!p && M) {
            b = we ? b : new Oe(this);
            var se = e.apply(b, w);
            return se.__actions__.push({ func: cs, args: [W], thisArg: i }), new gn(se, V);
          }
          return oe && we ? e.apply(this, w) : (se = this.thru(W), oe ? f ? se.value()[0] : se.value() : se);
        });
      }), dn(["pop", "push", "shift", "sort", "splice", "unshift"], function(e) {
        var r = Bo[e], s = /^(?:push|sort|unshift)$/.test(e) ? "tap" : "thru", f = /^(?:pop|shift)$/.test(e);
        d.prototype[e] = function() {
          var c = arguments;
          if (f && !this.__chain__) {
            var p = this.value();
            return r.apply(ge(p) ? p : [], c);
          }
          return this[s](function(b) {
            return r.apply(ge(b) ? b : [], c);
          });
        };
      }), Pn(Oe.prototype, function(e, r) {
        var s = d[r];
        if (s) {
          var f = s.name + "";
          Ue.call(hi, f) || (hi[f] = []), hi[f].push({ name: r, func: s });
        }
      }), hi[is(i, B).name] = [{
        name: "wrapper",
        func: i
      }], Oe.prototype.clone = Fv, Oe.prototype.reverse = Mv, Oe.prototype.value = Nv, d.prototype.at = a0, d.prototype.chain = c0, d.prototype.commit = h0, d.prototype.next = d0, d.prototype.plant = g0, d.prototype.reverse = v0, d.prototype.toJSON = d.prototype.valueOf = d.prototype.value = _0, d.prototype.first = d.prototype.head, Wi && (d.prototype[Wi] = p0), d;
    }, fi = hv();
    Nr ? ((Nr.exports = fi)._ = fi, Sl._ = fi) : xt._ = fi;
  }).call(eo);
})(Qs, Qs.exports);
var Cs = Qs.exports;
function go(t) {
  const i = document.getSelection().getRangeAt(0), o = i.cloneRange();
  return o.selectNodeContents(t), o.setEnd(i.endContainer, i.endOffset), o.toString().length;
}
function nd(t, n) {
  const i = Sx(t, n), o = document.getSelection();
  o.removeAllRanges(), o.addRange(i);
}
function rd(t) {
  const n = document.createRange(), i = document.getSelection();
  n.setStart(t, t.childNodes.length), n.collapse(!0), i.removeAllRanges(), i.addRange(n);
}
const Sx = (t, n) => {
  var u;
  const i = document.createRange();
  i.selectNode(t), i.setStart(t, 0);
  let o = 0;
  const l = [t];
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
  return i.setStart(t, t.childNodes.length), i.setEnd(t, t.childNodes.length), i;
};
function Cp(t, n) {
  let i = [];
  Mt([t, ...n], () => {
    t.value && (i = [...t.value.querySelectorAll("[contenteditable]")]);
  }, {
    flush: "post"
  });
  const o = (S) => i.findIndex((C) => C.isEqualNode(S)), l = (S) => i[o(S) - 1], u = (S) => i[o(S) + 1], a = (S, C) => {
    if (S) {
      const I = u(S);
      if (I instanceof HTMLElement)
        return I.focus(), C && rd(I), I;
    } else {
      const I = i[0];
      I instanceof HTMLElement && I.focus();
    }
  }, h = (S, C) => {
    if (S.target instanceof HTMLElement) {
      const I = S.target, P = go(I), B = C === "up" ? l(I) : u(I);
      B instanceof HTMLElement && (S.preventDefault(), B.focus(), nd(B, P));
    }
  };
  return {
    getPreviousElementSibling: l,
    getNextElementSibling: u,
    getCurrentPositionInNavigationList: o,
    focusNextTask: a,
    focusTaskById: (S) => {
      const C = i.find((I) => {
        var P;
        return I instanceof HTMLElement && ((P = I.dataset) == null ? void 0 : P.id) == S;
      });
      C instanceof HTMLElement && C.focus();
    },
    onUp: (S) => {
      h(S, "up");
    },
    onDown: (S) => {
      h(S, "down");
    },
    onLeft: (S, C) => {
      if (S.target instanceof HTMLElement) {
        const I = S.target;
        if (go(I) === 0) {
          const P = l(I);
          P instanceof HTMLElement && (S.preventDefault(), C && (P.innerText += C), P.focus(), rd(P), C && nd(P, P.innerText.length - C.length));
        }
      }
    },
    onRight: (S) => {
      if (S.target instanceof HTMLElement) {
        const C = S.target;
        if (go(C) === C.innerText.length) {
          const I = u(C);
          I instanceof HTMLElement && (S.preventDefault(), I.focus());
        }
      }
    }
  };
}
const Tx = { class: "tw-group tw-flex tw-relative tw-gap-2 tw-py-4 tw-px-6 -tw-mx-6" }, Ax = {
  key: 0,
  class: "sortable-handle tw-cursor-grabbing tw-absolute -tw-translate-x-4 tw-hidden group-hover:tw-block"
}, Cx = {
  class: "tw-w-4 tw-h-4 tw-flex-none",
  onClick: () => {
  }
}, Ox = { class: "tw-flex-auto" }, Ix = ["contenteditable", "placeholder", "data-id", "textContent"], Dx = { key: 1 }, Rx = /* @__PURE__ */ Lf({
  __name: "TaskListItem2",
  props: {
    task: {},
    sortable: { type: Boolean },
    editable: { type: Boolean },
    placeholder: {},
    addableProps: {}
  },
  emits: ["delete", "update", "insert", "blur"],
  setup(t, { emit: n }) {
    const i = t, o = n, l = _t(!1);
    let u = {
      name: i.task.name,
      due_date: i.task.due_date,
      due_datetime: i.task.due_datetime,
      priority: i.task.priority,
      location_id: i.task.location_id
    };
    const a = _t({ ...u });
    let h;
    Mt([() => i.task, () => i.addableProps], async ([P]) => {
      h && h(), u = Cs.pick(P, Object.keys(u)), a.value = { ...u }, h = Mt(a, async () => {
        const B = Cs.reduce(a.value, (z, X, L) => Cs.isEqual(X, u[L]) ? z : [...z, L], []);
        B.length && o("update", i.task, Cs.pick(a.value, B));
      }, {
        deep: !0
      });
    }, {
      immediate: !0
    });
    const g = async (P) => {
      P.target instanceof HTMLElement && (P.target.innerText = P.target.innerText.trim(), a.value.name = P.target.innerText), o("blur", i.task, a.value);
    }, v = (P) => {
      var z;
      P.preventDefault();
      const B = (z = P.clipboardData) == null ? void 0 : z.getData("text/plain");
      B && document.execCommand("insertText", !1, B);
    }, _ = (P) => {
      P.target instanceof HTMLElement && go(P.target) === 0 && (C(P, P.target.innerText.trim()), o("delete", i.task));
    }, m = async (P) => {
      if (P.preventDefault(), P.target instanceof HTMLElement) {
        const B = P.target, z = B.innerText.trim(), X = go(B), L = {};
        X !== 0 && (L.currentName = z.substring(0, X).trim(), L.newName = z.substring(X, z.length).trim(), B.innerText = L.currentName), o("insert", i.task, L);
      }
    }, {
      onUp: A,
      onDown: S,
      onLeft: C,
      onRight: I
    } = ir("listNavigation");
    return (P, B) => {
      var z;
      return Hn(), Zr("div", Tx, [
        i.sortable ? (Hn(), Zr("div", Ax, B[6] || (B[6] = [
          Bn("i", { class: "fas fa-grip-vertical" }, null, -1)
        ]))) : Wh("", !0),
        Bn("div", Cx, [
          Bn("div", {
            class: ll(["tw-w-4 tw-h-4 tw-border tw-border-solid tw-border-gray-300 tw-rounded-full tw-cursor-pointer", !1])
          })
        ]),
        Bn("div", Ox, [
          Bn("div", {
            contenteditable: i.editable || void 0,
            placeholder: i.placeholder,
            "data-id": i.task.id,
            onKeydown: [
              yi(m, ["enter"]),
              B[0] || (B[0] = yi(
                //@ts-ignore
                (...X) => rt(C) && rt(C)(...X),
                ["left"]
              )),
              B[1] || (B[1] = yi(
                //@ts-ignore
                (...X) => rt(A) && rt(A)(...X),
                ["up"]
              )),
              B[2] || (B[2] = yi(
                //@ts-ignore
                (...X) => rt(I) && rt(I)(...X),
                ["right"]
              )),
              B[3] || (B[3] = yi(
                //@ts-ignore
                (...X) => rt(S) && rt(S)(...X),
                ["down"]
              )),
              yi(_, ["delete"])
            ],
            onBlur: g,
            onFocus: B[4] || (B[4] = (X) => l.value = !0),
            onFocusout: B[5] || (B[5] = (X) => l.value = !1),
            onPaste: v,
            textContent: ju(i.task.name)
          }, null, 40, Ix)
        ]),
        (z = i.task.extended_data) != null && z.comments_count ? (Hn(), Zr("div", Dx, [
          B[7] || (B[7] = Bn("i", { class: "far fa-comment" }, null, -1)),
          wp(" " + ju(i.task.extended_data.comments_count), 1)
        ])) : Wh("", !0)
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
function id(t, n) {
  var i = Object.keys(t);
  if (Object.getOwnPropertySymbols) {
    var o = Object.getOwnPropertySymbols(t);
    n && (o = o.filter(function(l) {
      return Object.getOwnPropertyDescriptor(t, l).enumerable;
    })), i.push.apply(i, o);
  }
  return i;
}
function Gn(t) {
  for (var n = 1; n < arguments.length; n++) {
    var i = arguments[n] != null ? arguments[n] : {};
    n % 2 ? id(Object(i), !0).forEach(function(o) {
      Px(t, o, i[o]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(i)) : id(Object(i)).forEach(function(o) {
      Object.defineProperty(t, o, Object.getOwnPropertyDescriptor(i, o));
    });
  }
  return t;
}
function Hs(t) {
  "@babel/helpers - typeof";
  return typeof Symbol == "function" && typeof Symbol.iterator == "symbol" ? Hs = function(n) {
    return typeof n;
  } : Hs = function(n) {
    return n && typeof Symbol == "function" && n.constructor === Symbol && n !== Symbol.prototype ? "symbol" : typeof n;
  }, Hs(t);
}
function Px(t, n, i) {
  return n in t ? Object.defineProperty(t, n, {
    value: i,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : t[n] = i, t;
}
function Dn() {
  return Dn = Object.assign || function(t) {
    for (var n = 1; n < arguments.length; n++) {
      var i = arguments[n];
      for (var o in i)
        Object.prototype.hasOwnProperty.call(i, o) && (t[o] = i[o]);
    }
    return t;
  }, Dn.apply(this, arguments);
}
function Fx(t, n) {
  if (t == null)
    return {};
  var i = {}, o = Object.keys(t), l, u;
  for (u = 0; u < o.length; u++)
    l = o[u], !(n.indexOf(l) >= 0) && (i[l] = t[l]);
  return i;
}
function Mx(t, n) {
  if (t == null)
    return {};
  var i = Fx(t, n), o, l;
  if (Object.getOwnPropertySymbols) {
    var u = Object.getOwnPropertySymbols(t);
    for (l = 0; l < u.length; l++)
      o = u[l], !(n.indexOf(o) >= 0) && Object.prototype.propertyIsEnumerable.call(t, o) && (i[o] = t[o]);
  }
  return i;
}
function Nx(t) {
  return Lx(t) || Bx(t) || Wx(t) || Ux();
}
function Lx(t) {
  if (Array.isArray(t))
    return pf(t);
}
function Bx(t) {
  if (typeof Symbol < "u" && t[Symbol.iterator] != null || t["@@iterator"] != null)
    return Array.from(t);
}
function Wx(t, n) {
  if (t) {
    if (typeof t == "string")
      return pf(t, n);
    var i = Object.prototype.toString.call(t).slice(8, -1);
    if (i === "Object" && t.constructor && (i = t.constructor.name), i === "Map" || i === "Set")
      return Array.from(t);
    if (i === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i))
      return pf(t, n);
  }
}
function pf(t, n) {
  (n == null || n > t.length) && (n = t.length);
  for (var i = 0, o = new Array(n); i < n; i++)
    o[i] = t[i];
  return o;
}
function Ux() {
  throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`);
}
var Hx = "1.15.2";
function or(t) {
  if (typeof window < "u" && window.navigator)
    return !!/* @__PURE__ */ navigator.userAgent.match(t);
}
var lr = or(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i), Oo = or(/Edge/i), od = or(/firefox/i), vo = or(/safari/i) && !or(/chrome/i) && !or(/android/i), Op = or(/iP(ad|od|hone)/i), Ip = or(/chrome/i) && or(/android/i), Dp = {
  capture: !1,
  passive: !1
};
function Ie(t, n, i) {
  t.addEventListener(n, i, !lr && Dp);
}
function Te(t, n, i) {
  t.removeEventListener(n, i, !lr && Dp);
}
function js(t, n) {
  if (n) {
    if (n[0] === ">" && (n = n.substring(1)), t)
      try {
        if (t.matches)
          return t.matches(n);
        if (t.msMatchesSelector)
          return t.msMatchesSelector(n);
        if (t.webkitMatchesSelector)
          return t.webkitMatchesSelector(n);
      } catch {
        return !1;
      }
    return !1;
  }
}
function $x(t) {
  return t.host && t !== document && t.host.nodeType ? t.host : t.parentNode;
}
function yn(t, n, i, o) {
  if (t) {
    i = i || document;
    do {
      if (n != null && (n[0] === ">" ? t.parentNode === i && js(t, n) : js(t, n)) || o && t === i)
        return t;
      if (t === i)
        break;
    } while (t = $x(t));
  }
  return null;
}
var sd = /\s+/g;
function ft(t, n, i) {
  if (t && n)
    if (t.classList)
      t.classList[i ? "add" : "remove"](n);
    else {
      var o = (" " + t.className + " ").replace(sd, " ").replace(" " + n + " ", " ");
      t.className = (o + (i ? " " + n : "")).replace(sd, " ");
    }
}
function j(t, n, i) {
  var o = t && t.style;
  if (o) {
    if (i === void 0)
      return document.defaultView && document.defaultView.getComputedStyle ? i = document.defaultView.getComputedStyle(t, "") : t.currentStyle && (i = t.currentStyle), n === void 0 ? i : i[n];
    !(n in o) && n.indexOf("webkit") === -1 && (n = "-webkit-" + n), o[n] = i + (typeof i == "string" ? "" : "px");
  }
}
function jr(t, n) {
  var i = "";
  if (typeof t == "string")
    i = t;
  else
    do {
      var o = j(t, "transform");
      o && o !== "none" && (i = o + " " + i);
    } while (!n && (t = t.parentNode));
  var l = window.DOMMatrix || window.WebKitCSSMatrix || window.CSSMatrix || window.MSCSSMatrix;
  return l && new l(i);
}
function Rp(t, n, i) {
  if (t) {
    var o = t.getElementsByTagName(n), l = 0, u = o.length;
    if (i)
      for (; l < u; l++)
        i(o[l], l);
    return o;
  }
  return [];
}
function $n() {
  var t = document.scrollingElement;
  return t || document.documentElement;
}
function Xe(t, n, i, o, l) {
  if (!(!t.getBoundingClientRect && t !== window)) {
    var u, a, h, g, v, _, m;
    if (t !== window && t.parentNode && t !== $n() ? (u = t.getBoundingClientRect(), a = u.top, h = u.left, g = u.bottom, v = u.right, _ = u.height, m = u.width) : (a = 0, h = 0, g = window.innerHeight, v = window.innerWidth, _ = window.innerHeight, m = window.innerWidth), (n || i) && t !== window && (l = l || t.parentNode, !lr))
      do
        if (l && l.getBoundingClientRect && (j(l, "transform") !== "none" || i && j(l, "position") !== "static")) {
          var A = l.getBoundingClientRect();
          a -= A.top + parseInt(j(l, "border-top-width")), h -= A.left + parseInt(j(l, "border-left-width")), g = a + u.height, v = h + u.width;
          break;
        }
      while (l = l.parentNode);
    if (o && t !== window) {
      var S = jr(l || t), C = S && S.a, I = S && S.d;
      S && (a /= I, h /= C, m /= C, _ /= I, g = a + _, v = h + m);
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
function ld(t, n, i) {
  for (var o = Cr(t, !0), l = Xe(t)[n]; o; ) {
    var u = Xe(o)[i], a = void 0;
    if (i === "top" || i === "left" ? a = l >= u : a = l <= u, !a)
      return o;
    if (o === $n())
      break;
    o = Cr(o, !1);
  }
  return !1;
}
function Li(t, n, i, o) {
  for (var l = 0, u = 0, a = t.children; u < a.length; ) {
    if (a[u].style.display !== "none" && a[u] !== le.ghost && (o || a[u] !== le.dragged) && yn(a[u], i.draggable, t, !1)) {
      if (l === n)
        return a[u];
      l++;
    }
    u++;
  }
  return null;
}
function Gf(t, n) {
  for (var i = t.lastElementChild; i && (i === le.ghost || j(i, "display") === "none" || n && !js(i, n)); )
    i = i.previousElementSibling;
  return i || null;
}
function at(t, n) {
  var i = 0;
  if (!t || !t.parentNode)
    return -1;
  for (; t = t.previousElementSibling; )
    t.nodeName.toUpperCase() !== "TEMPLATE" && t !== le.clone && (!n || js(t, n)) && i++;
  return i;
}
function ud(t) {
  var n = 0, i = 0, o = $n();
  if (t)
    do {
      var l = jr(t), u = l.a, a = l.d;
      n += t.scrollLeft * u, i += t.scrollTop * a;
    } while (t !== o && (t = t.parentNode));
  return [n, i];
}
function Kx(t, n) {
  for (var i in t)
    if (t.hasOwnProperty(i)) {
      for (var o in n)
        if (n.hasOwnProperty(o) && n[o] === t[i][o])
          return Number(i);
    }
  return -1;
}
function Cr(t, n) {
  if (!t || !t.getBoundingClientRect)
    return $n();
  var i = t, o = !1;
  do
    if (i.clientWidth < i.scrollWidth || i.clientHeight < i.scrollHeight) {
      var l = j(i);
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
function Gx(t, n) {
  if (t && n)
    for (var i in n)
      n.hasOwnProperty(i) && (t[i] = n[i]);
  return t;
}
function Gu(t, n) {
  return Math.round(t.top) === Math.round(n.top) && Math.round(t.left) === Math.round(n.left) && Math.round(t.height) === Math.round(n.height) && Math.round(t.width) === Math.round(n.width);
}
var _o;
function Pp(t, n) {
  return function() {
    if (!_o) {
      var i = arguments, o = this;
      i.length === 1 ? t.call(o, i[0]) : t.apply(o, i), _o = setTimeout(function() {
        _o = void 0;
      }, n);
    }
  };
}
function Yx() {
  clearTimeout(_o), _o = void 0;
}
function Fp(t, n, i) {
  t.scrollLeft += n, t.scrollTop += i;
}
function Yf(t) {
  var n = window.Polymer, i = window.jQuery || window.Zepto;
  return n && n.dom ? n.dom(t).cloneNode(!0) : i ? i(t).clone(!0)[0] : t.cloneNode(!0);
}
function fd(t, n) {
  j(t, "position", "absolute"), j(t, "top", n.top), j(t, "left", n.left), j(t, "width", n.width), j(t, "height", n.height);
}
function Yu(t) {
  j(t, "position", ""), j(t, "top", ""), j(t, "left", ""), j(t, "width", ""), j(t, "height", "");
}
function Mp(t, n, i) {
  var o = {};
  return Array.from(t.children).forEach(function(l) {
    var u, a, h, g;
    if (!(!yn(l, n.draggable, t, !1) || l.animated || l === i)) {
      var v = Xe(l);
      o.left = Math.min((u = o.left) !== null && u !== void 0 ? u : 1 / 0, v.left), o.top = Math.min((a = o.top) !== null && a !== void 0 ? a : 1 / 0, v.top), o.right = Math.max((h = o.right) !== null && h !== void 0 ? h : -1 / 0, v.right), o.bottom = Math.max((g = o.bottom) !== null && g !== void 0 ? g : -1 / 0, v.bottom);
    }
  }), o.width = o.right - o.left, o.height = o.bottom - o.top, o.x = o.left, o.y = o.top, o;
}
var Ft = "Sortable" + (/* @__PURE__ */ new Date()).getTime();
function zx() {
  var t = [], n;
  return {
    captureAnimationState: function() {
      if (t = [], !!this.options.animation) {
        var o = [].slice.call(this.el.children);
        o.forEach(function(l) {
          if (!(j(l, "display") === "none" || l === le.ghost)) {
            t.push({
              target: l,
              rect: Xe(l)
            });
            var u = Gn({}, t[t.length - 1].rect);
            if (l.thisAnimationDuration) {
              var a = jr(l, !0);
              a && (u.top -= a.f, u.left -= a.e);
            }
            l.fromRect = u;
          }
        });
      }
    },
    addAnimationState: function(o) {
      t.push(o);
    },
    removeAnimationState: function(o) {
      t.splice(Kx(t, {
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
      t.forEach(function(h) {
        var g = 0, v = h.target, _ = v.fromRect, m = Xe(v), A = v.prevFromRect, S = v.prevToRect, C = h.rect, I = jr(v, !0);
        I && (m.top -= I.f, m.left -= I.e), v.toRect = m, v.thisAnimationDuration && Gu(A, m) && !Gu(_, m) && // Make sure animatingRect is on line between toRect & fromRect
        (C.top - m.top) / (C.left - m.left) === (_.top - m.top) / (_.left - m.left) && (g = qx(C, A, S, l.options)), Gu(m, _) || (v.prevFromRect = _, v.prevToRect = m, g || (g = l.options.animation), l.animate(v, C, m, g)), g && (u = !0, a = Math.max(a, g), clearTimeout(v.animationResetTimer), v.animationResetTimer = setTimeout(function() {
          v.animationTime = 0, v.prevFromRect = null, v.fromRect = null, v.prevToRect = null, v.thisAnimationDuration = null;
        }, g), v.thisAnimationDuration = g);
      }), clearTimeout(n), u ? n = setTimeout(function() {
        typeof o == "function" && o();
      }, a) : typeof o == "function" && o(), t = [];
    },
    animate: function(o, l, u, a) {
      if (a) {
        j(o, "transition", ""), j(o, "transform", "");
        var h = jr(this.el), g = h && h.a, v = h && h.d, _ = (l.left - u.left) / (g || 1), m = (l.top - u.top) / (v || 1);
        o.animatingX = !!_, o.animatingY = !!m, j(o, "transform", "translate3d(" + _ + "px," + m + "px,0)"), this.forRepaintDummy = Xx(o), j(o, "transition", "transform " + a + "ms" + (this.options.easing ? " " + this.options.easing : "")), j(o, "transform", "translate3d(0,0,0)"), typeof o.animated == "number" && clearTimeout(o.animated), o.animated = setTimeout(function() {
          j(o, "transition", ""), j(o, "transform", ""), o.animated = !1, o.animatingX = !1, o.animatingY = !1;
        }, a);
      }
    }
  };
}
function Xx(t) {
  return t.offsetWidth;
}
function qx(t, n, i, o) {
  return Math.sqrt(Math.pow(n.top - t.top, 2) + Math.pow(n.left - t.left, 2)) / Math.sqrt(Math.pow(n.top - i.top, 2) + Math.pow(n.left - i.left, 2)) * o.animation;
}
var Ei = [], zu = {
  initializeByDefault: !0
}, Io = {
  mount: function(n) {
    for (var i in zu)
      zu.hasOwnProperty(i) && !(i in n) && (n[i] = zu[i]);
    Ei.forEach(function(o) {
      if (o.pluginName === n.pluginName)
        throw "Sortable: Cannot mount plugin ".concat(n.pluginName, " more than once");
    }), Ei.push(n);
  },
  pluginEvent: function(n, i, o) {
    var l = this;
    this.eventCanceled = !1, o.cancel = function() {
      l.eventCanceled = !0;
    };
    var u = n + "Global";
    Ei.forEach(function(a) {
      i[a.pluginName] && (i[a.pluginName][u] && i[a.pluginName][u](Gn({
        sortable: i
      }, o)), i.options[a.pluginName] && i[a.pluginName][n] && i[a.pluginName][n](Gn({
        sortable: i
      }, o)));
    });
  },
  initializePlugins: function(n, i, o, l) {
    Ei.forEach(function(h) {
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
    return Ei.forEach(function(l) {
      typeof l.eventProperties == "function" && Dn(o, l.eventProperties.call(i[l.pluginName], n));
    }), o;
  },
  modifyOption: function(n, i, o) {
    var l;
    return Ei.forEach(function(u) {
      n[u.pluginName] && u.optionListeners && typeof u.optionListeners[i] == "function" && (l = u.optionListeners[i].call(n[u.pluginName], o));
    }), l;
  }
};
function oo(t) {
  var n = t.sortable, i = t.rootEl, o = t.name, l = t.targetEl, u = t.cloneEl, a = t.toEl, h = t.fromEl, g = t.oldIndex, v = t.newIndex, _ = t.oldDraggableIndex, m = t.newDraggableIndex, A = t.originalEvent, S = t.putSortable, C = t.extraEventProperties;
  if (n = n || i && i[Ft], !!n) {
    var I, P = n.options, B = "on" + o.charAt(0).toUpperCase() + o.substr(1);
    window.CustomEvent && !lr && !Oo ? I = new CustomEvent(o, {
      bubbles: !0,
      cancelable: !0
    }) : (I = document.createEvent("Event"), I.initEvent(o, !0, !0)), I.to = a || i, I.from = h || i, I.item = l || i, I.clone = u, I.oldIndex = g, I.newIndex = v, I.oldDraggableIndex = _, I.newDraggableIndex = m, I.originalEvent = A, I.pullMode = S ? S.lastPutMode : void 0;
    var z = Gn(Gn({}, C), Io.getEventProperties(o, n));
    for (var X in z)
      I[X] = z[X];
    i && i.dispatchEvent(I), P[B] && P[B].call(n, I);
  }
}
var Vx = ["evt"], kt = function(n, i) {
  var o = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, l = o.evt, u = Mx(o, Vx);
  Io.pluginEvent.bind(le)(n, i, Gn({
    dragEl: Y,
    parentEl: et,
    ghostEl: pe,
    rootEl: Je,
    nextEl: Jr,
    lastDownEl: $s,
    cloneEl: Qe,
    cloneHidden: Ar,
    dragStarted: so,
    putSortable: Tt,
    activeSortable: le.active,
    originalEvent: l,
    oldIndex: Ci,
    oldDraggableIndex: mo,
    newIndex: un,
    newDraggableIndex: Sr,
    hideGhostForTarget: Wp,
    unhideGhostForTarget: Up,
    cloneNowHidden: function() {
      Ar = !0;
    },
    cloneNowShown: function() {
      Ar = !1;
    },
    dispatchSortableEvent: function(h) {
      $t({
        sortable: i,
        name: h,
        originalEvent: l
      });
    }
  }, u));
};
function $t(t) {
  oo(Gn({
    putSortable: Tt,
    cloneEl: Qe,
    targetEl: Y,
    rootEl: Je,
    oldIndex: Ci,
    oldDraggableIndex: mo,
    newIndex: un,
    newDraggableIndex: Sr
  }, t));
}
var Y, et, pe, Je, Jr, $s, Qe, Ar, Ci, un, mo, Sr, Os, Tt, Ai = !1, el = !1, tl = [], Xr, Cn, Xu, qu, ad, cd, so, xi, bo, yo = !1, Is = !1, Ks, Dt, Vu = [], gf = !1, nl = [], vl = typeof document < "u", Ds = Op, hd = Oo || lr ? "cssFloat" : "float", Jx = vl && !Ip && !Op && "draggable" in document.createElement("div"), Np = function() {
  if (vl) {
    if (lr)
      return !1;
    var t = document.createElement("x");
    return t.style.cssText = "pointer-events:auto", t.style.pointerEvents === "auto";
  }
}(), Lp = function(n, i) {
  var o = j(n), l = parseInt(o.width) - parseInt(o.paddingLeft) - parseInt(o.paddingRight) - parseInt(o.borderLeftWidth) - parseInt(o.borderRightWidth), u = Li(n, 0, i), a = Li(n, 1, i), h = u && j(u), g = a && j(a), v = h && parseInt(h.marginLeft) + parseInt(h.marginRight) + Xe(u).width, _ = g && parseInt(g.marginLeft) + parseInt(g.marginRight) + Xe(a).width;
  if (o.display === "flex")
    return o.flexDirection === "column" || o.flexDirection === "column-reverse" ? "vertical" : "horizontal";
  if (o.display === "grid")
    return o.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
  if (u && h.float && h.float !== "none") {
    var m = h.float === "left" ? "left" : "right";
    return a && (g.clear === "both" || g.clear === m) ? "vertical" : "horizontal";
  }
  return u && (h.display === "block" || h.display === "flex" || h.display === "table" || h.display === "grid" || v >= l && o[hd] === "none" || a && o[hd] === "none" && v + _ > l) ? "vertical" : "horizontal";
}, kx = function(n, i, o) {
  var l = o ? n.left : n.top, u = o ? n.right : n.bottom, a = o ? n.width : n.height, h = o ? i.left : i.top, g = o ? i.right : i.bottom, v = o ? i.width : i.height;
  return l === h || u === g || l + a / 2 === h + v / 2;
}, Zx = function(n, i) {
  var o;
  return tl.some(function(l) {
    var u = l[Ft].options.emptyInsertThreshold;
    if (!(!u || Gf(l))) {
      var a = Xe(l), h = n >= a.left - u && n <= a.right + u, g = i >= a.top - u && i <= a.bottom + u;
      if (h && g)
        return o = l;
    }
  }), o;
}, Bp = function(n) {
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
      var A = (a ? h : g).options.group.name;
      return u === !0 || typeof u == "string" && u === A || u.join && u.indexOf(A) > -1;
    };
  }
  var o = {}, l = n.group;
  (!l || Hs(l) != "object") && (l = {
    name: l
  }), o.name = l.name, o.checkPull = i(l.pull, !0), o.checkPut = i(l.put), o.revertClone = l.revertClone, n.group = o;
}, Wp = function() {
  !Np && pe && j(pe, "display", "none");
}, Up = function() {
  !Np && pe && j(pe, "display", "");
};
vl && !Ip && document.addEventListener("click", function(t) {
  if (el)
    return t.preventDefault(), t.stopPropagation && t.stopPropagation(), t.stopImmediatePropagation && t.stopImmediatePropagation(), el = !1, !1;
}, !0);
var qr = function(n) {
  if (Y) {
    n = n.touches ? n.touches[0] : n;
    var i = Zx(n.clientX, n.clientY);
    if (i) {
      var o = {};
      for (var l in n)
        n.hasOwnProperty(l) && (o[l] = n[l]);
      o.target = o.rootEl = i, o.preventDefault = void 0, o.stopPropagation = void 0, i[Ft]._onDragOver(o);
    }
  }
}, Qx = function(n) {
  Y && Y.parentNode[Ft]._isOutsideThisEl(n.target);
};
function le(t, n) {
  if (!(t && t.nodeType && t.nodeType === 1))
    throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(t));
  this.el = t, this.options = n = Dn({}, n), t[Ft] = this;
  var i = {
    group: null,
    sort: !0,
    disabled: !1,
    store: null,
    handle: null,
    draggable: /^[uo]l$/i.test(t.nodeName) ? ">li" : ">*",
    swapThreshold: 1,
    // percentage; 0 <= x <= 1
    invertSwap: !1,
    // invert always
    invertedSwapThreshold: null,
    // will be set to same as swapThreshold if default
    removeCloneOnHide: !0,
    direction: function() {
      return Lp(t, this.options);
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
    supportPointer: le.supportPointer !== !1 && "PointerEvent" in window && !vo,
    emptyInsertThreshold: 5
  };
  Io.initializePlugins(this, t, i);
  for (var o in i)
    !(o in n) && (n[o] = i[o]);
  Bp(n);
  for (var l in this)
    l.charAt(0) === "_" && typeof this[l] == "function" && (this[l] = this[l].bind(this));
  this.nativeDraggable = n.forceFallback ? !1 : Jx, this.nativeDraggable && (this.options.touchStartThreshold = 1), n.supportPointer ? Ie(t, "pointerdown", this._onTapStart) : (Ie(t, "mousedown", this._onTapStart), Ie(t, "touchstart", this._onTapStart)), this.nativeDraggable && (Ie(t, "dragover", this), Ie(t, "dragenter", this)), tl.push(this.el), n.store && n.store.get && this.sort(n.store.get(this) || []), Dn(this, zx());
}
le.prototype = /** @lends Sortable.prototype */
{
  constructor: le,
  _isOutsideThisEl: function(n) {
    !this.el.contains(n) && n !== this.el && (xi = null);
  },
  _getDirection: function(n, i) {
    return typeof this.options.direction == "function" ? this.options.direction.call(this, n, i, Y) : this.options.direction;
  },
  _onTapStart: function(n) {
    if (n.cancelable) {
      var i = this, o = this.el, l = this.options, u = l.preventOnFilter, a = n.type, h = n.touches && n.touches[0] || n.pointerType && n.pointerType === "touch" && n, g = (h || n).target, v = n.target.shadowRoot && (n.path && n.path[0] || n.composedPath && n.composedPath()[0]) || g, _ = l.filter;
      if (sS(o), !Y && !(/mousedown|pointerdown/.test(a) && n.button !== 0 || l.disabled) && !v.isContentEditable && !(!this.nativeDraggable && vo && g && g.tagName.toUpperCase() === "SELECT") && (g = yn(g, l.draggable, o, !1), !(g && g.animated) && $s !== g)) {
        if (Ci = at(g), mo = at(g, l.draggable), typeof _ == "function") {
          if (_.call(this, n, g, this)) {
            $t({
              sortable: i,
              rootEl: v,
              name: "filter",
              targetEl: g,
              toEl: o,
              fromEl: o
            }), kt("filter", i, {
              evt: n
            }), u && n.cancelable && n.preventDefault();
            return;
          }
        } else if (_ && (_ = _.split(",").some(function(m) {
          if (m = yn(v, m.trim(), o, !1), m)
            return $t({
              sortable: i,
              rootEl: m,
              name: "filter",
              targetEl: g,
              fromEl: o,
              toEl: o
            }), kt("filter", i, {
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
      var v = Xe(o);
      if (Je = u, Y = o, et = Y.parentNode, Jr = Y.nextSibling, $s = o, Os = a.group, le.dragged = Y, Xr = {
        target: Y,
        clientX: (i || n).clientX,
        clientY: (i || n).clientY
      }, ad = Xr.clientX - v.left, cd = Xr.clientY - v.top, this._lastX = (i || n).clientX, this._lastY = (i || n).clientY, Y.style["will-change"] = "all", g = function() {
        if (kt("delayEnded", l, {
          evt: n
        }), le.eventCanceled) {
          l._onDrop();
          return;
        }
        l._disableDelayedDragEvents(), !od && l.nativeDraggable && (Y.draggable = !0), l._triggerDragStart(n, i), $t({
          sortable: l,
          name: "choose",
          originalEvent: n
        }), ft(Y, a.chosenClass, !0);
      }, a.ignore.split(",").forEach(function(_) {
        Rp(Y, _.trim(), Ju);
      }), Ie(h, "dragover", qr), Ie(h, "mousemove", qr), Ie(h, "touchmove", qr), Ie(h, "mouseup", l._onDrop), Ie(h, "touchend", l._onDrop), Ie(h, "touchcancel", l._onDrop), od && this.nativeDraggable && (this.options.touchStartThreshold = 4, Y.draggable = !0), kt("delayStart", this, {
        evt: n
      }), a.delay && (!a.delayOnTouchOnly || i) && (!this.nativeDraggable || !(Oo || lr))) {
        if (le.eventCanceled) {
          this._onDrop();
          return;
        }
        Ie(h, "mouseup", l._disableDelayedDrag), Ie(h, "touchend", l._disableDelayedDrag), Ie(h, "touchcancel", l._disableDelayedDrag), Ie(h, "mousemove", l._delayedDragTouchMoveHandler), Ie(h, "touchmove", l._delayedDragTouchMoveHandler), a.supportPointer && Ie(h, "pointermove", l._delayedDragTouchMoveHandler), l._dragStartTimer = setTimeout(g, a.delay);
      } else
        g();
    }
  },
  _delayedDragTouchMoveHandler: function(n) {
    var i = n.touches ? n.touches[0] : n;
    Math.max(Math.abs(i.clientX - this._lastX), Math.abs(i.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1)) && this._disableDelayedDrag();
  },
  _disableDelayedDrag: function() {
    Y && Ju(Y), clearTimeout(this._dragStartTimer), this._disableDelayedDragEvents();
  },
  _disableDelayedDragEvents: function() {
    var n = this.el.ownerDocument;
    Te(n, "mouseup", this._disableDelayedDrag), Te(n, "touchend", this._disableDelayedDrag), Te(n, "touchcancel", this._disableDelayedDrag), Te(n, "mousemove", this._delayedDragTouchMoveHandler), Te(n, "touchmove", this._delayedDragTouchMoveHandler), Te(n, "pointermove", this._delayedDragTouchMoveHandler);
  },
  _triggerDragStart: function(n, i) {
    i = i || n.pointerType == "touch" && n, !this.nativeDraggable || i ? this.options.supportPointer ? Ie(document, "pointermove", this._onTouchMove) : i ? Ie(document, "touchmove", this._onTouchMove) : Ie(document, "mousemove", this._onTouchMove) : (Ie(Y, "dragend", this), Ie(Je, "dragstart", this._onDragStart));
    try {
      document.selection ? Gs(function() {
        document.selection.empty();
      }) : window.getSelection().removeAllRanges();
    } catch {
    }
  },
  _dragStarted: function(n, i) {
    if (Ai = !1, Je && Y) {
      kt("dragStarted", this, {
        evt: i
      }), this.nativeDraggable && Ie(document, "dragover", Qx);
      var o = this.options;
      !n && ft(Y, o.dragClass, !1), ft(Y, o.ghostClass, !0), le.active = this, n && this._appendGhost(), $t({
        sortable: this,
        name: "start",
        originalEvent: i
      });
    } else
      this._nulling();
  },
  _emulateDragOver: function() {
    if (Cn) {
      this._lastX = Cn.clientX, this._lastY = Cn.clientY, Wp();
      for (var n = document.elementFromPoint(Cn.clientX, Cn.clientY), i = n; n && n.shadowRoot && (n = n.shadowRoot.elementFromPoint(Cn.clientX, Cn.clientY), n !== i); )
        i = n;
      if (Y.parentNode[Ft]._isOutsideThisEl(n), i)
        do {
          if (i[Ft]) {
            var o = void 0;
            if (o = i[Ft]._onDragOver({
              clientX: Cn.clientX,
              clientY: Cn.clientY,
              target: n,
              rootEl: i
            }), o && !this.options.dragoverBubble)
              break;
          }
          n = i;
        } while (i = i.parentNode);
      Up();
    }
  },
  _onTouchMove: function(n) {
    if (Xr) {
      var i = this.options, o = i.fallbackTolerance, l = i.fallbackOffset, u = n.touches ? n.touches[0] : n, a = pe && jr(pe, !0), h = pe && a && a.a, g = pe && a && a.d, v = Ds && Dt && ud(Dt), _ = (u.clientX - Xr.clientX + l.x) / (h || 1) + (v ? v[0] - Vu[0] : 0) / (h || 1), m = (u.clientY - Xr.clientY + l.y) / (g || 1) + (v ? v[1] - Vu[1] : 0) / (g || 1);
      if (!le.active && !Ai) {
        if (o && Math.max(Math.abs(u.clientX - this._lastX), Math.abs(u.clientY - this._lastY)) < o)
          return;
        this._onDragStart(n, !0);
      }
      if (pe) {
        a ? (a.e += _ - (Xu || 0), a.f += m - (qu || 0)) : a = {
          a: 1,
          b: 0,
          c: 0,
          d: 1,
          e: _,
          f: m
        };
        var A = "matrix(".concat(a.a, ",").concat(a.b, ",").concat(a.c, ",").concat(a.d, ",").concat(a.e, ",").concat(a.f, ")");
        j(pe, "webkitTransform", A), j(pe, "mozTransform", A), j(pe, "msTransform", A), j(pe, "transform", A), Xu = _, qu = m, Cn = u;
      }
      n.cancelable && n.preventDefault();
    }
  },
  _appendGhost: function() {
    if (!pe) {
      var n = this.options.fallbackOnBody ? document.body : Je, i = Xe(Y, !0, Ds, !0, n), o = this.options;
      if (Ds) {
        for (Dt = n; j(Dt, "position") === "static" && j(Dt, "transform") === "none" && Dt !== document; )
          Dt = Dt.parentNode;
        Dt !== document.body && Dt !== document.documentElement ? (Dt === document && (Dt = $n()), i.top += Dt.scrollTop, i.left += Dt.scrollLeft) : Dt = $n(), Vu = ud(Dt);
      }
      pe = Y.cloneNode(!0), ft(pe, o.ghostClass, !1), ft(pe, o.fallbackClass, !0), ft(pe, o.dragClass, !0), j(pe, "transition", ""), j(pe, "transform", ""), j(pe, "box-sizing", "border-box"), j(pe, "margin", 0), j(pe, "top", i.top), j(pe, "left", i.left), j(pe, "width", i.width), j(pe, "height", i.height), j(pe, "opacity", "0.8"), j(pe, "position", Ds ? "absolute" : "fixed"), j(pe, "zIndex", "100000"), j(pe, "pointerEvents", "none"), le.ghost = pe, n.appendChild(pe), j(pe, "transform-origin", ad / parseInt(pe.style.width) * 100 + "% " + cd / parseInt(pe.style.height) * 100 + "%");
    }
  },
  _onDragStart: function(n, i) {
    var o = this, l = n.dataTransfer, u = o.options;
    if (kt("dragStart", this, {
      evt: n
    }), le.eventCanceled) {
      this._onDrop();
      return;
    }
    kt("setupClone", this), le.eventCanceled || (Qe = Yf(Y), Qe.removeAttribute("id"), Qe.draggable = !1, Qe.style["will-change"] = "", this._hideClone(), ft(Qe, this.options.chosenClass, !1), le.clone = Qe), o.cloneId = Gs(function() {
      kt("clone", o), !le.eventCanceled && (o.options.removeCloneOnHide || Je.insertBefore(Qe, Y), o._hideClone(), $t({
        sortable: o,
        name: "clone"
      }));
    }), !i && ft(Y, u.dragClass, !0), i ? (el = !0, o._loopId = setInterval(o._emulateDragOver, 50)) : (Te(document, "mouseup", o._onDrop), Te(document, "touchend", o._onDrop), Te(document, "touchcancel", o._onDrop), l && (l.effectAllowed = "move", u.setData && u.setData.call(o, l, Y)), Ie(document, "drop", o), j(Y, "transform", "translateZ(0)")), Ai = !0, o._dragStartId = Gs(o._dragStarted.bind(o, i, n)), Ie(document, "selectstart", o), so = !0, vo && j(document.body, "user-select", "none");
  },
  // Returns true - if no further action is needed (either inserted or another condition)
  _onDragOver: function(n) {
    var i = this.el, o = n.target, l, u, a, h = this.options, g = h.group, v = le.active, _ = Os === g, m = h.sort, A = Tt || v, S, C = this, I = !1;
    if (gf)
      return;
    function P(ie, mt) {
      kt(ie, C, Gn({
        evt: n,
        isOwner: _,
        axis: S ? "vertical" : "horizontal",
        revert: a,
        dragRect: l,
        targetRect: u,
        canSort: m,
        fromSortable: A,
        target: o,
        completed: z,
        onMove: function(qe, ot) {
          return Rs(Je, i, Y, l, qe, Xe(qe), n, ot);
        },
        changed: X
      }, mt));
    }
    function B() {
      P("dragOverAnimationCapture"), C.captureAnimationState(), C !== A && A.captureAnimationState();
    }
    function z(ie) {
      return P("dragOverCompleted", {
        insertion: ie
      }), ie && (_ ? v._hideClone() : v._showClone(C), C !== A && (ft(Y, Tt ? Tt.options.ghostClass : v.options.ghostClass, !1), ft(Y, h.ghostClass, !0)), Tt !== C && C !== le.active ? Tt = C : C === le.active && Tt && (Tt = null), A === C && (C._ignoreWhileAnimating = o), C.animateAll(function() {
        P("dragOverAnimationComplete"), C._ignoreWhileAnimating = null;
      }), C !== A && (A.animateAll(), A._ignoreWhileAnimating = null)), (o === Y && !Y.animated || o === i && !o.animated) && (xi = null), !h.dragoverBubble && !n.rootEl && o !== document && (Y.parentNode[Ft]._isOutsideThisEl(n.target), !ie && qr(n)), !h.dragoverBubble && n.stopPropagation && n.stopPropagation(), I = !0;
    }
    function X() {
      un = at(Y), Sr = at(Y, h.draggable), $t({
        sortable: C,
        name: "change",
        toEl: i,
        newIndex: un,
        newDraggableIndex: Sr,
        originalEvent: n
      });
    }
    if (n.preventDefault !== void 0 && n.cancelable && n.preventDefault(), o = yn(o, h.draggable, i, !0), P("dragOver"), le.eventCanceled)
      return I;
    if (Y.contains(n.target) || o.animated && o.animatingX && o.animatingY || C._ignoreWhileAnimating === o)
      return z(!1);
    if (el = !1, v && !h.disabled && (_ ? m || (a = et !== Je) : Tt === this || (this.lastPutMode = Os.checkPull(this, v, Y, n)) && g.checkPut(this, v, Y, n))) {
      if (S = this._getDirection(n, o) === "vertical", l = Xe(Y), P("dragOverValid"), le.eventCanceled)
        return I;
      if (a)
        return et = Je, B(), this._hideClone(), P("revert"), le.eventCanceled || (Jr ? Je.insertBefore(Y, Jr) : Je.appendChild(Y)), z(!0);
      var L = Gf(i, h.draggable);
      if (!L || nS(n, S, this) && !L.animated) {
        if (L === Y)
          return z(!1);
        if (L && i === n.target && (o = L), o && (u = Xe(o)), Rs(Je, i, Y, l, o, u, n, !!o) !== !1)
          return B(), L && L.nextSibling ? i.insertBefore(Y, L.nextSibling) : i.appendChild(Y), et = i, X(), z(!0);
      } else if (L && tS(n, S, this)) {
        var te = Li(i, 0, h, !0);
        if (te === Y)
          return z(!1);
        if (o = te, u = Xe(o), Rs(Je, i, Y, l, o, u, n, !1) !== !1)
          return B(), i.insertBefore(Y, te), et = i, X(), z(!0);
      } else if (o.parentNode === i) {
        u = Xe(o);
        var Ee = 0, Se, Re = Y.parentNode !== i, ue = !kx(Y.animated && Y.toRect || l, o.animated && o.toRect || u, S), Z = S ? "top" : "left", he = ld(o, "top", "top") || ld(Y, "top", "top"), Ce = he ? he.scrollTop : void 0;
        xi !== o && (Se = u[Z], yo = !1, Is = !ue && h.invertSwap || Re), Ee = rS(n, o, u, S, ue ? 1 : h.swapThreshold, h.invertedSwapThreshold == null ? h.swapThreshold : h.invertedSwapThreshold, Is, xi === o);
        var We;
        if (Ee !== 0) {
          var pt = at(Y);
          do
            pt -= Ee, We = et.children[pt];
          while (We && (j(We, "display") === "none" || We === pe));
        }
        if (Ee === 0 || We === o)
          return z(!1);
        xi = o, bo = Ee;
        var Ct = o.nextElementSibling, me = !1;
        me = Ee === 1;
        var fe = Rs(Je, i, Y, l, o, u, n, me);
        if (fe !== !1)
          return (fe === 1 || fe === -1) && (me = fe === 1), gf = !0, setTimeout(eS, 30), B(), me && !Ct ? i.appendChild(Y) : o.parentNode.insertBefore(Y, me ? Ct : o), he && Fp(he, 0, Ce - he.scrollTop), et = Y.parentNode, Se !== void 0 && !Is && (Ks = Math.abs(Se - Xe(o)[Z])), X(), z(!0);
      }
      if (i.contains(Y))
        return z(!1);
    }
    return !1;
  },
  _ignoreWhileAnimating: null,
  _offMoveEvents: function() {
    Te(document, "mousemove", this._onTouchMove), Te(document, "touchmove", this._onTouchMove), Te(document, "pointermove", this._onTouchMove), Te(document, "dragover", qr), Te(document, "mousemove", qr), Te(document, "touchmove", qr);
  },
  _offUpEvents: function() {
    var n = this.el.ownerDocument;
    Te(n, "mouseup", this._onDrop), Te(n, "touchend", this._onDrop), Te(n, "pointerup", this._onDrop), Te(n, "touchcancel", this._onDrop), Te(document, "selectstart", this);
  },
  _onDrop: function(n) {
    var i = this.el, o = this.options;
    if (un = at(Y), Sr = at(Y, o.draggable), kt("drop", this, {
      evt: n
    }), et = Y && Y.parentNode, un = at(Y), Sr = at(Y, o.draggable), le.eventCanceled) {
      this._nulling();
      return;
    }
    Ai = !1, Is = !1, yo = !1, clearInterval(this._loopId), clearTimeout(this._dragStartTimer), vf(this.cloneId), vf(this._dragStartId), this.nativeDraggable && (Te(document, "drop", this), Te(i, "dragstart", this._onDragStart)), this._offMoveEvents(), this._offUpEvents(), vo && j(document.body, "user-select", ""), j(Y, "transform", ""), n && (so && (n.cancelable && n.preventDefault(), !o.dropBubble && n.stopPropagation()), pe && pe.parentNode && pe.parentNode.removeChild(pe), (Je === et || Tt && Tt.lastPutMode !== "clone") && Qe && Qe.parentNode && Qe.parentNode.removeChild(Qe), Y && (this.nativeDraggable && Te(Y, "dragend", this), Ju(Y), Y.style["will-change"] = "", so && !Ai && ft(Y, Tt ? Tt.options.ghostClass : this.options.ghostClass, !1), ft(Y, this.options.chosenClass, !1), $t({
      sortable: this,
      name: "unchoose",
      toEl: et,
      newIndex: null,
      newDraggableIndex: null,
      originalEvent: n
    }), Je !== et ? (un >= 0 && ($t({
      rootEl: et,
      name: "add",
      toEl: et,
      fromEl: Je,
      originalEvent: n
    }), $t({
      sortable: this,
      name: "remove",
      toEl: et,
      originalEvent: n
    }), $t({
      rootEl: et,
      name: "sort",
      toEl: et,
      fromEl: Je,
      originalEvent: n
    }), $t({
      sortable: this,
      name: "sort",
      toEl: et,
      originalEvent: n
    })), Tt && Tt.save()) : un !== Ci && un >= 0 && ($t({
      sortable: this,
      name: "update",
      toEl: et,
      originalEvent: n
    }), $t({
      sortable: this,
      name: "sort",
      toEl: et,
      originalEvent: n
    })), le.active && ((un == null || un === -1) && (un = Ci, Sr = mo), $t({
      sortable: this,
      name: "end",
      toEl: et,
      originalEvent: n
    }), this.save()))), this._nulling();
  },
  _nulling: function() {
    kt("nulling", this), Je = Y = et = pe = Jr = Qe = $s = Ar = Xr = Cn = so = un = Sr = Ci = mo = xi = bo = Tt = Os = le.dragged = le.ghost = le.clone = le.active = null, nl.forEach(function(n) {
      n.checked = !0;
    }), nl.length = Xu = qu = 0;
  },
  handleEvent: function(n) {
    switch (n.type) {
      case "drop":
      case "dragend":
        this._onDrop(n);
        break;
      case "dragenter":
      case "dragover":
        Y && (this._onDragOver(n), jx(n));
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
      i = o[l], yn(i, a.draggable, this.el, !1) && n.push(i.getAttribute(a.dataIdAttr) || oS(i));
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
    typeof l < "u" ? o[n] = l : o[n] = i, n === "group" && Bp(o);
  },
  /**
   * Destroy
   */
  destroy: function() {
    kt("destroy", this);
    var n = this.el;
    n[Ft] = null, Te(n, "mousedown", this._onTapStart), Te(n, "touchstart", this._onTapStart), Te(n, "pointerdown", this._onTapStart), this.nativeDraggable && (Te(n, "dragover", this), Te(n, "dragenter", this)), Array.prototype.forEach.call(n.querySelectorAll("[draggable]"), function(i) {
      i.removeAttribute("draggable");
    }), this._onDrop(), this._disableDelayedDragEvents(), tl.splice(tl.indexOf(this.el), 1), this.el = n = null;
  },
  _hideClone: function() {
    if (!Ar) {
      if (kt("hideClone", this), le.eventCanceled)
        return;
      j(Qe, "display", "none"), this.options.removeCloneOnHide && Qe.parentNode && Qe.parentNode.removeChild(Qe), Ar = !0;
    }
  },
  _showClone: function(n) {
    if (n.lastPutMode !== "clone") {
      this._hideClone();
      return;
    }
    if (Ar) {
      if (kt("showClone", this), le.eventCanceled)
        return;
      Y.parentNode == Je && !this.options.group.revertClone ? Je.insertBefore(Qe, Y) : Jr ? Je.insertBefore(Qe, Jr) : Je.appendChild(Qe), this.options.group.revertClone && this.animate(Y, Qe), j(Qe, "display", ""), Ar = !1;
    }
  }
};
function jx(t) {
  t.dataTransfer && (t.dataTransfer.dropEffect = "move"), t.cancelable && t.preventDefault();
}
function Rs(t, n, i, o, l, u, a, h) {
  var g, v = t[Ft], _ = v.options.onMove, m;
  return window.CustomEvent && !lr && !Oo ? g = new CustomEvent("move", {
    bubbles: !0,
    cancelable: !0
  }) : (g = document.createEvent("Event"), g.initEvent("move", !0, !0)), g.to = n, g.from = t, g.dragged = i, g.draggedRect = o, g.related = l || n, g.relatedRect = u || Xe(n), g.willInsertAfter = h, g.originalEvent = a, t.dispatchEvent(g), _ && (m = _.call(v, g, a)), m;
}
function Ju(t) {
  t.draggable = !1;
}
function eS() {
  gf = !1;
}
function tS(t, n, i) {
  var o = Xe(Li(i.el, 0, i.options, !0)), l = Mp(i.el, i.options, pe), u = 10;
  return n ? t.clientX < l.left - u || t.clientY < o.top && t.clientX < o.right : t.clientY < l.top - u || t.clientY < o.bottom && t.clientX < o.left;
}
function nS(t, n, i) {
  var o = Xe(Gf(i.el, i.options.draggable)), l = Mp(i.el, i.options, pe), u = 10;
  return n ? t.clientX > l.right + u || t.clientY > o.bottom && t.clientX > o.left : t.clientY > l.bottom + u || t.clientX > o.right && t.clientY > o.top;
}
function rS(t, n, i, o, l, u, a, h) {
  var g = o ? t.clientY : t.clientX, v = o ? i.height : i.width, _ = o ? i.top : i.left, m = o ? i.bottom : i.right, A = !1;
  if (!a) {
    if (h && Ks < v * l) {
      if (!yo && (bo === 1 ? g > _ + v * u / 2 : g < m - v * u / 2) && (yo = !0), yo)
        A = !0;
      else if (bo === 1 ? g < _ + Ks : g > m - Ks)
        return -bo;
    } else if (g > _ + v * (1 - l) / 2 && g < m - v * (1 - l) / 2)
      return iS(n);
  }
  return A = A || a, A && (g < _ + v * u / 2 || g > m - v * u / 2) ? g > _ + v / 2 ? 1 : -1 : 0;
}
function iS(t) {
  return at(Y) < at(t) ? 1 : -1;
}
function oS(t) {
  for (var n = t.tagName + t.className + t.src + t.href + t.textContent, i = n.length, o = 0; i--; )
    o += n.charCodeAt(i);
  return o.toString(36);
}
function sS(t) {
  nl.length = 0;
  for (var n = t.getElementsByTagName("input"), i = n.length; i--; ) {
    var o = n[i];
    o.checked && nl.push(o);
  }
}
function Gs(t) {
  return setTimeout(t, 0);
}
function vf(t) {
  return clearTimeout(t);
}
vl && Ie(document, "touchmove", function(t) {
  (le.active || Ai) && t.cancelable && t.preventDefault();
});
le.utils = {
  on: Ie,
  off: Te,
  css: j,
  find: Rp,
  is: function(n, i) {
    return !!yn(n, i, n, !1);
  },
  extend: Gx,
  throttle: Pp,
  closest: yn,
  toggleClass: ft,
  clone: Yf,
  index: at,
  nextTick: Gs,
  cancelNextTick: vf,
  detectDirection: Lp,
  getChild: Li
};
le.get = function(t) {
  return t[Ft];
};
le.mount = function() {
  for (var t = arguments.length, n = new Array(t), i = 0; i < t; i++)
    n[i] = arguments[i];
  n[0].constructor === Array && (n = n[0]), n.forEach(function(o) {
    if (!o.prototype || !o.prototype.constructor)
      throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(o));
    o.utils && (le.utils = Gn(Gn({}, le.utils), o.utils)), Io.mount(o);
  });
};
le.create = function(t, n) {
  return new le(t, n);
};
le.version = Hx;
var ut = [], lo, _f, mf = !1, ku, Zu, rl, uo;
function lS() {
  function t() {
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
  return t.prototype = {
    dragStarted: function(i) {
      var o = i.originalEvent;
      this.sortable.nativeDraggable ? Ie(document, "dragover", this._handleAutoScroll) : this.options.supportPointer ? Ie(document, "pointermove", this._handleFallbackAutoScroll) : o.touches ? Ie(document, "touchmove", this._handleFallbackAutoScroll) : Ie(document, "mousemove", this._handleFallbackAutoScroll);
    },
    dragOverCompleted: function(i) {
      var o = i.originalEvent;
      !this.options.dragOverBubble && !o.rootEl && this._handleAutoScroll(o);
    },
    drop: function() {
      this.sortable.nativeDraggable ? Te(document, "dragover", this._handleAutoScroll) : (Te(document, "pointermove", this._handleFallbackAutoScroll), Te(document, "touchmove", this._handleFallbackAutoScroll), Te(document, "mousemove", this._handleFallbackAutoScroll)), dd(), Ys(), Yx();
    },
    nulling: function() {
      rl = _f = lo = mf = uo = ku = Zu = null, ut.length = 0;
    },
    _handleFallbackAutoScroll: function(i) {
      this._handleAutoScroll(i, !0);
    },
    _handleAutoScroll: function(i, o) {
      var l = this, u = (i.touches ? i.touches[0] : i).clientX, a = (i.touches ? i.touches[0] : i).clientY, h = document.elementFromPoint(u, a);
      if (rl = i, o || this.options.forceAutoScrollFallback || Oo || lr || vo) {
        Qu(i, this.options, h, o);
        var g = Cr(h, !0);
        mf && (!uo || u !== ku || a !== Zu) && (uo && dd(), uo = setInterval(function() {
          var v = Cr(document.elementFromPoint(u, a), !0);
          v !== g && (g = v, Ys()), Qu(i, l.options, v, o);
        }, 10), ku = u, Zu = a);
      } else {
        if (!this.options.bubbleScroll || Cr(h, !0) === $n()) {
          Ys();
          return;
        }
        Qu(i, this.options, Cr(h, !1), !1);
      }
    }
  }, Dn(t, {
    pluginName: "scroll",
    initializeByDefault: !0
  });
}
function Ys() {
  ut.forEach(function(t) {
    clearInterval(t.pid);
  }), ut = [];
}
function dd() {
  clearInterval(uo);
}
var Qu = Pp(function(t, n, i, o) {
  if (n.scroll) {
    var l = (t.touches ? t.touches[0] : t).clientX, u = (t.touches ? t.touches[0] : t).clientY, a = n.scrollSensitivity, h = n.scrollSpeed, g = $n(), v = !1, _;
    _f !== i && (_f = i, Ys(), lo = n.scroll, _ = n.scrollFn, lo === !0 && (lo = Cr(i, !0)));
    var m = 0, A = lo;
    do {
      var S = A, C = Xe(S), I = C.top, P = C.bottom, B = C.left, z = C.right, X = C.width, L = C.height, te = void 0, Ee = void 0, Se = S.scrollWidth, Re = S.scrollHeight, ue = j(S), Z = S.scrollLeft, he = S.scrollTop;
      S === g ? (te = X < Se && (ue.overflowX === "auto" || ue.overflowX === "scroll" || ue.overflowX === "visible"), Ee = L < Re && (ue.overflowY === "auto" || ue.overflowY === "scroll" || ue.overflowY === "visible")) : (te = X < Se && (ue.overflowX === "auto" || ue.overflowX === "scroll"), Ee = L < Re && (ue.overflowY === "auto" || ue.overflowY === "scroll"));
      var Ce = te && (Math.abs(z - l) <= a && Z + X < Se) - (Math.abs(B - l) <= a && !!Z), We = Ee && (Math.abs(P - u) <= a && he + L < Re) - (Math.abs(I - u) <= a && !!he);
      if (!ut[m])
        for (var pt = 0; pt <= m; pt++)
          ut[pt] || (ut[pt] = {});
      (ut[m].vx != Ce || ut[m].vy != We || ut[m].el !== S) && (ut[m].el = S, ut[m].vx = Ce, ut[m].vy = We, clearInterval(ut[m].pid), (Ce != 0 || We != 0) && (v = !0, ut[m].pid = setInterval((function() {
        o && this.layer === 0 && le.active._onTouchMove(rl);
        var Ct = ut[this.layer].vy ? ut[this.layer].vy * h : 0, me = ut[this.layer].vx ? ut[this.layer].vx * h : 0;
        typeof _ == "function" && _.call(le.dragged.parentNode[Ft], me, Ct, t, rl, ut[this.layer].el) !== "continue" || Fp(ut[this.layer].el, me, Ct);
      }).bind({
        layer: m
      }), 24))), m++;
    } while (n.bubbleScroll && A !== g && (A = Cr(A, !1)));
    mf = v;
  }
}, 30), Hp = function(n) {
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
function zf() {
}
zf.prototype = {
  startIndex: null,
  dragStart: function(n) {
    var i = n.oldDraggableIndex;
    this.startIndex = i;
  },
  onSpill: function(n) {
    var i = n.dragEl, o = n.putSortable;
    this.sortable.captureAnimationState(), o && o.captureAnimationState();
    var l = Li(this.sortable.el, this.startIndex - (ce.length ? ce.indexOf(i) : 0), this.options);
    l ? this.sortable.el.insertBefore(i, l) : this.sortable.el.appendChild(i), this.sortable.animateAll(), o && o.animateAll();
  },
  drop: Hp
};
Dn(zf, {
  pluginName: "revertOnSpill"
});
function Xf() {
}
Xf.prototype = {
  onSpill: function(n) {
    var i = n.dragEl, o = n.putSortable, l = o || this.sortable;
    l.captureAnimationState(), i.parentNode && i.parentNode.removeChild(i), l.animateAll();
  },
  drop: Hp
};
Dn(Xf, {
  pluginName: "removeOnSpill"
});
var ce = [], sn = [], to, On, no = !1, Zt = !1, Si = !1, Ge, ro, Ps;
function uS() {
  function t(n) {
    for (var i in this)
      i.charAt(0) === "_" && typeof this[i] == "function" && (this[i] = this[i].bind(this));
    n.options.avoidImplicitDeselect || (n.options.supportPointer ? Ie(document, "pointerup", this._deselectMultiDrag) : (Ie(document, "mouseup", this._deselectMultiDrag), Ie(document, "touchend", this._deselectMultiDrag))), Ie(document, "keydown", this._checkKeyDown), Ie(document, "keyup", this._checkKeyUp), this.defaults = {
      selectedClass: "sortable-selected",
      multiDragKey: null,
      avoidImplicitDeselect: !1,
      setData: function(l, u) {
        var a = "";
        ce.length && On === n ? ce.forEach(function(h, g) {
          a += (g ? ", " : "") + h.textContent;
        }) : a = u.textContent, l.setData("Text", a);
      }
    };
  }
  return t.prototype = {
    multiDragKeyDown: !1,
    isMultiDrag: !1,
    delayStartGlobal: function(i) {
      var o = i.dragEl;
      Ge = o;
    },
    delayEnded: function() {
      this.isMultiDrag = ~ce.indexOf(Ge);
    },
    setupClone: function(i) {
      var o = i.sortable, l = i.cancel;
      if (this.isMultiDrag) {
        for (var u = 0; u < ce.length; u++)
          sn.push(Yf(ce[u])), sn[u].sortableIndex = ce[u].sortableIndex, sn[u].draggable = !1, sn[u].style["will-change"] = "", ft(sn[u], this.options.selectedClass, !1), ce[u] === Ge && ft(sn[u], this.options.chosenClass, !1);
        o._hideClone(), l();
      }
    },
    clone: function(i) {
      var o = i.sortable, l = i.rootEl, u = i.dispatchSortableEvent, a = i.cancel;
      this.isMultiDrag && (this.options.removeCloneOnHide || ce.length && On === o && (pd(!0, l), u("clone"), a()));
    },
    showClone: function(i) {
      var o = i.cloneNowShown, l = i.rootEl, u = i.cancel;
      this.isMultiDrag && (pd(!1, l), sn.forEach(function(a) {
        j(a, "display", "");
      }), o(), Ps = !1, u());
    },
    hideClone: function(i) {
      var o = this;
      i.sortable;
      var l = i.cloneNowHidden, u = i.cancel;
      this.isMultiDrag && (sn.forEach(function(a) {
        j(a, "display", "none"), o.options.removeCloneOnHide && a.parentNode && a.parentNode.removeChild(a);
      }), l(), Ps = !0, u());
    },
    dragStartGlobal: function(i) {
      i.sortable, !this.isMultiDrag && On && On.multiDrag._deselectMultiDrag(), ce.forEach(function(o) {
        o.sortableIndex = at(o);
      }), ce = ce.sort(function(o, l) {
        return o.sortableIndex - l.sortableIndex;
      }), Si = !0;
    },
    dragStarted: function(i) {
      var o = this, l = i.sortable;
      if (this.isMultiDrag) {
        if (this.options.sort && (l.captureAnimationState(), this.options.animation)) {
          ce.forEach(function(a) {
            a !== Ge && j(a, "position", "absolute");
          });
          var u = Xe(Ge, !1, !0, !0);
          ce.forEach(function(a) {
            a !== Ge && fd(a, u);
          }), Zt = !0, no = !0;
        }
        l.animateAll(function() {
          Zt = !1, no = !1, o.options.animation && ce.forEach(function(a) {
            Yu(a);
          }), o.options.sort && Fs();
        });
      }
    },
    dragOver: function(i) {
      var o = i.target, l = i.completed, u = i.cancel;
      Zt && ~ce.indexOf(o) && (l(!1), u());
    },
    revert: function(i) {
      var o = i.fromSortable, l = i.rootEl, u = i.sortable, a = i.dragRect;
      ce.length > 1 && (ce.forEach(function(h) {
        u.addAnimationState({
          target: h,
          rect: Zt ? Xe(h) : a
        }), Yu(h), h.fromRect = a, o.removeAnimationState(h);
      }), Zt = !1, fS(!this.options.removeCloneOnHide, l));
    },
    dragOverCompleted: function(i) {
      var o = i.sortable, l = i.isOwner, u = i.insertion, a = i.activeSortable, h = i.parentEl, g = i.putSortable, v = this.options;
      if (u) {
        if (l && a._hideClone(), no = !1, v.animation && ce.length > 1 && (Zt || !l && !a.options.sort && !g)) {
          var _ = Xe(Ge, !1, !0, !0);
          ce.forEach(function(A) {
            A !== Ge && (fd(A, _), h.appendChild(A));
          }), Zt = !0;
        }
        if (!l)
          if (Zt || Fs(), ce.length > 1) {
            var m = Ps;
            a._showClone(o), a.options.animation && !Ps && m && sn.forEach(function(A) {
              a.addAnimationState({
                target: A,
                rect: ro
              }), A.fromRect = ro, A.thisAnimationDuration = null;
            });
          } else
            a._showClone(o);
      }
    },
    dragOverAnimationCapture: function(i) {
      var o = i.dragRect, l = i.isOwner, u = i.activeSortable;
      if (ce.forEach(function(h) {
        h.thisAnimationDuration = null;
      }), u.options.animation && !l && u.multiDrag.isMultiDrag) {
        ro = Dn({}, o);
        var a = jr(Ge, !0);
        ro.top -= a.f, ro.left -= a.e;
      }
    },
    dragOverAnimationComplete: function() {
      Zt && (Zt = !1, Fs());
    },
    drop: function(i) {
      var o = i.originalEvent, l = i.rootEl, u = i.parentEl, a = i.sortable, h = i.dispatchSortableEvent, g = i.oldIndex, v = i.putSortable, _ = v || this.sortable;
      if (o) {
        var m = this.options, A = u.children;
        if (!Si)
          if (m.multiDragKey && !this.multiDragKeyDown && this._deselectMultiDrag(), ft(Ge, m.selectedClass, !~ce.indexOf(Ge)), ~ce.indexOf(Ge))
            ce.splice(ce.indexOf(Ge), 1), to = null, oo({
              sortable: a,
              rootEl: l,
              name: "deselect",
              targetEl: Ge,
              originalEvent: o
            });
          else {
            if (ce.push(Ge), oo({
              sortable: a,
              rootEl: l,
              name: "select",
              targetEl: Ge,
              originalEvent: o
            }), o.shiftKey && to && a.el.contains(to)) {
              var S = at(to), C = at(Ge);
              if (~S && ~C && S !== C) {
                var I, P;
                for (C > S ? (P = S, I = C) : (P = C, I = S + 1); P < I; P++)
                  ~ce.indexOf(A[P]) || (ft(A[P], m.selectedClass, !0), ce.push(A[P]), oo({
                    sortable: a,
                    rootEl: l,
                    name: "select",
                    targetEl: A[P],
                    originalEvent: o
                  }));
              }
            } else
              to = Ge;
            On = _;
          }
        if (Si && this.isMultiDrag) {
          if (Zt = !1, (u[Ft].options.sort || u !== l) && ce.length > 1) {
            var B = Xe(Ge), z = at(Ge, ":not(." + this.options.selectedClass + ")");
            if (!no && m.animation && (Ge.thisAnimationDuration = null), _.captureAnimationState(), !no && (m.animation && (Ge.fromRect = B, ce.forEach(function(L) {
              if (L.thisAnimationDuration = null, L !== Ge) {
                var te = Zt ? Xe(L) : B;
                L.fromRect = te, _.addAnimationState({
                  target: L,
                  rect: te
                });
              }
            })), Fs(), ce.forEach(function(L) {
              A[z] ? u.insertBefore(L, A[z]) : u.appendChild(L), z++;
            }), g === at(Ge))) {
              var X = !1;
              ce.forEach(function(L) {
                if (L.sortableIndex !== at(L)) {
                  X = !0;
                  return;
                }
              }), X && (h("update"), h("sort"));
            }
            ce.forEach(function(L) {
              Yu(L);
            }), _.animateAll();
          }
          On = _;
        }
        (l === u || v && v.lastPutMode !== "clone") && sn.forEach(function(L) {
          L.parentNode && L.parentNode.removeChild(L);
        });
      }
    },
    nullingGlobal: function() {
      this.isMultiDrag = Si = !1, sn.length = 0;
    },
    destroyGlobal: function() {
      this._deselectMultiDrag(), Te(document, "pointerup", this._deselectMultiDrag), Te(document, "mouseup", this._deselectMultiDrag), Te(document, "touchend", this._deselectMultiDrag), Te(document, "keydown", this._checkKeyDown), Te(document, "keyup", this._checkKeyUp);
    },
    _deselectMultiDrag: function(i) {
      if (!(typeof Si < "u" && Si) && On === this.sortable && !(i && yn(i.target, this.options.draggable, this.sortable.el, !1)) && !(i && i.button !== 0))
        for (; ce.length; ) {
          var o = ce[0];
          ft(o, this.options.selectedClass, !1), ce.shift(), oo({
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
  }, Dn(t, {
    // Static methods & properties
    pluginName: "multiDrag",
    utils: {
      /**
       * Selects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be selected
       */
      select: function(i) {
        var o = i.parentNode[Ft];
        !o || !o.options.multiDrag || ~ce.indexOf(i) || (On && On !== o && (On.multiDrag._deselectMultiDrag(), On = o), ft(i, o.options.selectedClass, !0), ce.push(i));
      },
      /**
       * Deselects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be deselected
       */
      deselect: function(i) {
        var o = i.parentNode[Ft], l = ce.indexOf(i);
        !o || !o.options.multiDrag || !~l || (ft(i, o.options.selectedClass, !1), ce.splice(l, 1));
      }
    },
    eventProperties: function() {
      var i = this, o = [], l = [];
      return ce.forEach(function(u) {
        o.push({
          multiDragElement: u,
          index: u.sortableIndex
        });
        var a;
        Zt && u !== Ge ? a = -1 : Zt ? a = at(u, ":not(." + i.options.selectedClass + ")") : a = at(u), l.push({
          multiDragElement: u,
          index: a
        });
      }), {
        items: Nx(ce),
        clones: [].concat(sn),
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
function fS(t, n) {
  ce.forEach(function(i, o) {
    var l = n.children[i.sortableIndex + (t ? Number(o) : 0)];
    l ? n.insertBefore(i, l) : n.appendChild(i);
  });
}
function pd(t, n) {
  sn.forEach(function(i, o) {
    var l = n.children[i.sortableIndex + (t ? Number(o) : 0)];
    l ? n.insertBefore(i, l) : n.appendChild(i);
  });
}
function Fs() {
  ce.forEach(function(t) {
    t !== Ge && t.parentNode && t.parentNode.removeChild(t);
  });
}
le.mount(new lS());
le.mount(Xf, zf);
le.mount(new uS());
function Ms(t) {
  return typeof t == "number" && !isNaN(t) ? t : typeof t == "string" && !isNaN(Number(t.trim())) ? parseFloat(t) : String(t);
}
let Ns;
function aS() {
  return {
    draggingEntityName: Ns,
    setDraggingEntityName: (o) => {
      Ns = o;
    },
    getDraggingEntityName: () => Ns,
    clearDraggingEntityName: () => {
      Ns = void 0;
    }
  };
}
const cS = ["data-id"], hS = ["data-id"], dS = /* @__PURE__ */ Lf({
  __name: "SortableList",
  props: {
    items: {},
    sortableListId: {},
    sortableGroupName: {}
  },
  emits: ["sort"],
  setup(t, { emit: n }) {
    const i = n, o = t, l = _t(null), { setDraggingEntityName: u, clearDraggingEntityName: a } = aS();
    let h;
    Mt(() => o.items, async () => {
      await Ir(), h && h.destroy(), g();
    }, {
      immediate: !0
    });
    function g() {
      l.value && (h = le.create(l.value, {
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
              taskIds: (m.length ? m : [_]).map((A) => Ms(A.dataset.id))
            }));
          }
        },
        onEnd: (v) => {
          if (a(), v.oldIndex === v.newIndex && v.from.dataset.listId === v.to.dataset.listId)
            return;
          const _ = Ms(v.to.dataset.id), m = v.items.length ? v.items : [v.item], A = m[0].previousElementSibling ? Ms(m[0].previousElementSibling.dataset.id) ?? null : null, S = m.map((C) => Ms(C.dataset.id)).filter((C) => C);
          _ && i("sort", _, A, S);
        }
      }));
    }
    return (v, _) => (Hn(), Zr("div", {
      ref_key: "sortableRef",
      ref: l,
      class: "tw-flex tw-flex-col",
      "data-id": o.sortableListId
    }, [
      (Hn(!0), Zr(fn, null, M1(o.items, (m) => (Hn(), Zr("div", {
        key: m.uuid || m.id,
        "data-id": m.id
      }, [
        N1(v.$slots, "default", { item: m })
      ], 8, hS))), 128))
    ], 8, cS));
  }
}), pS = "live", gS = 3, vS = 1e3, _S = "ping", mS = 3e4, bS = 1e3, yS = "PL-client-id", wS = async (t) => {
  var o;
  let n = null;
  const { data: i } = await t(`pocketlists.system.getWebsocketUrl?channel=${pS}`).get().json();
  return (o = i.value) != null && o.data.url && (n = xx(i.value.data.url, {
    heartbeat: {
      message: _S,
      interval: mS,
      pongTimeout: bS
    },
    autoReconnect: {
      retries: gS,
      delay: vS,
      onFailed() {
      }
    }
  })), n;
}, gd = (t) => {
  const n = Object.entries(t).filter(([i, o]) => o).reduce((i, o) => {
    const l = o1(o[1]);
    if (Array.isArray(l))
      for (const u of l)
        i.push([o[0], u.toString()]);
    else
      i.push([o[0], String(o[1])]);
    return i;
  }, []);
  return n.length ? `?${new URLSearchParams(n).toString()}` : "";
}, $p = vx(yS, crypto.randomUUID());
function ES(t) {
  const n = _t([]), i = ir("useFetch"), o = ir("options"), l = Cp(t, [() => n.value.length]), u = async () => {
    const { data: S } = await i(`pocketlists.items.get${gd({ external_app_id: o.externalAppId, external_entity_type: o.externalEntityType, external_entity_id: o.externalEntityId })}`).get().json();
    n.value = S.value.data;
  }, a = async () => {
    const S = A();
    n.value.unshift(S), await Ir(), l.focusTaskById(S.id);
  }, h = async (S, C) => {
    const I = n.value.findIndex((P) => P.id === S.id);
    if (I > -1) {
      const P = C.newName ? A({ name: C.newName }) : A();
      n.value.splice(I + (C.currentName ? 1 : 0), 0, P), await Ir(), l.focusTaskById(P.id), C.newName && g(P);
    }
  }, g = async (S, C) => {
    if (typeof S.id == "string") {
      const { data: I } = await i("pocketlists.items.add", {
        method: "PUT",
        body: JSON.stringify([
          {
            ...S,
            ...C,
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
      if (I.value.status_code === "ok" && Array.isArray(I.value.data)) {
        const P = I.value.data.find((B) => B.success && B.data.uuid === S.id).data.id;
        if (P) {
          const B = n.value.findIndex((z) => z.id === S.id);
          B > -1 && n.value.splice(B, 1, {
            ...S,
            id: P
          });
        }
      }
    } else
      await i("pocketlists.items.update", {
        method: "PATCH",
        body: JSON.stringify([{
          id: S.id,
          ...C
        }])
      }).json();
  }, v = async (S, C) => {
    const I = n.value.findIndex((P) => P.id === S.id);
    I > -1 && (n.value.splice(I, 1), C != null && C.silently || await i(`pocketlists.items.delete${gd({ "id[]": S.id })}`).delete().json());
  }, _ = (S, C) => {
    typeof S.id == "string" && !C.name && v(S, { silently: !0 });
  }, m = (S) => {
    if (S.client !== $p.value && S.entity_type === "item") {
      let C;
      try {
        C = {
          id: S.item_id,
          ...JSON.parse(S.params).item
        };
      } catch {
        return;
      }
      if (S.action === "add" && n.value.push(C), S.action === "update") {
        const I = n.value.findIndex((P) => P.id === C.id);
        I > -1 && n.value.splice(I, 1, C);
      }
      S.action === "delete" && v(C, { silently: !0 });
    }
  };
  function A(S) {
    const C = crypto.randomUUID();
    return {
      id: C,
      uuid: C,
      name: "",
      ...S
    };
  }
  return {
    items: n,
    fetchItems: u,
    onAdd: a,
    onInsert: h,
    onUpdate: g,
    onDelete: v,
    onBlur: _,
    handleLog: m
  };
}
const xS = { class: "tw-p-8" }, SS = /* @__PURE__ */ Lf({
  __name: "App",
  setup(t) {
    const n = ir("useFetch"), i = ir("options"), o = _t(), {
      items: l,
      fetchItems: u,
      onAdd: a,
      onInsert: h,
      onUpdate: g,
      onDelete: v,
      onBlur: _,
      handleLog: m
    } = ES(o);
    return np("listNavigation", Cp(o, [() => l.value.length])), Bf(async () => {
      u();
      const A = await wS(n);
      A && Mt(A.data, (S, C) => {
        if (typeof S == "string" && S !== C)
          try {
            m(JSON.parse(S));
          } catch {
          }
      });
    }), (A, S) => (Hn(), Zr("div", xS, [
      Bn("div", {
        class: "tw-mb-4",
        onClick: S[0] || (S[0] = //@ts-ignore
        (...C) => rt(a) && rt(a)(...C))
      }, " + New To-Do "),
      Bn("div", {
        ref_key: "navigatableRef",
        ref: o
      }, [
        En(dS, {
          "sortable-list-id": 666,
          "sortable-group-name": "tasks",
          items: rt(l),
          onSort: () => {
          }
        }, {
          default: Vd(({ item: C }) => [
            En(Rx, {
              task: C,
              sortable: !1,
              editable: !0,
              "addable-props": {
                external_app_id: rt(i).externalAppId,
                external_entity_type: rt(i).externalEntityType,
                external_entity_id: rt(i).externalEntityId
              },
              onInsert: rt(h),
              onUpdate: rt(g),
              onDelete: rt(v),
              onBlur: rt(_)
            }, null, 8, ["task", "addable-props", "onInsert", "onUpdate", "onDelete", "onBlur"])
          ]),
          _: 1
        }, 8, ["items"])
      ], 512)
    ]));
  }
}), TS = {
  apiBaseUrl: "",
  apiToken: ""
}, AS = (t = {}) => {
  const n = {
    ...TS,
    ...t
  }, i = VE(SS);
  i.provide("options", n), i.provide("useFetch", yx({
    baseUrl: n.apiBaseUrl,
    options: {
      beforeFetch({ options: o }) {
        return o.headers = {
          ...o.headers,
          Authorization: `Bearer ${n.apiToken}`,
          "X-PL-API-Client": $p.value
        }, {
          options: o
        };
      }
    }
  })), i.mount("#app");
};
export {
  AS as init
};
