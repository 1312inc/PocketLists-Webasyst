/**
* @vue/shared v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function Ef(t) {
  const n = /* @__PURE__ */ Object.create(null);
  for (const i of t.split(","))
    n[i] = 1;
  return (i) => i in n;
}
const Ye = {}, Di = [], $n = () => {
}, Cw = () => !1, ll = (t) => t.charCodeAt(0) === 111 && t.charCodeAt(1) === 110 && // uppercase letter
(t.charCodeAt(2) > 122 || t.charCodeAt(2) < 97), Sf = (t) => t.startsWith("onUpdate:"), Ct = Object.assign, Tf = (t, n) => {
  const i = t.indexOf(n);
  i > -1 && t.splice(i, 1);
}, Ow = Object.prototype.hasOwnProperty, Be = (t, n) => Ow.call(t, n), _e = Array.isArray, Ri = (t) => ul(t) === "[object Map]", xd = (t) => ul(t) === "[object Set]", ve = (t) => typeof t == "function", ct = (t) => typeof t == "string", Fr = (t) => typeof t == "symbol", ke = (t) => t !== null && typeof t == "object", Ed = (t) => (ke(t) || ve(t)) && ve(t.then) && ve(t.catch), Sd = Object.prototype.toString, ul = (t) => Sd.call(t), Iw = (t) => ul(t).slice(8, -1), Td = (t) => ul(t) === "[object Object]", Af = (t) => ct(t) && t !== "NaN" && t[0] !== "-" && "" + parseInt(t, 10) === t, co = /* @__PURE__ */ Ef(
  // the leading comma is intentional so empty string "" is also included
  ",key,ref,ref_for,ref_key,onVnodeBeforeMount,onVnodeMounted,onVnodeBeforeUpdate,onVnodeUpdated,onVnodeBeforeUnmount,onVnodeUnmounted"
), fl = (t) => {
  const n = /* @__PURE__ */ Object.create(null);
  return (i) => n[i] || (n[i] = t(i));
}, Dw = /-(\w)/g, ni = fl(
  (t) => t.replace(Dw, (n, i) => i ? i.toUpperCase() : "")
), Rw = /\B([A-Z])/g, Mr = fl(
  (t) => t.replace(Rw, "-$1").toLowerCase()
), Ad = fl((t) => t.charAt(0).toUpperCase() + t.slice(1)), Pu = fl(
  (t) => t ? `on${Ad(t)}` : ""
), Dr = (t, n) => !Object.is(t, n), Fu = (t, ...n) => {
  for (let i = 0; i < t.length; i++)
    t[i](...n);
}, Cd = (t, n, i, o = !1) => {
  Object.defineProperty(t, n, {
    configurable: !0,
    enumerable: !1,
    writable: o,
    value: i
  });
}, Pw = (t) => {
  const n = parseFloat(t);
  return isNaN(n) ? t : n;
};
let Ah;
const Od = () => Ah || (Ah = typeof globalThis < "u" ? globalThis : typeof self < "u" ? self : typeof window < "u" ? window : typeof global < "u" ? global : {});
function Cf(t) {
  if (_e(t)) {
    const n = {};
    for (let i = 0; i < t.length; i++) {
      const o = t[i], l = ct(o) ? Lw(o) : Cf(o);
      if (l)
        for (const u in l)
          n[u] = l[u];
    }
    return n;
  } else if (ct(t) || ke(t))
    return t;
}
const Fw = /;(?![^(]*\))/g, Mw = /:([^]+)/, Nw = /\/\*[^]*?\*\//g;
function Lw(t) {
  const n = {};
  return t.replace(Nw, "").split(Fw).forEach((i) => {
    if (i) {
      const o = i.split(Mw);
      o.length > 1 && (n[o[0].trim()] = o[1].trim());
    }
  }), n;
}
function al(t) {
  let n = "";
  if (ct(t))
    n = t;
  else if (_e(t))
    for (let i = 0; i < t.length; i++) {
      const o = al(t[i]);
      o && (n += o + " ");
    }
  else if (ke(t))
    for (const i in t)
      t[i] && (n += i + " ");
  return n.trim();
}
const Bw = "itemscope,allowfullscreen,formnovalidate,ismap,nomodule,novalidate,readonly", Ww = /* @__PURE__ */ Ef(Bw);
function Id(t) {
  return !!t || t === "";
}
const Dd = (t) => !!(t && t.__v_isRef === !0), rf = (t) => ct(t) ? t : t == null ? "" : _e(t) || ke(t) && (t.toString === Sd || !ve(t.toString)) ? Dd(t) ? rf(t.value) : JSON.stringify(t, Rd, 2) : String(t), Rd = (t, n) => Dd(n) ? Rd(t, n.value) : Ri(n) ? {
  [`Map(${n.size})`]: [...n.entries()].reduce(
    (i, [o, l], u) => (i[Mu(o, u) + " =>"] = l, i),
    {}
  )
} : xd(n) ? {
  [`Set(${n.size})`]: [...n.values()].map((i) => Mu(i))
} : Fr(n) ? Mu(n) : ke(n) && !_e(n) && !Td(n) ? String(n) : n, Mu = (t, n = "") => {
  var i;
  return (
    // Symbol.description in es2019+ so we need to cast here to pass
    // the lib: es2016 check
    Fr(t) ? `Symbol(${(i = t.description) != null ? i : n})` : t
  );
};
/**
* @vue/reactivity v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let Gt;
class Hw {
  constructor(n = !1) {
    this.detached = n, this._active = !0, this.effects = [], this.cleanups = [], this._isPaused = !1, this.parent = Gt, !n && Gt && (this.index = (Gt.scopes || (Gt.scopes = [])).push(
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
      const i = Gt;
      try {
        return Gt = this, n();
      } finally {
        Gt = i;
      }
    }
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  on() {
    Gt = this;
  }
  /**
   * This should only be called on non-detached scopes
   * @internal
   */
  off() {
    Gt = this.parent;
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
function Pd() {
  return Gt;
}
function Uw(t, n = !1) {
  Gt && Gt.cleanups.push(t);
}
let $e;
const Nu = /* @__PURE__ */ new WeakSet();
class Fd {
  constructor(n) {
    this.fn = n, this.deps = void 0, this.depsTail = void 0, this.flags = 5, this.next = void 0, this.cleanup = void 0, this.scheduler = void 0, Gt && Gt.active && Gt.effects.push(this);
  }
  pause() {
    this.flags |= 64;
  }
  resume() {
    this.flags & 64 && (this.flags &= -65, Nu.has(this) && (Nu.delete(this), this.trigger()));
  }
  /**
   * @internal
   */
  notify() {
    this.flags & 2 && !(this.flags & 32) || this.flags & 8 || Nd(this);
  }
  run() {
    if (!(this.flags & 1))
      return this.fn();
    this.flags |= 2, Ch(this), Ld(this);
    const n = $e, i = Rn;
    $e = this, Rn = !0;
    try {
      return this.fn();
    } finally {
      Bd(this), $e = n, Rn = i, this.flags &= -3;
    }
  }
  stop() {
    if (this.flags & 1) {
      for (let n = this.deps; n; n = n.nextDep)
        Df(n);
      this.deps = this.depsTail = void 0, Ch(this), this.onStop && this.onStop(), this.flags &= -2;
    }
  }
  trigger() {
    this.flags & 64 ? Nu.add(this) : this.scheduler ? this.scheduler() : this.runIfDirty();
  }
  /**
   * @internal
   */
  runIfDirty() {
    of(this) && this.run();
  }
  get dirty() {
    return of(this);
  }
}
let Md = 0, ho;
function Nd(t) {
  t.flags |= 8, t.next = ho, ho = t;
}
function Of() {
  Md++;
}
function If() {
  if (--Md > 0)
    return;
  let t;
  for (; ho; ) {
    let n = ho;
    for (ho = void 0; n; ) {
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
function Ld(t) {
  for (let n = t.deps; n; n = n.nextDep)
    n.version = -1, n.prevActiveLink = n.dep.activeLink, n.dep.activeLink = n;
}
function Bd(t) {
  let n, i = t.depsTail, o = i;
  for (; o; ) {
    const l = o.prevDep;
    o.version === -1 ? (o === i && (i = l), Df(o), $w(o)) : n = o, o.dep.activeLink = o.prevActiveLink, o.prevActiveLink = void 0, o = l;
  }
  t.deps = n, t.depsTail = i;
}
function of(t) {
  for (let n = t.deps; n; n = n.nextDep)
    if (n.dep.version !== n.version || n.dep.computed && (Wd(n.dep.computed) || n.dep.version !== n.version))
      return !0;
  return !!t._dirty;
}
function Wd(t) {
  if (t.flags & 4 && !(t.flags & 16) || (t.flags &= -17, t.globalVersion === Eo))
    return;
  t.globalVersion = Eo;
  const n = t.dep;
  if (t.flags |= 2, n.version > 0 && !t.isSSR && t.deps && !of(t)) {
    t.flags &= -3;
    return;
  }
  const i = $e, o = Rn;
  $e = t, Rn = !0;
  try {
    Ld(t);
    const l = t.fn(t._value);
    (n.version === 0 || Dr(l, t._value)) && (t._value = l, n.version++);
  } catch (l) {
    throw n.version++, l;
  } finally {
    $e = i, Rn = o, Bd(t), t.flags &= -3;
  }
}
function Df(t) {
  const { dep: n, prevSub: i, nextSub: o } = t;
  if (i && (i.nextSub = o, t.prevSub = void 0), o && (o.prevSub = i, t.nextSub = void 0), n.subs === t && (n.subs = i), !n.subs && n.computed) {
    n.computed.flags &= -5;
    for (let l = n.computed.deps; l; l = l.nextDep)
      Df(l);
  }
}
function $w(t) {
  const { prevDep: n, nextDep: i } = t;
  n && (n.nextDep = i, t.prevDep = void 0), i && (i.prevDep = n, t.nextDep = void 0);
}
let Rn = !0;
const Hd = [];
function Nr() {
  Hd.push(Rn), Rn = !1;
}
function Lr() {
  const t = Hd.pop();
  Rn = t === void 0 ? !0 : t;
}
function Ch(t) {
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
let Eo = 0;
class Kw {
  constructor(n, i) {
    this.sub = n, this.dep = i, this.version = i.version, this.nextDep = this.prevDep = this.nextSub = this.prevSub = this.prevActiveLink = void 0;
  }
}
class cl {
  constructor(n) {
    this.computed = n, this.version = 0, this.activeLink = void 0, this.subs = void 0;
  }
  track(n) {
    if (!$e || !Rn || $e === this.computed)
      return;
    let i = this.activeLink;
    if (i === void 0 || i.sub !== $e)
      i = this.activeLink = new Kw($e, this), $e.deps ? (i.prevDep = $e.depsTail, $e.depsTail.nextDep = i, $e.depsTail = i) : $e.deps = $e.depsTail = i, $e.flags & 4 && Ud(i);
    else if (i.version === -1 && (i.version = this.version, i.nextDep)) {
      const o = i.nextDep;
      o.prevDep = i.prevDep, i.prevDep && (i.prevDep.nextDep = o), i.prevDep = $e.depsTail, i.nextDep = void 0, $e.depsTail.nextDep = i, $e.depsTail = i, $e.deps === i && ($e.deps = o);
    }
    return i;
  }
  trigger(n) {
    this.version++, Eo++, this.notify(n);
  }
  notify(n) {
    Of();
    try {
      for (let i = this.subs; i; i = i.prevSub)
        i.sub.notify() && i.sub.dep.notify();
    } finally {
      If();
    }
  }
}
function Ud(t) {
  const n = t.dep.computed;
  if (n && !t.dep.subs) {
    n.flags |= 20;
    for (let o = n.deps; o; o = o.nextDep)
      Ud(o);
  }
  const i = t.dep.subs;
  i !== t && (t.prevSub = i, i && (i.nextSub = t)), t.dep.subs = t;
}
const Vs = /* @__PURE__ */ new WeakMap(), Qr = Symbol(
  ""
), sf = Symbol(
  ""
), So = Symbol(
  ""
);
function Lt(t, n, i) {
  if (Rn && $e) {
    let o = Vs.get(t);
    o || Vs.set(t, o = /* @__PURE__ */ new Map());
    let l = o.get(i);
    l || o.set(i, l = new cl()), l.track();
  }
}
function or(t, n, i, o, l, u) {
  const a = Vs.get(t);
  if (!a) {
    Eo++;
    return;
  }
  const h = (p) => {
    p && p.trigger();
  };
  if (Of(), n === "clear")
    a.forEach(h);
  else {
    const p = _e(t), v = p && Af(i);
    if (p && i === "length") {
      const _ = Number(o);
      a.forEach((m, A) => {
        (A === "length" || A === So || !Fr(A) && A >= _) && h(m);
      });
    } else
      switch (i !== void 0 && h(a.get(i)), v && h(a.get(So)), n) {
        case "add":
          p ? v && h(a.get("length")) : (h(a.get(Qr)), Ri(t) && h(a.get(sf)));
          break;
        case "delete":
          p || (h(a.get(Qr)), Ri(t) && h(a.get(sf)));
          break;
        case "set":
          Ri(t) && h(a.get(Qr));
          break;
      }
  }
  If();
}
function Gw(t, n) {
  var i;
  return (i = Vs.get(t)) == null ? void 0 : i.get(n);
}
function wi(t) {
  const n = Ne(t);
  return n === t ? n : (Lt(n, "iterate", So), xn(t) ? n : n.map(Pt));
}
function hl(t) {
  return Lt(t = Ne(t), "iterate", So), t;
}
const Yw = {
  __proto__: null,
  [Symbol.iterator]() {
    return Lu(this, Symbol.iterator, Pt);
  },
  concat(...t) {
    return wi(this).concat(
      ...t.map((n) => _e(n) ? wi(n) : n)
    );
  },
  entries() {
    return Lu(this, "entries", (t) => (t[1] = Pt(t[1]), t));
  },
  every(t, n) {
    return rr(this, "every", t, n, void 0, arguments);
  },
  filter(t, n) {
    return rr(this, "filter", t, n, (i) => i.map(Pt), arguments);
  },
  find(t, n) {
    return rr(this, "find", t, n, Pt, arguments);
  },
  findIndex(t, n) {
    return rr(this, "findIndex", t, n, void 0, arguments);
  },
  findLast(t, n) {
    return rr(this, "findLast", t, n, Pt, arguments);
  },
  findLastIndex(t, n) {
    return rr(this, "findLastIndex", t, n, void 0, arguments);
  },
  // flat, flatMap could benefit from ARRAY_ITERATE but are not straight-forward to implement
  forEach(t, n) {
    return rr(this, "forEach", t, n, void 0, arguments);
  },
  includes(...t) {
    return Bu(this, "includes", t);
  },
  indexOf(...t) {
    return Bu(this, "indexOf", t);
  },
  join(t) {
    return wi(this).join(t);
  },
  // keys() iterator only reads `length`, no optimisation required
  lastIndexOf(...t) {
    return Bu(this, "lastIndexOf", t);
  },
  map(t, n) {
    return rr(this, "map", t, n, void 0, arguments);
  },
  pop() {
    return eo(this, "pop");
  },
  push(...t) {
    return eo(this, "push", t);
  },
  reduce(t, ...n) {
    return Oh(this, "reduce", t, n);
  },
  reduceRight(t, ...n) {
    return Oh(this, "reduceRight", t, n);
  },
  shift() {
    return eo(this, "shift");
  },
  // slice could use ARRAY_ITERATE but also seems to beg for range tracking
  some(t, n) {
    return rr(this, "some", t, n, void 0, arguments);
  },
  splice(...t) {
    return eo(this, "splice", t);
  },
  toReversed() {
    return wi(this).toReversed();
  },
  toSorted(t) {
    return wi(this).toSorted(t);
  },
  toSpliced(...t) {
    return wi(this).toSpliced(...t);
  },
  unshift(...t) {
    return eo(this, "unshift", t);
  },
  values() {
    return Lu(this, "values", Pt);
  }
};
function Lu(t, n, i) {
  const o = hl(t), l = o[n]();
  return o !== t && !xn(t) && (l._next = l.next, l.next = () => {
    const u = l._next();
    return u.value && (u.value = i(u.value)), u;
  }), l;
}
const qw = Array.prototype;
function rr(t, n, i, o, l, u) {
  const a = hl(t), h = a !== t && !xn(t), p = a[n];
  if (p !== qw[n]) {
    const m = p.apply(t, u);
    return h ? Pt(m) : m;
  }
  let v = i;
  a !== t && (h ? v = function(m, A) {
    return i.call(this, Pt(m), A, t);
  } : i.length > 2 && (v = function(m, A) {
    return i.call(this, m, A, t);
  }));
  const _ = p.call(a, v, o);
  return h && l ? l(_) : _;
}
function Oh(t, n, i, o) {
  const l = hl(t);
  let u = i;
  return l !== t && (xn(t) ? i.length > 3 && (u = function(a, h, p) {
    return i.call(this, a, h, p, t);
  }) : u = function(a, h, p) {
    return i.call(this, a, Pt(h), p, t);
  }), l[n](u, ...o);
}
function Bu(t, n, i) {
  const o = Ne(t);
  Lt(o, "iterate", So);
  const l = o[n](...i);
  return (l === -1 || l === !1) && Nf(i[0]) ? (i[0] = Ne(i[0]), o[n](...i)) : l;
}
function eo(t, n, i = []) {
  Nr(), Of();
  const o = Ne(t)[n].apply(t, i);
  return If(), Lr(), o;
}
const zw = /* @__PURE__ */ Ef("__proto__,__v_isRef,__isVue"), $d = new Set(
  /* @__PURE__ */ Object.getOwnPropertyNames(Symbol).filter((t) => t !== "arguments" && t !== "caller").map((t) => Symbol[t]).filter(Fr)
);
function Xw(t) {
  Fr(t) || (t = String(t));
  const n = Ne(this);
  return Lt(n, "has", t), n.hasOwnProperty(t);
}
class Kd {
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
      return o === (l ? u ? s1 : zd : u ? qd : Yd).get(n) || // receiver is not the reactive proxy, but has the same prototype
      // this means the receiver is a user proxy of the reactive proxy
      Object.getPrototypeOf(n) === Object.getPrototypeOf(o) ? n : void 0;
    const a = _e(n);
    if (!l) {
      let p;
      if (a && (p = Yw[i]))
        return p;
      if (i === "hasOwnProperty")
        return Xw;
    }
    const h = Reflect.get(
      n,
      i,
      // if this is a proxy wrapping a ref, return methods using the raw ref
      // as receiver so that we don't have to call `toRaw` on the ref in all
      // its class methods
      it(n) ? n : o
    );
    return (Fr(i) ? $d.has(i) : zw(i)) || (l || Lt(n, "get", i), u) ? h : it(h) ? a && Af(i) ? h : h.value : ke(h) ? l ? ri(h) : Ff(h) : h;
  }
}
class Gd extends Kd {
  constructor(n = !1) {
    super(!1, n);
  }
  set(n, i, o, l) {
    let u = n[i];
    if (!this._isShallow) {
      const p = ii(u);
      if (!xn(o) && !ii(o) && (u = Ne(u), o = Ne(o)), !_e(n) && it(u) && !it(o))
        return p ? !1 : (u.value = o, !0);
    }
    const a = _e(n) && Af(i) ? Number(i) < n.length : Be(n, i), h = Reflect.set(
      n,
      i,
      o,
      it(n) ? n : l
    );
    return n === Ne(l) && (a ? Dr(o, u) && or(n, "set", i, o) : or(n, "add", i, o)), h;
  }
  deleteProperty(n, i) {
    const o = Be(n, i);
    n[i];
    const l = Reflect.deleteProperty(n, i);
    return l && o && or(n, "delete", i, void 0), l;
  }
  has(n, i) {
    const o = Reflect.has(n, i);
    return (!Fr(i) || !$d.has(i)) && Lt(n, "has", i), o;
  }
  ownKeys(n) {
    return Lt(
      n,
      "iterate",
      _e(n) ? "length" : Qr
    ), Reflect.ownKeys(n);
  }
}
class Vw extends Kd {
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
const Jw = /* @__PURE__ */ new Gd(), kw = /* @__PURE__ */ new Vw(), Zw = /* @__PURE__ */ new Gd(!0);
const Rf = (t) => t, dl = (t) => Reflect.getPrototypeOf(t);
function ws(t, n, i = !1, o = !1) {
  t = t.__v_raw;
  const l = Ne(t), u = Ne(n);
  i || (Dr(n, u) && Lt(l, "get", n), Lt(l, "get", u));
  const { has: a } = dl(l), h = o ? Rf : i ? Lf : Pt;
  if (a.call(l, n))
    return h(t.get(n));
  if (a.call(l, u))
    return h(t.get(u));
  t !== l && t.get(n);
}
function xs(t, n = !1) {
  const i = this.__v_raw, o = Ne(i), l = Ne(t);
  return n || (Dr(t, l) && Lt(o, "has", t), Lt(o, "has", l)), t === l ? i.has(t) : i.has(t) || i.has(l);
}
function Es(t, n = !1) {
  return t = t.__v_raw, !n && Lt(Ne(t), "iterate", Qr), Reflect.get(t, "size", t);
}
function Ih(t, n = !1) {
  !n && !xn(t) && !ii(t) && (t = Ne(t));
  const i = Ne(this);
  return dl(i).has.call(i, t) || (i.add(t), or(i, "add", t, t)), this;
}
function Dh(t, n, i = !1) {
  !i && !xn(n) && !ii(n) && (n = Ne(n));
  const o = Ne(this), { has: l, get: u } = dl(o);
  let a = l.call(o, t);
  a || (t = Ne(t), a = l.call(o, t));
  const h = u.call(o, t);
  return o.set(t, n), a ? Dr(n, h) && or(o, "set", t, n) : or(o, "add", t, n), this;
}
function Rh(t) {
  const n = Ne(this), { has: i, get: o } = dl(n);
  let l = i.call(n, t);
  l || (t = Ne(t), l = i.call(n, t)), o && o.call(n, t);
  const u = n.delete(t);
  return l && or(n, "delete", t, void 0), u;
}
function Ph() {
  const t = Ne(this), n = t.size !== 0, i = t.clear();
  return n && or(t, "clear", void 0, void 0), i;
}
function Ss(t, n) {
  return function(o, l) {
    const u = this, a = u.__v_raw, h = Ne(a), p = n ? Rf : t ? Lf : Pt;
    return !t && Lt(h, "iterate", Qr), a.forEach((v, _) => o.call(l, p(v), p(_), u));
  };
}
function Ts(t, n, i) {
  return function(...o) {
    const l = this.__v_raw, u = Ne(l), a = Ri(u), h = t === "entries" || t === Symbol.iterator && a, p = t === "keys" && a, v = l[t](...o), _ = i ? Rf : n ? Lf : Pt;
    return !n && Lt(
      u,
      "iterate",
      p ? sf : Qr
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
function Er(t) {
  return function(...n) {
    return t === "delete" ? !1 : t === "clear" ? void 0 : this;
  };
}
function Qw() {
  const t = {
    get(u) {
      return ws(this, u);
    },
    get size() {
      return Es(this);
    },
    has: xs,
    add: Ih,
    set: Dh,
    delete: Rh,
    clear: Ph,
    forEach: Ss(!1, !1)
  }, n = {
    get(u) {
      return ws(this, u, !1, !0);
    },
    get size() {
      return Es(this);
    },
    has: xs,
    add(u) {
      return Ih.call(this, u, !0);
    },
    set(u, a) {
      return Dh.call(this, u, a, !0);
    },
    delete: Rh,
    clear: Ph,
    forEach: Ss(!1, !0)
  }, i = {
    get(u) {
      return ws(this, u, !0);
    },
    get size() {
      return Es(this, !0);
    },
    has(u) {
      return xs.call(this, u, !0);
    },
    add: Er("add"),
    set: Er("set"),
    delete: Er("delete"),
    clear: Er("clear"),
    forEach: Ss(!0, !1)
  }, o = {
    get(u) {
      return ws(this, u, !0, !0);
    },
    get size() {
      return Es(this, !0);
    },
    has(u) {
      return xs.call(this, u, !0);
    },
    add: Er("add"),
    set: Er("set"),
    delete: Er("delete"),
    clear: Er("clear"),
    forEach: Ss(!0, !0)
  };
  return [
    "keys",
    "values",
    "entries",
    Symbol.iterator
  ].forEach((u) => {
    t[u] = Ts(u, !1, !1), i[u] = Ts(u, !0, !1), n[u] = Ts(u, !1, !0), o[u] = Ts(
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
  jw,
  e1,
  t1,
  n1
] = /* @__PURE__ */ Qw();
function Pf(t, n) {
  const i = n ? t ? n1 : t1 : t ? e1 : jw;
  return (o, l, u) => l === "__v_isReactive" ? !t : l === "__v_isReadonly" ? t : l === "__v_raw" ? o : Reflect.get(
    Be(i, l) && l in o ? i : o,
    l,
    u
  );
}
const r1 = {
  get: /* @__PURE__ */ Pf(!1, !1)
}, i1 = {
  get: /* @__PURE__ */ Pf(!1, !0)
}, o1 = {
  get: /* @__PURE__ */ Pf(!0, !1)
};
const Yd = /* @__PURE__ */ new WeakMap(), qd = /* @__PURE__ */ new WeakMap(), zd = /* @__PURE__ */ new WeakMap(), s1 = /* @__PURE__ */ new WeakMap();
function l1(t) {
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
function u1(t) {
  return t.__v_skip || !Object.isExtensible(t) ? 0 : l1(Iw(t));
}
function Ff(t) {
  return ii(t) ? t : Mf(
    t,
    !1,
    Jw,
    r1,
    Yd
  );
}
function f1(t) {
  return Mf(
    t,
    !1,
    Zw,
    i1,
    qd
  );
}
function ri(t) {
  return Mf(
    t,
    !0,
    kw,
    o1,
    zd
  );
}
function Mf(t, n, i, o, l) {
  if (!ke(t) || t.__v_raw && !(n && t.__v_isReactive))
    return t;
  const u = l.get(t);
  if (u)
    return u;
  const a = u1(t);
  if (a === 0)
    return t;
  const h = new Proxy(
    t,
    a === 2 ? o : i
  );
  return l.set(t, h), h;
}
function Pi(t) {
  return ii(t) ? Pi(t.__v_raw) : !!(t && t.__v_isReactive);
}
function ii(t) {
  return !!(t && t.__v_isReadonly);
}
function xn(t) {
  return !!(t && t.__v_isShallow);
}
function Nf(t) {
  return t ? !!t.__v_raw : !1;
}
function Ne(t) {
  const n = t && t.__v_raw;
  return n ? Ne(n) : t;
}
function a1(t) {
  return !Be(t, "__v_skip") && Object.isExtensible(t) && Cd(t, "__v_skip", !0), t;
}
const Pt = (t) => ke(t) ? Ff(t) : t, Lf = (t) => ke(t) ? ri(t) : t;
function it(t) {
  return t ? t.__v_isRef === !0 : !1;
}
function _t(t) {
  return Xd(t, !1);
}
function Hs(t) {
  return Xd(t, !0);
}
function Xd(t, n) {
  return it(t) ? t : new c1(t, n);
}
class c1 {
  constructor(n, i) {
    this.dep = new cl(), this.__v_isRef = !0, this.__v_isShallow = !1, this._rawValue = i ? n : Ne(n), this._value = i ? n : Pt(n), this.__v_isShallow = i;
  }
  get value() {
    return this.dep.track(), this._value;
  }
  set value(n) {
    const i = this._rawValue, o = this.__v_isShallow || xn(n) || ii(n);
    n = o ? n : Ne(n), Dr(n, i) && (this._rawValue = n, this._value = o ? n : Pt(n), this.dep.trigger());
  }
}
function rt(t) {
  return it(t) ? t.value : t;
}
function h1(t) {
  return ve(t) ? t() : rt(t);
}
const d1 = {
  get: (t, n, i) => n === "__v_raw" ? t : rt(Reflect.get(t, n, i)),
  set: (t, n, i, o) => {
    const l = t[n];
    return it(l) && !it(i) ? (l.value = i, !0) : Reflect.set(t, n, i, o);
  }
};
function Vd(t) {
  return Pi(t) ? t : new Proxy(t, d1);
}
class p1 {
  constructor(n) {
    this.__v_isRef = !0, this._value = void 0;
    const i = this.dep = new cl(), { get: o, set: l } = n(i.track.bind(i), i.trigger.bind(i));
    this._get = o, this._set = l;
  }
  get value() {
    return this._value = this._get();
  }
  set value(n) {
    this._set(n);
  }
}
function g1(t) {
  return new p1(t);
}
class v1 {
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
    return Gw(Ne(this._object), this._key);
  }
}
class _1 {
  constructor(n) {
    this._getter = n, this.__v_isRef = !0, this.__v_isReadonly = !0, this._value = void 0;
  }
  get value() {
    return this._value = this._getter();
  }
}
function m1(t, n, i) {
  return it(t) ? t : ve(t) ? new _1(t) : ke(t) && arguments.length > 1 ? b1(t, n, i) : _t(t);
}
function b1(t, n, i) {
  const o = t[n];
  return it(o) ? o : new v1(t, n, i);
}
class y1 {
  constructor(n, i, o) {
    this.fn = n, this.setter = i, this._value = void 0, this.dep = new cl(this), this.__v_isRef = !0, this.deps = void 0, this.depsTail = void 0, this.flags = 16, this.globalVersion = Eo - 1, this.effect = this, this.__v_isReadonly = !i, this.isSSR = o;
  }
  /**
   * @internal
   */
  notify() {
    if (this.flags |= 16, !(this.flags & 8) && // avoid infinite self recursion
    $e !== this)
      return Nd(this), !0;
  }
  get value() {
    const n = this.dep.track();
    return Wd(this), n && (n.version = this.dep.version), this._value;
  }
  set value(n) {
    this.setter && this.setter(n);
  }
}
function w1(t, n, i = !1) {
  let o, l;
  return ve(t) ? o = t : (o = t.get, l = t.set), new y1(o, l, i);
}
const As = {}, Js = /* @__PURE__ */ new WeakMap();
let kr;
function x1(t, n = !1, i = kr) {
  if (i) {
    let o = Js.get(i);
    o || Js.set(i, o = []), o.push(t);
  }
}
function E1(t, n, i = Ye) {
  const { immediate: o, deep: l, once: u, scheduler: a, augmentJob: h, call: p } = i, v = (L) => l ? L : xn(L) || l === !1 || l === 0 ? Cr(L, 1) : Cr(L);
  let _, m, A, S, C = !1, I = !1;
  if (it(t) ? (m = () => t.value, C = xn(t)) : Pi(t) ? (m = () => v(t), C = !0) : _e(t) ? (I = !0, C = t.some((L) => Pi(L) || xn(L)), m = () => t.map((L) => {
    if (it(L))
      return L.value;
    if (Pi(L))
      return v(L);
    if (ve(L))
      return p ? p(L, 2) : L();
  })) : ve(t) ? n ? m = p ? () => p(t, 2) : t : m = () => {
    if (A) {
      Nr();
      try {
        A();
      } finally {
        Lr();
      }
    }
    const L = kr;
    kr = _;
    try {
      return p ? p(t, 3, [S]) : t(S);
    } finally {
      kr = L;
    }
  } : m = $n, n && l) {
    const L = m, te = l === !0 ? 1 / 0 : l;
    m = () => Cr(L(), te);
  }
  const P = Pd(), B = () => {
    _.stop(), P && Tf(P.effects, _);
  };
  if (u && n) {
    const L = n;
    n = (...te) => {
      L(...te), B();
    };
  }
  let q = I ? new Array(t.length).fill(As) : As;
  const z = (L) => {
    if (!(!(_.flags & 1) || !_.dirty && !L))
      if (n) {
        const te = _.run();
        if (l || C || (I ? te.some((xe, Se) => Dr(xe, q[Se])) : Dr(te, q))) {
          A && A();
          const xe = kr;
          kr = _;
          try {
            const Se = [
              te,
              // pass undefined as the old value when it's changed for the first time
              q === As ? void 0 : I && q[0] === As ? [] : q,
              S
            ];
            p ? p(n, 3, Se) : (
              // @ts-expect-error
              n(...Se)
            ), q = te;
          } finally {
            kr = xe;
          }
        }
      } else
        _.run();
  };
  return h && h(z), _ = new Fd(m), _.scheduler = a ? () => a(z, !1) : z, S = (L) => x1(L, !1, _), A = _.onStop = () => {
    const L = Js.get(_);
    if (L) {
      if (p)
        p(L, 4);
      else
        for (const te of L)
          te();
      Js.delete(_);
    }
  }, n ? o ? z(!0) : q = _.run() : a ? a(z.bind(null, !0), !0) : _.run(), B.pause = _.pause.bind(_), B.resume = _.resume.bind(_), B.stop = B, B;
}
function Cr(t, n = 1 / 0, i) {
  if (n <= 0 || !ke(t) || t.__v_skip || (i = i || /* @__PURE__ */ new Set(), i.has(t)))
    return t;
  if (i.add(t), n--, it(t))
    Cr(t.value, n, i);
  else if (_e(t))
    for (let o = 0; o < t.length; o++)
      Cr(t[o], n, i);
  else if (xd(t) || Ri(t))
    t.forEach((o) => {
      Cr(o, n, i);
    });
  else if (Td(t)) {
    for (const o in t)
      Cr(t[o], n, i);
    for (const o of Object.getOwnPropertySymbols(t))
      Object.prototype.propertyIsEnumerable.call(t, o) && Cr(t[o], n, i);
  }
  return t;
}
/**
* @vue/runtime-core v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
function Oo(t, n, i, o) {
  try {
    return o ? t(...o) : t();
  } catch (l) {
    pl(l, n, i);
  }
}
function Yn(t, n, i, o) {
  if (ve(t)) {
    const l = Oo(t, n, i, o);
    return l && Ed(l) && l.catch((u) => {
      pl(u, n, i);
    }), l;
  }
  if (_e(t)) {
    const l = [];
    for (let u = 0; u < t.length; u++)
      l.push(Yn(t[u], n, i, o));
    return l;
  }
}
function pl(t, n, i, o = !0) {
  const l = n ? n.vnode : null, { errorHandler: u, throwUnhandledErrorInProduction: a } = n && n.appContext.config || Ye;
  if (n) {
    let h = n.parent;
    const p = n.proxy, v = `https://vuejs.org/error-reference/#runtime-${i}`;
    for (; h; ) {
      const _ = h.ec;
      if (_) {
        for (let m = 0; m < _.length; m++)
          if (_[m](t, p, v) === !1)
            return;
      }
      h = h.parent;
    }
    if (u) {
      Nr(), Oo(u, null, 10, [
        t,
        p,
        v
      ]), Lr();
      return;
    }
  }
  S1(t, i, l, o, a);
}
function S1(t, n, i, o = !0, l = !1) {
  if (l)
    throw t;
  console.error(t);
}
let To = !1, lf = !1;
const Yt = [];
let Wn = 0;
const Fi = [];
let Sr = null, Ci = 0;
const Jd = /* @__PURE__ */ Promise.resolve();
let Bf = null;
function Rr(t) {
  const n = Bf || Jd;
  return t ? n.then(this ? t.bind(this) : t) : n;
}
function T1(t) {
  let n = To ? Wn + 1 : 0, i = Yt.length;
  for (; n < i; ) {
    const o = n + i >>> 1, l = Yt[o], u = Ao(l);
    u < t || u === t && l.flags & 2 ? n = o + 1 : i = o;
  }
  return n;
}
function Wf(t) {
  if (!(t.flags & 1)) {
    const n = Ao(t), i = Yt[Yt.length - 1];
    !i || // fast path when the job id is larger than the tail
    !(t.flags & 2) && n >= Ao(i) ? Yt.push(t) : Yt.splice(T1(n), 0, t), t.flags |= 1, kd();
  }
}
function kd() {
  !To && !lf && (lf = !0, Bf = Jd.then(Qd));
}
function A1(t) {
  _e(t) ? Fi.push(...t) : Sr && t.id === -1 ? Sr.splice(Ci + 1, 0, t) : t.flags & 1 || (Fi.push(t), t.flags |= 1), kd();
}
function Fh(t, n, i = To ? Wn + 1 : 0) {
  for (; i < Yt.length; i++) {
    const o = Yt[i];
    if (o && o.flags & 2) {
      if (t && o.id !== t.uid)
        continue;
      Yt.splice(i, 1), i--, o.flags & 4 && (o.flags &= -2), o(), o.flags &= -2;
    }
  }
}
function Zd(t) {
  if (Fi.length) {
    const n = [...new Set(Fi)].sort(
      (i, o) => Ao(i) - Ao(o)
    );
    if (Fi.length = 0, Sr) {
      Sr.push(...n);
      return;
    }
    for (Sr = n, Ci = 0; Ci < Sr.length; Ci++) {
      const i = Sr[Ci];
      i.flags & 4 && (i.flags &= -2), i.flags & 8 || i(), i.flags &= -2;
    }
    Sr = null, Ci = 0;
  }
}
const Ao = (t) => t.id == null ? t.flags & 2 ? -1 : 1 / 0 : t.id;
function Qd(t) {
  lf = !1, To = !0;
  try {
    for (Wn = 0; Wn < Yt.length; Wn++) {
      const n = Yt[Wn];
      n && !(n.flags & 8) && (n.flags & 4 && (n.flags &= -2), Oo(
        n,
        n.i,
        n.i ? 15 : 14
      ), n.flags &= -2);
    }
  } finally {
    for (; Wn < Yt.length; Wn++) {
      const n = Yt[Wn];
      n && (n.flags &= -2);
    }
    Wn = 0, Yt.length = 0, Zd(), To = !1, Bf = null, (Yt.length || Fi.length) && Qd();
  }
}
let qt = null, jd = null;
function ks(t) {
  const n = qt;
  return qt = t, jd = t && t.type.__scopeId || null, n;
}
function ep(t, n = qt, i) {
  if (!n || t._n)
    return t;
  const o = (...l) => {
    o._d && $h(-1);
    const u = ks(n);
    let a;
    try {
      a = t(...l);
    } finally {
      ks(u), o._d && $h(1);
    }
    return a;
  };
  return o._n = !0, o._c = !0, o._d = !0, o;
}
function zr(t, n, i, o) {
  const l = t.dirs, u = n && n.dirs;
  for (let a = 0; a < l.length; a++) {
    const h = l[a];
    u && (h.oldValue = u[a].value);
    let p = h.dir[o];
    p && (Nr(), Yn(p, i, 8, [
      t.el,
      h,
      t,
      n
    ]), Lr());
  }
}
const C1 = Symbol("_vte"), O1 = (t) => t.__isTeleport;
function Hf(t, n) {
  t.shapeFlag & 6 && t.component ? (t.transition = n, Hf(t.component.subTree, n)) : t.shapeFlag & 128 ? (t.ssContent.transition = n.clone(t.ssContent), t.ssFallback.transition = n.clone(t.ssFallback)) : t.transition = n;
}
/*! #__NO_SIDE_EFFECTS__ */
// @__NO_SIDE_EFFECTS__
function Uf(t, n) {
  return ve(t) ? (
    // #8236: extend call and options.name access are considered side-effects
    // by Rollup, so we have to wrap it in a pure-annotated IIFE.
    Ct({ name: t.name }, n, { setup: t })
  ) : t;
}
function tp(t) {
  t.ids = [t.ids[0] + t.ids[2]++ + "-", 0, 0];
}
function uf(t, n, i, o, l = !1) {
  if (_e(t)) {
    t.forEach(
      (C, I) => uf(
        C,
        n && (_e(n) ? n[I] : n),
        i,
        o,
        l
      )
    );
    return;
  }
  if (Mi(o) && !l)
    return;
  const u = o.shapeFlag & 4 ? qf(o.component) : o.el, a = l ? null : u, { i: h, r: p } = t, v = n && n.r, _ = h.refs === Ye ? h.refs = {} : h.refs, m = h.setupState, A = Ne(m), S = m === Ye ? () => !1 : (C) => Be(A, C);
  if (v != null && v !== p && (ct(v) ? (_[v] = null, S(v) && (m[v] = null)) : it(v) && (v.value = null)), ve(p))
    Oo(p, h, 12, [a, _]);
  else {
    const C = ct(p), I = it(p);
    if (C || I) {
      const P = () => {
        if (t.f) {
          const B = C ? S(p) ? m[p] : _[p] : p.value;
          l ? _e(B) && Tf(B, u) : _e(B) ? B.includes(u) || B.push(u) : C ? (_[p] = [u], S(p) && (m[p] = _[p])) : (p.value = [u], t.k && (_[t.k] = p.value));
        } else
          C ? (_[p] = a, S(p) && (m[p] = a)) : I && (p.value = a, t.k && (_[t.k] = a));
      };
      a ? (P.id = -1, un(P, i)) : P();
    }
  }
}
const Mi = (t) => !!t.type.__asyncLoader, np = (t) => t.type.__isKeepAlive;
function I1(t, n) {
  rp(t, "a", n);
}
function D1(t, n) {
  rp(t, "da", n);
}
function rp(t, n, i = Ft) {
  const o = t.__wdc || (t.__wdc = () => {
    let l = i;
    for (; l; ) {
      if (l.isDeactivated)
        return;
      l = l.parent;
    }
    return t();
  });
  if (gl(n, o, i), i) {
    let l = i.parent;
    for (; l && l.parent; )
      np(l.parent.vnode) && R1(o, n, i, l), l = l.parent;
  }
}
function R1(t, n, i, o) {
  const l = gl(
    n,
    t,
    o,
    !0
    /* prepend */
  );
  ip(() => {
    Tf(o[n], l);
  }, i);
}
function gl(t, n, i = Ft, o = !1) {
  if (i) {
    const l = i[t] || (i[t] = []), u = n.__weh || (n.__weh = (...a) => {
      Nr();
      const h = Io(i), p = Yn(n, i, t, a);
      return h(), Lr(), p;
    });
    return o ? l.unshift(u) : l.push(u), u;
  }
}
const ur = (t) => (n, i = Ft) => {
  (!ml || t === "sp") && gl(t, (...o) => n(...o), i);
}, P1 = ur("bm"), $f = ur("m"), F1 = ur(
  "bu"
), M1 = ur("u"), N1 = ur(
  "bum"
), ip = ur("um"), L1 = ur(
  "sp"
), B1 = ur("rtg"), W1 = ur("rtc");
function H1(t, n = Ft) {
  gl("ec", t, n);
}
const U1 = Symbol.for("v-ndc");
function $1(t, n, i, o) {
  let l;
  const u = i && i[o], a = _e(t);
  if (a || ct(t)) {
    const h = a && Pi(t);
    let p = !1;
    h && (p = !xn(t), t = hl(t)), l = new Array(t.length);
    for (let v = 0, _ = t.length; v < _; v++)
      l[v] = n(
        p ? Pt(t[v]) : t[v],
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
        (h, p) => n(h, p, void 0, u && u[p])
      );
    else {
      const h = Object.keys(t);
      l = new Array(h.length);
      for (let p = 0, v = h.length; p < v; p++) {
        const _ = h[p];
        l[p] = n(t[_], _, p, u && u[p]);
      }
    }
  else
    l = [];
  return i && (i[o] = l), l;
}
function K1(t, n, i = {}, o, l) {
  if (qt.ce || qt.parent && Mi(qt.parent) && qt.parent.ce)
    return n !== "default" && (i.name = n), Kn(), df(
      an,
      null,
      [En("slot", i, o && o())],
      64
    );
  let u = t[n];
  u && u._c && (u._d = !1), Kn();
  const a = u && op(u(i)), h = df(
    an,
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
function op(t) {
  return t.some((n) => Tp(n) ? !(n.type === Pr || n.type === an && !op(n.children)) : !0) ? t : null;
}
const ff = (t) => t ? Op(t) ? qf(t) : ff(t.parent) : null, po = (
  // Move PURE marker to new line to workaround compiler discarding it
  // due to type annotation
  /* @__PURE__ */ Ct(/* @__PURE__ */ Object.create(null), {
    $: (t) => t,
    $el: (t) => t.vnode.el,
    $data: (t) => t.data,
    $props: (t) => t.props,
    $attrs: (t) => t.attrs,
    $slots: (t) => t.slots,
    $refs: (t) => t.refs,
    $parent: (t) => ff(t.parent),
    $root: (t) => ff(t.root),
    $host: (t) => t.ce,
    $emit: (t) => t.emit,
    $options: (t) => Kf(t),
    $forceUpdate: (t) => t.f || (t.f = () => {
      Wf(t.update);
    }),
    $nextTick: (t) => t.n || (t.n = Rr.bind(t.proxy)),
    $watch: (t) => fx.bind(t)
  })
), Wu = (t, n) => t !== Ye && !t.__isScriptSetup && Be(t, n), G1 = {
  get({ _: t }, n) {
    if (n === "__v_skip")
      return !0;
    const { ctx: i, setupState: o, data: l, props: u, accessCache: a, type: h, appContext: p } = t;
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
        if (Wu(o, n))
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
        af && (a[n] = 0);
      }
    }
    const _ = po[n];
    let m, A;
    if (_)
      return n === "$attrs" && Lt(t.attrs, "get", ""), _(t);
    if (
      // css module (injected by vue-loader)
      (m = h.__cssModules) && (m = m[n])
    )
      return m;
    if (i !== Ye && Be(i, n))
      return a[n] = 4, i[n];
    if (
      // global properties
      A = p.config.globalProperties, Be(A, n)
    )
      return A[n];
  },
  set({ _: t }, n, i) {
    const { data: o, setupState: l, ctx: u } = t;
    return Wu(l, n) ? (l[n] = i, !0) : o !== Ye && Be(o, n) ? (o[n] = i, !0) : Be(t.props, n) || n[0] === "$" && n.slice(1) in t ? !1 : (u[n] = i, !0);
  },
  has({
    _: { data: t, setupState: n, accessCache: i, ctx: o, appContext: l, propsOptions: u }
  }, a) {
    let h;
    return !!i[a] || t !== Ye && Be(t, a) || Wu(n, a) || (h = u[0]) && Be(h, a) || Be(o, a) || Be(po, a) || Be(l.config.globalProperties, a);
  },
  defineProperty(t, n, i) {
    return i.get != null ? t._.accessCache[n] = 0 : Be(i, "value") && this.set(t, n, i.value, null), Reflect.defineProperty(t, n, i);
  }
};
function Mh(t) {
  return _e(t) ? t.reduce(
    (n, i) => (n[i] = null, n),
    {}
  ) : t;
}
let af = !0;
function Y1(t) {
  const n = Kf(t), i = t.proxy, o = t.ctx;
  af = !1, n.beforeCreate && Nh(n.beforeCreate, t, "bc");
  const {
    // state
    data: l,
    computed: u,
    methods: a,
    watch: h,
    provide: p,
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
    beforeUnmount: q,
    destroyed: z,
    unmounted: L,
    render: te,
    renderTracked: xe,
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
  if (v && q1(v, o, null), a)
    for (const fe in a) {
      const ie = a[fe];
      ve(ie) && (o[fe] = ie.bind(i));
    }
  if (l) {
    const fe = l.call(i, i);
    ke(fe) && (t.data = Ff(fe));
  }
  if (af = !0, u)
    for (const fe in u) {
      const ie = u[fe], mt = ve(ie) ? ie.bind(i, i) : ve(ie.get) ? ie.get.bind(i, i) : $n, gt = !ve(ie) && ve(ie.set) ? ie.set.bind(i) : $n, Xe = zf({
        get: mt,
        set: gt
      });
      Object.defineProperty(o, fe, {
        enumerable: !0,
        configurable: !0,
        get: () => Xe.value,
        set: (ot) => Xe.value = ot
      });
    }
  if (h)
    for (const fe in h)
      sp(h[fe], o, i, fe);
  if (p) {
    const fe = ve(p) ? p.call(i) : p;
    Reflect.ownKeys(fe).forEach((ie) => {
      up(ie, fe[ie]);
    });
  }
  _ && Nh(_, t, "c");
  function me(fe, ie) {
    _e(ie) ? ie.forEach((mt) => fe(mt.bind(i))) : ie && fe(ie.bind(i));
  }
  if (me(P1, m), me($f, A), me(F1, S), me(M1, C), me(I1, I), me(D1, P), me(H1, Re), me(W1, xe), me(B1, Se), me(N1, q), me(ip, L), me(L1, ue), _e(Z))
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
  te && t.render === $n && (t.render = te), he != null && (t.inheritAttrs = he), Ce && (t.components = Ce), We && (t.directives = We), ue && tp(t);
}
function q1(t, n, i = $n) {
  _e(t) && (t = cf(t));
  for (const o in t) {
    const l = t[o];
    let u;
    ke(l) ? "default" in l ? u = sr(
      l.from || o,
      l.default,
      !0
    ) : u = sr(l.from || o) : u = sr(l), it(u) ? Object.defineProperty(n, o, {
      enumerable: !0,
      configurable: !0,
      get: () => u.value,
      set: (a) => u.value = a
    }) : n[o] = u;
  }
}
function Nh(t, n, i) {
  Yn(
    _e(t) ? t.map((o) => o.bind(n.proxy)) : t.bind(n.proxy),
    n,
    i
  );
}
function sp(t, n, i, o) {
  let l = o.includes(".") ? wp(i, o) : () => i[o];
  if (ct(t)) {
    const u = n[t];
    ve(u) && Nt(l, u);
  } else if (ve(t))
    Nt(l, t.bind(i));
  else if (ke(t))
    if (_e(t))
      t.forEach((u) => sp(u, n, i, o));
    else {
      const u = ve(t.handler) ? t.handler.bind(i) : n[t.handler];
      ve(u) && Nt(l, u, t);
    }
}
function Kf(t) {
  const n = t.type, { mixins: i, extends: o } = n, {
    mixins: l,
    optionsCache: u,
    config: { optionMergeStrategies: a }
  } = t.appContext, h = u.get(n);
  let p;
  return h ? p = h : !l.length && !i && !o ? p = n : (p = {}, l.length && l.forEach(
    (v) => Zs(p, v, a, !0)
  ), Zs(p, n, a)), ke(n) && u.set(n, p), p;
}
function Zs(t, n, i, o = !1) {
  const { mixins: l, extends: u } = n;
  u && Zs(t, u, i, !0), l && l.forEach(
    (a) => Zs(t, a, i, !0)
  );
  for (const a in n)
    if (!(o && a === "expose")) {
      const h = z1[a] || i && i[a];
      t[a] = h ? h(t[a], n[a]) : n[a];
    }
  return t;
}
const z1 = {
  data: Lh,
  props: Bh,
  emits: Bh,
  // objects
  methods: so,
  computed: so,
  // lifecycle
  beforeCreate: $t,
  created: $t,
  beforeMount: $t,
  mounted: $t,
  beforeUpdate: $t,
  updated: $t,
  beforeDestroy: $t,
  beforeUnmount: $t,
  destroyed: $t,
  unmounted: $t,
  activated: $t,
  deactivated: $t,
  errorCaptured: $t,
  serverPrefetch: $t,
  // assets
  components: so,
  directives: so,
  // watch
  watch: V1,
  // provide / inject
  provide: Lh,
  inject: X1
};
function Lh(t, n) {
  return n ? t ? function() {
    return Ct(
      ve(t) ? t.call(this, this) : t,
      ve(n) ? n.call(this, this) : n
    );
  } : n : t;
}
function X1(t, n) {
  return so(cf(t), cf(n));
}
function cf(t) {
  if (_e(t)) {
    const n = {};
    for (let i = 0; i < t.length; i++)
      n[t[i]] = t[i];
    return n;
  }
  return t;
}
function $t(t, n) {
  return t ? [...new Set([].concat(t, n))] : n;
}
function so(t, n) {
  return t ? Ct(/* @__PURE__ */ Object.create(null), t, n) : n;
}
function Bh(t, n) {
  return t ? _e(t) && _e(n) ? [.../* @__PURE__ */ new Set([...t, ...n])] : Ct(
    /* @__PURE__ */ Object.create(null),
    Mh(t),
    Mh(n ?? {})
  ) : n;
}
function V1(t, n) {
  if (!t)
    return n;
  if (!n)
    return t;
  const i = Ct(/* @__PURE__ */ Object.create(null), t);
  for (const o in n)
    i[o] = $t(t[o], n[o]);
  return i;
}
function lp() {
  return {
    app: null,
    config: {
      isNativeTag: Cw,
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
let J1 = 0;
function k1(t, n) {
  return function(o, l = null) {
    ve(o) || (o = Ct({}, o)), l != null && !ke(l) && (l = null);
    const u = lp(), a = /* @__PURE__ */ new WeakSet(), h = [];
    let p = !1;
    const v = u.app = {
      _uid: J1++,
      _component: o,
      _props: l,
      _container: null,
      _context: u,
      _instance: null,
      version: Dx,
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
        if (!p) {
          const S = v._ceVNode || En(o, l);
          return S.appContext = u, A === !0 ? A = "svg" : A === !1 && (A = void 0), m && n ? n(S, _) : t(S, _, A), p = !0, v._container = _, _.__vue_app__ = v, qf(S.component);
        }
      },
      onUnmount(_) {
        h.push(_);
      },
      unmount() {
        p && (Yn(
          h,
          v._instance,
          16
        ), t(null, v._container), delete v._container.__vue_app__);
      },
      provide(_, m) {
        return u.provides[_] = m, v;
      },
      runWithContext(_) {
        const m = Ni;
        Ni = v;
        try {
          return _();
        } finally {
          Ni = m;
        }
      }
    };
    return v;
  };
}
let Ni = null;
function up(t, n) {
  if (Ft) {
    let i = Ft.provides;
    const o = Ft.parent && Ft.parent.provides;
    o === i && (i = Ft.provides = Object.create(o)), i[t] = n;
  }
}
function sr(t, n, i = !1) {
  const o = Ft || qt;
  if (o || Ni) {
    const l = Ni ? Ni._context.provides : o ? o.parent == null ? o.vnode.appContext && o.vnode.appContext.provides : o.parent.provides : void 0;
    if (l && t in l)
      return l[t];
    if (arguments.length > 1)
      return i && ve(n) ? n.call(o && o.proxy) : n;
  }
}
const fp = {}, ap = () => Object.create(fp), cp = (t) => Object.getPrototypeOf(t) === fp;
function Z1(t, n, i, o = !1) {
  const l = {}, u = ap();
  t.propsDefaults = /* @__PURE__ */ Object.create(null), hp(t, n, l, u);
  for (const a in t.propsOptions[0])
    a in l || (l[a] = void 0);
  i ? t.props = o ? l : f1(l) : t.type.props ? t.props = l : t.props = u, t.attrs = u;
}
function Q1(t, n, i, o) {
  const {
    props: l,
    attrs: u,
    vnode: { patchFlag: a }
  } = t, h = Ne(l), [p] = t.propsOptions;
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
        if (vl(t.emitsOptions, A))
          continue;
        const S = n[A];
        if (p)
          if (Be(u, A))
            S !== u[A] && (u[A] = S, v = !0);
          else {
            const C = ni(A);
            l[C] = hf(
              p,
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
    hp(t, n, l, u) && (v = !0);
    let _;
    for (const m in h)
      (!n || // for camelCase
      !Be(n, m) && // it's possible the original props was passed in as kebab-case
      // and converted to camelCase (#955)
      ((_ = Mr(m)) === m || !Be(n, _))) && (p ? i && // for camelCase
      (i[m] !== void 0 || // for kebab-case
      i[_] !== void 0) && (l[m] = hf(
        p,
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
  v && or(t.attrs, "set", "");
}
function hp(t, n, i, o) {
  const [l, u] = t.propsOptions;
  let a = !1, h;
  if (n)
    for (let p in n) {
      if (co(p))
        continue;
      const v = n[p];
      let _;
      l && Be(l, _ = ni(p)) ? !u || !u.includes(_) ? i[_] = v : (h || (h = {}))[_] = v : vl(t.emitsOptions, p) || (!(p in o) || v !== o[p]) && (o[p] = v, a = !0);
    }
  if (u) {
    const p = Ne(i), v = h || Ye;
    for (let _ = 0; _ < u.length; _++) {
      const m = u[_];
      i[m] = hf(
        l,
        p,
        m,
        v[m],
        t,
        !Be(v, m)
      );
    }
  }
  return a;
}
function hf(t, n, i, o, l, u) {
  const a = t[i];
  if (a != null) {
    const h = Be(a, "default");
    if (h && o === void 0) {
      const p = a.default;
      if (a.type !== Function && !a.skipFactory && ve(p)) {
        const { propsDefaults: v } = l;
        if (i in v)
          o = v[i];
        else {
          const _ = Io(l);
          o = v[i] = p.call(
            null,
            n
          ), _();
        }
      } else
        o = p;
      l.ce && l.ce._setProp(i, o);
    }
    a[
      0
      /* shouldCast */
    ] && (u && !h ? o = !1 : a[
      1
      /* shouldCastTrue */
    ] && (o === "" || o === Mr(i)) && (o = !0));
  }
  return o;
}
const j1 = /* @__PURE__ */ new WeakMap();
function dp(t, n, i = !1) {
  const o = i ? j1 : n.propsCache, l = o.get(t);
  if (l)
    return l;
  const u = t.props, a = {}, h = [];
  let p = !1;
  if (!ve(t)) {
    const _ = (m) => {
      p = !0;
      const [A, S] = dp(m, n, !0);
      Ct(a, A), S && h.push(...S);
    };
    !i && n.mixins.length && n.mixins.forEach(_), t.extends && _(t.extends), t.mixins && t.mixins.forEach(_);
  }
  if (!u && !p)
    return ke(t) && o.set(t, Di), Di;
  if (_e(u))
    for (let _ = 0; _ < u.length; _++) {
      const m = ni(u[_]);
      Wh(m) && (a[m] = Ye);
    }
  else if (u)
    for (const _ in u) {
      const m = ni(_);
      if (Wh(m)) {
        const A = u[_], S = a[m] = _e(A) || ve(A) ? { type: A } : Ct({}, A), C = S.type;
        let I = !1, P = !0;
        if (_e(C))
          for (let B = 0; B < C.length; ++B) {
            const q = C[B], z = ve(q) && q.name;
            if (z === "Boolean") {
              I = !0;
              break;
            } else
              z === "String" && (P = !1);
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
function Wh(t) {
  return t[0] !== "$" && !co(t);
}
const pp = (t) => t[0] === "_" || t === "$stable", Gf = (t) => _e(t) ? t.map(Un) : [Un(t)], ex = (t, n, i) => {
  if (n._n)
    return n;
  const o = ep((...l) => Gf(n(...l)), i);
  return o._c = !1, o;
}, gp = (t, n, i) => {
  const o = t._ctx;
  for (const l in t) {
    if (pp(l))
      continue;
    const u = t[l];
    if (ve(u))
      n[l] = ex(l, u, o);
    else if (u != null) {
      const a = Gf(u);
      n[l] = () => a;
    }
  }
}, vp = (t, n) => {
  const i = Gf(n);
  t.slots.default = () => i;
}, _p = (t, n, i) => {
  for (const o in n)
    (i || o !== "_") && (t[o] = n[o]);
}, tx = (t, n, i) => {
  const o = t.slots = ap();
  if (t.vnode.shapeFlag & 32) {
    const l = n._;
    l ? (_p(o, n, i), i && Cd(o, "_", l, !0)) : gp(n, o);
  } else
    n && vp(t, n);
}, nx = (t, n, i) => {
  const { vnode: o, slots: l } = t;
  let u = !0, a = Ye;
  if (o.shapeFlag & 32) {
    const h = n._;
    h ? i && h === 1 ? u = !1 : _p(l, n, i) : (u = !n.$stable, gp(n, l)), a = n;
  } else
    n && (vp(t, n), a = { default: 1 });
  if (u)
    for (const h in l)
      !pp(h) && a[h] == null && delete l[h];
}, un = vx;
function rx(t) {
  return ix(t);
}
function ix(t, n) {
  const i = Od();
  i.__VUE__ = !0;
  const {
    insert: o,
    remove: l,
    patchProp: u,
    createElement: a,
    createText: h,
    createComment: p,
    setText: v,
    setElementText: _,
    parentNode: m,
    nextSibling: A,
    setScopeId: S = $n,
    insertStaticContent: C
  } = t, I = (y, x, D, $ = null, N = null, U = null, X = void 0, G = null, K = !!x.dynamicChildren) => {
    if (y === x)
      return;
    y && !to(y, x) && ($ = oi(y), ot(y, N, U, !0), y = null), x.patchFlag === -2 && (K = !1, x.dynamicChildren = null);
    const { type: H, ref: ne, shapeFlag: J } = x;
    switch (H) {
      case _l:
        P(y, x, D, $);
        break;
      case Pr:
        B(y, x, D, $);
        break;
      case $u:
        y == null && q(x, D, $, X);
        break;
      case an:
        Ce(
          y,
          x,
          D,
          $,
          N,
          U,
          X,
          G,
          K
        );
        break;
      default:
        J & 1 ? te(
          y,
          x,
          D,
          $,
          N,
          U,
          X,
          G,
          K
        ) : J & 6 ? We(
          y,
          x,
          D,
          $,
          N,
          U,
          X,
          G,
          K
        ) : (J & 64 || J & 128) && H.process(
          y,
          x,
          D,
          $,
          N,
          U,
          X,
          G,
          K,
          cr
        );
    }
    ne != null && N && uf(ne, y && y.ref, U, x || y, !x);
  }, P = (y, x, D, $) => {
    if (y == null)
      o(
        x.el = h(x.children),
        D,
        $
      );
    else {
      const N = x.el = y.el;
      x.children !== y.children && v(N, x.children);
    }
  }, B = (y, x, D, $) => {
    y == null ? o(
      x.el = p(x.children || ""),
      D,
      $
    ) : x.el = y.el;
  }, q = (y, x, D, $) => {
    [y.el, y.anchor] = C(
      y.children,
      x,
      D,
      $,
      y.el,
      y.anchor
    );
  }, z = ({ el: y, anchor: x }, D, $) => {
    let N;
    for (; y && y !== x; )
      N = A(y), o(y, D, $), y = N;
    o(x, D, $);
  }, L = ({ el: y, anchor: x }) => {
    let D;
    for (; y && y !== x; )
      D = A(y), l(y), y = D;
    l(x);
  }, te = (y, x, D, $, N, U, X, G, K) => {
    x.type === "svg" ? X = "svg" : x.type === "math" && (X = "mathml"), y == null ? xe(
      x,
      D,
      $,
      N,
      U,
      X,
      G,
      K
    ) : ue(
      y,
      x,
      N,
      U,
      X,
      G,
      K
    );
  }, xe = (y, x, D, $, N, U, X, G) => {
    let K, H;
    const { props: ne, shapeFlag: J, transition: Q, dirs: ae } = y;
    if (K = y.el = a(
      y.type,
      U,
      ne && ne.is,
      ne
    ), J & 8 ? _(K, y.children) : J & 16 && Re(
      y.children,
      K,
      null,
      $,
      N,
      Hu(y, U),
      X,
      G
    ), ae && zr(y, null, $, "created"), Se(K, y, y.scopeId, X, $), ne) {
      for (const Pe in ne)
        Pe !== "value" && !co(Pe) && u(K, Pe, null, ne[Pe], U, $);
      "value" in ne && u(K, "value", null, ne.value, U), (H = ne.onVnodeBeforeMount) && Bn(H, $, y);
    }
    ae && zr(y, null, $, "beforeMount");
    const ye = ox(N, Q);
    ye && Q.beforeEnter(K), o(K, x, D), ((H = ne && ne.onVnodeMounted) || ye || ae) && un(() => {
      H && Bn(H, $, y), ye && Q.enter(K), ae && zr(y, null, $, "mounted");
    }, N);
  }, Se = (y, x, D, $, N) => {
    if (D && S(y, D), $)
      for (let U = 0; U < $.length; U++)
        S(y, $[U]);
    if (N) {
      let U = N.subTree;
      if (x === U || Ep(U.type) && (U.ssContent === x || U.ssFallback === x)) {
        const X = N.vnode;
        Se(
          y,
          X,
          X.scopeId,
          X.slotScopeIds,
          N.parent
        );
      }
    }
  }, Re = (y, x, D, $, N, U, X, G, K = 0) => {
    for (let H = K; H < y.length; H++) {
      const ne = y[H] = G ? Tr(y[H]) : Un(y[H]);
      I(
        null,
        ne,
        x,
        D,
        $,
        N,
        U,
        X,
        G
      );
    }
  }, ue = (y, x, D, $, N, U, X) => {
    const G = x.el = y.el;
    let { patchFlag: K, dynamicChildren: H, dirs: ne } = x;
    K |= y.patchFlag & 16;
    const J = y.props || Ye, Q = x.props || Ye;
    let ae;
    if (D && Xr(D, !1), (ae = Q.onVnodeBeforeUpdate) && Bn(ae, D, x, y), ne && zr(x, y, D, "beforeUpdate"), D && Xr(D, !0), (J.innerHTML && Q.innerHTML == null || J.textContent && Q.textContent == null) && _(G, ""), H ? Z(
      y.dynamicChildren,
      H,
      G,
      D,
      $,
      Hu(x, N),
      U
    ) : X || ie(
      y,
      x,
      G,
      null,
      D,
      $,
      Hu(x, N),
      U,
      !1
    ), K > 0) {
      if (K & 16)
        he(G, J, Q, D, N);
      else if (K & 2 && J.class !== Q.class && u(G, "class", null, Q.class, N), K & 4 && u(G, "style", J.style, Q.style, N), K & 8) {
        const ye = x.dynamicProps;
        for (let Pe = 0; Pe < ye.length; Pe++) {
          const Fe = ye[Pe], yt = J[Fe], lt = Q[Fe];
          (lt !== yt || Fe === "value") && u(G, Fe, yt, lt, N, D);
        }
      }
      K & 1 && y.children !== x.children && _(G, x.children);
    } else
      !X && H == null && he(G, J, Q, D, N);
    ((ae = Q.onVnodeUpdated) || ne) && un(() => {
      ae && Bn(ae, D, x, y), ne && zr(x, y, D, "updated");
    }, $);
  }, Z = (y, x, D, $, N, U, X) => {
    for (let G = 0; G < x.length; G++) {
      const K = y[G], H = x[G], ne = (
        // oldVNode may be an errored async setup() component inside Suspense
        // which will not have a mounted element
        K.el && // - In the case of a Fragment, we need to provide the actual parent
        // of the Fragment itself so it can move its children.
        (K.type === an || // - In the case of different nodes, there is going to be a replacement
        // which also requires the correct parent container
        !to(K, H) || // - In the case of a component, it could contain anything.
        K.shapeFlag & 70) ? m(K.el) : (
          // In other cases, the parent container is not actually used so we
          // just pass the block element here to avoid a DOM parentNode call.
          D
        )
      );
      I(
        K,
        H,
        ne,
        null,
        $,
        N,
        U,
        X,
        !0
      );
    }
  }, he = (y, x, D, $, N) => {
    if (x !== D) {
      if (x !== Ye)
        for (const U in x)
          !co(U) && !(U in D) && u(
            y,
            U,
            x[U],
            null,
            N,
            $
          );
      for (const U in D) {
        if (co(U))
          continue;
        const X = D[U], G = x[U];
        X !== G && U !== "value" && u(y, U, G, X, N, $);
      }
      "value" in D && u(y, "value", x.value, D.value, N);
    }
  }, Ce = (y, x, D, $, N, U, X, G, K) => {
    const H = x.el = y ? y.el : h(""), ne = x.anchor = y ? y.anchor : h("");
    let { patchFlag: J, dynamicChildren: Q, slotScopeIds: ae } = x;
    ae && (G = G ? G.concat(ae) : ae), y == null ? (o(H, D, $), o(ne, D, $), Re(
      // #10007
      // such fragment like `<></>` will be compiled into
      // a fragment which doesn't have a children.
      // In this case fallback to an empty array
      x.children || [],
      D,
      ne,
      N,
      U,
      X,
      G,
      K
    )) : J > 0 && J & 64 && Q && // #2715 the previous fragment could've been a BAILed one as a result
    // of renderSlot() with no valid children
    y.dynamicChildren ? (Z(
      y.dynamicChildren,
      Q,
      D,
      N,
      U,
      X,
      G
    ), // #2080 if the stable fragment has a key, it's a <template v-for> that may
    //  get moved around. Make sure all root level vnodes inherit el.
    // #2134 or if it's a component root, it may also get moved around
    // as the component is being moved.
    (x.key != null || N && x === N.subTree) && mp(
      y,
      x,
      !0
      /* shallow */
    )) : ie(
      y,
      x,
      D,
      ne,
      N,
      U,
      X,
      G,
      K
    );
  }, We = (y, x, D, $, N, U, X, G, K) => {
    x.slotScopeIds = G, y == null ? x.shapeFlag & 512 ? N.ctx.activate(
      x,
      D,
      $,
      X,
      K
    ) : pt(
      x,
      D,
      $,
      N,
      U,
      X,
      K
    ) : Ot(y, x, K);
  }, pt = (y, x, D, $, N, U, X) => {
    const G = y.component = Ex(
      y,
      $,
      N
    );
    if (np(y) && (G.ctx.renderer = cr), Tx(G, !1, X), G.asyncDep) {
      if (N && N.registerDep(G, me, X), !y.el) {
        const K = G.subTree = En(Pr);
        B(null, K, x, D);
      }
    } else
      me(
        G,
        y,
        x,
        D,
        N,
        U,
        X
      );
  }, Ot = (y, x, D) => {
    const $ = x.component = y.component;
    if (px(y, x, D))
      if ($.asyncDep && !$.asyncResolved) {
        fe($, x, D);
        return;
      } else
        $.next = x, $.update();
    else
      x.el = y.el, $.vnode = x;
  }, me = (y, x, D, $, N, U, X) => {
    const G = () => {
      if (y.isMounted) {
        let { next: J, bu: Q, u: ae, parent: ye, vnode: Pe } = y;
        {
          const wt = bp(y);
          if (wt) {
            J && (J.el = Pe.el, fe(y, J, X)), wt.asyncDep.then(() => {
              y.isUnmounted || G();
            });
            return;
          }
        }
        let Fe = J, yt;
        Xr(y, !1), J ? (J.el = Pe.el, fe(y, J, X)) : J = Pe, Q && Fu(Q), (yt = J.props && J.props.onVnodeBeforeUpdate) && Bn(yt, ye, J, Pe), Xr(y, !0);
        const lt = Uu(y), Bt = y.subTree;
        y.subTree = lt, I(
          Bt,
          lt,
          // parent may have changed if it's in a teleport
          m(Bt.el),
          // anchor may have changed if it's in a fragment
          oi(Bt),
          y,
          N,
          U
        ), J.el = lt.el, Fe === null && gx(y, lt.el), ae && un(ae, N), (yt = J.props && J.props.onVnodeUpdated) && un(
          () => Bn(yt, ye, J, Pe),
          N
        );
      } else {
        let J;
        const { el: Q, props: ae } = x, { bm: ye, m: Pe, parent: Fe, root: yt, type: lt } = y, Bt = Mi(x);
        if (Xr(y, !1), ye && Fu(ye), !Bt && (J = ae && ae.onVnodeBeforeMount) && Bn(J, Fe, x), Xr(y, !0), Q && dr) {
          const wt = () => {
            y.subTree = Uu(y), dr(
              Q,
              y.subTree,
              y,
              N,
              null
            );
          };
          Bt && lt.__asyncHydrate ? lt.__asyncHydrate(
            Q,
            y,
            wt
          ) : wt();
        } else {
          yt.ce && yt.ce._injectChildStyle(lt);
          const wt = y.subTree = Uu(y);
          I(
            null,
            wt,
            D,
            $,
            y,
            N,
            U
          ), x.el = wt.el;
        }
        if (Pe && un(Pe, N), !Bt && (J = ae && ae.onVnodeMounted)) {
          const wt = x;
          un(
            () => Bn(J, Fe, wt),
            N
          );
        }
        (x.shapeFlag & 256 || Fe && Mi(Fe.vnode) && Fe.vnode.shapeFlag & 256) && y.a && un(y.a, N), y.isMounted = !0, x = D = $ = null;
      }
    };
    y.scope.on();
    const K = y.effect = new Fd(G);
    y.scope.off();
    const H = y.update = K.run.bind(K), ne = y.job = K.runIfDirty.bind(K);
    ne.i = y, ne.id = y.uid, K.scheduler = () => Wf(ne), Xr(y, !0), H();
  }, fe = (y, x, D) => {
    x.component = y;
    const $ = y.vnode.props;
    y.vnode = x, y.next = null, Q1(y, x.props, $, D), nx(y, x.children, D), Nr(), Fh(y), Lr();
  }, ie = (y, x, D, $, N, U, X, G, K = !1) => {
    const H = y && y.children, ne = y ? y.shapeFlag : 0, J = x.children, { patchFlag: Q, shapeFlag: ae } = x;
    if (Q > 0) {
      if (Q & 128) {
        gt(
          H,
          J,
          D,
          $,
          N,
          U,
          X,
          G,
          K
        );
        return;
      } else if (Q & 256) {
        mt(
          H,
          J,
          D,
          $,
          N,
          U,
          X,
          G,
          K
        );
        return;
      }
    }
    ae & 8 ? (ne & 16 && Fn(H, N, U), J !== H && _(D, J)) : ne & 16 ? ae & 16 ? gt(
      H,
      J,
      D,
      $,
      N,
      U,
      X,
      G,
      K
    ) : Fn(H, N, U, !0) : (ne & 8 && _(D, ""), ae & 16 && Re(
      J,
      D,
      $,
      N,
      U,
      X,
      G,
      K
    ));
  }, mt = (y, x, D, $, N, U, X, G, K) => {
    y = y || Di, x = x || Di;
    const H = y.length, ne = x.length, J = Math.min(H, ne);
    let Q;
    for (Q = 0; Q < J; Q++) {
      const ae = x[Q] = K ? Tr(x[Q]) : Un(x[Q]);
      I(
        y[Q],
        ae,
        D,
        null,
        N,
        U,
        X,
        G,
        K
      );
    }
    H > ne ? Fn(
      y,
      N,
      U,
      !0,
      !1,
      J
    ) : Re(
      x,
      D,
      $,
      N,
      U,
      X,
      G,
      K,
      J
    );
  }, gt = (y, x, D, $, N, U, X, G, K) => {
    let H = 0;
    const ne = x.length;
    let J = y.length - 1, Q = ne - 1;
    for (; H <= J && H <= Q; ) {
      const ae = y[H], ye = x[H] = K ? Tr(x[H]) : Un(x[H]);
      if (to(ae, ye))
        I(
          ae,
          ye,
          D,
          null,
          N,
          U,
          X,
          G,
          K
        );
      else
        break;
      H++;
    }
    for (; H <= J && H <= Q; ) {
      const ae = y[J], ye = x[Q] = K ? Tr(x[Q]) : Un(x[Q]);
      if (to(ae, ye))
        I(
          ae,
          ye,
          D,
          null,
          N,
          U,
          X,
          G,
          K
        );
      else
        break;
      J--, Q--;
    }
    if (H > J) {
      if (H <= Q) {
        const ae = Q + 1, ye = ae < ne ? x[ae].el : $;
        for (; H <= Q; )
          I(
            null,
            x[H] = K ? Tr(x[H]) : Un(x[H]),
            D,
            ye,
            N,
            U,
            X,
            G,
            K
          ), H++;
      }
    } else if (H > Q)
      for (; H <= J; )
        ot(y[H], N, U, !0), H++;
    else {
      const ae = H, ye = H, Pe = /* @__PURE__ */ new Map();
      for (H = ye; H <= Q; H++) {
        const xt = x[H] = K ? Tr(x[H]) : Un(x[H]);
        xt.key != null && Pe.set(xt.key, H);
      }
      let Fe, yt = 0;
      const lt = Q - ye + 1;
      let Bt = !1, wt = 0;
      const Xn = new Array(lt);
      for (H = 0; H < lt; H++)
        Xn[H] = 0;
      for (H = ae; H <= J; H++) {
        const xt = y[H];
        if (yt >= lt) {
          ot(xt, N, U, !0);
          continue;
        }
        let zt;
        if (xt.key != null)
          zt = Pe.get(xt.key);
        else
          for (Fe = ye; Fe <= Q; Fe++)
            if (Xn[Fe - ye] === 0 && to(xt, x[Fe])) {
              zt = Fe;
              break;
            }
        zt === void 0 ? ot(xt, N, U, !0) : (Xn[zt - ye] = H + 1, zt >= wt ? wt = zt : Bt = !0, I(
          xt,
          x[zt],
          D,
          null,
          N,
          U,
          X,
          G,
          K
        ), yt++);
      }
      const si = Bt ? sx(Xn) : Di;
      for (Fe = si.length - 1, H = lt - 1; H >= 0; H--) {
        const xt = ye + H, zt = x[xt], Po = xt + 1 < ne ? x[xt + 1].el : $;
        Xn[H] === 0 ? I(
          null,
          zt,
          D,
          Po,
          N,
          U,
          X,
          G,
          K
        ) : Bt && (Fe < 0 || H !== si[Fe] ? Xe(zt, D, Po, 2) : Fe--);
      }
    }
  }, Xe = (y, x, D, $, N = null) => {
    const { el: U, type: X, transition: G, children: K, shapeFlag: H } = y;
    if (H & 6) {
      Xe(y.component.subTree, x, D, $);
      return;
    }
    if (H & 128) {
      y.suspense.move(x, D, $);
      return;
    }
    if (H & 64) {
      X.move(y, x, D, cr);
      return;
    }
    if (X === an) {
      o(U, x, D);
      for (let J = 0; J < K.length; J++)
        Xe(K[J], x, D, $);
      o(y.anchor, x, D);
      return;
    }
    if (X === $u) {
      z(y, x, D);
      return;
    }
    if ($ !== 2 && H & 1 && G)
      if ($ === 0)
        G.beforeEnter(U), o(U, x, D), un(() => G.enter(U), N);
      else {
        const { leave: J, delayLeave: Q, afterLeave: ae } = G, ye = () => o(U, x, D), Pe = () => {
          J(U, () => {
            ye(), ae && ae();
          });
        };
        Q ? Q(U, ye, Pe) : Pe();
      }
    else
      o(U, x, D);
  }, ot = (y, x, D, $ = !1, N = !1) => {
    const {
      type: U,
      props: X,
      ref: G,
      children: K,
      dynamicChildren: H,
      shapeFlag: ne,
      patchFlag: J,
      dirs: Q,
      cacheIndex: ae
    } = y;
    if (J === -2 && (N = !1), G != null && uf(G, null, D, y, !0), ae != null && (x.renderCache[ae] = void 0), ne & 256) {
      x.ctx.deactivate(y);
      return;
    }
    const ye = ne & 1 && Q, Pe = !Mi(y);
    let Fe;
    if (Pe && (Fe = X && X.onVnodeBeforeUnmount) && Bn(Fe, x, y), ne & 6)
      bt(y.component, D, $);
    else {
      if (ne & 128) {
        y.suspense.unmount(D, $);
        return;
      }
      ye && zr(y, null, x, "beforeUnmount"), ne & 64 ? y.type.remove(
        y,
        x,
        D,
        cr,
        $
      ) : H && // #5154
      // when v-once is used inside a block, setBlockTracking(-1) marks the
      // parent block with hasOnce: true
      // so that it doesn't take the fast path during unmount - otherwise
      // components nested in v-once are never unmounted.
      !H.hasOnce && // #1153: fast path should not be taken for non-stable (v-for) fragments
      (U !== an || J > 0 && J & 64) ? Fn(
        H,
        x,
        D,
        !1,
        !0
      ) : (U === an && J & 384 || !N && ne & 16) && Fn(K, x, D), $ && hn(y);
    }
    (Pe && (Fe = X && X.onVnodeUnmounted) || ye) && un(() => {
      Fe && Bn(Fe, x, y), ye && zr(y, null, x, "unmounted");
    }, D);
  }, hn = (y) => {
    const { type: x, el: D, anchor: $, transition: N } = y;
    if (x === an) {
      st(D, $);
      return;
    }
    if (x === $u) {
      L(y);
      return;
    }
    const U = () => {
      l(D), N && !N.persisted && N.afterLeave && N.afterLeave();
    };
    if (y.shapeFlag & 1 && N && !N.persisted) {
      const { leave: X, delayLeave: G } = N, K = () => X(D, U);
      G ? G(y.el, U, K) : K();
    } else
      U();
  }, st = (y, x) => {
    let D;
    for (; y !== x; )
      D = A(y), l(y), y = D;
    l(x);
  }, bt = (y, x, D) => {
    const { bum: $, scope: N, job: U, subTree: X, um: G, m: K, a: H } = y;
    Hh(K), Hh(H), $ && Fu($), N.stop(), U && (U.flags |= 8, ot(X, y, x, D)), G && un(G, x), un(() => {
      y.isUnmounted = !0;
    }, x), x && x.pendingBranch && !x.isUnmounted && y.asyncDep && !y.asyncResolved && y.suspenseId === x.pendingId && (x.deps--, x.deps === 0 && x.resolve());
  }, Fn = (y, x, D, $ = !1, N = !1, U = 0) => {
    for (let X = U; X < y.length; X++)
      ot(y[X], x, D, $, N);
  }, oi = (y) => {
    if (y.shapeFlag & 6)
      return oi(y.component.subTree);
    if (y.shapeFlag & 128)
      return y.suspense.next();
    const x = A(y.anchor || y.el), D = x && x[C1];
    return D ? A(D) : x;
  };
  let zn = !1;
  const ar = (y, x, D) => {
    y == null ? x._vnode && ot(x._vnode, null, null, !0) : I(
      x._vnode || null,
      y,
      x,
      null,
      null,
      null,
      D
    ), x._vnode = y, zn || (zn = !0, Fh(), Zd(), zn = !1);
  }, cr = {
    p: I,
    um: ot,
    m: Xe,
    r: hn,
    mt: pt,
    mc: Re,
    pc: ie,
    pbc: Z,
    n: oi,
    o: t
  };
  let hr, dr;
  return n && ([hr, dr] = n(
    cr
  )), {
    render: ar,
    hydrate: hr,
    createApp: k1(ar, hr)
  };
}
function Hu({ type: t, props: n }, i) {
  return i === "svg" && t === "foreignObject" || i === "mathml" && t === "annotation-xml" && n && n.encoding && n.encoding.includes("html") ? void 0 : i;
}
function Xr({ effect: t, job: n }, i) {
  i ? (t.flags |= 32, n.flags |= 4) : (t.flags &= -33, n.flags &= -5);
}
function ox(t, n) {
  return (!t || t && !t.pendingBranch) && n && !n.persisted;
}
function mp(t, n, i = !1) {
  const o = t.children, l = n.children;
  if (_e(o) && _e(l))
    for (let u = 0; u < o.length; u++) {
      const a = o[u];
      let h = l[u];
      h.shapeFlag & 1 && !h.dynamicChildren && ((h.patchFlag <= 0 || h.patchFlag === 32) && (h = l[u] = Tr(l[u]), h.el = a.el), !i && h.patchFlag !== -2 && mp(a, h)), h.type === _l && (h.el = a.el);
    }
}
function sx(t) {
  const n = t.slice(), i = [0];
  let o, l, u, a, h;
  const p = t.length;
  for (o = 0; o < p; o++) {
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
function bp(t) {
  const n = t.subTree.component;
  if (n)
    return n.asyncDep && !n.asyncResolved ? n : bp(n);
}
function Hh(t) {
  if (t)
    for (let n = 0; n < t.length; n++)
      t[n].flags |= 8;
}
const lx = Symbol.for("v-scx"), ux = () => sr(lx);
function Nt(t, n, i) {
  return yp(t, n, i);
}
function yp(t, n, i = Ye) {
  const { immediate: o, deep: l, flush: u, once: a } = i, h = Ct({}, i);
  let p;
  if (ml)
    if (u === "sync") {
      const A = ux();
      p = A.__watcherHandles || (A.__watcherHandles = []);
    } else if (!n || o)
      h.once = !0;
    else {
      const A = () => {
      };
      return A.stop = $n, A.resume = $n, A.pause = $n, A;
    }
  const v = Ft;
  h.call = (A, S, C) => Yn(A, v, S, C);
  let _ = !1;
  u === "post" ? h.scheduler = (A) => {
    un(A, v && v.suspense);
  } : u !== "sync" && (_ = !0, h.scheduler = (A, S) => {
    S ? A() : Wf(A);
  }), h.augmentJob = (A) => {
    n && (A.flags |= 4), _ && (A.flags |= 2, v && (A.id = v.uid, A.i = v));
  };
  const m = E1(t, n, h);
  return p && p.push(m), m;
}
function fx(t, n, i) {
  const o = this.proxy, l = ct(t) ? t.includes(".") ? wp(o, t) : () => o[t] : t.bind(o, o);
  let u;
  ve(n) ? u = n : (u = n.handler, i = n);
  const a = Io(this), h = yp(l, u.bind(o), i);
  return a(), h;
}
function wp(t, n) {
  const i = n.split(".");
  return () => {
    let o = t;
    for (let l = 0; l < i.length && o; l++)
      o = o[i[l]];
    return o;
  };
}
const ax = (t, n) => n === "modelValue" || n === "model-value" ? t.modelModifiers : t[`${n}Modifiers`] || t[`${ni(n)}Modifiers`] || t[`${Mr(n)}Modifiers`];
function cx(t, n, ...i) {
  if (t.isUnmounted)
    return;
  const o = t.vnode.props || Ye;
  let l = i;
  const u = n.startsWith("update:"), a = u && ax(o, n.slice(7));
  a && (a.trim && (l = i.map((_) => ct(_) ? _.trim() : _)), a.number && (l = i.map(Pw)));
  let h, p = o[h = Pu(n)] || // also try camelCase event handler (#2249)
  o[h = Pu(ni(n))];
  !p && u && (p = o[h = Pu(Mr(n))]), p && Yn(
    p,
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
    t.emitted[h] = !0, Yn(
      v,
      t,
      6,
      l
    );
  }
}
function xp(t, n, i = !1) {
  const o = n.emitsCache, l = o.get(t);
  if (l !== void 0)
    return l;
  const u = t.emits;
  let a = {}, h = !1;
  if (!ve(t)) {
    const p = (v) => {
      const _ = xp(v, n, !0);
      _ && (h = !0, Ct(a, _));
    };
    !i && n.mixins.length && n.mixins.forEach(p), t.extends && p(t.extends), t.mixins && t.mixins.forEach(p);
  }
  return !u && !h ? (ke(t) && o.set(t, null), null) : (_e(u) ? u.forEach((p) => a[p] = null) : Ct(a, u), ke(t) && o.set(t, a), a);
}
function vl(t, n) {
  return !t || !ll(n) ? !1 : (n = n.slice(2).replace(/Once$/, ""), Be(t, n[0].toLowerCase() + n.slice(1)) || Be(t, Mr(n)) || Be(t, n));
}
function Uu(t) {
  const {
    type: n,
    vnode: i,
    proxy: o,
    withProxy: l,
    propsOptions: [u],
    slots: a,
    attrs: h,
    emit: p,
    render: v,
    renderCache: _,
    props: m,
    data: A,
    setupState: S,
    ctx: C,
    inheritAttrs: I
  } = t, P = ks(t);
  let B, q;
  try {
    if (i.shapeFlag & 4) {
      const L = l || o, te = L;
      B = Un(
        v.call(
          te,
          L,
          _,
          m,
          S,
          A,
          C
        )
      ), q = h;
    } else {
      const L = n;
      B = Un(
        L.length > 1 ? L(
          m,
          { attrs: h, slots: a, emit: p }
        ) : L(
          m,
          null
        )
      ), q = n.props ? h : hx(h);
    }
  } catch (L) {
    go.length = 0, pl(L, t, 1), B = En(Pr);
  }
  let z = B;
  if (q && I !== !1) {
    const L = Object.keys(q), { shapeFlag: te } = z;
    L.length && te & 7 && (u && L.some(Sf) && (q = dx(
      q,
      u
    )), z = Li(z, q, !1, !0));
  }
  return i.dirs && (z = Li(z, null, !1, !0), z.dirs = z.dirs ? z.dirs.concat(i.dirs) : i.dirs), i.transition && Hf(z, i.transition), B = z, ks(P), B;
}
const hx = (t) => {
  let n;
  for (const i in t)
    (i === "class" || i === "style" || ll(i)) && ((n || (n = {}))[i] = t[i]);
  return n;
}, dx = (t, n) => {
  const i = {};
  for (const o in t)
    (!Sf(o) || !(o.slice(9) in n)) && (i[o] = t[o]);
  return i;
};
function px(t, n, i) {
  const { props: o, children: l, component: u } = t, { props: a, children: h, patchFlag: p } = n, v = u.emitsOptions;
  if (n.dirs || n.transition)
    return !0;
  if (i && p >= 0) {
    if (p & 1024)
      return !0;
    if (p & 16)
      return o ? Uh(o, a, v) : !!a;
    if (p & 8) {
      const _ = n.dynamicProps;
      for (let m = 0; m < _.length; m++) {
        const A = _[m];
        if (a[A] !== o[A] && !vl(v, A))
          return !0;
      }
    }
  } else
    return (l || h) && (!h || !h.$stable) ? !0 : o === a ? !1 : o ? a ? Uh(o, a, v) : !0 : !!a;
  return !1;
}
function Uh(t, n, i) {
  const o = Object.keys(n);
  if (o.length !== Object.keys(t).length)
    return !0;
  for (let l = 0; l < o.length; l++) {
    const u = o[l];
    if (n[u] !== t[u] && !vl(i, u))
      return !0;
  }
  return !1;
}
function gx({ vnode: t, parent: n }, i) {
  for (; n; ) {
    const o = n.subTree;
    if (o.suspense && o.suspense.activeBranch === t && (o.el = t.el), o === t)
      (t = n.vnode).el = i, n = n.parent;
    else
      break;
  }
}
const Ep = (t) => t.__isSuspense;
function vx(t, n) {
  n && n.pendingBranch ? _e(t) ? n.effects.push(...t) : n.effects.push(t) : A1(t);
}
const an = Symbol.for("v-fgt"), _l = Symbol.for("v-txt"), Pr = Symbol.for("v-cmt"), $u = Symbol.for("v-stc"), go = [];
let cn = null;
function Kn(t = !1) {
  go.push(cn = t ? null : []);
}
function _x() {
  go.pop(), cn = go[go.length - 1] || null;
}
let Co = 1;
function $h(t) {
  Co += t, t < 0 && cn && (cn.hasOnce = !0);
}
function Sp(t) {
  return t.dynamicChildren = Co > 0 ? cn || Di : null, _x(), Co > 0 && cn && cn.push(t), t;
}
function jr(t, n, i, o, l, u) {
  return Sp(
    Hn(
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
function df(t, n, i, o, l) {
  return Sp(
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
function Tp(t) {
  return t ? t.__v_isVNode === !0 : !1;
}
function to(t, n) {
  return t.type === n.type && t.key === n.key;
}
const Ap = ({ key: t }) => t ?? null, Us = ({
  ref: t,
  ref_key: n,
  ref_for: i
}) => (typeof t == "number" && (t = "" + t), t != null ? ct(t) || it(t) || ve(t) ? { i: qt, r: t, k: n, f: !!i } : t : null);
function Hn(t, n = null, i = null, o = 0, l = null, u = t === an ? 0 : 1, a = !1, h = !1) {
  const p = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: t,
    props: n,
    key: n && Ap(n),
    ref: n && Us(n),
    scopeId: jd,
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
    ctx: qt
  };
  return h ? (Yf(p, i), u & 128 && t.normalize(p)) : i && (p.shapeFlag |= ct(i) ? 8 : 16), Co > 0 && // avoid a block node from tracking itself
  !a && // has current parent block
  cn && // presence of a patch flag indicates this node needs patching on updates.
  // component nodes also should always be patched, because even if the
  // component doesn't need to update, it needs to persist the instance on to
  // the next vnode so that it can be properly unmounted later.
  (p.patchFlag > 0 || u & 6) && // the EVENTS flag is only for hydration and if it is the only flag, the
  // vnode should not be considered dynamic due to handler caching.
  p.patchFlag !== 32 && cn.push(p), p;
}
const En = mx;
function mx(t, n = null, i = null, o = 0, l = null, u = !1) {
  if ((!t || t === U1) && (t = Pr), Tp(t)) {
    const h = Li(
      t,
      n,
      !0
      /* mergeRef: true */
    );
    return i && Yf(h, i), Co > 0 && !u && cn && (h.shapeFlag & 6 ? cn[cn.indexOf(t)] = h : cn.push(h)), h.patchFlag = -2, h;
  }
  if (Ix(t) && (t = t.__vccOpts), n) {
    n = bx(n);
    let { class: h, style: p } = n;
    h && !ct(h) && (n.class = al(h)), ke(p) && (Nf(p) && !_e(p) && (p = Ct({}, p)), n.style = Cf(p));
  }
  const a = ct(t) ? 1 : Ep(t) ? 128 : O1(t) ? 64 : ke(t) ? 4 : ve(t) ? 2 : 0;
  return Hn(
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
function bx(t) {
  return t ? Nf(t) || cp(t) ? Ct({}, t) : t : null;
}
function Li(t, n, i = !1, o = !1) {
  const { props: l, ref: u, patchFlag: a, children: h, transition: p } = t, v = n ? yx(l || {}, n) : l, _ = {
    __v_isVNode: !0,
    __v_skip: !0,
    type: t.type,
    props: v,
    key: v && Ap(v),
    ref: n && n.ref ? (
      // #2078 in the case of <component :is="vnode" ref="extra"/>
      // if the vnode itself already has a ref, cloneVNode will need to merge
      // the refs so the single vnode can be set on multiple refs
      i && u ? _e(u) ? u.concat(Us(n)) : [u, Us(n)] : Us(n)
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
    patchFlag: n && t.type !== an ? a === -1 ? 16 : a | 16 : a,
    dynamicProps: t.dynamicProps,
    dynamicChildren: t.dynamicChildren,
    appContext: t.appContext,
    dirs: t.dirs,
    transition: p,
    // These should technically only be non-null on mounted VNodes. However,
    // they *should* be copied for kept-alive vnodes. So we just always copy
    // them since them being non-null during a mount doesn't affect the logic as
    // they will simply be overwritten.
    component: t.component,
    suspense: t.suspense,
    ssContent: t.ssContent && Li(t.ssContent),
    ssFallback: t.ssFallback && Li(t.ssFallback),
    el: t.el,
    anchor: t.anchor,
    ctx: t.ctx,
    ce: t.ce
  };
  return p && o && Hf(
    _,
    p.clone(_)
  ), _;
}
function Cp(t = " ", n = 0) {
  return En(_l, null, t, n);
}
function Kh(t = "", n = !1) {
  return n ? (Kn(), df(Pr, null, t)) : En(Pr, null, t);
}
function Un(t) {
  return t == null || typeof t == "boolean" ? En(Pr) : _e(t) ? En(
    an,
    null,
    // #3666, avoid reference pollution when reusing vnode
    t.slice()
  ) : typeof t == "object" ? Tr(t) : En(_l, null, String(t));
}
function Tr(t) {
  return t.el === null && t.patchFlag !== -1 || t.memo ? t : Li(t);
}
function Yf(t, n) {
  let i = 0;
  const { shapeFlag: o } = t;
  if (n == null)
    n = null;
  else if (_e(n))
    i = 16;
  else if (typeof n == "object")
    if (o & 65) {
      const l = n.default;
      l && (l._c && (l._d = !1), Yf(t, l()), l._c && (l._d = !0));
      return;
    } else {
      i = 32;
      const l = n._;
      !l && !cp(n) ? n._ctx = qt : l === 3 && qt && (qt.slots._ === 1 ? n._ = 1 : (n._ = 2, t.patchFlag |= 1024));
    }
  else
    ve(n) ? (n = { default: n, _ctx: qt }, i = 32) : (n = String(n), o & 64 ? (i = 16, n = [Cp(n)]) : i = 8);
  t.children = n, t.shapeFlag |= i;
}
function yx(...t) {
  const n = {};
  for (let i = 0; i < t.length; i++) {
    const o = t[i];
    for (const l in o)
      if (l === "class")
        n.class !== o.class && (n.class = al([n.class, o.class]));
      else if (l === "style")
        n.style = Cf([n.style, o.style]);
      else if (ll(l)) {
        const u = n[l], a = o[l];
        a && u !== a && !(_e(u) && u.includes(a)) && (n[l] = u ? [].concat(u, a) : a);
      } else
        l !== "" && (n[l] = o[l]);
  }
  return n;
}
function Bn(t, n, i, o = null) {
  Yn(t, n, 7, [
    i,
    o
  ]);
}
const wx = lp();
let xx = 0;
function Ex(t, n, i) {
  const o = t.type, l = (n ? n.appContext : t.appContext) || wx, u = {
    uid: xx++,
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
    scope: new Hw(
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
    propsOptions: dp(o, l),
    emitsOptions: xp(o, l),
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
  return u.ctx = { _: u }, u.root = n ? n.root : u, u.emit = cx.bind(null, u), t.ce && t.ce(u), u;
}
let Ft = null;
const Sx = () => Ft || qt;
let Qs, pf;
{
  const t = Od(), n = (i, o) => {
    let l;
    return (l = t[i]) || (l = t[i] = []), l.push(o), (u) => {
      l.length > 1 ? l.forEach((a) => a(u)) : l[0](u);
    };
  };
  Qs = n(
    "__VUE_INSTANCE_SETTERS__",
    (i) => Ft = i
  ), pf = n(
    "__VUE_SSR_SETTERS__",
    (i) => ml = i
  );
}
const Io = (t) => {
  const n = Ft;
  return Qs(t), t.scope.on(), () => {
    t.scope.off(), Qs(n);
  };
}, Gh = () => {
  Ft && Ft.scope.off(), Qs(null);
};
function Op(t) {
  return t.vnode.shapeFlag & 4;
}
let ml = !1;
function Tx(t, n = !1, i = !1) {
  n && pf(n);
  const { props: o, children: l } = t.vnode, u = Op(t);
  Z1(t, o, u, n), tx(t, l, i);
  const a = u ? Ax(t, n) : void 0;
  return n && pf(!1), a;
}
function Ax(t, n) {
  const i = t.type;
  t.accessCache = /* @__PURE__ */ Object.create(null), t.proxy = new Proxy(t.ctx, G1);
  const { setup: o } = i;
  if (o) {
    const l = t.setupContext = o.length > 1 ? Ox(t) : null, u = Io(t);
    Nr();
    const a = Oo(
      o,
      t,
      0,
      [
        t.props,
        l
      ]
    );
    if (Lr(), u(), Ed(a)) {
      if (Mi(t) || tp(t), a.then(Gh, Gh), n)
        return a.then((h) => {
          Yh(t, h, n);
        }).catch((h) => {
          pl(h, t, 0);
        });
      t.asyncDep = a;
    } else
      Yh(t, a, n);
  } else
    Ip(t, n);
}
function Yh(t, n, i) {
  ve(n) ? t.type.__ssrInlineRender ? t.ssrRender = n : t.render = n : ke(n) && (t.setupState = Vd(n)), Ip(t, i);
}
let qh;
function Ip(t, n, i) {
  const o = t.type;
  if (!t.render) {
    if (!n && qh && !o.render) {
      const l = o.template || Kf(t).template;
      if (l) {
        const { isCustomElement: u, compilerOptions: a } = t.appContext.config, { delimiters: h, compilerOptions: p } = o, v = Ct(
          Ct(
            {
              isCustomElement: u,
              delimiters: h
            },
            a
          ),
          p
        );
        o.render = qh(l, v);
      }
    }
    t.render = o.render || $n;
  }
  {
    const l = Io(t);
    Nr();
    try {
      Y1(t);
    } finally {
      Lr(), l();
    }
  }
}
const Cx = {
  get(t, n) {
    return Lt(t, "get", ""), t[n];
  }
};
function Ox(t) {
  const n = (i) => {
    t.exposed = i || {};
  };
  return {
    attrs: new Proxy(t.attrs, Cx),
    slots: t.slots,
    emit: t.emit,
    expose: n
  };
}
function qf(t) {
  return t.exposed ? t.exposeProxy || (t.exposeProxy = new Proxy(Vd(a1(t.exposed)), {
    get(n, i) {
      if (i in n)
        return n[i];
      if (i in po)
        return po[i](t);
    },
    has(n, i) {
      return i in n || i in po;
    }
  })) : t.proxy;
}
function Ix(t) {
  return ve(t) && "__vccOpts" in t;
}
const zf = (t, n) => w1(t, n, ml), Dx = "3.5.6";
/**
* @vue/runtime-dom v3.5.6
* (c) 2018-present Yuxi (Evan) You and Vue contributors
* @license MIT
**/
let gf;
const zh = typeof window < "u" && window.trustedTypes;
if (zh)
  try {
    gf = /* @__PURE__ */ zh.createPolicy("vue", {
      createHTML: (t) => t
    });
  } catch {
  }
const Dp = gf ? (t) => gf.createHTML(t) : (t) => t, Rx = "http://www.w3.org/2000/svg", Px = "http://www.w3.org/1998/Math/MathML", ir = typeof document < "u" ? document : null, Xh = ir && /* @__PURE__ */ ir.createElement("template"), Fx = {
  insert: (t, n, i) => {
    n.insertBefore(t, i || null);
  },
  remove: (t) => {
    const n = t.parentNode;
    n && n.removeChild(t);
  },
  createElement: (t, n, i, o) => {
    const l = n === "svg" ? ir.createElementNS(Rx, t) : n === "mathml" ? ir.createElementNS(Px, t) : i ? ir.createElement(t, { is: i }) : ir.createElement(t);
    return t === "select" && o && o.multiple != null && l.setAttribute("multiple", o.multiple), l;
  },
  createText: (t) => ir.createTextNode(t),
  createComment: (t) => ir.createComment(t),
  setText: (t, n) => {
    t.nodeValue = n;
  },
  setElementText: (t, n) => {
    t.textContent = n;
  },
  parentNode: (t) => t.parentNode,
  nextSibling: (t) => t.nextSibling,
  querySelector: (t) => ir.querySelector(t),
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
      Xh.innerHTML = Dp(
        o === "svg" ? `<svg>${t}</svg>` : o === "mathml" ? `<math>${t}</math>` : t
      );
      const h = Xh.content;
      if (o === "svg" || o === "mathml") {
        const p = h.firstChild;
        for (; p.firstChild; )
          h.appendChild(p.firstChild);
        h.removeChild(p);
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
}, Mx = Symbol("_vtc");
function Nx(t, n, i) {
  const o = t[Mx];
  o && (n = (n ? [n, ...o] : [...o]).join(" ")), n == null ? t.removeAttribute("class") : i ? t.setAttribute("class", n) : t.className = n;
}
const Vh = Symbol("_vod"), Lx = Symbol("_vsh"), Bx = Symbol(""), Wx = /(^|;)\s*display\s*:/;
function Hx(t, n, i) {
  const o = t.style, l = ct(i);
  let u = !1;
  if (i && !l) {
    if (n)
      if (ct(n))
        for (const a of n.split(";")) {
          const h = a.slice(0, a.indexOf(":")).trim();
          i[h] == null && $s(o, h, "");
        }
      else
        for (const a in n)
          i[a] == null && $s(o, a, "");
    for (const a in i)
      a === "display" && (u = !0), $s(o, a, i[a]);
  } else if (l) {
    if (n !== i) {
      const a = o[Bx];
      a && (i += ";" + a), o.cssText = i, u = Wx.test(i);
    }
  } else
    n && t.removeAttribute("style");
  Vh in t && (t[Vh] = u ? o.display : "", t[Lx] && (o.display = "none"));
}
const Jh = /\s*!important$/;
function $s(t, n, i) {
  if (_e(i))
    i.forEach((o) => $s(t, n, o));
  else if (i == null && (i = ""), n.startsWith("--"))
    t.setProperty(n, i);
  else {
    const o = Ux(t, n);
    Jh.test(i) ? t.setProperty(
      Mr(o),
      i.replace(Jh, ""),
      "important"
    ) : t[o] = i;
  }
}
const kh = ["Webkit", "Moz", "ms"], Ku = {};
function Ux(t, n) {
  const i = Ku[n];
  if (i)
    return i;
  let o = ni(n);
  if (o !== "filter" && o in t)
    return Ku[n] = o;
  o = Ad(o);
  for (let l = 0; l < kh.length; l++) {
    const u = kh[l] + o;
    if (u in t)
      return Ku[n] = u;
  }
  return n;
}
const Zh = "http://www.w3.org/1999/xlink";
function Qh(t, n, i, o, l, u = Ww(n)) {
  o && n.startsWith("xlink:") ? i == null ? t.removeAttributeNS(Zh, n.slice(6, n.length)) : t.setAttributeNS(Zh, n, i) : i == null || u && !Id(i) ? t.removeAttribute(n) : t.setAttribute(
    n,
    u ? "" : Fr(i) ? String(i) : i
  );
}
function $x(t, n, i, o) {
  if (n === "innerHTML" || n === "textContent") {
    i != null && (t[n] = n === "innerHTML" ? Dp(i) : i);
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
    a === "boolean" ? i = Id(i) : i == null && a === "string" ? (i = "", u = !0) : a === "number" && (i = 0, u = !0);
  }
  try {
    t[n] = i;
  } catch {
  }
  u && t.removeAttribute(n);
}
function Kx(t, n, i, o) {
  t.addEventListener(n, i, o);
}
function Gx(t, n, i, o) {
  t.removeEventListener(n, i, o);
}
const jh = Symbol("_vei");
function Yx(t, n, i, o, l = null) {
  const u = t[jh] || (t[jh] = {}), a = u[n];
  if (o && a)
    a.value = o;
  else {
    const [h, p] = qx(n);
    if (o) {
      const v = u[n] = Vx(
        o,
        l
      );
      Kx(t, h, v, p);
    } else
      a && (Gx(t, h, a, p), u[n] = void 0);
  }
}
const ed = /(?:Once|Passive|Capture)$/;
function qx(t) {
  let n;
  if (ed.test(t)) {
    n = {};
    let o;
    for (; o = t.match(ed); )
      t = t.slice(0, t.length - o[0].length), n[o[0].toLowerCase()] = !0;
  }
  return [t[2] === ":" ? t.slice(3) : Mr(t.slice(2)), n];
}
let Gu = 0;
const zx = /* @__PURE__ */ Promise.resolve(), Xx = () => Gu || (zx.then(() => Gu = 0), Gu = Date.now());
function Vx(t, n) {
  const i = (o) => {
    if (!o._vts)
      o._vts = Date.now();
    else if (o._vts <= i.attached)
      return;
    Yn(
      Jx(o, i.value),
      n,
      5,
      [o]
    );
  };
  return i.value = t, i.attached = Xx(), i;
}
function Jx(t, n) {
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
const td = (t) => t.charCodeAt(0) === 111 && t.charCodeAt(1) === 110 && // lowercase letter
t.charCodeAt(2) > 96 && t.charCodeAt(2) < 123, kx = (t, n, i, o, l, u) => {
  const a = l === "svg";
  n === "class" ? Nx(t, o, a) : n === "style" ? Hx(t, i, o) : ll(n) ? Sf(n) || Yx(t, n, i, o, u) : (n[0] === "." ? (n = n.slice(1), !0) : n[0] === "^" ? (n = n.slice(1), !1) : Zx(t, n, o, a)) ? ($x(t, n, o), !t.tagName.includes("-") && (n === "value" || n === "checked" || n === "selected") && Qh(t, n, o, a, u, n !== "value")) : (n === "true-value" ? t._trueValue = o : n === "false-value" && (t._falseValue = o), Qh(t, n, o, a));
};
function Zx(t, n, i, o) {
  if (o)
    return !!(n === "innerHTML" || n === "textContent" || n in t && td(n) && ve(i));
  if (n === "spellcheck" || n === "draggable" || n === "translate" || n === "form" || n === "list" && t.tagName === "INPUT" || n === "type" && t.tagName === "TEXTAREA")
    return !1;
  if (n === "width" || n === "height") {
    const l = t.tagName;
    if (l === "IMG" || l === "VIDEO" || l === "CANVAS" || l === "SOURCE")
      return !1;
  }
  return td(n) && ct(i) ? !1 : !!(n in t || t._isVueCE && (/[A-Z]/.test(n) || !ct(i)));
}
const Qx = {
  esc: "escape",
  space: " ",
  up: "arrow-up",
  left: "arrow-left",
  right: "arrow-right",
  down: "arrow-down",
  delete: "backspace"
}, xi = (t, n) => {
  const i = t._withKeys || (t._withKeys = {}), o = n.join(".");
  return i[o] || (i[o] = (l) => {
    if (!("key" in l))
      return;
    const u = Mr(l.key);
    if (n.some(
      (a) => a === u || Qx[a] === u
    ))
      return t(l);
  });
}, jx = /* @__PURE__ */ Ct({ patchProp: kx }, Fx);
let nd;
function eE() {
  return nd || (nd = rx(jx));
}
const tE = (...t) => {
  const n = eE().createApp(...t), { mount: i } = n;
  return n.mount = (o) => {
    const l = rE(o);
    if (!l)
      return;
    const u = n._component;
    !ve(u) && !u.render && !u.template && (u.template = l.innerHTML), l.nodeType === 1 && (l.textContent = "");
    const a = i(l, !1, nE(l));
    return l instanceof Element && (l.removeAttribute("v-cloak"), l.setAttribute("data-v-app", "")), a;
  }, n;
};
function nE(t) {
  if (t instanceof SVGElement)
    return "svg";
  if (typeof MathMLElement == "function" && t instanceof MathMLElement)
    return "mathml";
}
function rE(t) {
  return ct(t) ? document.querySelector(t) : t;
}
function Bi(t) {
  return Pd() ? (Uw(t), !0) : !1;
}
function Yu() {
  const t = /* @__PURE__ */ new Set(), n = (l) => {
    t.delete(l);
  };
  return {
    on: (l) => {
      t.add(l);
      const u = () => n(l);
      return Bi(u), {
        off: u
      };
    },
    off: n,
    trigger: (...l) => Promise.all(Array.from(t).map((u) => u(...l)))
  };
}
function jt(t) {
  return typeof t == "function" ? t() : rt(t);
}
const ei = typeof window < "u" && typeof document < "u", iE = typeof WorkerGlobalScope < "u" && globalThis instanceof WorkerGlobalScope, oE = Object.prototype.toString, sE = (t) => oE.call(t) === "[object Object]", Rp = () => {
};
function lE(t, n) {
  function i(...o) {
    return new Promise((l, u) => {
      Promise.resolve(t(() => n.apply(this, o), { fn: n, thisArg: this, args: o })).then(l).catch(u);
    });
  }
  return i;
}
const Pp = (t) => t();
function uE(t = Pp) {
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
  return { isActive: ri(n), pause: i, resume: o, eventFilter: l };
}
function rd(t, n = !1, i = "Timeout") {
  return new Promise((o, l) => {
    setTimeout(n ? () => l(i) : o, t);
  });
}
function fE(t, ...n) {
  return n.some((i) => i in t);
}
function aE(t) {
  return t || Sx();
}
function Ks(...t) {
  if (t.length !== 1)
    return m1(...t);
  const n = t[0];
  return typeof n == "function" ? ri(g1(() => ({ get: n, set: Rp }))) : _t(n);
}
function cE(t, n, i = {}) {
  const {
    eventFilter: o = Pp,
    ...l
  } = i;
  return Nt(
    t,
    lE(
      o,
      n
    ),
    l
  );
}
function hE(t, n, i = {}) {
  const {
    eventFilter: o,
    ...l
  } = i, { eventFilter: u, pause: a, resume: h, isActive: p } = uE(o);
  return { stop: cE(
    t,
    n,
    {
      ...l,
      eventFilter: u
    }
  ), pause: a, resume: h, isActive: p };
}
function dE(t, n = !0, i) {
  aE() ? $f(t, i) : n ? t() : Rr(t);
}
function vf(t, n = !1) {
  function i(m, { flush: A = "sync", deep: S = !1, timeout: C, throwOnTimeout: I } = {}) {
    let P = null;
    const q = [new Promise((z) => {
      P = Nt(
        t,
        (L) => {
          m(L) !== n && (P ? P() : Rr(() => P == null ? void 0 : P()), z(L));
        },
        {
          flush: A,
          deep: S,
          immediate: !0
        }
      );
    })];
    return C != null && q.push(
      rd(C, I).then(() => jt(t)).finally(() => P == null ? void 0 : P())
    ), Promise.race(q);
  }
  function o(m, A) {
    if (!it(m))
      return i((L) => L === m, A);
    const { flush: S = "sync", deep: C = !1, timeout: I, throwOnTimeout: P } = A ?? {};
    let B = null;
    const z = [new Promise((L) => {
      B = Nt(
        [t, m],
        ([te, xe]) => {
          n !== (te === xe) && (B ? B() : Rr(() => B == null ? void 0 : B()), L(te));
        },
        {
          flush: S,
          deep: C,
          immediate: !0
        }
      );
    })];
    return I != null && z.push(
      rd(I, P).then(() => jt(t)).finally(() => (B == null || B(), jt(t)))
    ), Promise.race(z);
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
  function p(m, A) {
    return i((S) => {
      const C = Array.from(S);
      return C.includes(m) || C.includes(jt(m));
    }, A);
  }
  function v(m) {
    return _(1, m);
  }
  function _(m = 1, A) {
    let S = -1;
    return i(() => (S += 1, S >= m), A);
  }
  return Array.isArray(jt(t)) ? {
    toMatch: i,
    toContains: p,
    changed: v,
    changedTimes: _,
    get not() {
      return vf(t, !n);
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
      return vf(t, !n);
    }
  };
}
function pE(t) {
  return vf(t);
}
function gE(t, n = 1e3, i = {}) {
  const {
    immediate: o = !0,
    immediateCallback: l = !1
  } = i;
  let u = null;
  const a = _t(!1);
  function h() {
    u && (clearInterval(u), u = null);
  }
  function p() {
    a.value = !1, h();
  }
  function v() {
    const _ = jt(n);
    _ <= 0 || (a.value = !0, l && t(), h(), u = setInterval(t, _));
  }
  if (o && ei && v(), it(n) || typeof n == "function") {
    const _ = Nt(n, () => {
      a.value && ei && v();
    });
    Bi(_);
  }
  return Bi(p), {
    isActive: a,
    pause: p,
    resume: v
  };
}
function vE(t, n, i = {}) {
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
  function p(...v) {
    a(), l.value = !0, u = setTimeout(() => {
      l.value = !1, u = null, t(...v);
    }, jt(n));
  }
  return o && (l.value = !0, ei && p()), Bi(h), {
    isPending: ri(l),
    start: p,
    stop: h
  };
}
const js = ei ? window : void 0;
function _E(t) {
  var n;
  const i = jt(t);
  return (n = i == null ? void 0 : i.$el) != null ? n : i;
}
function _f(...t) {
  let n, i, o, l;
  if (typeof t[0] == "string" || Array.isArray(t[0]) ? ([i, o, l] = t, n = js) : [n, i, o, l] = t, !n)
    return Rp;
  Array.isArray(i) || (i = [i]), Array.isArray(o) || (o = [o]);
  const u = [], a = () => {
    u.forEach((_) => _()), u.length = 0;
  }, h = (_, m, A, S) => (_.addEventListener(m, A, S), () => _.removeEventListener(m, A, S)), p = Nt(
    () => [_E(n), jt(l)],
    ([_, m]) => {
      if (a(), !_)
        return;
      const A = sE(m) ? { ...m } : m;
      u.push(
        ...i.flatMap((S) => o.map((C) => h(_, S, C, A)))
      );
    },
    { immediate: !0, flush: "post" }
  ), v = () => {
    p(), a();
  };
  return Bi(v), v;
}
const Cs = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, Os = "__vueuse_ssr_handlers__", mE = /* @__PURE__ */ bE();
function bE() {
  return Os in Cs || (Cs[Os] = Cs[Os] || {}), Cs[Os];
}
function yE(t, n) {
  return mE[t] || n;
}
function wE(t) {
  return t == null ? "any" : t instanceof Set ? "set" : t instanceof Map ? "map" : t instanceof Date ? "date" : typeof t == "boolean" ? "boolean" : typeof t == "string" ? "string" : typeof t == "object" ? "object" : Number.isNaN(t) ? "any" : "number";
}
const xE = {
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
}, id = "vueuse-storage";
function EE(t, n, i, o = {}) {
  var l;
  const {
    flush: u = "pre",
    deep: a = !0,
    listenToStorageChanges: h = !0,
    writeDefaults: p = !0,
    mergeDefaults: v = !1,
    shallow: _,
    window: m = js,
    eventFilter: A,
    onError: S = (Z) => {
      console.error(Z);
    },
    initOnMounted: C
  } = o, I = (_ ? Hs : _t)(typeof n == "function" ? n() : n);
  if (!i)
    try {
      i = yE("getDefaultStorage", () => {
        var Z;
        return (Z = js) == null ? void 0 : Z.localStorage;
      })();
    } catch (Z) {
      S(Z);
    }
  if (!i)
    return I;
  const P = jt(n), B = wE(P), q = (l = o.serializer) != null ? l : xE[B], { pause: z, resume: L } = hE(
    I,
    () => xe(I.value),
    { flush: u, deep: a, eventFilter: A }
  );
  m && h && dE(() => {
    i instanceof Storage ? _f(m, "storage", Re) : _f(m, id, ue), C && Re();
  }), C || Re();
  function te(Z, he) {
    if (m) {
      const Ce = {
        key: t,
        oldValue: Z,
        newValue: he,
        storageArea: i
      };
      m.dispatchEvent(i instanceof Storage ? new StorageEvent("storage", Ce) : new CustomEvent(id, {
        detail: Ce
      }));
    }
  }
  function xe(Z) {
    try {
      const he = i.getItem(t);
      if (Z == null)
        te(he, null), i.removeItem(t);
      else {
        const Ce = q.write(Z);
        he !== Ce && (i.setItem(t, Ce), te(he, Ce));
      }
    } catch (he) {
      S(he);
    }
  }
  function Se(Z) {
    const he = Z ? Z.newValue : i.getItem(t);
    if (he == null)
      return p && P != null && i.setItem(t, q.write(P)), P;
    if (!Z && v) {
      const Ce = q.read(he);
      return typeof v == "function" ? v(Ce, P) : B === "object" && !Array.isArray(Ce) ? { ...P, ...Ce } : Ce;
    } else
      return typeof he != "string" ? he : q.read(he);
  }
  function Re(Z) {
    if (!(Z && Z.storageArea !== i)) {
      if (Z && Z.key == null) {
        I.value = P;
        return;
      }
      if (!(Z && Z.key !== t)) {
        z();
        try {
          (Z == null ? void 0 : Z.newValue) !== q.write(I.value) && (I.value = Se(Z));
        } catch (he) {
          S(he);
        } finally {
          Z ? Rr(L) : L();
        }
      }
    }
  }
  function ue(Z) {
    Re(Z.detail);
  }
  return I;
}
const SE = {
  json: "application/json",
  text: "text/plain"
};
function el(t) {
  return t && fE(t, "immediate", "refetch", "initialData", "timeout", "beforeFetch", "afterFetch", "onFetchError", "fetch", "updateDataOnError");
}
const TE = /^(?:[a-z][a-z\d+\-.]*:)?\/\//i;
function AE(t) {
  return TE.test(t);
}
function vo(t) {
  return typeof Headers < "u" && t instanceof Headers ? Object.fromEntries(t.entries()) : t;
}
function Ei(t, ...n) {
  return t === "overwrite" ? async (i) => {
    const o = n[n.length - 1];
    return o ? { ...i, ...await o(i) } : i;
  } : async (i) => {
    for (const o of n)
      o && (i = { ...i, ...await o(i) });
    return i;
  };
}
function CE(t = {}) {
  const n = t.combination || "chain", i = t.options || {}, o = t.fetchOptions || {};
  function l(u, ...a) {
    const h = zf(() => {
      const _ = jt(t.baseUrl), m = jt(u);
      return _ && !AE(m) ? IE(_, m) : m;
    });
    let p = i, v = o;
    return a.length > 0 && (el(a[0]) ? p = {
      ...p,
      ...a[0],
      beforeFetch: Ei(n, i.beforeFetch, a[0].beforeFetch),
      afterFetch: Ei(n, i.afterFetch, a[0].afterFetch),
      onFetchError: Ei(n, i.onFetchError, a[0].onFetchError)
    } : v = {
      ...v,
      ...a[0],
      headers: {
        ...vo(v.headers) || {},
        ...vo(a[0].headers) || {}
      }
    }), a.length > 1 && el(a[1]) && (p = {
      ...p,
      ...a[1],
      beforeFetch: Ei(n, i.beforeFetch, a[1].beforeFetch),
      afterFetch: Ei(n, i.afterFetch, a[1].afterFetch),
      onFetchError: Ei(n, i.onFetchError, a[1].onFetchError)
    }), OE(h, v, p);
  }
  return l;
}
function OE(t, ...n) {
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
  n.length > 0 && (el(n[0]) ? u = { ...u, ...n[0] } : l = n[0]), n.length > 1 && el(n[1]) && (u = { ...u, ...n[1] });
  const {
    fetch: h = (i = js) == null ? void 0 : i.fetch,
    initialData: p,
    timeout: v
  } = u, _ = Yu(), m = Yu(), A = Yu(), S = _t(!1), C = _t(!1), I = _t(!1), P = _t(null), B = Hs(null), q = Hs(null), z = Hs(p || null), L = zf(() => o && C.value);
  let te, xe;
  const Se = () => {
    o && (te == null || te.abort(), te = new AbortController(), te.signal.onabort = () => I.value = !0, l = {
      ...l,
      signal: te.signal
    });
  }, Re = (me) => {
    C.value = me, S.value = !me;
  };
  v && (xe = vE(Se, v, { immediate: !1 }));
  let ue = 0;
  const Z = async (me = !1) => {
    var fe, ie;
    Se(), Re(!0), q.value = null, P.value = null, I.value = !1, ue += 1;
    const mt = ue, gt = {
      method: a.method,
      headers: {}
    };
    if (a.payload) {
      const st = vo(gt.headers), bt = jt(a.payload);
      !a.payloadType && bt && Object.getPrototypeOf(bt) === Object.prototype && !(bt instanceof FormData) && (a.payloadType = "json"), a.payloadType && (st["Content-Type"] = (fe = SE[a.payloadType]) != null ? fe : a.payloadType), gt.body = a.payloadType === "json" ? JSON.stringify(bt) : bt;
    }
    let Xe = !1;
    const ot = {
      url: jt(t),
      options: {
        ...gt,
        ...l
      },
      cancel: () => {
        Xe = !0;
      }
    };
    if (u.beforeFetch && Object.assign(ot, await u.beforeFetch(ot)), Xe || !h)
      return Re(!1), Promise.resolve(null);
    let hn = null;
    return xe && xe.start(), h(
      ot.url,
      {
        ...gt,
        ...ot.options,
        headers: {
          ...vo(gt.headers),
          ...vo((ie = ot.options) == null ? void 0 : ie.headers)
        }
      }
    ).then(async (st) => {
      if (B.value = st, P.value = st.status, hn = await st.clone()[a.type](), !st.ok)
        throw z.value = p || null, new Error(st.statusText);
      return u.afterFetch && ({ data: hn } = await u.afterFetch({
        data: hn,
        response: st
      })), z.value = hn, _.trigger(st), st;
    }).catch(async (st) => {
      let bt = st.message || st.name;
      if (u.onFetchError && ({ error: bt, data: hn } = await u.onFetchError({
        data: hn,
        error: st,
        response: B.value
      })), q.value = bt, u.updateDataOnError && (z.value = hn), m.trigger(st), me)
        throw st;
      return null;
    }).finally(() => {
      mt === ue && Re(!1), xe && xe.stop(), A.trigger(null);
    });
  }, he = Ks(u.refetch);
  Nt(
    [
      he,
      Ks(t)
    ],
    ([me]) => me && Z(),
    { deep: !0 }
  );
  const Ce = {
    isFinished: ri(S),
    isFetching: ri(C),
    statusCode: P,
    response: B,
    error: q,
    data: z,
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
    json: Ot("json"),
    text: Ot("text"),
    blob: Ot("blob"),
    arrayBuffer: Ot("arrayBuffer"),
    formData: Ot("formData")
  };
  function We(me) {
    return (fe, ie) => {
      if (!C.value)
        return a.method = me, a.payload = fe, a.payloadType = ie, it(a.payload) && Nt(
          [
            he,
            Ks(a.payload)
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
      pE(S).toBe(!0).then(() => me(Ce)).catch((ie) => fe(ie));
    });
  }
  function Ot(me) {
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
function IE(t, n) {
  return !t.endsWith("/") && !n.startsWith("/") ? `${t}/${n}` : `${t}${n}`;
}
const od = "ping";
function qu(t) {
  return t === !0 ? {} : t;
}
function DE(t, n = {}) {
  const {
    onConnected: i,
    onDisconnected: o,
    onError: l,
    onMessage: u,
    immediate: a = !0,
    autoClose: h = !0,
    protocols: p = []
  } = n, v = _t(null), _ = _t("CLOSED"), m = _t(), A = Ks(t);
  let S, C, I = !1, P = 0, B = [], q;
  const z = () => {
    if (B.length && m.value && _.value === "OPEN") {
      for (const ue of B)
        m.value.send(ue);
      B = [];
    }
  }, L = () => {
    clearTimeout(q), q = void 0;
  }, te = (ue = 1e3, Z) => {
    !ei || !m.value || (I = !0, L(), S == null || S(), m.value.close(ue, Z), m.value = void 0);
  }, xe = (ue, Z = !0) => !m.value || _.value !== "OPEN" ? (Z && B.push(ue), !1) : (z(), m.value.send(ue), !0), Se = () => {
    if (I || typeof A.value > "u")
      return;
    const ue = new WebSocket(A.value, p);
    m.value = ue, _.value = "CONNECTING", ue.onopen = () => {
      _.value = "OPEN", P = 0, i == null || i(ue), C == null || C(), z();
    }, ue.onclose = (Z) => {
      if (_.value = "CLOSED", o == null || o(ue, Z), !I && n.autoReconnect && ue === m.value) {
        const {
          retries: he = -1,
          delay: Ce = 1e3,
          onFailed: We
        } = qu(n.autoReconnect);
        typeof he == "number" && (he < 0 || P < he) ? (P += 1, setTimeout(Se, Ce)) : typeof he == "function" && he() ? setTimeout(Se, Ce) : We == null || We();
      }
    }, ue.onerror = (Z) => {
      l == null || l(ue, Z);
    }, ue.onmessage = (Z) => {
      if (n.heartbeat) {
        L();
        const {
          message: he = od,
          responseMessage: Ce = he
        } = qu(n.heartbeat);
        if (Z.data === Ce)
          return;
      }
      v.value = Z.data, u == null || u(ue, Z);
    };
  };
  if (n.heartbeat) {
    const {
      message: ue = od,
      interval: Z = 1e3,
      pongTimeout: he = 1e3
    } = qu(n.heartbeat), { pause: Ce, resume: We } = gE(
      () => {
        xe(ue, !1), q == null && (q = setTimeout(() => {
          te(), I = !1;
        }, he));
      },
      Z,
      { immediate: !1 }
    );
    S = Ce, C = We;
  }
  h && (ei && _f("beforeunload", () => te()), Bi(te));
  const Re = () => {
    !ei && !iE || (te(), I = !1, P = 0, Se());
  };
  return a && Re(), Nt(A, Re), {
    data: v,
    status: _,
    close: te,
    send: xe,
    open: Re,
    ws: m
  };
}
var no = typeof globalThis < "u" ? globalThis : typeof window < "u" ? window : typeof global < "u" ? global : typeof self < "u" ? self : {}, tl = { exports: {} };
/**
 * @license
 * Lodash <https://lodash.com/>
 * Copyright OpenJS Foundation and other contributors <https://openjsf.org/>
 * Released under MIT license <https://lodash.com/license>
 * Based on Underscore.js 1.8.3 <http://underscorejs.org/LICENSE>
 * Copyright Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 */
tl.exports;
(function(t, n) {
  (function() {
    var i, o = "4.17.21", l = 200, u = "Unsupported core-js use. Try https://npms.io/search?q=ponyfill.", a = "Expected a function", h = "Invalid `variable` option passed into `_.template`", p = "__lodash_hash_undefined__", v = 500, _ = "__lodash_placeholder__", m = 1, A = 2, S = 4, C = 1, I = 2, P = 1, B = 2, q = 4, z = 8, L = 16, te = 32, xe = 64, Se = 128, Re = 256, ue = 512, Z = 30, he = "...", Ce = 800, We = 16, pt = 1, Ot = 2, me = 3, fe = 1 / 0, ie = 9007199254740991, mt = 17976931348623157e292, gt = NaN, Xe = 4294967295, ot = Xe - 1, hn = Xe >>> 1, st = [
      ["ary", Se],
      ["bind", P],
      ["bindKey", B],
      ["curry", z],
      ["curryRight", L],
      ["flip", ue],
      ["partial", te],
      ["partialRight", xe],
      ["rearg", Re]
    ], bt = "[object Arguments]", Fn = "[object Array]", oi = "[object AsyncFunction]", zn = "[object Boolean]", ar = "[object Date]", cr = "[object DOMException]", hr = "[object Error]", dr = "[object Function]", y = "[object GeneratorFunction]", x = "[object Map]", D = "[object Number]", $ = "[object Null]", N = "[object Object]", U = "[object Promise]", X = "[object Proxy]", G = "[object RegExp]", K = "[object Set]", H = "[object String]", ne = "[object Symbol]", J = "[object Undefined]", Q = "[object WeakMap]", ae = "[object WeakSet]", ye = "[object ArrayBuffer]", Pe = "[object DataView]", Fe = "[object Float32Array]", yt = "[object Float64Array]", lt = "[object Int8Array]", Bt = "[object Int16Array]", wt = "[object Int32Array]", Xn = "[object Uint8Array]", si = "[object Uint8ClampedArray]", xt = "[object Uint16Array]", zt = "[object Uint32Array]", Po = /\b__p \+= '';/g, Jp = /\b(__p \+=) '' \+/g, kp = /(__e\(.*?\)|\b__t\)) \+\n'';/g, Zf = /&(?:amp|lt|gt|quot|#39);/g, Qf = /[&<>"']/g, Zp = RegExp(Zf.source), Qp = RegExp(Qf.source), jp = /<%-([\s\S]+?)%>/g, eg = /<%([\s\S]+?)%>/g, jf = /<%=([\s\S]+?)%>/g, tg = /\.|\[(?:[^[\]]*|(["'])(?:(?!\1)[^\\]|\\.)*?\1)\]/, ng = /^\w*$/, rg = /[^.[\]]+|\[(?:(-?\d+(?:\.\d+)?)|(["'])((?:(?!\2)[^\\]|\\.)*?)\2)\]|(?=(?:\.|\[\])(?:\.|\[\]|$))/g, yl = /[\\^$.*+?()[\]{}|]/g, ig = RegExp(yl.source), wl = /^\s+/, og = /\s/, sg = /\{(?:\n\/\* \[wrapped with .+\] \*\/)?\n?/, lg = /\{\n\/\* \[wrapped with (.+)\] \*/, ug = /,? & /, fg = /[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g, ag = /[()=,{}\[\]\/\s]/, cg = /\\(\\)?/g, hg = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g, ea = /\w*$/, dg = /^[-+]0x[0-9a-f]+$/i, pg = /^0b[01]+$/i, gg = /^\[object .+?Constructor\]$/, vg = /^0o[0-7]+$/i, _g = /^(?:0|[1-9]\d*)$/, mg = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g, Fo = /($^)/, bg = /['\n\r\u2028\u2029\\]/g, Mo = "\\ud800-\\udfff", yg = "\\u0300-\\u036f", wg = "\\ufe20-\\ufe2f", xg = "\\u20d0-\\u20ff", ta = yg + wg + xg, na = "\\u2700-\\u27bf", ra = "a-z\\xdf-\\xf6\\xf8-\\xff", Eg = "\\xac\\xb1\\xd7\\xf7", Sg = "\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf", Tg = "\\u2000-\\u206f", Ag = " \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000", ia = "A-Z\\xc0-\\xd6\\xd8-\\xde", oa = "\\ufe0e\\ufe0f", sa = Eg + Sg + Tg + Ag, xl = "['’]", Cg = "[" + Mo + "]", la = "[" + sa + "]", No = "[" + ta + "]", ua = "\\d+", Og = "[" + na + "]", fa = "[" + ra + "]", aa = "[^" + Mo + sa + ua + na + ra + ia + "]", El = "\\ud83c[\\udffb-\\udfff]", Ig = "(?:" + No + "|" + El + ")", ca = "[^" + Mo + "]", Sl = "(?:\\ud83c[\\udde6-\\uddff]){2}", Tl = "[\\ud800-\\udbff][\\udc00-\\udfff]", li = "[" + ia + "]", ha = "\\u200d", da = "(?:" + fa + "|" + aa + ")", Dg = "(?:" + li + "|" + aa + ")", pa = "(?:" + xl + "(?:d|ll|m|re|s|t|ve))?", ga = "(?:" + xl + "(?:D|LL|M|RE|S|T|VE))?", va = Ig + "?", _a = "[" + oa + "]?", Rg = "(?:" + ha + "(?:" + [ca, Sl, Tl].join("|") + ")" + _a + va + ")*", Pg = "\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])", Fg = "\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])", ma = _a + va + Rg, Mg = "(?:" + [Og, Sl, Tl].join("|") + ")" + ma, Ng = "(?:" + [ca + No + "?", No, Sl, Tl, Cg].join("|") + ")", Lg = RegExp(xl, "g"), Bg = RegExp(No, "g"), Al = RegExp(El + "(?=" + El + ")|" + Ng + ma, "g"), Wg = RegExp([
      li + "?" + fa + "+" + pa + "(?=" + [la, li, "$"].join("|") + ")",
      Dg + "+" + ga + "(?=" + [la, li + da, "$"].join("|") + ")",
      li + "?" + da + "+" + pa,
      li + "+" + ga,
      Fg,
      Pg,
      ua,
      Mg
    ].join("|"), "g"), Hg = RegExp("[" + ha + Mo + ta + oa + "]"), Ug = /[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/, $g = [
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
    ], Kg = -1, qe = {};
    qe[Fe] = qe[yt] = qe[lt] = qe[Bt] = qe[wt] = qe[Xn] = qe[si] = qe[xt] = qe[zt] = !0, qe[bt] = qe[Fn] = qe[ye] = qe[zn] = qe[Pe] = qe[ar] = qe[hr] = qe[dr] = qe[x] = qe[D] = qe[N] = qe[G] = qe[K] = qe[H] = qe[Q] = !1;
    var Ke = {};
    Ke[bt] = Ke[Fn] = Ke[ye] = Ke[Pe] = Ke[zn] = Ke[ar] = Ke[Fe] = Ke[yt] = Ke[lt] = Ke[Bt] = Ke[wt] = Ke[x] = Ke[D] = Ke[N] = Ke[G] = Ke[K] = Ke[H] = Ke[ne] = Ke[Xn] = Ke[si] = Ke[xt] = Ke[zt] = !0, Ke[hr] = Ke[dr] = Ke[Q] = !1;
    var Gg = {
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
    }, Yg = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;"
    }, qg = {
      "&amp;": "&",
      "&lt;": "<",
      "&gt;": ">",
      "&quot;": '"',
      "&#39;": "'"
    }, zg = {
      "\\": "\\",
      "'": "'",
      "\n": "n",
      "\r": "r",
      "\u2028": "u2028",
      "\u2029": "u2029"
    }, Xg = parseFloat, Vg = parseInt, ba = typeof no == "object" && no && no.Object === Object && no, Jg = typeof self == "object" && self && self.Object === Object && self, Et = ba || Jg || Function("return this")(), Cl = n && !n.nodeType && n, Br = Cl && !0 && t && !t.nodeType && t, ya = Br && Br.exports === Cl, Ol = ya && ba.process, dn = function() {
      try {
        var E = Br && Br.require && Br.require("util").types;
        return E || Ol && Ol.binding && Ol.binding("util");
      } catch {
      }
    }(), wa = dn && dn.isArrayBuffer, xa = dn && dn.isDate, Ea = dn && dn.isMap, Sa = dn && dn.isRegExp, Ta = dn && dn.isSet, Aa = dn && dn.isTypedArray;
    function en(E, R, O) {
      switch (O.length) {
        case 0:
          return E.call(R);
        case 1:
          return E.call(R, O[0]);
        case 2:
          return E.call(R, O[0], O[1]);
        case 3:
          return E.call(R, O[0], O[1], O[2]);
      }
      return E.apply(R, O);
    }
    function kg(E, R, O, k) {
      for (var de = -1, Me = E == null ? 0 : E.length; ++de < Me; ) {
        var ht = E[de];
        R(k, ht, O(ht), E);
      }
      return k;
    }
    function pn(E, R) {
      for (var O = -1, k = E == null ? 0 : E.length; ++O < k && R(E[O], O, E) !== !1; )
        ;
      return E;
    }
    function Zg(E, R) {
      for (var O = E == null ? 0 : E.length; O-- && R(E[O], O, E) !== !1; )
        ;
      return E;
    }
    function Ca(E, R) {
      for (var O = -1, k = E == null ? 0 : E.length; ++O < k; )
        if (!R(E[O], O, E))
          return !1;
      return !0;
    }
    function pr(E, R) {
      for (var O = -1, k = E == null ? 0 : E.length, de = 0, Me = []; ++O < k; ) {
        var ht = E[O];
        R(ht, O, E) && (Me[de++] = ht);
      }
      return Me;
    }
    function Lo(E, R) {
      var O = E == null ? 0 : E.length;
      return !!O && ui(E, R, 0) > -1;
    }
    function Il(E, R, O) {
      for (var k = -1, de = E == null ? 0 : E.length; ++k < de; )
        if (O(R, E[k]))
          return !0;
      return !1;
    }
    function Ve(E, R) {
      for (var O = -1, k = E == null ? 0 : E.length, de = Array(k); ++O < k; )
        de[O] = R(E[O], O, E);
      return de;
    }
    function gr(E, R) {
      for (var O = -1, k = R.length, de = E.length; ++O < k; )
        E[de + O] = R[O];
      return E;
    }
    function Dl(E, R, O, k) {
      var de = -1, Me = E == null ? 0 : E.length;
      for (k && Me && (O = E[++de]); ++de < Me; )
        O = R(O, E[de], de, E);
      return O;
    }
    function Qg(E, R, O, k) {
      var de = E == null ? 0 : E.length;
      for (k && de && (O = E[--de]); de--; )
        O = R(O, E[de], de, E);
      return O;
    }
    function Rl(E, R) {
      for (var O = -1, k = E == null ? 0 : E.length; ++O < k; )
        if (R(E[O], O, E))
          return !0;
      return !1;
    }
    var jg = Pl("length");
    function ev(E) {
      return E.split("");
    }
    function tv(E) {
      return E.match(fg) || [];
    }
    function Oa(E, R, O) {
      var k;
      return O(E, function(de, Me, ht) {
        if (R(de, Me, ht))
          return k = Me, !1;
      }), k;
    }
    function Bo(E, R, O, k) {
      for (var de = E.length, Me = O + (k ? 1 : -1); k ? Me-- : ++Me < de; )
        if (R(E[Me], Me, E))
          return Me;
      return -1;
    }
    function ui(E, R, O) {
      return R === R ? dv(E, R, O) : Bo(E, Ia, O);
    }
    function nv(E, R, O, k) {
      for (var de = O - 1, Me = E.length; ++de < Me; )
        if (k(E[de], R))
          return de;
      return -1;
    }
    function Ia(E) {
      return E !== E;
    }
    function Da(E, R) {
      var O = E == null ? 0 : E.length;
      return O ? Ml(E, R) / O : gt;
    }
    function Pl(E) {
      return function(R) {
        return R == null ? i : R[E];
      };
    }
    function Fl(E) {
      return function(R) {
        return E == null ? i : E[R];
      };
    }
    function Ra(E, R, O, k, de) {
      return de(E, function(Me, ht, Ue) {
        O = k ? (k = !1, Me) : R(O, Me, ht, Ue);
      }), O;
    }
    function rv(E, R) {
      var O = E.length;
      for (E.sort(R); O--; )
        E[O] = E[O].value;
      return E;
    }
    function Ml(E, R) {
      for (var O, k = -1, de = E.length; ++k < de; ) {
        var Me = R(E[k]);
        Me !== i && (O = O === i ? Me : O + Me);
      }
      return O;
    }
    function Nl(E, R) {
      for (var O = -1, k = Array(E); ++O < E; )
        k[O] = R(O);
      return k;
    }
    function iv(E, R) {
      return Ve(R, function(O) {
        return [O, E[O]];
      });
    }
    function Pa(E) {
      return E && E.slice(0, La(E) + 1).replace(wl, "");
    }
    function tn(E) {
      return function(R) {
        return E(R);
      };
    }
    function Ll(E, R) {
      return Ve(R, function(O) {
        return E[O];
      });
    }
    function Hi(E, R) {
      return E.has(R);
    }
    function Fa(E, R) {
      for (var O = -1, k = E.length; ++O < k && ui(R, E[O], 0) > -1; )
        ;
      return O;
    }
    function Ma(E, R) {
      for (var O = E.length; O-- && ui(R, E[O], 0) > -1; )
        ;
      return O;
    }
    function ov(E, R) {
      for (var O = E.length, k = 0; O--; )
        E[O] === R && ++k;
      return k;
    }
    var sv = Fl(Gg), lv = Fl(Yg);
    function uv(E) {
      return "\\" + zg[E];
    }
    function fv(E, R) {
      return E == null ? i : E[R];
    }
    function fi(E) {
      return Hg.test(E);
    }
    function av(E) {
      return Ug.test(E);
    }
    function cv(E) {
      for (var R, O = []; !(R = E.next()).done; )
        O.push(R.value);
      return O;
    }
    function Bl(E) {
      var R = -1, O = Array(E.size);
      return E.forEach(function(k, de) {
        O[++R] = [de, k];
      }), O;
    }
    function Na(E, R) {
      return function(O) {
        return E(R(O));
      };
    }
    function vr(E, R) {
      for (var O = -1, k = E.length, de = 0, Me = []; ++O < k; ) {
        var ht = E[O];
        (ht === R || ht === _) && (E[O] = _, Me[de++] = O);
      }
      return Me;
    }
    function Wo(E) {
      var R = -1, O = Array(E.size);
      return E.forEach(function(k) {
        O[++R] = k;
      }), O;
    }
    function hv(E) {
      var R = -1, O = Array(E.size);
      return E.forEach(function(k) {
        O[++R] = [k, k];
      }), O;
    }
    function dv(E, R, O) {
      for (var k = O - 1, de = E.length; ++k < de; )
        if (E[k] === R)
          return k;
      return -1;
    }
    function pv(E, R, O) {
      for (var k = O + 1; k--; )
        if (E[k] === R)
          return k;
      return k;
    }
    function ai(E) {
      return fi(E) ? vv(E) : jg(E);
    }
    function Sn(E) {
      return fi(E) ? _v(E) : ev(E);
    }
    function La(E) {
      for (var R = E.length; R-- && og.test(E.charAt(R)); )
        ;
      return R;
    }
    var gv = Fl(qg);
    function vv(E) {
      for (var R = Al.lastIndex = 0; Al.test(E); )
        ++R;
      return R;
    }
    function _v(E) {
      return E.match(Al) || [];
    }
    function mv(E) {
      return E.match(Wg) || [];
    }
    var bv = function E(R) {
      R = R == null ? Et : ci.defaults(Et.Object(), R, ci.pick(Et, $g));
      var O = R.Array, k = R.Date, de = R.Error, Me = R.Function, ht = R.Math, Ue = R.Object, Wl = R.RegExp, yv = R.String, gn = R.TypeError, Ho = O.prototype, wv = Me.prototype, hi = Ue.prototype, Uo = R["__core-js_shared__"], $o = wv.toString, He = hi.hasOwnProperty, xv = 0, Ba = function() {
        var e = /[^.]+$/.exec(Uo && Uo.keys && Uo.keys.IE_PROTO || "");
        return e ? "Symbol(src)_1." + e : "";
      }(), Ko = hi.toString, Ev = $o.call(Ue), Sv = Et._, Tv = Wl(
        "^" + $o.call(He).replace(yl, "\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, "$1.*?") + "$"
      ), Go = ya ? R.Buffer : i, _r = R.Symbol, Yo = R.Uint8Array, Wa = Go ? Go.allocUnsafe : i, qo = Na(Ue.getPrototypeOf, Ue), Ha = Ue.create, Ua = hi.propertyIsEnumerable, zo = Ho.splice, $a = _r ? _r.isConcatSpreadable : i, Ui = _r ? _r.iterator : i, Wr = _r ? _r.toStringTag : i, Xo = function() {
        try {
          var e = Gr(Ue, "defineProperty");
          return e({}, "", {}), e;
        } catch {
        }
      }(), Av = R.clearTimeout !== Et.clearTimeout && R.clearTimeout, Cv = k && k.now !== Et.Date.now && k.now, Ov = R.setTimeout !== Et.setTimeout && R.setTimeout, Vo = ht.ceil, Jo = ht.floor, Hl = Ue.getOwnPropertySymbols, Iv = Go ? Go.isBuffer : i, Ka = R.isFinite, Dv = Ho.join, Rv = Na(Ue.keys, Ue), dt = ht.max, It = ht.min, Pv = k.now, Fv = R.parseInt, Ga = ht.random, Mv = Ho.reverse, Ul = Gr(R, "DataView"), $i = Gr(R, "Map"), $l = Gr(R, "Promise"), di = Gr(R, "Set"), Ki = Gr(R, "WeakMap"), Gi = Gr(Ue, "create"), ko = Ki && new Ki(), pi = {}, Nv = Yr(Ul), Lv = Yr($i), Bv = Yr($l), Wv = Yr(di), Hv = Yr(Ki), Zo = _r ? _r.prototype : i, Yi = Zo ? Zo.valueOf : i, Ya = Zo ? Zo.toString : i;
      function d(e) {
        if (je(e) && !ge(e) && !(e instanceof Oe)) {
          if (e instanceof vn)
            return e;
          if (He.call(e, "__wrapped__"))
            return qc(e);
        }
        return new vn(e);
      }
      var gi = /* @__PURE__ */ function() {
        function e() {
        }
        return function(r) {
          if (!Ze(r))
            return {};
          if (Ha)
            return Ha(r);
          e.prototype = r;
          var s = new e();
          return e.prototype = i, s;
        };
      }();
      function Qo() {
      }
      function vn(e, r) {
        this.__wrapped__ = e, this.__actions__ = [], this.__chain__ = !!r, this.__index__ = 0, this.__values__ = i;
      }
      d.templateSettings = {
        /**
         * Used to detect `data` property values to be HTML-escaped.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        escape: jp,
        /**
         * Used to detect code to be evaluated.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        evaluate: eg,
        /**
         * Used to detect `data` property values to inject.
         *
         * @memberOf _.templateSettings
         * @type {RegExp}
         */
        interpolate: jf,
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
      }, d.prototype = Qo.prototype, d.prototype.constructor = d, vn.prototype = gi(Qo.prototype), vn.prototype.constructor = vn;
      function Oe(e) {
        this.__wrapped__ = e, this.__actions__ = [], this.__dir__ = 1, this.__filtered__ = !1, this.__iteratees__ = [], this.__takeCount__ = Xe, this.__views__ = [];
      }
      function Uv() {
        var e = new Oe(this.__wrapped__);
        return e.__actions__ = Xt(this.__actions__), e.__dir__ = this.__dir__, e.__filtered__ = this.__filtered__, e.__iteratees__ = Xt(this.__iteratees__), e.__takeCount__ = this.__takeCount__, e.__views__ = Xt(this.__views__), e;
      }
      function $v() {
        if (this.__filtered__) {
          var e = new Oe(this);
          e.__dir__ = -1, e.__filtered__ = !0;
        } else
          e = this.clone(), e.__dir__ *= -1;
        return e;
      }
      function Kv() {
        var e = this.__wrapped__.value(), r = this.__dir__, s = ge(e), f = r < 0, c = s ? e.length : 0, g = em(0, c, this.__views__), b = g.start, w = g.end, T = w - b, F = f ? w : b - 1, M = this.__iteratees__, W = M.length, V = 0, ee = It(T, this.__takeCount__);
        if (!s || !f && c == T && ee == T)
          return pc(e, this.__actions__);
        var oe = [];
        e:
          for (; T-- && V < ee; ) {
            F += r;
            for (var we = -1, se = e[F]; ++we < W; ) {
              var Ae = M[we], De = Ae.iteratee, on = Ae.type, Ut = De(se);
              if (on == Ot)
                se = Ut;
              else if (!Ut) {
                if (on == pt)
                  continue e;
                break e;
              }
            }
            oe[V++] = se;
          }
        return oe;
      }
      Oe.prototype = gi(Qo.prototype), Oe.prototype.constructor = Oe;
      function Hr(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.clear(); ++r < s; ) {
          var f = e[r];
          this.set(f[0], f[1]);
        }
      }
      function Gv() {
        this.__data__ = Gi ? Gi(null) : {}, this.size = 0;
      }
      function Yv(e) {
        var r = this.has(e) && delete this.__data__[e];
        return this.size -= r ? 1 : 0, r;
      }
      function qv(e) {
        var r = this.__data__;
        if (Gi) {
          var s = r[e];
          return s === p ? i : s;
        }
        return He.call(r, e) ? r[e] : i;
      }
      function zv(e) {
        var r = this.__data__;
        return Gi ? r[e] !== i : He.call(r, e);
      }
      function Xv(e, r) {
        var s = this.__data__;
        return this.size += this.has(e) ? 0 : 1, s[e] = Gi && r === i ? p : r, this;
      }
      Hr.prototype.clear = Gv, Hr.prototype.delete = Yv, Hr.prototype.get = qv, Hr.prototype.has = zv, Hr.prototype.set = Xv;
      function Vn(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.clear(); ++r < s; ) {
          var f = e[r];
          this.set(f[0], f[1]);
        }
      }
      function Vv() {
        this.__data__ = [], this.size = 0;
      }
      function Jv(e) {
        var r = this.__data__, s = jo(r, e);
        if (s < 0)
          return !1;
        var f = r.length - 1;
        return s == f ? r.pop() : zo.call(r, s, 1), --this.size, !0;
      }
      function kv(e) {
        var r = this.__data__, s = jo(r, e);
        return s < 0 ? i : r[s][1];
      }
      function Zv(e) {
        return jo(this.__data__, e) > -1;
      }
      function Qv(e, r) {
        var s = this.__data__, f = jo(s, e);
        return f < 0 ? (++this.size, s.push([e, r])) : s[f][1] = r, this;
      }
      Vn.prototype.clear = Vv, Vn.prototype.delete = Jv, Vn.prototype.get = kv, Vn.prototype.has = Zv, Vn.prototype.set = Qv;
      function Jn(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.clear(); ++r < s; ) {
          var f = e[r];
          this.set(f[0], f[1]);
        }
      }
      function jv() {
        this.size = 0, this.__data__ = {
          hash: new Hr(),
          map: new ($i || Vn)(),
          string: new Hr()
        };
      }
      function e_(e) {
        var r = cs(this, e).delete(e);
        return this.size -= r ? 1 : 0, r;
      }
      function t_(e) {
        return cs(this, e).get(e);
      }
      function n_(e) {
        return cs(this, e).has(e);
      }
      function r_(e, r) {
        var s = cs(this, e), f = s.size;
        return s.set(e, r), this.size += s.size == f ? 0 : 1, this;
      }
      Jn.prototype.clear = jv, Jn.prototype.delete = e_, Jn.prototype.get = t_, Jn.prototype.has = n_, Jn.prototype.set = r_;
      function Ur(e) {
        var r = -1, s = e == null ? 0 : e.length;
        for (this.__data__ = new Jn(); ++r < s; )
          this.add(e[r]);
      }
      function i_(e) {
        return this.__data__.set(e, p), this;
      }
      function o_(e) {
        return this.__data__.has(e);
      }
      Ur.prototype.add = Ur.prototype.push = i_, Ur.prototype.has = o_;
      function Tn(e) {
        var r = this.__data__ = new Vn(e);
        this.size = r.size;
      }
      function s_() {
        this.__data__ = new Vn(), this.size = 0;
      }
      function l_(e) {
        var r = this.__data__, s = r.delete(e);
        return this.size = r.size, s;
      }
      function u_(e) {
        return this.__data__.get(e);
      }
      function f_(e) {
        return this.__data__.has(e);
      }
      function a_(e, r) {
        var s = this.__data__;
        if (s instanceof Vn) {
          var f = s.__data__;
          if (!$i || f.length < l - 1)
            return f.push([e, r]), this.size = ++s.size, this;
          s = this.__data__ = new Jn(f);
        }
        return s.set(e, r), this.size = s.size, this;
      }
      Tn.prototype.clear = s_, Tn.prototype.delete = l_, Tn.prototype.get = u_, Tn.prototype.has = f_, Tn.prototype.set = a_;
      function qa(e, r) {
        var s = ge(e), f = !s && qr(e), c = !s && !f && xr(e), g = !s && !f && !c && bi(e), b = s || f || c || g, w = b ? Nl(e.length, yv) : [], T = w.length;
        for (var F in e)
          (r || He.call(e, F)) && !(b && // Safari 9 has enumerable `arguments.length` in strict mode.
          (F == "length" || // Node.js 0.10 has enumerable non-index properties on buffers.
          c && (F == "offset" || F == "parent") || // PhantomJS 2 has enumerable non-index properties on typed arrays.
          g && (F == "buffer" || F == "byteLength" || F == "byteOffset") || // Skip index properties.
          jn(F, T))) && w.push(F);
        return w;
      }
      function za(e) {
        var r = e.length;
        return r ? e[Ql(0, r - 1)] : i;
      }
      function c_(e, r) {
        return hs(Xt(e), $r(r, 0, e.length));
      }
      function h_(e) {
        return hs(Xt(e));
      }
      function Kl(e, r, s) {
        (s !== i && !An(e[r], s) || s === i && !(r in e)) && kn(e, r, s);
      }
      function qi(e, r, s) {
        var f = e[r];
        (!(He.call(e, r) && An(f, s)) || s === i && !(r in e)) && kn(e, r, s);
      }
      function jo(e, r) {
        for (var s = e.length; s--; )
          if (An(e[s][0], r))
            return s;
        return -1;
      }
      function d_(e, r, s, f) {
        return mr(e, function(c, g, b) {
          r(f, c, s(c), b);
        }), f;
      }
      function Xa(e, r) {
        return e && Nn(r, vt(r), e);
      }
      function p_(e, r) {
        return e && Nn(r, Jt(r), e);
      }
      function kn(e, r, s) {
        r == "__proto__" && Xo ? Xo(e, r, {
          configurable: !0,
          enumerable: !0,
          value: s,
          writable: !0
        }) : e[r] = s;
      }
      function Gl(e, r) {
        for (var s = -1, f = r.length, c = O(f), g = e == null; ++s < f; )
          c[s] = g ? i : Eu(e, r[s]);
        return c;
      }
      function $r(e, r, s) {
        return e === e && (s !== i && (e = e <= s ? e : s), r !== i && (e = e >= r ? e : r)), e;
      }
      function _n(e, r, s, f, c, g) {
        var b, w = r & m, T = r & A, F = r & S;
        if (s && (b = c ? s(e, f, c, g) : s(e)), b !== i)
          return b;
        if (!Ze(e))
          return e;
        var M = ge(e);
        if (M) {
          if (b = nm(e), !w)
            return Xt(e, b);
        } else {
          var W = Dt(e), V = W == dr || W == y;
          if (xr(e))
            return _c(e, w);
          if (W == N || W == bt || V && !c) {
            if (b = T || V ? {} : Lc(e), !w)
              return T ? q_(e, p_(b, e)) : Y_(e, Xa(b, e));
          } else {
            if (!Ke[W])
              return c ? e : {};
            b = rm(e, W, w);
          }
        }
        g || (g = new Tn());
        var ee = g.get(e);
        if (ee)
          return ee;
        g.set(e, b), ch(e) ? e.forEach(function(se) {
          b.add(_n(se, r, s, se, e, g));
        }) : fh(e) && e.forEach(function(se, Ae) {
          b.set(Ae, _n(se, r, s, Ae, e, g));
        });
        var oe = F ? T ? fu : uu : T ? Jt : vt, we = M ? i : oe(e);
        return pn(we || e, function(se, Ae) {
          we && (Ae = se, se = e[Ae]), qi(b, Ae, _n(se, r, s, Ae, e, g));
        }), b;
      }
      function g_(e) {
        var r = vt(e);
        return function(s) {
          return Va(s, e, r);
        };
      }
      function Va(e, r, s) {
        var f = s.length;
        if (e == null)
          return !f;
        for (e = Ue(e); f--; ) {
          var c = s[f], g = r[c], b = e[c];
          if (b === i && !(c in e) || !g(b))
            return !1;
        }
        return !0;
      }
      function Ja(e, r, s) {
        if (typeof e != "function")
          throw new gn(a);
        return Qi(function() {
          e.apply(i, s);
        }, r);
      }
      function zi(e, r, s, f) {
        var c = -1, g = Lo, b = !0, w = e.length, T = [], F = r.length;
        if (!w)
          return T;
        s && (r = Ve(r, tn(s))), f ? (g = Il, b = !1) : r.length >= l && (g = Hi, b = !1, r = new Ur(r));
        e:
          for (; ++c < w; ) {
            var M = e[c], W = s == null ? M : s(M);
            if (M = f || M !== 0 ? M : 0, b && W === W) {
              for (var V = F; V--; )
                if (r[V] === W)
                  continue e;
              T.push(M);
            } else
              g(r, W, f) || T.push(M);
          }
        return T;
      }
      var mr = xc(Mn), ka = xc(ql, !0);
      function v_(e, r) {
        var s = !0;
        return mr(e, function(f, c, g) {
          return s = !!r(f, c, g), s;
        }), s;
      }
      function es(e, r, s) {
        for (var f = -1, c = e.length; ++f < c; ) {
          var g = e[f], b = r(g);
          if (b != null && (w === i ? b === b && !rn(b) : s(b, w)))
            var w = b, T = g;
        }
        return T;
      }
      function __(e, r, s, f) {
        var c = e.length;
        for (s = be(s), s < 0 && (s = -s > c ? 0 : c + s), f = f === i || f > c ? c : be(f), f < 0 && (f += c), f = s > f ? 0 : dh(f); s < f; )
          e[s++] = r;
        return e;
      }
      function Za(e, r) {
        var s = [];
        return mr(e, function(f, c, g) {
          r(f, c, g) && s.push(f);
        }), s;
      }
      function St(e, r, s, f, c) {
        var g = -1, b = e.length;
        for (s || (s = om), c || (c = []); ++g < b; ) {
          var w = e[g];
          r > 0 && s(w) ? r > 1 ? St(w, r - 1, s, f, c) : gr(c, w) : f || (c[c.length] = w);
        }
        return c;
      }
      var Yl = Ec(), Qa = Ec(!0);
      function Mn(e, r) {
        return e && Yl(e, r, vt);
      }
      function ql(e, r) {
        return e && Qa(e, r, vt);
      }
      function ts(e, r) {
        return pr(r, function(s) {
          return er(e[s]);
        });
      }
      function Kr(e, r) {
        r = yr(r, e);
        for (var s = 0, f = r.length; e != null && s < f; )
          e = e[Ln(r[s++])];
        return s && s == f ? e : i;
      }
      function ja(e, r, s) {
        var f = r(e);
        return ge(e) ? f : gr(f, s(e));
      }
      function Wt(e) {
        return e == null ? e === i ? J : $ : Wr && Wr in Ue(e) ? j_(e) : hm(e);
      }
      function zl(e, r) {
        return e > r;
      }
      function m_(e, r) {
        return e != null && He.call(e, r);
      }
      function b_(e, r) {
        return e != null && r in Ue(e);
      }
      function y_(e, r, s) {
        return e >= It(r, s) && e < dt(r, s);
      }
      function Xl(e, r, s) {
        for (var f = s ? Il : Lo, c = e[0].length, g = e.length, b = g, w = O(g), T = 1 / 0, F = []; b--; ) {
          var M = e[b];
          b && r && (M = Ve(M, tn(r))), T = It(M.length, T), w[b] = !s && (r || c >= 120 && M.length >= 120) ? new Ur(b && M) : i;
        }
        M = e[0];
        var W = -1, V = w[0];
        e:
          for (; ++W < c && F.length < T; ) {
            var ee = M[W], oe = r ? r(ee) : ee;
            if (ee = s || ee !== 0 ? ee : 0, !(V ? Hi(V, oe) : f(F, oe, s))) {
              for (b = g; --b; ) {
                var we = w[b];
                if (!(we ? Hi(we, oe) : f(e[b], oe, s)))
                  continue e;
              }
              V && V.push(oe), F.push(ee);
            }
          }
        return F;
      }
      function w_(e, r, s, f) {
        return Mn(e, function(c, g, b) {
          r(f, s(c), g, b);
        }), f;
      }
      function Xi(e, r, s) {
        r = yr(r, e), e = Uc(e, r);
        var f = e == null ? e : e[Ln(bn(r))];
        return f == null ? i : en(f, e, s);
      }
      function ec(e) {
        return je(e) && Wt(e) == bt;
      }
      function x_(e) {
        return je(e) && Wt(e) == ye;
      }
      function E_(e) {
        return je(e) && Wt(e) == ar;
      }
      function Vi(e, r, s, f, c) {
        return e === r ? !0 : e == null || r == null || !je(e) && !je(r) ? e !== e && r !== r : S_(e, r, s, f, Vi, c);
      }
      function S_(e, r, s, f, c, g) {
        var b = ge(e), w = ge(r), T = b ? Fn : Dt(e), F = w ? Fn : Dt(r);
        T = T == bt ? N : T, F = F == bt ? N : F;
        var M = T == N, W = F == N, V = T == F;
        if (V && xr(e)) {
          if (!xr(r))
            return !1;
          b = !0, M = !1;
        }
        if (V && !M)
          return g || (g = new Tn()), b || bi(e) ? Fc(e, r, s, f, c, g) : Z_(e, r, T, s, f, c, g);
        if (!(s & C)) {
          var ee = M && He.call(e, "__wrapped__"), oe = W && He.call(r, "__wrapped__");
          if (ee || oe) {
            var we = ee ? e.value() : e, se = oe ? r.value() : r;
            return g || (g = new Tn()), c(we, se, s, f, g);
          }
        }
        return V ? (g || (g = new Tn()), Q_(e, r, s, f, c, g)) : !1;
      }
      function T_(e) {
        return je(e) && Dt(e) == x;
      }
      function Vl(e, r, s, f) {
        var c = s.length, g = c, b = !f;
        if (e == null)
          return !g;
        for (e = Ue(e); c--; ) {
          var w = s[c];
          if (b && w[2] ? w[1] !== e[w[0]] : !(w[0] in e))
            return !1;
        }
        for (; ++c < g; ) {
          w = s[c];
          var T = w[0], F = e[T], M = w[1];
          if (b && w[2]) {
            if (F === i && !(T in e))
              return !1;
          } else {
            var W = new Tn();
            if (f)
              var V = f(F, M, T, e, r, W);
            if (!(V === i ? Vi(M, F, C | I, f, W) : V))
              return !1;
          }
        }
        return !0;
      }
      function tc(e) {
        if (!Ze(e) || lm(e))
          return !1;
        var r = er(e) ? Tv : gg;
        return r.test(Yr(e));
      }
      function A_(e) {
        return je(e) && Wt(e) == G;
      }
      function C_(e) {
        return je(e) && Dt(e) == K;
      }
      function O_(e) {
        return je(e) && ms(e.length) && !!qe[Wt(e)];
      }
      function nc(e) {
        return typeof e == "function" ? e : e == null ? kt : typeof e == "object" ? ge(e) ? oc(e[0], e[1]) : ic(e) : Sh(e);
      }
      function Jl(e) {
        if (!Zi(e))
          return Rv(e);
        var r = [];
        for (var s in Ue(e))
          He.call(e, s) && s != "constructor" && r.push(s);
        return r;
      }
      function I_(e) {
        if (!Ze(e))
          return cm(e);
        var r = Zi(e), s = [];
        for (var f in e)
          f == "constructor" && (r || !He.call(e, f)) || s.push(f);
        return s;
      }
      function kl(e, r) {
        return e < r;
      }
      function rc(e, r) {
        var s = -1, f = Vt(e) ? O(e.length) : [];
        return mr(e, function(c, g, b) {
          f[++s] = r(c, g, b);
        }), f;
      }
      function ic(e) {
        var r = cu(e);
        return r.length == 1 && r[0][2] ? Wc(r[0][0], r[0][1]) : function(s) {
          return s === e || Vl(s, e, r);
        };
      }
      function oc(e, r) {
        return du(e) && Bc(r) ? Wc(Ln(e), r) : function(s) {
          var f = Eu(s, e);
          return f === i && f === r ? Su(s, e) : Vi(r, f, C | I);
        };
      }
      function ns(e, r, s, f, c) {
        e !== r && Yl(r, function(g, b) {
          if (c || (c = new Tn()), Ze(g))
            D_(e, r, b, s, ns, f, c);
          else {
            var w = f ? f(gu(e, b), g, b + "", e, r, c) : i;
            w === i && (w = g), Kl(e, b, w);
          }
        }, Jt);
      }
      function D_(e, r, s, f, c, g, b) {
        var w = gu(e, s), T = gu(r, s), F = b.get(T);
        if (F) {
          Kl(e, s, F);
          return;
        }
        var M = g ? g(w, T, s + "", e, r, b) : i, W = M === i;
        if (W) {
          var V = ge(T), ee = !V && xr(T), oe = !V && !ee && bi(T);
          M = T, V || ee || oe ? ge(w) ? M = w : tt(w) ? M = Xt(w) : ee ? (W = !1, M = _c(T, !0)) : oe ? (W = !1, M = mc(T, !0)) : M = [] : ji(T) || qr(T) ? (M = w, qr(w) ? M = ph(w) : (!Ze(w) || er(w)) && (M = Lc(T))) : W = !1;
        }
        W && (b.set(T, M), c(M, T, f, g, b), b.delete(T)), Kl(e, s, M);
      }
      function sc(e, r) {
        var s = e.length;
        if (s)
          return r += r < 0 ? s : 0, jn(r, s) ? e[r] : i;
      }
      function lc(e, r, s) {
        r.length ? r = Ve(r, function(g) {
          return ge(g) ? function(b) {
            return Kr(b, g.length === 1 ? g[0] : g);
          } : g;
        }) : r = [kt];
        var f = -1;
        r = Ve(r, tn(re()));
        var c = rc(e, function(g, b, w) {
          var T = Ve(r, function(F) {
            return F(g);
          });
          return { criteria: T, index: ++f, value: g };
        });
        return rv(c, function(g, b) {
          return G_(g, b, s);
        });
      }
      function R_(e, r) {
        return uc(e, r, function(s, f) {
          return Su(e, f);
        });
      }
      function uc(e, r, s) {
        for (var f = -1, c = r.length, g = {}; ++f < c; ) {
          var b = r[f], w = Kr(e, b);
          s(w, b) && Ji(g, yr(b, e), w);
        }
        return g;
      }
      function P_(e) {
        return function(r) {
          return Kr(r, e);
        };
      }
      function Zl(e, r, s, f) {
        var c = f ? nv : ui, g = -1, b = r.length, w = e;
        for (e === r && (r = Xt(r)), s && (w = Ve(e, tn(s))); ++g < b; )
          for (var T = 0, F = r[g], M = s ? s(F) : F; (T = c(w, M, T, f)) > -1; )
            w !== e && zo.call(w, T, 1), zo.call(e, T, 1);
        return e;
      }
      function fc(e, r) {
        for (var s = e ? r.length : 0, f = s - 1; s--; ) {
          var c = r[s];
          if (s == f || c !== g) {
            var g = c;
            jn(c) ? zo.call(e, c, 1) : tu(e, c);
          }
        }
        return e;
      }
      function Ql(e, r) {
        return e + Jo(Ga() * (r - e + 1));
      }
      function F_(e, r, s, f) {
        for (var c = -1, g = dt(Vo((r - e) / (s || 1)), 0), b = O(g); g--; )
          b[f ? g : ++c] = e, e += s;
        return b;
      }
      function jl(e, r) {
        var s = "";
        if (!e || r < 1 || r > ie)
          return s;
        do
          r % 2 && (s += e), r = Jo(r / 2), r && (e += e);
        while (r);
        return s;
      }
      function Ee(e, r) {
        return vu(Hc(e, r, kt), e + "");
      }
      function M_(e) {
        return za(yi(e));
      }
      function N_(e, r) {
        var s = yi(e);
        return hs(s, $r(r, 0, s.length));
      }
      function Ji(e, r, s, f) {
        if (!Ze(e))
          return e;
        r = yr(r, e);
        for (var c = -1, g = r.length, b = g - 1, w = e; w != null && ++c < g; ) {
          var T = Ln(r[c]), F = s;
          if (T === "__proto__" || T === "constructor" || T === "prototype")
            return e;
          if (c != b) {
            var M = w[T];
            F = f ? f(M, T, w) : i, F === i && (F = Ze(M) ? M : jn(r[c + 1]) ? [] : {});
          }
          qi(w, T, F), w = w[T];
        }
        return e;
      }
      var ac = ko ? function(e, r) {
        return ko.set(e, r), e;
      } : kt, L_ = Xo ? function(e, r) {
        return Xo(e, "toString", {
          configurable: !0,
          enumerable: !1,
          value: Au(r),
          writable: !0
        });
      } : kt;
      function B_(e) {
        return hs(yi(e));
      }
      function mn(e, r, s) {
        var f = -1, c = e.length;
        r < 0 && (r = -r > c ? 0 : c + r), s = s > c ? c : s, s < 0 && (s += c), c = r > s ? 0 : s - r >>> 0, r >>>= 0;
        for (var g = O(c); ++f < c; )
          g[f] = e[f + r];
        return g;
      }
      function W_(e, r) {
        var s;
        return mr(e, function(f, c, g) {
          return s = r(f, c, g), !s;
        }), !!s;
      }
      function rs(e, r, s) {
        var f = 0, c = e == null ? f : e.length;
        if (typeof r == "number" && r === r && c <= hn) {
          for (; f < c; ) {
            var g = f + c >>> 1, b = e[g];
            b !== null && !rn(b) && (s ? b <= r : b < r) ? f = g + 1 : c = g;
          }
          return c;
        }
        return eu(e, r, kt, s);
      }
      function eu(e, r, s, f) {
        var c = 0, g = e == null ? 0 : e.length;
        if (g === 0)
          return 0;
        r = s(r);
        for (var b = r !== r, w = r === null, T = rn(r), F = r === i; c < g; ) {
          var M = Jo((c + g) / 2), W = s(e[M]), V = W !== i, ee = W === null, oe = W === W, we = rn(W);
          if (b)
            var se = f || oe;
          else
            F ? se = oe && (f || V) : w ? se = oe && V && (f || !ee) : T ? se = oe && V && !ee && (f || !we) : ee || we ? se = !1 : se = f ? W <= r : W < r;
          se ? c = M + 1 : g = M;
        }
        return It(g, ot);
      }
      function cc(e, r) {
        for (var s = -1, f = e.length, c = 0, g = []; ++s < f; ) {
          var b = e[s], w = r ? r(b) : b;
          if (!s || !An(w, T)) {
            var T = w;
            g[c++] = b === 0 ? 0 : b;
          }
        }
        return g;
      }
      function hc(e) {
        return typeof e == "number" ? e : rn(e) ? gt : +e;
      }
      function nn(e) {
        if (typeof e == "string")
          return e;
        if (ge(e))
          return Ve(e, nn) + "";
        if (rn(e))
          return Ya ? Ya.call(e) : "";
        var r = e + "";
        return r == "0" && 1 / e == -fe ? "-0" : r;
      }
      function br(e, r, s) {
        var f = -1, c = Lo, g = e.length, b = !0, w = [], T = w;
        if (s)
          b = !1, c = Il;
        else if (g >= l) {
          var F = r ? null : J_(e);
          if (F)
            return Wo(F);
          b = !1, c = Hi, T = new Ur();
        } else
          T = r ? [] : w;
        e:
          for (; ++f < g; ) {
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
      function tu(e, r) {
        return r = yr(r, e), e = Uc(e, r), e == null || delete e[Ln(bn(r))];
      }
      function dc(e, r, s, f) {
        return Ji(e, r, s(Kr(e, r)), f);
      }
      function is(e, r, s, f) {
        for (var c = e.length, g = f ? c : -1; (f ? g-- : ++g < c) && r(e[g], g, e); )
          ;
        return s ? mn(e, f ? 0 : g, f ? g + 1 : c) : mn(e, f ? g + 1 : 0, f ? c : g);
      }
      function pc(e, r) {
        var s = e;
        return s instanceof Oe && (s = s.value()), Dl(r, function(f, c) {
          return c.func.apply(c.thisArg, gr([f], c.args));
        }, s);
      }
      function nu(e, r, s) {
        var f = e.length;
        if (f < 2)
          return f ? br(e[0]) : [];
        for (var c = -1, g = O(f); ++c < f; )
          for (var b = e[c], w = -1; ++w < f; )
            w != c && (g[c] = zi(g[c] || b, e[w], r, s));
        return br(St(g, 1), r, s);
      }
      function gc(e, r, s) {
        for (var f = -1, c = e.length, g = r.length, b = {}; ++f < c; ) {
          var w = f < g ? r[f] : i;
          s(b, e[f], w);
        }
        return b;
      }
      function ru(e) {
        return tt(e) ? e : [];
      }
      function iu(e) {
        return typeof e == "function" ? e : kt;
      }
      function yr(e, r) {
        return ge(e) ? e : du(e, r) ? [e] : Yc(Le(e));
      }
      var H_ = Ee;
      function wr(e, r, s) {
        var f = e.length;
        return s = s === i ? f : s, !r && s >= f ? e : mn(e, r, s);
      }
      var vc = Av || function(e) {
        return Et.clearTimeout(e);
      };
      function _c(e, r) {
        if (r)
          return e.slice();
        var s = e.length, f = Wa ? Wa(s) : new e.constructor(s);
        return e.copy(f), f;
      }
      function ou(e) {
        var r = new e.constructor(e.byteLength);
        return new Yo(r).set(new Yo(e)), r;
      }
      function U_(e, r) {
        var s = r ? ou(e.buffer) : e.buffer;
        return new e.constructor(s, e.byteOffset, e.byteLength);
      }
      function $_(e) {
        var r = new e.constructor(e.source, ea.exec(e));
        return r.lastIndex = e.lastIndex, r;
      }
      function K_(e) {
        return Yi ? Ue(Yi.call(e)) : {};
      }
      function mc(e, r) {
        var s = r ? ou(e.buffer) : e.buffer;
        return new e.constructor(s, e.byteOffset, e.length);
      }
      function bc(e, r) {
        if (e !== r) {
          var s = e !== i, f = e === null, c = e === e, g = rn(e), b = r !== i, w = r === null, T = r === r, F = rn(r);
          if (!w && !F && !g && e > r || g && b && T && !w && !F || f && b && T || !s && T || !c)
            return 1;
          if (!f && !g && !F && e < r || F && s && c && !f && !g || w && s && c || !b && c || !T)
            return -1;
        }
        return 0;
      }
      function G_(e, r, s) {
        for (var f = -1, c = e.criteria, g = r.criteria, b = c.length, w = s.length; ++f < b; ) {
          var T = bc(c[f], g[f]);
          if (T) {
            if (f >= w)
              return T;
            var F = s[f];
            return T * (F == "desc" ? -1 : 1);
          }
        }
        return e.index - r.index;
      }
      function yc(e, r, s, f) {
        for (var c = -1, g = e.length, b = s.length, w = -1, T = r.length, F = dt(g - b, 0), M = O(T + F), W = !f; ++w < T; )
          M[w] = r[w];
        for (; ++c < b; )
          (W || c < g) && (M[s[c]] = e[c]);
        for (; F--; )
          M[w++] = e[c++];
        return M;
      }
      function wc(e, r, s, f) {
        for (var c = -1, g = e.length, b = -1, w = s.length, T = -1, F = r.length, M = dt(g - w, 0), W = O(M + F), V = !f; ++c < M; )
          W[c] = e[c];
        for (var ee = c; ++T < F; )
          W[ee + T] = r[T];
        for (; ++b < w; )
          (V || c < g) && (W[ee + s[b]] = e[c++]);
        return W;
      }
      function Xt(e, r) {
        var s = -1, f = e.length;
        for (r || (r = O(f)); ++s < f; )
          r[s] = e[s];
        return r;
      }
      function Nn(e, r, s, f) {
        var c = !s;
        s || (s = {});
        for (var g = -1, b = r.length; ++g < b; ) {
          var w = r[g], T = f ? f(s[w], e[w], w, s, e) : i;
          T === i && (T = e[w]), c ? kn(s, w, T) : qi(s, w, T);
        }
        return s;
      }
      function Y_(e, r) {
        return Nn(e, hu(e), r);
      }
      function q_(e, r) {
        return Nn(e, Mc(e), r);
      }
      function os(e, r) {
        return function(s, f) {
          var c = ge(s) ? kg : d_, g = r ? r() : {};
          return c(s, e, re(f, 2), g);
        };
      }
      function vi(e) {
        return Ee(function(r, s) {
          var f = -1, c = s.length, g = c > 1 ? s[c - 1] : i, b = c > 2 ? s[2] : i;
          for (g = e.length > 3 && typeof g == "function" ? (c--, g) : i, b && Ht(s[0], s[1], b) && (g = c < 3 ? i : g, c = 1), r = Ue(r); ++f < c; ) {
            var w = s[f];
            w && e(r, w, f, g);
          }
          return r;
        });
      }
      function xc(e, r) {
        return function(s, f) {
          if (s == null)
            return s;
          if (!Vt(s))
            return e(s, f);
          for (var c = s.length, g = r ? c : -1, b = Ue(s); (r ? g-- : ++g < c) && f(b[g], g, b) !== !1; )
            ;
          return s;
        };
      }
      function Ec(e) {
        return function(r, s, f) {
          for (var c = -1, g = Ue(r), b = f(r), w = b.length; w--; ) {
            var T = b[e ? w : ++c];
            if (s(g[T], T, g) === !1)
              break;
          }
          return r;
        };
      }
      function z_(e, r, s) {
        var f = r & P, c = ki(e);
        function g() {
          var b = this && this !== Et && this instanceof g ? c : e;
          return b.apply(f ? s : this, arguments);
        }
        return g;
      }
      function Sc(e) {
        return function(r) {
          r = Le(r);
          var s = fi(r) ? Sn(r) : i, f = s ? s[0] : r.charAt(0), c = s ? wr(s, 1).join("") : r.slice(1);
          return f[e]() + c;
        };
      }
      function _i(e) {
        return function(r) {
          return Dl(xh(wh(r).replace(Lg, "")), e, "");
        };
      }
      function ki(e) {
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
          var s = gi(e.prototype), f = e.apply(s, r);
          return Ze(f) ? f : s;
        };
      }
      function X_(e, r, s) {
        var f = ki(e);
        function c() {
          for (var g = arguments.length, b = O(g), w = g, T = mi(c); w--; )
            b[w] = arguments[w];
          var F = g < 3 && b[0] !== T && b[g - 1] !== T ? [] : vr(b, T);
          if (g -= F.length, g < s)
            return Ic(
              e,
              r,
              ss,
              c.placeholder,
              i,
              b,
              F,
              i,
              i,
              s - g
            );
          var M = this && this !== Et && this instanceof c ? f : e;
          return en(M, this, b);
        }
        return c;
      }
      function Tc(e) {
        return function(r, s, f) {
          var c = Ue(r);
          if (!Vt(r)) {
            var g = re(s, 3);
            r = vt(r), s = function(w) {
              return g(c[w], w, c);
            };
          }
          var b = e(r, s, f);
          return b > -1 ? c[g ? r[b] : b] : i;
        };
      }
      function Ac(e) {
        return Qn(function(r) {
          var s = r.length, f = s, c = vn.prototype.thru;
          for (e && r.reverse(); f--; ) {
            var g = r[f];
            if (typeof g != "function")
              throw new gn(a);
            if (c && !b && as(g) == "wrapper")
              var b = new vn([], !0);
          }
          for (f = b ? f : s; ++f < s; ) {
            g = r[f];
            var w = as(g), T = w == "wrapper" ? au(g) : i;
            T && pu(T[0]) && T[1] == (Se | z | te | Re) && !T[4].length && T[9] == 1 ? b = b[as(T[0])].apply(b, T[3]) : b = g.length == 1 && pu(g) ? b[w]() : b.thru(g);
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
      function ss(e, r, s, f, c, g, b, w, T, F) {
        var M = r & Se, W = r & P, V = r & B, ee = r & (z | L), oe = r & ue, we = V ? i : ki(e);
        function se() {
          for (var Ae = arguments.length, De = O(Ae), on = Ae; on--; )
            De[on] = arguments[on];
          if (ee)
            var Ut = mi(se), sn = ov(De, Ut);
          if (f && (De = yc(De, f, c, ee)), g && (De = wc(De, g, b, ee)), Ae -= sn, ee && Ae < F) {
            var nt = vr(De, Ut);
            return Ic(
              e,
              r,
              ss,
              se.placeholder,
              s,
              De,
              nt,
              w,
              T,
              F - Ae
            );
          }
          var Cn = W ? s : this, nr = V ? Cn[e] : e;
          return Ae = De.length, w ? De = dm(De, w) : oe && Ae > 1 && De.reverse(), M && T < Ae && (De.length = T), this && this !== Et && this instanceof se && (nr = we || ki(nr)), nr.apply(Cn, De);
        }
        return se;
      }
      function Cc(e, r) {
        return function(s, f) {
          return w_(s, e, r(f), {});
        };
      }
      function ls(e, r) {
        return function(s, f) {
          var c;
          if (s === i && f === i)
            return r;
          if (s !== i && (c = s), f !== i) {
            if (c === i)
              return f;
            typeof s == "string" || typeof f == "string" ? (s = nn(s), f = nn(f)) : (s = hc(s), f = hc(f)), c = e(s, f);
          }
          return c;
        };
      }
      function su(e) {
        return Qn(function(r) {
          return r = Ve(r, tn(re())), Ee(function(s) {
            var f = this;
            return e(r, function(c) {
              return en(c, f, s);
            });
          });
        });
      }
      function us(e, r) {
        r = r === i ? " " : nn(r);
        var s = r.length;
        if (s < 2)
          return s ? jl(r, e) : r;
        var f = jl(r, Vo(e / ai(r)));
        return fi(r) ? wr(Sn(f), 0, e).join("") : f.slice(0, e);
      }
      function V_(e, r, s, f) {
        var c = r & P, g = ki(e);
        function b() {
          for (var w = -1, T = arguments.length, F = -1, M = f.length, W = O(M + T), V = this && this !== Et && this instanceof b ? g : e; ++F < M; )
            W[F] = f[F];
          for (; T--; )
            W[F++] = arguments[++w];
          return en(V, c ? s : this, W);
        }
        return b;
      }
      function Oc(e) {
        return function(r, s, f) {
          return f && typeof f != "number" && Ht(r, s, f) && (s = f = i), r = tr(r), s === i ? (s = r, r = 0) : s = tr(s), f = f === i ? r < s ? 1 : -1 : tr(f), F_(r, s, f, e);
        };
      }
      function fs(e) {
        return function(r, s) {
          return typeof r == "string" && typeof s == "string" || (r = yn(r), s = yn(s)), e(r, s);
        };
      }
      function Ic(e, r, s, f, c, g, b, w, T, F) {
        var M = r & z, W = M ? b : i, V = M ? i : b, ee = M ? g : i, oe = M ? i : g;
        r |= M ? te : xe, r &= ~(M ? xe : te), r & q || (r &= ~(P | B));
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
        return pu(e) && $c(se, we), se.placeholder = f, Kc(se, e, r);
      }
      function lu(e) {
        var r = ht[e];
        return function(s, f) {
          if (s = yn(s), f = f == null ? 0 : It(be(f), 292), f && Ka(s)) {
            var c = (Le(s) + "e").split("e"), g = r(c[0] + "e" + (+c[1] + f));
            return c = (Le(g) + "e").split("e"), +(c[0] + "e" + (+c[1] - f));
          }
          return r(s);
        };
      }
      var J_ = di && 1 / Wo(new di([, -0]))[1] == fe ? function(e) {
        return new di(e);
      } : Iu;
      function Dc(e) {
        return function(r) {
          var s = Dt(r);
          return s == x ? Bl(r) : s == K ? hv(r) : iv(r, e(r));
        };
      }
      function Zn(e, r, s, f, c, g, b, w) {
        var T = r & B;
        if (!T && typeof e != "function")
          throw new gn(a);
        var F = f ? f.length : 0;
        if (F || (r &= ~(te | xe), f = c = i), b = b === i ? b : dt(be(b), 0), w = w === i ? w : be(w), F -= c ? c.length : 0, r & xe) {
          var M = f, W = c;
          f = c = i;
        }
        var V = T ? i : au(e), ee = [
          e,
          r,
          s,
          f,
          c,
          M,
          W,
          g,
          b,
          w
        ];
        if (V && am(ee, V), e = ee[0], r = ee[1], s = ee[2], f = ee[3], c = ee[4], w = ee[9] = ee[9] === i ? T ? 0 : e.length : dt(ee[9] - F, 0), !w && r & (z | L) && (r &= ~(z | L)), !r || r == P)
          var oe = z_(e, r, s);
        else
          r == z || r == L ? oe = X_(e, r, w) : (r == te || r == (P | te)) && !c.length ? oe = V_(e, r, s, f) : oe = ss.apply(i, ee);
        var we = V ? ac : $c;
        return Kc(we(oe, ee), e, r);
      }
      function Rc(e, r, s, f) {
        return e === i || An(e, hi[s]) && !He.call(f, s) ? r : e;
      }
      function Pc(e, r, s, f, c, g) {
        return Ze(e) && Ze(r) && (g.set(r, e), ns(e, r, i, Pc, g), g.delete(r)), e;
      }
      function k_(e) {
        return ji(e) ? i : e;
      }
      function Fc(e, r, s, f, c, g) {
        var b = s & C, w = e.length, T = r.length;
        if (w != T && !(b && T > w))
          return !1;
        var F = g.get(e), M = g.get(r);
        if (F && M)
          return F == r && M == e;
        var W = -1, V = !0, ee = s & I ? new Ur() : i;
        for (g.set(e, r), g.set(r, e); ++W < w; ) {
          var oe = e[W], we = r[W];
          if (f)
            var se = b ? f(we, oe, W, r, e, g) : f(oe, we, W, e, r, g);
          if (se !== i) {
            if (se)
              continue;
            V = !1;
            break;
          }
          if (ee) {
            if (!Rl(r, function(Ae, De) {
              if (!Hi(ee, De) && (oe === Ae || c(oe, Ae, s, f, g)))
                return ee.push(De);
            })) {
              V = !1;
              break;
            }
          } else if (!(oe === we || c(oe, we, s, f, g))) {
            V = !1;
            break;
          }
        }
        return g.delete(e), g.delete(r), V;
      }
      function Z_(e, r, s, f, c, g, b) {
        switch (s) {
          case Pe:
            if (e.byteLength != r.byteLength || e.byteOffset != r.byteOffset)
              return !1;
            e = e.buffer, r = r.buffer;
          case ye:
            return !(e.byteLength != r.byteLength || !g(new Yo(e), new Yo(r)));
          case zn:
          case ar:
          case D:
            return An(+e, +r);
          case hr:
            return e.name == r.name && e.message == r.message;
          case G:
          case H:
            return e == r + "";
          case x:
            var w = Bl;
          case K:
            var T = f & C;
            if (w || (w = Wo), e.size != r.size && !T)
              return !1;
            var F = b.get(e);
            if (F)
              return F == r;
            f |= I, b.set(e, r);
            var M = Fc(w(e), w(r), f, c, g, b);
            return b.delete(e), M;
          case ne:
            if (Yi)
              return Yi.call(e) == Yi.call(r);
        }
        return !1;
      }
      function Q_(e, r, s, f, c, g) {
        var b = s & C, w = uu(e), T = w.length, F = uu(r), M = F.length;
        if (T != M && !b)
          return !1;
        for (var W = T; W--; ) {
          var V = w[W];
          if (!(b ? V in r : He.call(r, V)))
            return !1;
        }
        var ee = g.get(e), oe = g.get(r);
        if (ee && oe)
          return ee == r && oe == e;
        var we = !0;
        g.set(e, r), g.set(r, e);
        for (var se = b; ++W < T; ) {
          V = w[W];
          var Ae = e[V], De = r[V];
          if (f)
            var on = b ? f(De, Ae, V, r, e, g) : f(Ae, De, V, e, r, g);
          if (!(on === i ? Ae === De || c(Ae, De, s, f, g) : on)) {
            we = !1;
            break;
          }
          se || (se = V == "constructor");
        }
        if (we && !se) {
          var Ut = e.constructor, sn = r.constructor;
          Ut != sn && "constructor" in e && "constructor" in r && !(typeof Ut == "function" && Ut instanceof Ut && typeof sn == "function" && sn instanceof sn) && (we = !1);
        }
        return g.delete(e), g.delete(r), we;
      }
      function Qn(e) {
        return vu(Hc(e, i, Vc), e + "");
      }
      function uu(e) {
        return ja(e, vt, hu);
      }
      function fu(e) {
        return ja(e, Jt, Mc);
      }
      var au = ko ? function(e) {
        return ko.get(e);
      } : Iu;
      function as(e) {
        for (var r = e.name + "", s = pi[r], f = He.call(pi, r) ? s.length : 0; f--; ) {
          var c = s[f], g = c.func;
          if (g == null || g == e)
            return c.name;
        }
        return r;
      }
      function mi(e) {
        var r = He.call(d, "placeholder") ? d : e;
        return r.placeholder;
      }
      function re() {
        var e = d.iteratee || Cu;
        return e = e === Cu ? nc : e, arguments.length ? e(arguments[0], arguments[1]) : e;
      }
      function cs(e, r) {
        var s = e.__data__;
        return sm(r) ? s[typeof r == "string" ? "string" : "hash"] : s.map;
      }
      function cu(e) {
        for (var r = vt(e), s = r.length; s--; ) {
          var f = r[s], c = e[f];
          r[s] = [f, c, Bc(c)];
        }
        return r;
      }
      function Gr(e, r) {
        var s = fv(e, r);
        return tc(s) ? s : i;
      }
      function j_(e) {
        var r = He.call(e, Wr), s = e[Wr];
        try {
          e[Wr] = i;
          var f = !0;
        } catch {
        }
        var c = Ko.call(e);
        return f && (r ? e[Wr] = s : delete e[Wr]), c;
      }
      var hu = Hl ? function(e) {
        return e == null ? [] : (e = Ue(e), pr(Hl(e), function(r) {
          return Ua.call(e, r);
        }));
      } : Du, Mc = Hl ? function(e) {
        for (var r = []; e; )
          gr(r, hu(e)), e = qo(e);
        return r;
      } : Du, Dt = Wt;
      (Ul && Dt(new Ul(new ArrayBuffer(1))) != Pe || $i && Dt(new $i()) != x || $l && Dt($l.resolve()) != U || di && Dt(new di()) != K || Ki && Dt(new Ki()) != Q) && (Dt = function(e) {
        var r = Wt(e), s = r == N ? e.constructor : i, f = s ? Yr(s) : "";
        if (f)
          switch (f) {
            case Nv:
              return Pe;
            case Lv:
              return x;
            case Bv:
              return U;
            case Wv:
              return K;
            case Hv:
              return Q;
          }
        return r;
      });
      function em(e, r, s) {
        for (var f = -1, c = s.length; ++f < c; ) {
          var g = s[f], b = g.size;
          switch (g.type) {
            case "drop":
              e += b;
              break;
            case "dropRight":
              r -= b;
              break;
            case "take":
              r = It(r, e + b);
              break;
            case "takeRight":
              e = dt(e, r - b);
              break;
          }
        }
        return { start: e, end: r };
      }
      function tm(e) {
        var r = e.match(lg);
        return r ? r[1].split(ug) : [];
      }
      function Nc(e, r, s) {
        r = yr(r, e);
        for (var f = -1, c = r.length, g = !1; ++f < c; ) {
          var b = Ln(r[f]);
          if (!(g = e != null && s(e, b)))
            break;
          e = e[b];
        }
        return g || ++f != c ? g : (c = e == null ? 0 : e.length, !!c && ms(c) && jn(b, c) && (ge(e) || qr(e)));
      }
      function nm(e) {
        var r = e.length, s = new e.constructor(r);
        return r && typeof e[0] == "string" && He.call(e, "index") && (s.index = e.index, s.input = e.input), s;
      }
      function Lc(e) {
        return typeof e.constructor == "function" && !Zi(e) ? gi(qo(e)) : {};
      }
      function rm(e, r, s) {
        var f = e.constructor;
        switch (r) {
          case ye:
            return ou(e);
          case zn:
          case ar:
            return new f(+e);
          case Pe:
            return U_(e, s);
          case Fe:
          case yt:
          case lt:
          case Bt:
          case wt:
          case Xn:
          case si:
          case xt:
          case zt:
            return mc(e, s);
          case x:
            return new f();
          case D:
          case H:
            return new f(e);
          case G:
            return $_(e);
          case K:
            return new f();
          case ne:
            return K_(e);
        }
      }
      function im(e, r) {
        var s = r.length;
        if (!s)
          return e;
        var f = s - 1;
        return r[f] = (s > 1 ? "& " : "") + r[f], r = r.join(s > 2 ? ", " : " "), e.replace(sg, `{
/* [wrapped with ` + r + `] */
`);
      }
      function om(e) {
        return ge(e) || qr(e) || !!($a && e && e[$a]);
      }
      function jn(e, r) {
        var s = typeof e;
        return r = r ?? ie, !!r && (s == "number" || s != "symbol" && _g.test(e)) && e > -1 && e % 1 == 0 && e < r;
      }
      function Ht(e, r, s) {
        if (!Ze(s))
          return !1;
        var f = typeof r;
        return (f == "number" ? Vt(s) && jn(r, s.length) : f == "string" && r in s) ? An(s[r], e) : !1;
      }
      function du(e, r) {
        if (ge(e))
          return !1;
        var s = typeof e;
        return s == "number" || s == "symbol" || s == "boolean" || e == null || rn(e) ? !0 : ng.test(e) || !tg.test(e) || r != null && e in Ue(r);
      }
      function sm(e) {
        var r = typeof e;
        return r == "string" || r == "number" || r == "symbol" || r == "boolean" ? e !== "__proto__" : e === null;
      }
      function pu(e) {
        var r = as(e), s = d[r];
        if (typeof s != "function" || !(r in Oe.prototype))
          return !1;
        if (e === s)
          return !0;
        var f = au(s);
        return !!f && e === f[0];
      }
      function lm(e) {
        return !!Ba && Ba in e;
      }
      var um = Uo ? er : Ru;
      function Zi(e) {
        var r = e && e.constructor, s = typeof r == "function" && r.prototype || hi;
        return e === s;
      }
      function Bc(e) {
        return e === e && !Ze(e);
      }
      function Wc(e, r) {
        return function(s) {
          return s == null ? !1 : s[e] === r && (r !== i || e in Ue(s));
        };
      }
      function fm(e) {
        var r = vs(e, function(f) {
          return s.size === v && s.clear(), f;
        }), s = r.cache;
        return r;
      }
      function am(e, r) {
        var s = e[1], f = r[1], c = s | f, g = c < (P | B | Se), b = f == Se && s == z || f == Se && s == Re && e[7].length <= r[8] || f == (Se | Re) && r[7].length <= r[8] && s == z;
        if (!(g || b))
          return e;
        f & P && (e[2] = r[2], c |= s & P ? 0 : q);
        var w = r[3];
        if (w) {
          var T = e[3];
          e[3] = T ? yc(T, w, r[4]) : w, e[4] = T ? vr(e[3], _) : r[4];
        }
        return w = r[5], w && (T = e[5], e[5] = T ? wc(T, w, r[6]) : w, e[6] = T ? vr(e[5], _) : r[6]), w = r[7], w && (e[7] = w), f & Se && (e[8] = e[8] == null ? r[8] : It(e[8], r[8])), e[9] == null && (e[9] = r[9]), e[0] = r[0], e[1] = c, e;
      }
      function cm(e) {
        var r = [];
        if (e != null)
          for (var s in Ue(e))
            r.push(s);
        return r;
      }
      function hm(e) {
        return Ko.call(e);
      }
      function Hc(e, r, s) {
        return r = dt(r === i ? e.length - 1 : r, 0), function() {
          for (var f = arguments, c = -1, g = dt(f.length - r, 0), b = O(g); ++c < g; )
            b[c] = f[r + c];
          c = -1;
          for (var w = O(r + 1); ++c < r; )
            w[c] = f[c];
          return w[r] = s(b), en(e, this, w);
        };
      }
      function Uc(e, r) {
        return r.length < 2 ? e : Kr(e, mn(r, 0, -1));
      }
      function dm(e, r) {
        for (var s = e.length, f = It(r.length, s), c = Xt(e); f--; ) {
          var g = r[f];
          e[f] = jn(g, s) ? c[g] : i;
        }
        return e;
      }
      function gu(e, r) {
        if (!(r === "constructor" && typeof e[r] == "function") && r != "__proto__")
          return e[r];
      }
      var $c = Gc(ac), Qi = Ov || function(e, r) {
        return Et.setTimeout(e, r);
      }, vu = Gc(L_);
      function Kc(e, r, s) {
        var f = r + "";
        return vu(e, im(f, pm(tm(f), s)));
      }
      function Gc(e) {
        var r = 0, s = 0;
        return function() {
          var f = Pv(), c = We - (f - s);
          if (s = f, c > 0) {
            if (++r >= Ce)
              return arguments[0];
          } else
            r = 0;
          return e.apply(i, arguments);
        };
      }
      function hs(e, r) {
        var s = -1, f = e.length, c = f - 1;
        for (r = r === i ? f : r; ++s < r; ) {
          var g = Ql(s, c), b = e[g];
          e[g] = e[s], e[s] = b;
        }
        return e.length = r, e;
      }
      var Yc = fm(function(e) {
        var r = [];
        return e.charCodeAt(0) === 46 && r.push(""), e.replace(rg, function(s, f, c, g) {
          r.push(c ? g.replace(cg, "$1") : f || s);
        }), r;
      });
      function Ln(e) {
        if (typeof e == "string" || rn(e))
          return e;
        var r = e + "";
        return r == "0" && 1 / e == -fe ? "-0" : r;
      }
      function Yr(e) {
        if (e != null) {
          try {
            return $o.call(e);
          } catch {
          }
          try {
            return e + "";
          } catch {
          }
        }
        return "";
      }
      function pm(e, r) {
        return pn(st, function(s) {
          var f = "_." + s[0];
          r & s[1] && !Lo(e, f) && e.push(f);
        }), e.sort();
      }
      function qc(e) {
        if (e instanceof Oe)
          return e.clone();
        var r = new vn(e.__wrapped__, e.__chain__);
        return r.__actions__ = Xt(e.__actions__), r.__index__ = e.__index__, r.__values__ = e.__values__, r;
      }
      function gm(e, r, s) {
        (s ? Ht(e, r, s) : r === i) ? r = 1 : r = dt(be(r), 0);
        var f = e == null ? 0 : e.length;
        if (!f || r < 1)
          return [];
        for (var c = 0, g = 0, b = O(Vo(f / r)); c < f; )
          b[g++] = mn(e, c, c += r);
        return b;
      }
      function vm(e) {
        for (var r = -1, s = e == null ? 0 : e.length, f = 0, c = []; ++r < s; ) {
          var g = e[r];
          g && (c[f++] = g);
        }
        return c;
      }
      function _m() {
        var e = arguments.length;
        if (!e)
          return [];
        for (var r = O(e - 1), s = arguments[0], f = e; f--; )
          r[f - 1] = arguments[f];
        return gr(ge(s) ? Xt(s) : [s], St(r, 1));
      }
      var mm = Ee(function(e, r) {
        return tt(e) ? zi(e, St(r, 1, tt, !0)) : [];
      }), bm = Ee(function(e, r) {
        var s = bn(r);
        return tt(s) && (s = i), tt(e) ? zi(e, St(r, 1, tt, !0), re(s, 2)) : [];
      }), ym = Ee(function(e, r) {
        var s = bn(r);
        return tt(s) && (s = i), tt(e) ? zi(e, St(r, 1, tt, !0), i, s) : [];
      });
      function wm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (r = s || r === i ? 1 : be(r), mn(e, r < 0 ? 0 : r, f)) : [];
      }
      function xm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (r = s || r === i ? 1 : be(r), r = f - r, mn(e, 0, r < 0 ? 0 : r)) : [];
      }
      function Em(e, r) {
        return e && e.length ? is(e, re(r, 3), !0, !0) : [];
      }
      function Sm(e, r) {
        return e && e.length ? is(e, re(r, 3), !0) : [];
      }
      function Tm(e, r, s, f) {
        var c = e == null ? 0 : e.length;
        return c ? (s && typeof s != "number" && Ht(e, r, s) && (s = 0, f = c), __(e, r, s, f)) : [];
      }
      function zc(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = s == null ? 0 : be(s);
        return c < 0 && (c = dt(f + c, 0)), Bo(e, re(r, 3), c);
      }
      function Xc(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = f - 1;
        return s !== i && (c = be(s), c = s < 0 ? dt(f + c, 0) : It(c, f - 1)), Bo(e, re(r, 3), c, !0);
      }
      function Vc(e) {
        var r = e == null ? 0 : e.length;
        return r ? St(e, 1) : [];
      }
      function Am(e) {
        var r = e == null ? 0 : e.length;
        return r ? St(e, fe) : [];
      }
      function Cm(e, r) {
        var s = e == null ? 0 : e.length;
        return s ? (r = r === i ? 1 : be(r), St(e, r)) : [];
      }
      function Om(e) {
        for (var r = -1, s = e == null ? 0 : e.length, f = {}; ++r < s; ) {
          var c = e[r];
          f[c[0]] = c[1];
        }
        return f;
      }
      function Jc(e) {
        return e && e.length ? e[0] : i;
      }
      function Im(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = s == null ? 0 : be(s);
        return c < 0 && (c = dt(f + c, 0)), ui(e, r, c);
      }
      function Dm(e) {
        var r = e == null ? 0 : e.length;
        return r ? mn(e, 0, -1) : [];
      }
      var Rm = Ee(function(e) {
        var r = Ve(e, ru);
        return r.length && r[0] === e[0] ? Xl(r) : [];
      }), Pm = Ee(function(e) {
        var r = bn(e), s = Ve(e, ru);
        return r === bn(s) ? r = i : s.pop(), s.length && s[0] === e[0] ? Xl(s, re(r, 2)) : [];
      }), Fm = Ee(function(e) {
        var r = bn(e), s = Ve(e, ru);
        return r = typeof r == "function" ? r : i, r && s.pop(), s.length && s[0] === e[0] ? Xl(s, i, r) : [];
      });
      function Mm(e, r) {
        return e == null ? "" : Dv.call(e, r);
      }
      function bn(e) {
        var r = e == null ? 0 : e.length;
        return r ? e[r - 1] : i;
      }
      function Nm(e, r, s) {
        var f = e == null ? 0 : e.length;
        if (!f)
          return -1;
        var c = f;
        return s !== i && (c = be(s), c = c < 0 ? dt(f + c, 0) : It(c, f - 1)), r === r ? pv(e, r, c) : Bo(e, Ia, c, !0);
      }
      function Lm(e, r) {
        return e && e.length ? sc(e, be(r)) : i;
      }
      var Bm = Ee(kc);
      function kc(e, r) {
        return e && e.length && r && r.length ? Zl(e, r) : e;
      }
      function Wm(e, r, s) {
        return e && e.length && r && r.length ? Zl(e, r, re(s, 2)) : e;
      }
      function Hm(e, r, s) {
        return e && e.length && r && r.length ? Zl(e, r, i, s) : e;
      }
      var Um = Qn(function(e, r) {
        var s = e == null ? 0 : e.length, f = Gl(e, r);
        return fc(e, Ve(r, function(c) {
          return jn(c, s) ? +c : c;
        }).sort(bc)), f;
      });
      function $m(e, r) {
        var s = [];
        if (!(e && e.length))
          return s;
        var f = -1, c = [], g = e.length;
        for (r = re(r, 3); ++f < g; ) {
          var b = e[f];
          r(b, f, e) && (s.push(b), c.push(f));
        }
        return fc(e, c), s;
      }
      function _u(e) {
        return e == null ? e : Mv.call(e);
      }
      function Km(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (s && typeof s != "number" && Ht(e, r, s) ? (r = 0, s = f) : (r = r == null ? 0 : be(r), s = s === i ? f : be(s)), mn(e, r, s)) : [];
      }
      function Gm(e, r) {
        return rs(e, r);
      }
      function Ym(e, r, s) {
        return eu(e, r, re(s, 2));
      }
      function qm(e, r) {
        var s = e == null ? 0 : e.length;
        if (s) {
          var f = rs(e, r);
          if (f < s && An(e[f], r))
            return f;
        }
        return -1;
      }
      function zm(e, r) {
        return rs(e, r, !0);
      }
      function Xm(e, r, s) {
        return eu(e, r, re(s, 2), !0);
      }
      function Vm(e, r) {
        var s = e == null ? 0 : e.length;
        if (s) {
          var f = rs(e, r, !0) - 1;
          if (An(e[f], r))
            return f;
        }
        return -1;
      }
      function Jm(e) {
        return e && e.length ? cc(e) : [];
      }
      function km(e, r) {
        return e && e.length ? cc(e, re(r, 2)) : [];
      }
      function Zm(e) {
        var r = e == null ? 0 : e.length;
        return r ? mn(e, 1, r) : [];
      }
      function Qm(e, r, s) {
        return e && e.length ? (r = s || r === i ? 1 : be(r), mn(e, 0, r < 0 ? 0 : r)) : [];
      }
      function jm(e, r, s) {
        var f = e == null ? 0 : e.length;
        return f ? (r = s || r === i ? 1 : be(r), r = f - r, mn(e, r < 0 ? 0 : r, f)) : [];
      }
      function e0(e, r) {
        return e && e.length ? is(e, re(r, 3), !1, !0) : [];
      }
      function t0(e, r) {
        return e && e.length ? is(e, re(r, 3)) : [];
      }
      var n0 = Ee(function(e) {
        return br(St(e, 1, tt, !0));
      }), r0 = Ee(function(e) {
        var r = bn(e);
        return tt(r) && (r = i), br(St(e, 1, tt, !0), re(r, 2));
      }), i0 = Ee(function(e) {
        var r = bn(e);
        return r = typeof r == "function" ? r : i, br(St(e, 1, tt, !0), i, r);
      });
      function o0(e) {
        return e && e.length ? br(e) : [];
      }
      function s0(e, r) {
        return e && e.length ? br(e, re(r, 2)) : [];
      }
      function l0(e, r) {
        return r = typeof r == "function" ? r : i, e && e.length ? br(e, i, r) : [];
      }
      function mu(e) {
        if (!(e && e.length))
          return [];
        var r = 0;
        return e = pr(e, function(s) {
          if (tt(s))
            return r = dt(s.length, r), !0;
        }), Nl(r, function(s) {
          return Ve(e, Pl(s));
        });
      }
      function Zc(e, r) {
        if (!(e && e.length))
          return [];
        var s = mu(e);
        return r == null ? s : Ve(s, function(f) {
          return en(r, i, f);
        });
      }
      var u0 = Ee(function(e, r) {
        return tt(e) ? zi(e, r) : [];
      }), f0 = Ee(function(e) {
        return nu(pr(e, tt));
      }), a0 = Ee(function(e) {
        var r = bn(e);
        return tt(r) && (r = i), nu(pr(e, tt), re(r, 2));
      }), c0 = Ee(function(e) {
        var r = bn(e);
        return r = typeof r == "function" ? r : i, nu(pr(e, tt), i, r);
      }), h0 = Ee(mu);
      function d0(e, r) {
        return gc(e || [], r || [], qi);
      }
      function p0(e, r) {
        return gc(e || [], r || [], Ji);
      }
      var g0 = Ee(function(e) {
        var r = e.length, s = r > 1 ? e[r - 1] : i;
        return s = typeof s == "function" ? (e.pop(), s) : i, Zc(e, s);
      });
      function Qc(e) {
        var r = d(e);
        return r.__chain__ = !0, r;
      }
      function v0(e, r) {
        return r(e), e;
      }
      function ds(e, r) {
        return r(e);
      }
      var _0 = Qn(function(e) {
        var r = e.length, s = r ? e[0] : 0, f = this.__wrapped__, c = function(g) {
          return Gl(g, e);
        };
        return r > 1 || this.__actions__.length || !(f instanceof Oe) || !jn(s) ? this.thru(c) : (f = f.slice(s, +s + (r ? 1 : 0)), f.__actions__.push({
          func: ds,
          args: [c],
          thisArg: i
        }), new vn(f, this.__chain__).thru(function(g) {
          return r && !g.length && g.push(i), g;
        }));
      });
      function m0() {
        return Qc(this);
      }
      function b0() {
        return new vn(this.value(), this.__chain__);
      }
      function y0() {
        this.__values__ === i && (this.__values__ = hh(this.value()));
        var e = this.__index__ >= this.__values__.length, r = e ? i : this.__values__[this.__index__++];
        return { done: e, value: r };
      }
      function w0() {
        return this;
      }
      function x0(e) {
        for (var r, s = this; s instanceof Qo; ) {
          var f = qc(s);
          f.__index__ = 0, f.__values__ = i, r ? c.__wrapped__ = f : r = f;
          var c = f;
          s = s.__wrapped__;
        }
        return c.__wrapped__ = e, r;
      }
      function E0() {
        var e = this.__wrapped__;
        if (e instanceof Oe) {
          var r = e;
          return this.__actions__.length && (r = new Oe(this)), r = r.reverse(), r.__actions__.push({
            func: ds,
            args: [_u],
            thisArg: i
          }), new vn(r, this.__chain__);
        }
        return this.thru(_u);
      }
      function S0() {
        return pc(this.__wrapped__, this.__actions__);
      }
      var T0 = os(function(e, r, s) {
        He.call(e, s) ? ++e[s] : kn(e, s, 1);
      });
      function A0(e, r, s) {
        var f = ge(e) ? Ca : v_;
        return s && Ht(e, r, s) && (r = i), f(e, re(r, 3));
      }
      function C0(e, r) {
        var s = ge(e) ? pr : Za;
        return s(e, re(r, 3));
      }
      var O0 = Tc(zc), I0 = Tc(Xc);
      function D0(e, r) {
        return St(ps(e, r), 1);
      }
      function R0(e, r) {
        return St(ps(e, r), fe);
      }
      function P0(e, r, s) {
        return s = s === i ? 1 : be(s), St(ps(e, r), s);
      }
      function jc(e, r) {
        var s = ge(e) ? pn : mr;
        return s(e, re(r, 3));
      }
      function eh(e, r) {
        var s = ge(e) ? Zg : ka;
        return s(e, re(r, 3));
      }
      var F0 = os(function(e, r, s) {
        He.call(e, s) ? e[s].push(r) : kn(e, s, [r]);
      });
      function M0(e, r, s, f) {
        e = Vt(e) ? e : yi(e), s = s && !f ? be(s) : 0;
        var c = e.length;
        return s < 0 && (s = dt(c + s, 0)), bs(e) ? s <= c && e.indexOf(r, s) > -1 : !!c && ui(e, r, s) > -1;
      }
      var N0 = Ee(function(e, r, s) {
        var f = -1, c = typeof r == "function", g = Vt(e) ? O(e.length) : [];
        return mr(e, function(b) {
          g[++f] = c ? en(r, b, s) : Xi(b, r, s);
        }), g;
      }), L0 = os(function(e, r, s) {
        kn(e, s, r);
      });
      function ps(e, r) {
        var s = ge(e) ? Ve : rc;
        return s(e, re(r, 3));
      }
      function B0(e, r, s, f) {
        return e == null ? [] : (ge(r) || (r = r == null ? [] : [r]), s = f ? i : s, ge(s) || (s = s == null ? [] : [s]), lc(e, r, s));
      }
      var W0 = os(function(e, r, s) {
        e[s ? 0 : 1].push(r);
      }, function() {
        return [[], []];
      });
      function H0(e, r, s) {
        var f = ge(e) ? Dl : Ra, c = arguments.length < 3;
        return f(e, re(r, 4), s, c, mr);
      }
      function U0(e, r, s) {
        var f = ge(e) ? Qg : Ra, c = arguments.length < 3;
        return f(e, re(r, 4), s, c, ka);
      }
      function $0(e, r) {
        var s = ge(e) ? pr : Za;
        return s(e, _s(re(r, 3)));
      }
      function K0(e) {
        var r = ge(e) ? za : M_;
        return r(e);
      }
      function G0(e, r, s) {
        (s ? Ht(e, r, s) : r === i) ? r = 1 : r = be(r);
        var f = ge(e) ? c_ : N_;
        return f(e, r);
      }
      function Y0(e) {
        var r = ge(e) ? h_ : B_;
        return r(e);
      }
      function q0(e) {
        if (e == null)
          return 0;
        if (Vt(e))
          return bs(e) ? ai(e) : e.length;
        var r = Dt(e);
        return r == x || r == K ? e.size : Jl(e).length;
      }
      function z0(e, r, s) {
        var f = ge(e) ? Rl : W_;
        return s && Ht(e, r, s) && (r = i), f(e, re(r, 3));
      }
      var X0 = Ee(function(e, r) {
        if (e == null)
          return [];
        var s = r.length;
        return s > 1 && Ht(e, r[0], r[1]) ? r = [] : s > 2 && Ht(r[0], r[1], r[2]) && (r = [r[0]]), lc(e, St(r, 1), []);
      }), gs = Cv || function() {
        return Et.Date.now();
      };
      function V0(e, r) {
        if (typeof r != "function")
          throw new gn(a);
        return e = be(e), function() {
          if (--e < 1)
            return r.apply(this, arguments);
        };
      }
      function th(e, r, s) {
        return r = s ? i : r, r = e && r == null ? e.length : r, Zn(e, Se, i, i, i, i, r);
      }
      function nh(e, r) {
        var s;
        if (typeof r != "function")
          throw new gn(a);
        return e = be(e), function() {
          return --e > 0 && (s = r.apply(this, arguments)), e <= 1 && (r = i), s;
        };
      }
      var bu = Ee(function(e, r, s) {
        var f = P;
        if (s.length) {
          var c = vr(s, mi(bu));
          f |= te;
        }
        return Zn(e, f, r, s, c);
      }), rh = Ee(function(e, r, s) {
        var f = P | B;
        if (s.length) {
          var c = vr(s, mi(rh));
          f |= te;
        }
        return Zn(r, f, e, s, c);
      });
      function ih(e, r, s) {
        r = s ? i : r;
        var f = Zn(e, z, i, i, i, i, i, r);
        return f.placeholder = ih.placeholder, f;
      }
      function oh(e, r, s) {
        r = s ? i : r;
        var f = Zn(e, L, i, i, i, i, i, r);
        return f.placeholder = oh.placeholder, f;
      }
      function sh(e, r, s) {
        var f, c, g, b, w, T, F = 0, M = !1, W = !1, V = !0;
        if (typeof e != "function")
          throw new gn(a);
        r = yn(r) || 0, Ze(s) && (M = !!s.leading, W = "maxWait" in s, g = W ? dt(yn(s.maxWait) || 0, r) : g, V = "trailing" in s ? !!s.trailing : V);
        function ee(nt) {
          var Cn = f, nr = c;
          return f = c = i, F = nt, b = e.apply(nr, Cn), b;
        }
        function oe(nt) {
          return F = nt, w = Qi(Ae, r), M ? ee(nt) : b;
        }
        function we(nt) {
          var Cn = nt - T, nr = nt - F, Th = r - Cn;
          return W ? It(Th, g - nr) : Th;
        }
        function se(nt) {
          var Cn = nt - T, nr = nt - F;
          return T === i || Cn >= r || Cn < 0 || W && nr >= g;
        }
        function Ae() {
          var nt = gs();
          if (se(nt))
            return De(nt);
          w = Qi(Ae, we(nt));
        }
        function De(nt) {
          return w = i, V && f ? ee(nt) : (f = c = i, b);
        }
        function on() {
          w !== i && vc(w), F = 0, f = T = c = w = i;
        }
        function Ut() {
          return w === i ? b : De(gs());
        }
        function sn() {
          var nt = gs(), Cn = se(nt);
          if (f = arguments, c = this, T = nt, Cn) {
            if (w === i)
              return oe(T);
            if (W)
              return vc(w), w = Qi(Ae, r), ee(T);
          }
          return w === i && (w = Qi(Ae, r)), b;
        }
        return sn.cancel = on, sn.flush = Ut, sn;
      }
      var J0 = Ee(function(e, r) {
        return Ja(e, 1, r);
      }), k0 = Ee(function(e, r, s) {
        return Ja(e, yn(r) || 0, s);
      });
      function Z0(e) {
        return Zn(e, ue);
      }
      function vs(e, r) {
        if (typeof e != "function" || r != null && typeof r != "function")
          throw new gn(a);
        var s = function() {
          var f = arguments, c = r ? r.apply(this, f) : f[0], g = s.cache;
          if (g.has(c))
            return g.get(c);
          var b = e.apply(this, f);
          return s.cache = g.set(c, b) || g, b;
        };
        return s.cache = new (vs.Cache || Jn)(), s;
      }
      vs.Cache = Jn;
      function _s(e) {
        if (typeof e != "function")
          throw new gn(a);
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
      function Q0(e) {
        return nh(2, e);
      }
      var j0 = H_(function(e, r) {
        r = r.length == 1 && ge(r[0]) ? Ve(r[0], tn(re())) : Ve(St(r, 1), tn(re()));
        var s = r.length;
        return Ee(function(f) {
          for (var c = -1, g = It(f.length, s); ++c < g; )
            f[c] = r[c].call(this, f[c]);
          return en(e, this, f);
        });
      }), yu = Ee(function(e, r) {
        var s = vr(r, mi(yu));
        return Zn(e, te, i, r, s);
      }), lh = Ee(function(e, r) {
        var s = vr(r, mi(lh));
        return Zn(e, xe, i, r, s);
      }), eb = Qn(function(e, r) {
        return Zn(e, Re, i, i, i, r);
      });
      function tb(e, r) {
        if (typeof e != "function")
          throw new gn(a);
        return r = r === i ? r : be(r), Ee(e, r);
      }
      function nb(e, r) {
        if (typeof e != "function")
          throw new gn(a);
        return r = r == null ? 0 : dt(be(r), 0), Ee(function(s) {
          var f = s[r], c = wr(s, 0, r);
          return f && gr(c, f), en(e, this, c);
        });
      }
      function rb(e, r, s) {
        var f = !0, c = !0;
        if (typeof e != "function")
          throw new gn(a);
        return Ze(s) && (f = "leading" in s ? !!s.leading : f, c = "trailing" in s ? !!s.trailing : c), sh(e, r, {
          leading: f,
          maxWait: r,
          trailing: c
        });
      }
      function ib(e) {
        return th(e, 1);
      }
      function ob(e, r) {
        return yu(iu(r), e);
      }
      function sb() {
        if (!arguments.length)
          return [];
        var e = arguments[0];
        return ge(e) ? e : [e];
      }
      function lb(e) {
        return _n(e, S);
      }
      function ub(e, r) {
        return r = typeof r == "function" ? r : i, _n(e, S, r);
      }
      function fb(e) {
        return _n(e, m | S);
      }
      function ab(e, r) {
        return r = typeof r == "function" ? r : i, _n(e, m | S, r);
      }
      function cb(e, r) {
        return r == null || Va(e, r, vt(r));
      }
      function An(e, r) {
        return e === r || e !== e && r !== r;
      }
      var hb = fs(zl), db = fs(function(e, r) {
        return e >= r;
      }), qr = ec(/* @__PURE__ */ function() {
        return arguments;
      }()) ? ec : function(e) {
        return je(e) && He.call(e, "callee") && !Ua.call(e, "callee");
      }, ge = O.isArray, pb = wa ? tn(wa) : x_;
      function Vt(e) {
        return e != null && ms(e.length) && !er(e);
      }
      function tt(e) {
        return je(e) && Vt(e);
      }
      function gb(e) {
        return e === !0 || e === !1 || je(e) && Wt(e) == zn;
      }
      var xr = Iv || Ru, vb = xa ? tn(xa) : E_;
      function _b(e) {
        return je(e) && e.nodeType === 1 && !ji(e);
      }
      function mb(e) {
        if (e == null)
          return !0;
        if (Vt(e) && (ge(e) || typeof e == "string" || typeof e.splice == "function" || xr(e) || bi(e) || qr(e)))
          return !e.length;
        var r = Dt(e);
        if (r == x || r == K)
          return !e.size;
        if (Zi(e))
          return !Jl(e).length;
        for (var s in e)
          if (He.call(e, s))
            return !1;
        return !0;
      }
      function bb(e, r) {
        return Vi(e, r);
      }
      function yb(e, r, s) {
        s = typeof s == "function" ? s : i;
        var f = s ? s(e, r) : i;
        return f === i ? Vi(e, r, i, s) : !!f;
      }
      function wu(e) {
        if (!je(e))
          return !1;
        var r = Wt(e);
        return r == hr || r == cr || typeof e.message == "string" && typeof e.name == "string" && !ji(e);
      }
      function wb(e) {
        return typeof e == "number" && Ka(e);
      }
      function er(e) {
        if (!Ze(e))
          return !1;
        var r = Wt(e);
        return r == dr || r == y || r == oi || r == X;
      }
      function uh(e) {
        return typeof e == "number" && e == be(e);
      }
      function ms(e) {
        return typeof e == "number" && e > -1 && e % 1 == 0 && e <= ie;
      }
      function Ze(e) {
        var r = typeof e;
        return e != null && (r == "object" || r == "function");
      }
      function je(e) {
        return e != null && typeof e == "object";
      }
      var fh = Ea ? tn(Ea) : T_;
      function xb(e, r) {
        return e === r || Vl(e, r, cu(r));
      }
      function Eb(e, r, s) {
        return s = typeof s == "function" ? s : i, Vl(e, r, cu(r), s);
      }
      function Sb(e) {
        return ah(e) && e != +e;
      }
      function Tb(e) {
        if (um(e))
          throw new de(u);
        return tc(e);
      }
      function Ab(e) {
        return e === null;
      }
      function Cb(e) {
        return e == null;
      }
      function ah(e) {
        return typeof e == "number" || je(e) && Wt(e) == D;
      }
      function ji(e) {
        if (!je(e) || Wt(e) != N)
          return !1;
        var r = qo(e);
        if (r === null)
          return !0;
        var s = He.call(r, "constructor") && r.constructor;
        return typeof s == "function" && s instanceof s && $o.call(s) == Ev;
      }
      var xu = Sa ? tn(Sa) : A_;
      function Ob(e) {
        return uh(e) && e >= -ie && e <= ie;
      }
      var ch = Ta ? tn(Ta) : C_;
      function bs(e) {
        return typeof e == "string" || !ge(e) && je(e) && Wt(e) == H;
      }
      function rn(e) {
        return typeof e == "symbol" || je(e) && Wt(e) == ne;
      }
      var bi = Aa ? tn(Aa) : O_;
      function Ib(e) {
        return e === i;
      }
      function Db(e) {
        return je(e) && Dt(e) == Q;
      }
      function Rb(e) {
        return je(e) && Wt(e) == ae;
      }
      var Pb = fs(kl), Fb = fs(function(e, r) {
        return e <= r;
      });
      function hh(e) {
        if (!e)
          return [];
        if (Vt(e))
          return bs(e) ? Sn(e) : Xt(e);
        if (Ui && e[Ui])
          return cv(e[Ui]());
        var r = Dt(e), s = r == x ? Bl : r == K ? Wo : yi;
        return s(e);
      }
      function tr(e) {
        if (!e)
          return e === 0 ? e : 0;
        if (e = yn(e), e === fe || e === -fe) {
          var r = e < 0 ? -1 : 1;
          return r * mt;
        }
        return e === e ? e : 0;
      }
      function be(e) {
        var r = tr(e), s = r % 1;
        return r === r ? s ? r - s : r : 0;
      }
      function dh(e) {
        return e ? $r(be(e), 0, Xe) : 0;
      }
      function yn(e) {
        if (typeof e == "number")
          return e;
        if (rn(e))
          return gt;
        if (Ze(e)) {
          var r = typeof e.valueOf == "function" ? e.valueOf() : e;
          e = Ze(r) ? r + "" : r;
        }
        if (typeof e != "string")
          return e === 0 ? e : +e;
        e = Pa(e);
        var s = pg.test(e);
        return s || vg.test(e) ? Vg(e.slice(2), s ? 2 : 8) : dg.test(e) ? gt : +e;
      }
      function ph(e) {
        return Nn(e, Jt(e));
      }
      function Mb(e) {
        return e ? $r(be(e), -ie, ie) : e === 0 ? e : 0;
      }
      function Le(e) {
        return e == null ? "" : nn(e);
      }
      var Nb = vi(function(e, r) {
        if (Zi(r) || Vt(r)) {
          Nn(r, vt(r), e);
          return;
        }
        for (var s in r)
          He.call(r, s) && qi(e, s, r[s]);
      }), gh = vi(function(e, r) {
        Nn(r, Jt(r), e);
      }), ys = vi(function(e, r, s, f) {
        Nn(r, Jt(r), e, f);
      }), Lb = vi(function(e, r, s, f) {
        Nn(r, vt(r), e, f);
      }), Bb = Qn(Gl);
      function Wb(e, r) {
        var s = gi(e);
        return r == null ? s : Xa(s, r);
      }
      var Hb = Ee(function(e, r) {
        e = Ue(e);
        var s = -1, f = r.length, c = f > 2 ? r[2] : i;
        for (c && Ht(r[0], r[1], c) && (f = 1); ++s < f; )
          for (var g = r[s], b = Jt(g), w = -1, T = b.length; ++w < T; ) {
            var F = b[w], M = e[F];
            (M === i || An(M, hi[F]) && !He.call(e, F)) && (e[F] = g[F]);
          }
        return e;
      }), Ub = Ee(function(e) {
        return e.push(i, Pc), en(vh, i, e);
      });
      function $b(e, r) {
        return Oa(e, re(r, 3), Mn);
      }
      function Kb(e, r) {
        return Oa(e, re(r, 3), ql);
      }
      function Gb(e, r) {
        return e == null ? e : Yl(e, re(r, 3), Jt);
      }
      function Yb(e, r) {
        return e == null ? e : Qa(e, re(r, 3), Jt);
      }
      function qb(e, r) {
        return e && Mn(e, re(r, 3));
      }
      function zb(e, r) {
        return e && ql(e, re(r, 3));
      }
      function Xb(e) {
        return e == null ? [] : ts(e, vt(e));
      }
      function Vb(e) {
        return e == null ? [] : ts(e, Jt(e));
      }
      function Eu(e, r, s) {
        var f = e == null ? i : Kr(e, r);
        return f === i ? s : f;
      }
      function Jb(e, r) {
        return e != null && Nc(e, r, m_);
      }
      function Su(e, r) {
        return e != null && Nc(e, r, b_);
      }
      var kb = Cc(function(e, r, s) {
        r != null && typeof r.toString != "function" && (r = Ko.call(r)), e[r] = s;
      }, Au(kt)), Zb = Cc(function(e, r, s) {
        r != null && typeof r.toString != "function" && (r = Ko.call(r)), He.call(e, r) ? e[r].push(s) : e[r] = [s];
      }, re), Qb = Ee(Xi);
      function vt(e) {
        return Vt(e) ? qa(e) : Jl(e);
      }
      function Jt(e) {
        return Vt(e) ? qa(e, !0) : I_(e);
      }
      function jb(e, r) {
        var s = {};
        return r = re(r, 3), Mn(e, function(f, c, g) {
          kn(s, r(f, c, g), f);
        }), s;
      }
      function ey(e, r) {
        var s = {};
        return r = re(r, 3), Mn(e, function(f, c, g) {
          kn(s, c, r(f, c, g));
        }), s;
      }
      var ty = vi(function(e, r, s) {
        ns(e, r, s);
      }), vh = vi(function(e, r, s, f) {
        ns(e, r, s, f);
      }), ny = Qn(function(e, r) {
        var s = {};
        if (e == null)
          return s;
        var f = !1;
        r = Ve(r, function(g) {
          return g = yr(g, e), f || (f = g.length > 1), g;
        }), Nn(e, fu(e), s), f && (s = _n(s, m | A | S, k_));
        for (var c = r.length; c--; )
          tu(s, r[c]);
        return s;
      });
      function ry(e, r) {
        return _h(e, _s(re(r)));
      }
      var iy = Qn(function(e, r) {
        return e == null ? {} : R_(e, r);
      });
      function _h(e, r) {
        if (e == null)
          return {};
        var s = Ve(fu(e), function(f) {
          return [f];
        });
        return r = re(r), uc(e, s, function(f, c) {
          return r(f, c[0]);
        });
      }
      function oy(e, r, s) {
        r = yr(r, e);
        var f = -1, c = r.length;
        for (c || (c = 1, e = i); ++f < c; ) {
          var g = e == null ? i : e[Ln(r[f])];
          g === i && (f = c, g = s), e = er(g) ? g.call(e) : g;
        }
        return e;
      }
      function sy(e, r, s) {
        return e == null ? e : Ji(e, r, s);
      }
      function ly(e, r, s, f) {
        return f = typeof f == "function" ? f : i, e == null ? e : Ji(e, r, s, f);
      }
      var mh = Dc(vt), bh = Dc(Jt);
      function uy(e, r, s) {
        var f = ge(e), c = f || xr(e) || bi(e);
        if (r = re(r, 4), s == null) {
          var g = e && e.constructor;
          c ? s = f ? new g() : [] : Ze(e) ? s = er(g) ? gi(qo(e)) : {} : s = {};
        }
        return (c ? pn : Mn)(e, function(b, w, T) {
          return r(s, b, w, T);
        }), s;
      }
      function fy(e, r) {
        return e == null ? !0 : tu(e, r);
      }
      function ay(e, r, s) {
        return e == null ? e : dc(e, r, iu(s));
      }
      function cy(e, r, s, f) {
        return f = typeof f == "function" ? f : i, e == null ? e : dc(e, r, iu(s), f);
      }
      function yi(e) {
        return e == null ? [] : Ll(e, vt(e));
      }
      function hy(e) {
        return e == null ? [] : Ll(e, Jt(e));
      }
      function dy(e, r, s) {
        return s === i && (s = r, r = i), s !== i && (s = yn(s), s = s === s ? s : 0), r !== i && (r = yn(r), r = r === r ? r : 0), $r(yn(e), r, s);
      }
      function py(e, r, s) {
        return r = tr(r), s === i ? (s = r, r = 0) : s = tr(s), e = yn(e), y_(e, r, s);
      }
      function gy(e, r, s) {
        if (s && typeof s != "boolean" && Ht(e, r, s) && (r = s = i), s === i && (typeof r == "boolean" ? (s = r, r = i) : typeof e == "boolean" && (s = e, e = i)), e === i && r === i ? (e = 0, r = 1) : (e = tr(e), r === i ? (r = e, e = 0) : r = tr(r)), e > r) {
          var f = e;
          e = r, r = f;
        }
        if (s || e % 1 || r % 1) {
          var c = Ga();
          return It(e + c * (r - e + Xg("1e-" + ((c + "").length - 1))), r);
        }
        return Ql(e, r);
      }
      var vy = _i(function(e, r, s) {
        return r = r.toLowerCase(), e + (s ? yh(r) : r);
      });
      function yh(e) {
        return Tu(Le(e).toLowerCase());
      }
      function wh(e) {
        return e = Le(e), e && e.replace(mg, sv).replace(Bg, "");
      }
      function _y(e, r, s) {
        e = Le(e), r = nn(r);
        var f = e.length;
        s = s === i ? f : $r(be(s), 0, f);
        var c = s;
        return s -= r.length, s >= 0 && e.slice(s, c) == r;
      }
      function my(e) {
        return e = Le(e), e && Qp.test(e) ? e.replace(Qf, lv) : e;
      }
      function by(e) {
        return e = Le(e), e && ig.test(e) ? e.replace(yl, "\\$&") : e;
      }
      var yy = _i(function(e, r, s) {
        return e + (s ? "-" : "") + r.toLowerCase();
      }), wy = _i(function(e, r, s) {
        return e + (s ? " " : "") + r.toLowerCase();
      }), xy = Sc("toLowerCase");
      function Ey(e, r, s) {
        e = Le(e), r = be(r);
        var f = r ? ai(e) : 0;
        if (!r || f >= r)
          return e;
        var c = (r - f) / 2;
        return us(Jo(c), s) + e + us(Vo(c), s);
      }
      function Sy(e, r, s) {
        e = Le(e), r = be(r);
        var f = r ? ai(e) : 0;
        return r && f < r ? e + us(r - f, s) : e;
      }
      function Ty(e, r, s) {
        e = Le(e), r = be(r);
        var f = r ? ai(e) : 0;
        return r && f < r ? us(r - f, s) + e : e;
      }
      function Ay(e, r, s) {
        return s || r == null ? r = 0 : r && (r = +r), Fv(Le(e).replace(wl, ""), r || 0);
      }
      function Cy(e, r, s) {
        return (s ? Ht(e, r, s) : r === i) ? r = 1 : r = be(r), jl(Le(e), r);
      }
      function Oy() {
        var e = arguments, r = Le(e[0]);
        return e.length < 3 ? r : r.replace(e[1], e[2]);
      }
      var Iy = _i(function(e, r, s) {
        return e + (s ? "_" : "") + r.toLowerCase();
      });
      function Dy(e, r, s) {
        return s && typeof s != "number" && Ht(e, r, s) && (r = s = i), s = s === i ? Xe : s >>> 0, s ? (e = Le(e), e && (typeof r == "string" || r != null && !xu(r)) && (r = nn(r), !r && fi(e)) ? wr(Sn(e), 0, s) : e.split(r, s)) : [];
      }
      var Ry = _i(function(e, r, s) {
        return e + (s ? " " : "") + Tu(r);
      });
      function Py(e, r, s) {
        return e = Le(e), s = s == null ? 0 : $r(be(s), 0, e.length), r = nn(r), e.slice(s, s + r.length) == r;
      }
      function Fy(e, r, s) {
        var f = d.templateSettings;
        s && Ht(e, r, s) && (r = i), e = Le(e), r = ys({}, r, f, Rc);
        var c = ys({}, r.imports, f.imports, Rc), g = vt(c), b = Ll(c, g), w, T, F = 0, M = r.interpolate || Fo, W = "__p += '", V = Wl(
          (r.escape || Fo).source + "|" + M.source + "|" + (M === jf ? hg : Fo).source + "|" + (r.evaluate || Fo).source + "|$",
          "g"
        ), ee = "//# sourceURL=" + (He.call(r, "sourceURL") ? (r.sourceURL + "").replace(/\s/g, " ") : "lodash.templateSources[" + ++Kg + "]") + `
`;
        e.replace(V, function(se, Ae, De, on, Ut, sn) {
          return De || (De = on), W += e.slice(F, sn).replace(bg, uv), Ae && (w = !0, W += `' +
__e(` + Ae + `) +
'`), Ut && (T = !0, W += `';
` + Ut + `;
__p += '`), De && (W += `' +
((__t = (` + De + `)) == null ? '' : __t) +
'`), F = sn + se.length, se;
        }), W += `';
`;
        var oe = He.call(r, "variable") && r.variable;
        if (!oe)
          W = `with (obj) {
` + W + `
}
`;
        else if (ag.test(oe))
          throw new de(h);
        W = (T ? W.replace(Po, "") : W).replace(Jp, "$1").replace(kp, "$1;"), W = "function(" + (oe || "obj") + `) {
` + (oe ? "" : `obj || (obj = {});
`) + "var __t, __p = ''" + (w ? ", __e = _.escape" : "") + (T ? `, __j = Array.prototype.join;
function print() { __p += __j.call(arguments, '') }
` : `;
`) + W + `return __p
}`;
        var we = Eh(function() {
          return Me(g, ee + "return " + W).apply(i, b);
        });
        if (we.source = W, wu(we))
          throw we;
        return we;
      }
      function My(e) {
        return Le(e).toLowerCase();
      }
      function Ny(e) {
        return Le(e).toUpperCase();
      }
      function Ly(e, r, s) {
        if (e = Le(e), e && (s || r === i))
          return Pa(e);
        if (!e || !(r = nn(r)))
          return e;
        var f = Sn(e), c = Sn(r), g = Fa(f, c), b = Ma(f, c) + 1;
        return wr(f, g, b).join("");
      }
      function By(e, r, s) {
        if (e = Le(e), e && (s || r === i))
          return e.slice(0, La(e) + 1);
        if (!e || !(r = nn(r)))
          return e;
        var f = Sn(e), c = Ma(f, Sn(r)) + 1;
        return wr(f, 0, c).join("");
      }
      function Wy(e, r, s) {
        if (e = Le(e), e && (s || r === i))
          return e.replace(wl, "");
        if (!e || !(r = nn(r)))
          return e;
        var f = Sn(e), c = Fa(f, Sn(r));
        return wr(f, c).join("");
      }
      function Hy(e, r) {
        var s = Z, f = he;
        if (Ze(r)) {
          var c = "separator" in r ? r.separator : c;
          s = "length" in r ? be(r.length) : s, f = "omission" in r ? nn(r.omission) : f;
        }
        e = Le(e);
        var g = e.length;
        if (fi(e)) {
          var b = Sn(e);
          g = b.length;
        }
        if (s >= g)
          return e;
        var w = s - ai(f);
        if (w < 1)
          return f;
        var T = b ? wr(b, 0, w).join("") : e.slice(0, w);
        if (c === i)
          return T + f;
        if (b && (w += T.length - w), xu(c)) {
          if (e.slice(w).search(c)) {
            var F, M = T;
            for (c.global || (c = Wl(c.source, Le(ea.exec(c)) + "g")), c.lastIndex = 0; F = c.exec(M); )
              var W = F.index;
            T = T.slice(0, W === i ? w : W);
          }
        } else if (e.indexOf(nn(c), w) != w) {
          var V = T.lastIndexOf(c);
          V > -1 && (T = T.slice(0, V));
        }
        return T + f;
      }
      function Uy(e) {
        return e = Le(e), e && Zp.test(e) ? e.replace(Zf, gv) : e;
      }
      var $y = _i(function(e, r, s) {
        return e + (s ? " " : "") + r.toUpperCase();
      }), Tu = Sc("toUpperCase");
      function xh(e, r, s) {
        return e = Le(e), r = s ? i : r, r === i ? av(e) ? mv(e) : tv(e) : e.match(r) || [];
      }
      var Eh = Ee(function(e, r) {
        try {
          return en(e, i, r);
        } catch (s) {
          return wu(s) ? s : new de(s);
        }
      }), Ky = Qn(function(e, r) {
        return pn(r, function(s) {
          s = Ln(s), kn(e, s, bu(e[s], e));
        }), e;
      });
      function Gy(e) {
        var r = e == null ? 0 : e.length, s = re();
        return e = r ? Ve(e, function(f) {
          if (typeof f[1] != "function")
            throw new gn(a);
          return [s(f[0]), f[1]];
        }) : [], Ee(function(f) {
          for (var c = -1; ++c < r; ) {
            var g = e[c];
            if (en(g[0], this, f))
              return en(g[1], this, f);
          }
        });
      }
      function Yy(e) {
        return g_(_n(e, m));
      }
      function Au(e) {
        return function() {
          return e;
        };
      }
      function qy(e, r) {
        return e == null || e !== e ? r : e;
      }
      var zy = Ac(), Xy = Ac(!0);
      function kt(e) {
        return e;
      }
      function Cu(e) {
        return nc(typeof e == "function" ? e : _n(e, m));
      }
      function Vy(e) {
        return ic(_n(e, m));
      }
      function Jy(e, r) {
        return oc(e, _n(r, m));
      }
      var ky = Ee(function(e, r) {
        return function(s) {
          return Xi(s, e, r);
        };
      }), Zy = Ee(function(e, r) {
        return function(s) {
          return Xi(e, s, r);
        };
      });
      function Ou(e, r, s) {
        var f = vt(r), c = ts(r, f);
        s == null && !(Ze(r) && (c.length || !f.length)) && (s = r, r = e, e = this, c = ts(r, vt(r)));
        var g = !(Ze(s) && "chain" in s) || !!s.chain, b = er(e);
        return pn(c, function(w) {
          var T = r[w];
          e[w] = T, b && (e.prototype[w] = function() {
            var F = this.__chain__;
            if (g || F) {
              var M = e(this.__wrapped__), W = M.__actions__ = Xt(this.__actions__);
              return W.push({ func: T, args: arguments, thisArg: e }), M.__chain__ = F, M;
            }
            return T.apply(e, gr([this.value()], arguments));
          });
        }), e;
      }
      function Qy() {
        return Et._ === this && (Et._ = Sv), this;
      }
      function Iu() {
      }
      function jy(e) {
        return e = be(e), Ee(function(r) {
          return sc(r, e);
        });
      }
      var ew = su(Ve), tw = su(Ca), nw = su(Rl);
      function Sh(e) {
        return du(e) ? Pl(Ln(e)) : P_(e);
      }
      function rw(e) {
        return function(r) {
          return e == null ? i : Kr(e, r);
        };
      }
      var iw = Oc(), ow = Oc(!0);
      function Du() {
        return [];
      }
      function Ru() {
        return !1;
      }
      function sw() {
        return {};
      }
      function lw() {
        return "";
      }
      function uw() {
        return !0;
      }
      function fw(e, r) {
        if (e = be(e), e < 1 || e > ie)
          return [];
        var s = Xe, f = It(e, Xe);
        r = re(r), e -= Xe;
        for (var c = Nl(f, r); ++s < e; )
          r(s);
        return c;
      }
      function aw(e) {
        return ge(e) ? Ve(e, Ln) : rn(e) ? [e] : Xt(Yc(Le(e)));
      }
      function cw(e) {
        var r = ++xv;
        return Le(e) + r;
      }
      var hw = ls(function(e, r) {
        return e + r;
      }, 0), dw = lu("ceil"), pw = ls(function(e, r) {
        return e / r;
      }, 1), gw = lu("floor");
      function vw(e) {
        return e && e.length ? es(e, kt, zl) : i;
      }
      function _w(e, r) {
        return e && e.length ? es(e, re(r, 2), zl) : i;
      }
      function mw(e) {
        return Da(e, kt);
      }
      function bw(e, r) {
        return Da(e, re(r, 2));
      }
      function yw(e) {
        return e && e.length ? es(e, kt, kl) : i;
      }
      function ww(e, r) {
        return e && e.length ? es(e, re(r, 2), kl) : i;
      }
      var xw = ls(function(e, r) {
        return e * r;
      }, 1), Ew = lu("round"), Sw = ls(function(e, r) {
        return e - r;
      }, 0);
      function Tw(e) {
        return e && e.length ? Ml(e, kt) : 0;
      }
      function Aw(e, r) {
        return e && e.length ? Ml(e, re(r, 2)) : 0;
      }
      return d.after = V0, d.ary = th, d.assign = Nb, d.assignIn = gh, d.assignInWith = ys, d.assignWith = Lb, d.at = Bb, d.before = nh, d.bind = bu, d.bindAll = Ky, d.bindKey = rh, d.castArray = sb, d.chain = Qc, d.chunk = gm, d.compact = vm, d.concat = _m, d.cond = Gy, d.conforms = Yy, d.constant = Au, d.countBy = T0, d.create = Wb, d.curry = ih, d.curryRight = oh, d.debounce = sh, d.defaults = Hb, d.defaultsDeep = Ub, d.defer = J0, d.delay = k0, d.difference = mm, d.differenceBy = bm, d.differenceWith = ym, d.drop = wm, d.dropRight = xm, d.dropRightWhile = Em, d.dropWhile = Sm, d.fill = Tm, d.filter = C0, d.flatMap = D0, d.flatMapDeep = R0, d.flatMapDepth = P0, d.flatten = Vc, d.flattenDeep = Am, d.flattenDepth = Cm, d.flip = Z0, d.flow = zy, d.flowRight = Xy, d.fromPairs = Om, d.functions = Xb, d.functionsIn = Vb, d.groupBy = F0, d.initial = Dm, d.intersection = Rm, d.intersectionBy = Pm, d.intersectionWith = Fm, d.invert = kb, d.invertBy = Zb, d.invokeMap = N0, d.iteratee = Cu, d.keyBy = L0, d.keys = vt, d.keysIn = Jt, d.map = ps, d.mapKeys = jb, d.mapValues = ey, d.matches = Vy, d.matchesProperty = Jy, d.memoize = vs, d.merge = ty, d.mergeWith = vh, d.method = ky, d.methodOf = Zy, d.mixin = Ou, d.negate = _s, d.nthArg = jy, d.omit = ny, d.omitBy = ry, d.once = Q0, d.orderBy = B0, d.over = ew, d.overArgs = j0, d.overEvery = tw, d.overSome = nw, d.partial = yu, d.partialRight = lh, d.partition = W0, d.pick = iy, d.pickBy = _h, d.property = Sh, d.propertyOf = rw, d.pull = Bm, d.pullAll = kc, d.pullAllBy = Wm, d.pullAllWith = Hm, d.pullAt = Um, d.range = iw, d.rangeRight = ow, d.rearg = eb, d.reject = $0, d.remove = $m, d.rest = tb, d.reverse = _u, d.sampleSize = G0, d.set = sy, d.setWith = ly, d.shuffle = Y0, d.slice = Km, d.sortBy = X0, d.sortedUniq = Jm, d.sortedUniqBy = km, d.split = Dy, d.spread = nb, d.tail = Zm, d.take = Qm, d.takeRight = jm, d.takeRightWhile = e0, d.takeWhile = t0, d.tap = v0, d.throttle = rb, d.thru = ds, d.toArray = hh, d.toPairs = mh, d.toPairsIn = bh, d.toPath = aw, d.toPlainObject = ph, d.transform = uy, d.unary = ib, d.union = n0, d.unionBy = r0, d.unionWith = i0, d.uniq = o0, d.uniqBy = s0, d.uniqWith = l0, d.unset = fy, d.unzip = mu, d.unzipWith = Zc, d.update = ay, d.updateWith = cy, d.values = yi, d.valuesIn = hy, d.without = u0, d.words = xh, d.wrap = ob, d.xor = f0, d.xorBy = a0, d.xorWith = c0, d.zip = h0, d.zipObject = d0, d.zipObjectDeep = p0, d.zipWith = g0, d.entries = mh, d.entriesIn = bh, d.extend = gh, d.extendWith = ys, Ou(d, d), d.add = hw, d.attempt = Eh, d.camelCase = vy, d.capitalize = yh, d.ceil = dw, d.clamp = dy, d.clone = lb, d.cloneDeep = fb, d.cloneDeepWith = ab, d.cloneWith = ub, d.conformsTo = cb, d.deburr = wh, d.defaultTo = qy, d.divide = pw, d.endsWith = _y, d.eq = An, d.escape = my, d.escapeRegExp = by, d.every = A0, d.find = O0, d.findIndex = zc, d.findKey = $b, d.findLast = I0, d.findLastIndex = Xc, d.findLastKey = Kb, d.floor = gw, d.forEach = jc, d.forEachRight = eh, d.forIn = Gb, d.forInRight = Yb, d.forOwn = qb, d.forOwnRight = zb, d.get = Eu, d.gt = hb, d.gte = db, d.has = Jb, d.hasIn = Su, d.head = Jc, d.identity = kt, d.includes = M0, d.indexOf = Im, d.inRange = py, d.invoke = Qb, d.isArguments = qr, d.isArray = ge, d.isArrayBuffer = pb, d.isArrayLike = Vt, d.isArrayLikeObject = tt, d.isBoolean = gb, d.isBuffer = xr, d.isDate = vb, d.isElement = _b, d.isEmpty = mb, d.isEqual = bb, d.isEqualWith = yb, d.isError = wu, d.isFinite = wb, d.isFunction = er, d.isInteger = uh, d.isLength = ms, d.isMap = fh, d.isMatch = xb, d.isMatchWith = Eb, d.isNaN = Sb, d.isNative = Tb, d.isNil = Cb, d.isNull = Ab, d.isNumber = ah, d.isObject = Ze, d.isObjectLike = je, d.isPlainObject = ji, d.isRegExp = xu, d.isSafeInteger = Ob, d.isSet = ch, d.isString = bs, d.isSymbol = rn, d.isTypedArray = bi, d.isUndefined = Ib, d.isWeakMap = Db, d.isWeakSet = Rb, d.join = Mm, d.kebabCase = yy, d.last = bn, d.lastIndexOf = Nm, d.lowerCase = wy, d.lowerFirst = xy, d.lt = Pb, d.lte = Fb, d.max = vw, d.maxBy = _w, d.mean = mw, d.meanBy = bw, d.min = yw, d.minBy = ww, d.stubArray = Du, d.stubFalse = Ru, d.stubObject = sw, d.stubString = lw, d.stubTrue = uw, d.multiply = xw, d.nth = Lm, d.noConflict = Qy, d.noop = Iu, d.now = gs, d.pad = Ey, d.padEnd = Sy, d.padStart = Ty, d.parseInt = Ay, d.random = gy, d.reduce = H0, d.reduceRight = U0, d.repeat = Cy, d.replace = Oy, d.result = oy, d.round = Ew, d.runInContext = E, d.sample = K0, d.size = q0, d.snakeCase = Iy, d.some = z0, d.sortedIndex = Gm, d.sortedIndexBy = Ym, d.sortedIndexOf = qm, d.sortedLastIndex = zm, d.sortedLastIndexBy = Xm, d.sortedLastIndexOf = Vm, d.startCase = Ry, d.startsWith = Py, d.subtract = Sw, d.sum = Tw, d.sumBy = Aw, d.template = Fy, d.times = fw, d.toFinite = tr, d.toInteger = be, d.toLength = dh, d.toLower = My, d.toNumber = yn, d.toSafeInteger = Mb, d.toString = Le, d.toUpper = Ny, d.trim = Ly, d.trimEnd = By, d.trimStart = Wy, d.truncate = Hy, d.unescape = Uy, d.uniqueId = cw, d.upperCase = $y, d.upperFirst = Tu, d.each = jc, d.eachRight = eh, d.first = Jc, Ou(d, function() {
        var e = {};
        return Mn(d, function(r, s) {
          He.call(d.prototype, s) || (e[s] = r);
        }), e;
      }(), { chain: !1 }), d.VERSION = o, pn(["bind", "bindKey", "curry", "curryRight", "partial", "partialRight"], function(e) {
        d[e].placeholder = d;
      }), pn(["drop", "take"], function(e, r) {
        Oe.prototype[e] = function(s) {
          s = s === i ? 1 : dt(be(s), 0);
          var f = this.__filtered__ && !r ? new Oe(this) : this.clone();
          return f.__filtered__ ? f.__takeCount__ = It(s, f.__takeCount__) : f.__views__.push({
            size: It(s, Xe),
            type: e + (f.__dir__ < 0 ? "Right" : "")
          }), f;
        }, Oe.prototype[e + "Right"] = function(s) {
          return this.reverse()[e](s).reverse();
        };
      }), pn(["filter", "map", "takeWhile"], function(e, r) {
        var s = r + 1, f = s == pt || s == me;
        Oe.prototype[e] = function(c) {
          var g = this.clone();
          return g.__iteratees__.push({
            iteratee: re(c, 3),
            type: s
          }), g.__filtered__ = g.__filtered__ || f, g;
        };
      }), pn(["head", "last"], function(e, r) {
        var s = "take" + (r ? "Right" : "");
        Oe.prototype[e] = function() {
          return this[s](1).value()[0];
        };
      }), pn(["initial", "tail"], function(e, r) {
        var s = "drop" + (r ? "" : "Right");
        Oe.prototype[e] = function() {
          return this.__filtered__ ? new Oe(this) : this[s](1);
        };
      }), Oe.prototype.compact = function() {
        return this.filter(kt);
      }, Oe.prototype.find = function(e) {
        return this.filter(e).head();
      }, Oe.prototype.findLast = function(e) {
        return this.reverse().find(e);
      }, Oe.prototype.invokeMap = Ee(function(e, r) {
        return typeof e == "function" ? new Oe(this) : this.map(function(s) {
          return Xi(s, e, r);
        });
      }), Oe.prototype.reject = function(e) {
        return this.filter(_s(re(e)));
      }, Oe.prototype.slice = function(e, r) {
        e = be(e);
        var s = this;
        return s.__filtered__ && (e > 0 || r < 0) ? new Oe(s) : (e < 0 ? s = s.takeRight(-e) : e && (s = s.drop(e)), r !== i && (r = be(r), s = r < 0 ? s.dropRight(-r) : s.take(r - e)), s);
      }, Oe.prototype.takeRightWhile = function(e) {
        return this.reverse().takeWhile(e).reverse();
      }, Oe.prototype.toArray = function() {
        return this.take(Xe);
      }, Mn(Oe.prototype, function(e, r) {
        var s = /^(?:filter|find|map|reject)|While$/.test(r), f = /^(?:head|last)$/.test(r), c = d[f ? "take" + (r == "last" ? "Right" : "") : r], g = f || /^find/.test(r);
        c && (d.prototype[r] = function() {
          var b = this.__wrapped__, w = f ? [1] : arguments, T = b instanceof Oe, F = w[0], M = T || ge(b), W = function(Ae) {
            var De = c.apply(d, gr([Ae], w));
            return f && V ? De[0] : De;
          };
          M && s && typeof F == "function" && F.length != 1 && (T = M = !1);
          var V = this.__chain__, ee = !!this.__actions__.length, oe = g && !V, we = T && !ee;
          if (!g && M) {
            b = we ? b : new Oe(this);
            var se = e.apply(b, w);
            return se.__actions__.push({ func: ds, args: [W], thisArg: i }), new vn(se, V);
          }
          return oe && we ? e.apply(this, w) : (se = this.thru(W), oe ? f ? se.value()[0] : se.value() : se);
        });
      }), pn(["pop", "push", "shift", "sort", "splice", "unshift"], function(e) {
        var r = Ho[e], s = /^(?:push|sort|unshift)$/.test(e) ? "tap" : "thru", f = /^(?:pop|shift)$/.test(e);
        d.prototype[e] = function() {
          var c = arguments;
          if (f && !this.__chain__) {
            var g = this.value();
            return r.apply(ge(g) ? g : [], c);
          }
          return this[s](function(b) {
            return r.apply(ge(b) ? b : [], c);
          });
        };
      }), Mn(Oe.prototype, function(e, r) {
        var s = d[r];
        if (s) {
          var f = s.name + "";
          He.call(pi, f) || (pi[f] = []), pi[f].push({ name: r, func: s });
        }
      }), pi[ss(i, B).name] = [{
        name: "wrapper",
        func: i
      }], Oe.prototype.clone = Uv, Oe.prototype.reverse = $v, Oe.prototype.value = Kv, d.prototype.at = _0, d.prototype.chain = m0, d.prototype.commit = b0, d.prototype.next = y0, d.prototype.plant = x0, d.prototype.reverse = E0, d.prototype.toJSON = d.prototype.valueOf = d.prototype.value = S0, d.prototype.first = d.prototype.head, Ui && (d.prototype[Ui] = w0), d;
    }, ci = bv();
    Br ? ((Br.exports = ci)._ = ci, Cl._ = ci) : Et._ = ci;
  }).call(no);
})(tl, tl.exports);
var Is = tl.exports;
function _o(t) {
  const i = document.getSelection().getRangeAt(0), o = i.cloneRange();
  return o.selectNodeContents(t), o.setEnd(i.endContainer, i.endOffset), o.toString().length;
}
function sd(t, n) {
  const i = RE(t, n), o = document.getSelection();
  o.removeAllRanges(), o.addRange(i);
}
function ld(t) {
  const n = document.createRange(), i = document.getSelection();
  n.setStart(t, t.childNodes.length), n.collapse(!0), i.removeAllRanges(), i.addRange(n);
}
const RE = (t, n) => {
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
function Fp(t, n) {
  let i = [];
  Nt([t, ...n], () => {
    t.value && (i = [...t.value.querySelectorAll("[contenteditable]")]);
  }, {
    flush: "post"
  });
  const o = (S) => i.findIndex((C) => C.isEqualNode(S)), l = (S) => i[o(S) - 1], u = (S) => i[o(S) + 1], a = (S, C) => {
    if (S) {
      const I = u(S);
      if (I instanceof HTMLElement)
        return I.focus(), C && ld(I), I;
    } else {
      const I = i[0];
      I instanceof HTMLElement && I.focus();
    }
  }, h = (S, C) => {
    if (S.target instanceof HTMLElement) {
      const I = S.target, P = _o(I), B = C === "up" ? l(I) : u(I);
      B instanceof HTMLElement && (S.preventDefault(), B.focus(), sd(B, P));
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
        if (_o(I) === 0) {
          const P = l(I);
          P instanceof HTMLElement && (S.preventDefault(), C && (P.innerText += C), P.focus(), ld(P), C && sd(P, P.innerText.length - C.length));
        }
      }
    },
    onRight: (S) => {
      if (S.target instanceof HTMLElement) {
        const C = S.target;
        if (_o(C) === C.innerText.length) {
          const I = u(C);
          I instanceof HTMLElement && (S.preventDefault(), I.focus());
        }
      }
    }
  };
}
const PE = { class: "tw-group tw-flex tw-relative tw-gap-2 tw-py-4 tw-px-6 -tw-mx-6" }, FE = {
  key: 0,
  class: "sortable-handle tw-cursor-grabbing tw-absolute -tw-translate-x-4 tw-hidden group-hover:tw-block"
}, ME = {
  class: "tw-w-4 tw-h-4 tw-flex-none",
  onClick: () => {
  }
}, NE = { class: "tw-flex-auto" }, LE = ["contenteditable", "placeholder", "data-id", "textContent"], BE = { key: 1 }, WE = /* @__PURE__ */ Uf({
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
    Nt([() => i.task, () => i.addableProps], async ([P]) => {
      h && h(), u = Is.pick(P, Object.keys(u)), a.value = { ...u }, h = Nt(a, async () => {
        const B = Is.reduce(a.value, (q, z, L) => Is.isEqual(z, u[L]) ? q : [...q, L], []);
        B.length && o("update", i.task, Is.pick(a.value, B));
      }, {
        deep: !0
      });
    }, {
      immediate: !0
    });
    const p = async (P) => {
      P.target instanceof HTMLElement && (P.target.innerText = P.target.innerText.trim(), a.value.name = P.target.innerText), o("blur", i.task, a.value);
    }, v = (P) => {
      var q;
      P.preventDefault();
      const B = (q = P.clipboardData) == null ? void 0 : q.getData("text/plain");
      B && document.execCommand("insertText", !1, B);
    }, _ = (P) => {
      P.target instanceof HTMLElement && _o(P.target) === 0 && (C(P, P.target.innerText.trim()), o("delete", i.task));
    }, m = async (P) => {
      if (P.preventDefault(), P.target instanceof HTMLElement) {
        const B = P.target, q = B.innerText.trim(), z = _o(B), L = {};
        z !== 0 && (L.currentName = q.substring(0, z).trim(), L.newName = q.substring(z, q.length).trim(), B.innerText = L.currentName), o("insert", i.task, L);
      }
    }, {
      onUp: A,
      onDown: S,
      onLeft: C,
      onRight: I
    } = sr("listNavigation");
    return (P, B) => {
      var q;
      return Kn(), jr("div", PE, [
        i.sortable ? (Kn(), jr("div", FE, B[6] || (B[6] = [
          Hn("i", { class: "fas fa-grip-vertical" }, null, -1)
        ]))) : Kh("", !0),
        Hn("div", ME, [
          Hn("div", {
            class: al(["tw-w-4 tw-h-4 tw-border tw-border-solid tw-border-gray-300 tw-rounded-full tw-cursor-pointer", !1])
          })
        ]),
        Hn("div", NE, [
          Hn("div", {
            contenteditable: i.editable || void 0,
            placeholder: i.placeholder,
            "data-id": i.task.id,
            onKeydown: [
              xi(m, ["enter"]),
              B[0] || (B[0] = xi(
                //@ts-ignore
                (...z) => rt(C) && rt(C)(...z),
                ["left"]
              )),
              B[1] || (B[1] = xi(
                //@ts-ignore
                (...z) => rt(A) && rt(A)(...z),
                ["up"]
              )),
              B[2] || (B[2] = xi(
                //@ts-ignore
                (...z) => rt(I) && rt(I)(...z),
                ["right"]
              )),
              B[3] || (B[3] = xi(
                //@ts-ignore
                (...z) => rt(S) && rt(S)(...z),
                ["down"]
              )),
              xi(_, ["delete"])
            ],
            onBlur: p,
            onFocus: B[4] || (B[4] = (z) => l.value = !0),
            onFocusout: B[5] || (B[5] = (z) => l.value = !1),
            onPaste: v,
            textContent: rf(i.task.name)
          }, null, 40, LE)
        ]),
        (q = i.task.extended_data) != null && q.comments_count ? (Kn(), jr("div", BE, [
          B[7] || (B[7] = Hn("i", { class: "far fa-comment" }, null, -1)),
          Cp(" " + rf(i.task.extended_data.comments_count), 1)
        ])) : Kh("", !0)
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
function ud(t, n) {
  var i = Object.keys(t);
  if (Object.getOwnPropertySymbols) {
    var o = Object.getOwnPropertySymbols(t);
    n && (o = o.filter(function(l) {
      return Object.getOwnPropertyDescriptor(t, l).enumerable;
    })), i.push.apply(i, o);
  }
  return i;
}
function qn(t) {
  for (var n = 1; n < arguments.length; n++) {
    var i = arguments[n] != null ? arguments[n] : {};
    n % 2 ? ud(Object(i), !0).forEach(function(o) {
      HE(t, o, i[o]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(i)) : ud(Object(i)).forEach(function(o) {
      Object.defineProperty(t, o, Object.getOwnPropertyDescriptor(i, o));
    });
  }
  return t;
}
function Gs(t) {
  "@babel/helpers - typeof";
  return typeof Symbol == "function" && typeof Symbol.iterator == "symbol" ? Gs = function(n) {
    return typeof n;
  } : Gs = function(n) {
    return n && typeof Symbol == "function" && n.constructor === Symbol && n !== Symbol.prototype ? "symbol" : typeof n;
  }, Gs(t);
}
function HE(t, n, i) {
  return n in t ? Object.defineProperty(t, n, {
    value: i,
    enumerable: !0,
    configurable: !0,
    writable: !0
  }) : t[n] = i, t;
}
function Pn() {
  return Pn = Object.assign || function(t) {
    for (var n = 1; n < arguments.length; n++) {
      var i = arguments[n];
      for (var o in i)
        Object.prototype.hasOwnProperty.call(i, o) && (t[o] = i[o]);
    }
    return t;
  }, Pn.apply(this, arguments);
}
function UE(t, n) {
  if (t == null)
    return {};
  var i = {}, o = Object.keys(t), l, u;
  for (u = 0; u < o.length; u++)
    l = o[u], !(n.indexOf(l) >= 0) && (i[l] = t[l]);
  return i;
}
function $E(t, n) {
  if (t == null)
    return {};
  var i = UE(t, n), o, l;
  if (Object.getOwnPropertySymbols) {
    var u = Object.getOwnPropertySymbols(t);
    for (l = 0; l < u.length; l++)
      o = u[l], !(n.indexOf(o) >= 0) && Object.prototype.propertyIsEnumerable.call(t, o) && (i[o] = t[o]);
  }
  return i;
}
function KE(t) {
  return GE(t) || YE(t) || qE(t) || zE();
}
function GE(t) {
  if (Array.isArray(t))
    return mf(t);
}
function YE(t) {
  if (typeof Symbol < "u" && t[Symbol.iterator] != null || t["@@iterator"] != null)
    return Array.from(t);
}
function qE(t, n) {
  if (t) {
    if (typeof t == "string")
      return mf(t, n);
    var i = Object.prototype.toString.call(t).slice(8, -1);
    if (i === "Object" && t.constructor && (i = t.constructor.name), i === "Map" || i === "Set")
      return Array.from(t);
    if (i === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i))
      return mf(t, n);
  }
}
function mf(t, n) {
  (n == null || n > t.length) && (n = t.length);
  for (var i = 0, o = new Array(n); i < n; i++)
    o[i] = t[i];
  return o;
}
function zE() {
  throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`);
}
var XE = "1.15.2";
function lr(t) {
  if (typeof window < "u" && window.navigator)
    return !!/* @__PURE__ */ navigator.userAgent.match(t);
}
var fr = lr(/(?:Trident.*rv[ :]?11\.|msie|iemobile|Windows Phone)/i), Do = lr(/Edge/i), fd = lr(/firefox/i), mo = lr(/safari/i) && !lr(/chrome/i) && !lr(/android/i), Mp = lr(/iP(ad|od|hone)/i), Np = lr(/chrome/i) && lr(/android/i), Lp = {
  capture: !1,
  passive: !1
};
function Ie(t, n, i) {
  t.addEventListener(n, i, !fr && Lp);
}
function Te(t, n, i) {
  t.removeEventListener(n, i, !fr && Lp);
}
function nl(t, n) {
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
function VE(t) {
  return t.host && t !== document && t.host.nodeType ? t.host : t.parentNode;
}
function wn(t, n, i, o) {
  if (t) {
    i = i || document;
    do {
      if (n != null && (n[0] === ">" ? t.parentNode === i && nl(t, n) : nl(t, n)) || o && t === i)
        return t;
      if (t === i)
        break;
    } while (t = VE(t));
  }
  return null;
}
var ad = /\s+/g;
function ft(t, n, i) {
  if (t && n)
    if (t.classList)
      t.classList[i ? "add" : "remove"](n);
    else {
      var o = (" " + t.className + " ").replace(ad, " ").replace(" " + n + " ", " ");
      t.className = (o + (i ? " " + n : "")).replace(ad, " ");
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
function ti(t, n) {
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
function Bp(t, n, i) {
  if (t) {
    var o = t.getElementsByTagName(n), l = 0, u = o.length;
    if (i)
      for (; l < u; l++)
        i(o[l], l);
    return o;
  }
  return [];
}
function Gn() {
  var t = document.scrollingElement;
  return t || document.documentElement;
}
function ze(t, n, i, o, l) {
  if (!(!t.getBoundingClientRect && t !== window)) {
    var u, a, h, p, v, _, m;
    if (t !== window && t.parentNode && t !== Gn() ? (u = t.getBoundingClientRect(), a = u.top, h = u.left, p = u.bottom, v = u.right, _ = u.height, m = u.width) : (a = 0, h = 0, p = window.innerHeight, v = window.innerWidth, _ = window.innerHeight, m = window.innerWidth), (n || i) && t !== window && (l = l || t.parentNode, !fr))
      do
        if (l && l.getBoundingClientRect && (j(l, "transform") !== "none" || i && j(l, "position") !== "static")) {
          var A = l.getBoundingClientRect();
          a -= A.top + parseInt(j(l, "border-top-width")), h -= A.left + parseInt(j(l, "border-left-width")), p = a + u.height, v = h + u.width;
          break;
        }
      while (l = l.parentNode);
    if (o && t !== window) {
      var S = ti(l || t), C = S && S.a, I = S && S.d;
      S && (a /= I, h /= C, m /= C, _ /= I, p = a + _, v = h + m);
    }
    return {
      top: a,
      left: h,
      bottom: p,
      right: v,
      width: m,
      height: _
    };
  }
}
function cd(t, n, i) {
  for (var o = Ir(t, !0), l = ze(t)[n]; o; ) {
    var u = ze(o)[i], a = void 0;
    if (i === "top" || i === "left" ? a = l >= u : a = l <= u, !a)
      return o;
    if (o === Gn())
      break;
    o = Ir(o, !1);
  }
  return !1;
}
function Wi(t, n, i, o) {
  for (var l = 0, u = 0, a = t.children; u < a.length; ) {
    if (a[u].style.display !== "none" && a[u] !== le.ghost && (o || a[u] !== le.dragged) && wn(a[u], i.draggable, t, !1)) {
      if (l === n)
        return a[u];
      l++;
    }
    u++;
  }
  return null;
}
function Xf(t, n) {
  for (var i = t.lastElementChild; i && (i === le.ghost || j(i, "display") === "none" || n && !nl(i, n)); )
    i = i.previousElementSibling;
  return i || null;
}
function at(t, n) {
  var i = 0;
  if (!t || !t.parentNode)
    return -1;
  for (; t = t.previousElementSibling; )
    t.nodeName.toUpperCase() !== "TEMPLATE" && t !== le.clone && (!n || nl(t, n)) && i++;
  return i;
}
function hd(t) {
  var n = 0, i = 0, o = Gn();
  if (t)
    do {
      var l = ti(t), u = l.a, a = l.d;
      n += t.scrollLeft * u, i += t.scrollTop * a;
    } while (t !== o && (t = t.parentNode));
  return [n, i];
}
function JE(t, n) {
  for (var i in t)
    if (t.hasOwnProperty(i)) {
      for (var o in n)
        if (n.hasOwnProperty(o) && n[o] === t[i][o])
          return Number(i);
    }
  return -1;
}
function Ir(t, n) {
  if (!t || !t.getBoundingClientRect)
    return Gn();
  var i = t, o = !1;
  do
    if (i.clientWidth < i.scrollWidth || i.clientHeight < i.scrollHeight) {
      var l = j(i);
      if (i.clientWidth < i.scrollWidth && (l.overflowX == "auto" || l.overflowX == "scroll") || i.clientHeight < i.scrollHeight && (l.overflowY == "auto" || l.overflowY == "scroll")) {
        if (!i.getBoundingClientRect || i === document.body)
          return Gn();
        if (o || n)
          return i;
        o = !0;
      }
    }
  while (i = i.parentNode);
  return Gn();
}
function kE(t, n) {
  if (t && n)
    for (var i in n)
      n.hasOwnProperty(i) && (t[i] = n[i]);
  return t;
}
function zu(t, n) {
  return Math.round(t.top) === Math.round(n.top) && Math.round(t.left) === Math.round(n.left) && Math.round(t.height) === Math.round(n.height) && Math.round(t.width) === Math.round(n.width);
}
var bo;
function Wp(t, n) {
  return function() {
    if (!bo) {
      var i = arguments, o = this;
      i.length === 1 ? t.call(o, i[0]) : t.apply(o, i), bo = setTimeout(function() {
        bo = void 0;
      }, n);
    }
  };
}
function ZE() {
  clearTimeout(bo), bo = void 0;
}
function Hp(t, n, i) {
  t.scrollLeft += n, t.scrollTop += i;
}
function Vf(t) {
  var n = window.Polymer, i = window.jQuery || window.Zepto;
  return n && n.dom ? n.dom(t).cloneNode(!0) : i ? i(t).clone(!0)[0] : t.cloneNode(!0);
}
function dd(t, n) {
  j(t, "position", "absolute"), j(t, "top", n.top), j(t, "left", n.left), j(t, "width", n.width), j(t, "height", n.height);
}
function Xu(t) {
  j(t, "position", ""), j(t, "top", ""), j(t, "left", ""), j(t, "width", ""), j(t, "height", "");
}
function Up(t, n, i) {
  var o = {};
  return Array.from(t.children).forEach(function(l) {
    var u, a, h, p;
    if (!(!wn(l, n.draggable, t, !1) || l.animated || l === i)) {
      var v = ze(l);
      o.left = Math.min((u = o.left) !== null && u !== void 0 ? u : 1 / 0, v.left), o.top = Math.min((a = o.top) !== null && a !== void 0 ? a : 1 / 0, v.top), o.right = Math.max((h = o.right) !== null && h !== void 0 ? h : -1 / 0, v.right), o.bottom = Math.max((p = o.bottom) !== null && p !== void 0 ? p : -1 / 0, v.bottom);
    }
  }), o.width = o.right - o.left, o.height = o.bottom - o.top, o.x = o.left, o.y = o.top, o;
}
var Mt = "Sortable" + (/* @__PURE__ */ new Date()).getTime();
function QE() {
  var t = [], n;
  return {
    captureAnimationState: function() {
      if (t = [], !!this.options.animation) {
        var o = [].slice.call(this.el.children);
        o.forEach(function(l) {
          if (!(j(l, "display") === "none" || l === le.ghost)) {
            t.push({
              target: l,
              rect: ze(l)
            });
            var u = qn({}, t[t.length - 1].rect);
            if (l.thisAnimationDuration) {
              var a = ti(l, !0);
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
      t.splice(JE(t, {
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
        var p = 0, v = h.target, _ = v.fromRect, m = ze(v), A = v.prevFromRect, S = v.prevToRect, C = h.rect, I = ti(v, !0);
        I && (m.top -= I.f, m.left -= I.e), v.toRect = m, v.thisAnimationDuration && zu(A, m) && !zu(_, m) && // Make sure animatingRect is on line between toRect & fromRect
        (C.top - m.top) / (C.left - m.left) === (_.top - m.top) / (_.left - m.left) && (p = eS(C, A, S, l.options)), zu(m, _) || (v.prevFromRect = _, v.prevToRect = m, p || (p = l.options.animation), l.animate(v, C, m, p)), p && (u = !0, a = Math.max(a, p), clearTimeout(v.animationResetTimer), v.animationResetTimer = setTimeout(function() {
          v.animationTime = 0, v.prevFromRect = null, v.fromRect = null, v.prevToRect = null, v.thisAnimationDuration = null;
        }, p), v.thisAnimationDuration = p);
      }), clearTimeout(n), u ? n = setTimeout(function() {
        typeof o == "function" && o();
      }, a) : typeof o == "function" && o(), t = [];
    },
    animate: function(o, l, u, a) {
      if (a) {
        j(o, "transition", ""), j(o, "transform", "");
        var h = ti(this.el), p = h && h.a, v = h && h.d, _ = (l.left - u.left) / (p || 1), m = (l.top - u.top) / (v || 1);
        o.animatingX = !!_, o.animatingY = !!m, j(o, "transform", "translate3d(" + _ + "px," + m + "px,0)"), this.forRepaintDummy = jE(o), j(o, "transition", "transform " + a + "ms" + (this.options.easing ? " " + this.options.easing : "")), j(o, "transform", "translate3d(0,0,0)"), typeof o.animated == "number" && clearTimeout(o.animated), o.animated = setTimeout(function() {
          j(o, "transition", ""), j(o, "transform", ""), o.animated = !1, o.animatingX = !1, o.animatingY = !1;
        }, a);
      }
    }
  };
}
function jE(t) {
  return t.offsetWidth;
}
function eS(t, n, i, o) {
  return Math.sqrt(Math.pow(n.top - t.top, 2) + Math.pow(n.left - t.left, 2)) / Math.sqrt(Math.pow(n.top - i.top, 2) + Math.pow(n.left - i.left, 2)) * o.animation;
}
var Si = [], Vu = {
  initializeByDefault: !0
}, Ro = {
  mount: function(n) {
    for (var i in Vu)
      Vu.hasOwnProperty(i) && !(i in n) && (n[i] = Vu[i]);
    Si.forEach(function(o) {
      if (o.pluginName === n.pluginName)
        throw "Sortable: Cannot mount plugin ".concat(n.pluginName, " more than once");
    }), Si.push(n);
  },
  pluginEvent: function(n, i, o) {
    var l = this;
    this.eventCanceled = !1, o.cancel = function() {
      l.eventCanceled = !0;
    };
    var u = n + "Global";
    Si.forEach(function(a) {
      i[a.pluginName] && (i[a.pluginName][u] && i[a.pluginName][u](qn({
        sortable: i
      }, o)), i.options[a.pluginName] && i[a.pluginName][n] && i[a.pluginName][n](qn({
        sortable: i
      }, o)));
    });
  },
  initializePlugins: function(n, i, o, l) {
    Si.forEach(function(h) {
      var p = h.pluginName;
      if (!(!n.options[p] && !h.initializeByDefault)) {
        var v = new h(n, i, n.options);
        v.sortable = n, v.options = n.options, n[p] = v, Pn(o, v.defaults);
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
    return Si.forEach(function(l) {
      typeof l.eventProperties == "function" && Pn(o, l.eventProperties.call(i[l.pluginName], n));
    }), o;
  },
  modifyOption: function(n, i, o) {
    var l;
    return Si.forEach(function(u) {
      n[u.pluginName] && u.optionListeners && typeof u.optionListeners[i] == "function" && (l = u.optionListeners[i].call(n[u.pluginName], o));
    }), l;
  }
};
function lo(t) {
  var n = t.sortable, i = t.rootEl, o = t.name, l = t.targetEl, u = t.cloneEl, a = t.toEl, h = t.fromEl, p = t.oldIndex, v = t.newIndex, _ = t.oldDraggableIndex, m = t.newDraggableIndex, A = t.originalEvent, S = t.putSortable, C = t.extraEventProperties;
  if (n = n || i && i[Mt], !!n) {
    var I, P = n.options, B = "on" + o.charAt(0).toUpperCase() + o.substr(1);
    window.CustomEvent && !fr && !Do ? I = new CustomEvent(o, {
      bubbles: !0,
      cancelable: !0
    }) : (I = document.createEvent("Event"), I.initEvent(o, !0, !0)), I.to = a || i, I.from = h || i, I.item = l || i, I.clone = u, I.oldIndex = p, I.newIndex = v, I.oldDraggableIndex = _, I.newDraggableIndex = m, I.originalEvent = A, I.pullMode = S ? S.lastPutMode : void 0;
    var q = qn(qn({}, C), Ro.getEventProperties(o, n));
    for (var z in q)
      I[z] = q[z];
    i && i.dispatchEvent(I), P[B] && P[B].call(n, I);
  }
}
var tS = ["evt"], Zt = function(n, i) {
  var o = arguments.length > 2 && arguments[2] !== void 0 ? arguments[2] : {}, l = o.evt, u = $E(o, tS);
  Ro.pluginEvent.bind(le)(n, i, qn({
    dragEl: Y,
    parentEl: et,
    ghostEl: pe,
    rootEl: Je,
    nextEl: Zr,
    lastDownEl: Ys,
    cloneEl: Qe,
    cloneHidden: Or,
    dragStarted: uo,
    putSortable: At,
    activeSortable: le.active,
    originalEvent: l,
    oldIndex: Ii,
    oldDraggableIndex: yo,
    newIndex: fn,
    newDraggableIndex: Ar,
    hideGhostForTarget: Yp,
    unhideGhostForTarget: qp,
    cloneNowHidden: function() {
      Or = !0;
    },
    cloneNowShown: function() {
      Or = !1;
    },
    dispatchSortableEvent: function(h) {
      Kt({
        sortable: i,
        name: h,
        originalEvent: l
      });
    }
  }, u));
};
function Kt(t) {
  lo(qn({
    putSortable: At,
    cloneEl: Qe,
    targetEl: Y,
    rootEl: Je,
    oldIndex: Ii,
    oldDraggableIndex: yo,
    newIndex: fn,
    newDraggableIndex: Ar
  }, t));
}
var Y, et, pe, Je, Zr, Ys, Qe, Or, Ii, fn, yo, Ar, Ds, At, Oi = !1, rl = !1, il = [], Vr, On, Ju, ku, pd, gd, uo, Ti, wo, xo = !1, Rs = !1, qs, Rt, Zu = [], bf = !1, ol = [], bl = typeof document < "u", Ps = Mp, vd = Do || fr ? "cssFloat" : "float", nS = bl && !Np && !Mp && "draggable" in document.createElement("div"), $p = function() {
  if (bl) {
    if (fr)
      return !1;
    var t = document.createElement("x");
    return t.style.cssText = "pointer-events:auto", t.style.pointerEvents === "auto";
  }
}(), Kp = function(n, i) {
  var o = j(n), l = parseInt(o.width) - parseInt(o.paddingLeft) - parseInt(o.paddingRight) - parseInt(o.borderLeftWidth) - parseInt(o.borderRightWidth), u = Wi(n, 0, i), a = Wi(n, 1, i), h = u && j(u), p = a && j(a), v = h && parseInt(h.marginLeft) + parseInt(h.marginRight) + ze(u).width, _ = p && parseInt(p.marginLeft) + parseInt(p.marginRight) + ze(a).width;
  if (o.display === "flex")
    return o.flexDirection === "column" || o.flexDirection === "column-reverse" ? "vertical" : "horizontal";
  if (o.display === "grid")
    return o.gridTemplateColumns.split(" ").length <= 1 ? "vertical" : "horizontal";
  if (u && h.float && h.float !== "none") {
    var m = h.float === "left" ? "left" : "right";
    return a && (p.clear === "both" || p.clear === m) ? "vertical" : "horizontal";
  }
  return u && (h.display === "block" || h.display === "flex" || h.display === "table" || h.display === "grid" || v >= l && o[vd] === "none" || a && o[vd] === "none" && v + _ > l) ? "vertical" : "horizontal";
}, rS = function(n, i, o) {
  var l = o ? n.left : n.top, u = o ? n.right : n.bottom, a = o ? n.width : n.height, h = o ? i.left : i.top, p = o ? i.right : i.bottom, v = o ? i.width : i.height;
  return l === h || u === p || l + a / 2 === h + v / 2;
}, iS = function(n, i) {
  var o;
  return il.some(function(l) {
    var u = l[Mt].options.emptyInsertThreshold;
    if (!(!u || Xf(l))) {
      var a = ze(l), h = n >= a.left - u && n <= a.right + u, p = i >= a.top - u && i <= a.bottom + u;
      if (h && p)
        return o = l;
    }
  }), o;
}, Gp = function(n) {
  function i(u, a) {
    return function(h, p, v, _) {
      var m = h.options.group.name && p.options.group.name && h.options.group.name === p.options.group.name;
      if (u == null && (a || m))
        return !0;
      if (u == null || u === !1)
        return !1;
      if (a && u === "clone")
        return u;
      if (typeof u == "function")
        return i(u(h, p, v, _), a)(h, p, v, _);
      var A = (a ? h : p).options.group.name;
      return u === !0 || typeof u == "string" && u === A || u.join && u.indexOf(A) > -1;
    };
  }
  var o = {}, l = n.group;
  (!l || Gs(l) != "object") && (l = {
    name: l
  }), o.name = l.name, o.checkPull = i(l.pull, !0), o.checkPut = i(l.put), o.revertClone = l.revertClone, n.group = o;
}, Yp = function() {
  !$p && pe && j(pe, "display", "none");
}, qp = function() {
  !$p && pe && j(pe, "display", "");
};
bl && !Np && document.addEventListener("click", function(t) {
  if (rl)
    return t.preventDefault(), t.stopPropagation && t.stopPropagation(), t.stopImmediatePropagation && t.stopImmediatePropagation(), rl = !1, !1;
}, !0);
var Jr = function(n) {
  if (Y) {
    n = n.touches ? n.touches[0] : n;
    var i = iS(n.clientX, n.clientY);
    if (i) {
      var o = {};
      for (var l in n)
        n.hasOwnProperty(l) && (o[l] = n[l]);
      o.target = o.rootEl = i, o.preventDefault = void 0, o.stopPropagation = void 0, i[Mt]._onDragOver(o);
    }
  }
}, oS = function(n) {
  Y && Y.parentNode[Mt]._isOutsideThisEl(n.target);
};
function le(t, n) {
  if (!(t && t.nodeType && t.nodeType === 1))
    throw "Sortable: `el` must be an HTMLElement, not ".concat({}.toString.call(t));
  this.el = t, this.options = n = Pn({}, n), t[Mt] = this;
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
      return Kp(t, this.options);
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
    supportPointer: le.supportPointer !== !1 && "PointerEvent" in window && !mo,
    emptyInsertThreshold: 5
  };
  Ro.initializePlugins(this, t, i);
  for (var o in i)
    !(o in n) && (n[o] = i[o]);
  Gp(n);
  for (var l in this)
    l.charAt(0) === "_" && typeof this[l] == "function" && (this[l] = this[l].bind(this));
  this.nativeDraggable = n.forceFallback ? !1 : nS, this.nativeDraggable && (this.options.touchStartThreshold = 1), n.supportPointer ? Ie(t, "pointerdown", this._onTapStart) : (Ie(t, "mousedown", this._onTapStart), Ie(t, "touchstart", this._onTapStart)), this.nativeDraggable && (Ie(t, "dragover", this), Ie(t, "dragenter", this)), il.push(this.el), n.store && n.store.get && this.sort(n.store.get(this) || []), Pn(this, QE());
}
le.prototype = /** @lends Sortable.prototype */
{
  constructor: le,
  _isOutsideThisEl: function(n) {
    !this.el.contains(n) && n !== this.el && (Ti = null);
  },
  _getDirection: function(n, i) {
    return typeof this.options.direction == "function" ? this.options.direction.call(this, n, i, Y) : this.options.direction;
  },
  _onTapStart: function(n) {
    if (n.cancelable) {
      var i = this, o = this.el, l = this.options, u = l.preventOnFilter, a = n.type, h = n.touches && n.touches[0] || n.pointerType && n.pointerType === "touch" && n, p = (h || n).target, v = n.target.shadowRoot && (n.path && n.path[0] || n.composedPath && n.composedPath()[0]) || p, _ = l.filter;
      if (dS(o), !Y && !(/mousedown|pointerdown/.test(a) && n.button !== 0 || l.disabled) && !v.isContentEditable && !(!this.nativeDraggable && mo && p && p.tagName.toUpperCase() === "SELECT") && (p = wn(p, l.draggable, o, !1), !(p && p.animated) && Ys !== p)) {
        if (Ii = at(p), yo = at(p, l.draggable), typeof _ == "function") {
          if (_.call(this, n, p, this)) {
            Kt({
              sortable: i,
              rootEl: v,
              name: "filter",
              targetEl: p,
              toEl: o,
              fromEl: o
            }), Zt("filter", i, {
              evt: n
            }), u && n.cancelable && n.preventDefault();
            return;
          }
        } else if (_ && (_ = _.split(",").some(function(m) {
          if (m = wn(v, m.trim(), o, !1), m)
            return Kt({
              sortable: i,
              rootEl: m,
              name: "filter",
              targetEl: p,
              fromEl: o,
              toEl: o
            }), Zt("filter", i, {
              evt: n
            }), !0;
        }), _)) {
          u && n.cancelable && n.preventDefault();
          return;
        }
        l.handle && !wn(v, l.handle, o, !1) || this._prepareDragStart(n, h, p);
      }
    }
  },
  _prepareDragStart: function(n, i, o) {
    var l = this, u = l.el, a = l.options, h = u.ownerDocument, p;
    if (o && !Y && o.parentNode === u) {
      var v = ze(o);
      if (Je = u, Y = o, et = Y.parentNode, Zr = Y.nextSibling, Ys = o, Ds = a.group, le.dragged = Y, Vr = {
        target: Y,
        clientX: (i || n).clientX,
        clientY: (i || n).clientY
      }, pd = Vr.clientX - v.left, gd = Vr.clientY - v.top, this._lastX = (i || n).clientX, this._lastY = (i || n).clientY, Y.style["will-change"] = "all", p = function() {
        if (Zt("delayEnded", l, {
          evt: n
        }), le.eventCanceled) {
          l._onDrop();
          return;
        }
        l._disableDelayedDragEvents(), !fd && l.nativeDraggable && (Y.draggable = !0), l._triggerDragStart(n, i), Kt({
          sortable: l,
          name: "choose",
          originalEvent: n
        }), ft(Y, a.chosenClass, !0);
      }, a.ignore.split(",").forEach(function(_) {
        Bp(Y, _.trim(), Qu);
      }), Ie(h, "dragover", Jr), Ie(h, "mousemove", Jr), Ie(h, "touchmove", Jr), Ie(h, "mouseup", l._onDrop), Ie(h, "touchend", l._onDrop), Ie(h, "touchcancel", l._onDrop), fd && this.nativeDraggable && (this.options.touchStartThreshold = 4, Y.draggable = !0), Zt("delayStart", this, {
        evt: n
      }), a.delay && (!a.delayOnTouchOnly || i) && (!this.nativeDraggable || !(Do || fr))) {
        if (le.eventCanceled) {
          this._onDrop();
          return;
        }
        Ie(h, "mouseup", l._disableDelayedDrag), Ie(h, "touchend", l._disableDelayedDrag), Ie(h, "touchcancel", l._disableDelayedDrag), Ie(h, "mousemove", l._delayedDragTouchMoveHandler), Ie(h, "touchmove", l._delayedDragTouchMoveHandler), a.supportPointer && Ie(h, "pointermove", l._delayedDragTouchMoveHandler), l._dragStartTimer = setTimeout(p, a.delay);
      } else
        p();
    }
  },
  _delayedDragTouchMoveHandler: function(n) {
    var i = n.touches ? n.touches[0] : n;
    Math.max(Math.abs(i.clientX - this._lastX), Math.abs(i.clientY - this._lastY)) >= Math.floor(this.options.touchStartThreshold / (this.nativeDraggable && window.devicePixelRatio || 1)) && this._disableDelayedDrag();
  },
  _disableDelayedDrag: function() {
    Y && Qu(Y), clearTimeout(this._dragStartTimer), this._disableDelayedDragEvents();
  },
  _disableDelayedDragEvents: function() {
    var n = this.el.ownerDocument;
    Te(n, "mouseup", this._disableDelayedDrag), Te(n, "touchend", this._disableDelayedDrag), Te(n, "touchcancel", this._disableDelayedDrag), Te(n, "mousemove", this._delayedDragTouchMoveHandler), Te(n, "touchmove", this._delayedDragTouchMoveHandler), Te(n, "pointermove", this._delayedDragTouchMoveHandler);
  },
  _triggerDragStart: function(n, i) {
    i = i || n.pointerType == "touch" && n, !this.nativeDraggable || i ? this.options.supportPointer ? Ie(document, "pointermove", this._onTouchMove) : i ? Ie(document, "touchmove", this._onTouchMove) : Ie(document, "mousemove", this._onTouchMove) : (Ie(Y, "dragend", this), Ie(Je, "dragstart", this._onDragStart));
    try {
      document.selection ? zs(function() {
        document.selection.empty();
      }) : window.getSelection().removeAllRanges();
    } catch {
    }
  },
  _dragStarted: function(n, i) {
    if (Oi = !1, Je && Y) {
      Zt("dragStarted", this, {
        evt: i
      }), this.nativeDraggable && Ie(document, "dragover", oS);
      var o = this.options;
      !n && ft(Y, o.dragClass, !1), ft(Y, o.ghostClass, !0), le.active = this, n && this._appendGhost(), Kt({
        sortable: this,
        name: "start",
        originalEvent: i
      });
    } else
      this._nulling();
  },
  _emulateDragOver: function() {
    if (On) {
      this._lastX = On.clientX, this._lastY = On.clientY, Yp();
      for (var n = document.elementFromPoint(On.clientX, On.clientY), i = n; n && n.shadowRoot && (n = n.shadowRoot.elementFromPoint(On.clientX, On.clientY), n !== i); )
        i = n;
      if (Y.parentNode[Mt]._isOutsideThisEl(n), i)
        do {
          if (i[Mt]) {
            var o = void 0;
            if (o = i[Mt]._onDragOver({
              clientX: On.clientX,
              clientY: On.clientY,
              target: n,
              rootEl: i
            }), o && !this.options.dragoverBubble)
              break;
          }
          n = i;
        } while (i = i.parentNode);
      qp();
    }
  },
  _onTouchMove: function(n) {
    if (Vr) {
      var i = this.options, o = i.fallbackTolerance, l = i.fallbackOffset, u = n.touches ? n.touches[0] : n, a = pe && ti(pe, !0), h = pe && a && a.a, p = pe && a && a.d, v = Ps && Rt && hd(Rt), _ = (u.clientX - Vr.clientX + l.x) / (h || 1) + (v ? v[0] - Zu[0] : 0) / (h || 1), m = (u.clientY - Vr.clientY + l.y) / (p || 1) + (v ? v[1] - Zu[1] : 0) / (p || 1);
      if (!le.active && !Oi) {
        if (o && Math.max(Math.abs(u.clientX - this._lastX), Math.abs(u.clientY - this._lastY)) < o)
          return;
        this._onDragStart(n, !0);
      }
      if (pe) {
        a ? (a.e += _ - (Ju || 0), a.f += m - (ku || 0)) : a = {
          a: 1,
          b: 0,
          c: 0,
          d: 1,
          e: _,
          f: m
        };
        var A = "matrix(".concat(a.a, ",").concat(a.b, ",").concat(a.c, ",").concat(a.d, ",").concat(a.e, ",").concat(a.f, ")");
        j(pe, "webkitTransform", A), j(pe, "mozTransform", A), j(pe, "msTransform", A), j(pe, "transform", A), Ju = _, ku = m, On = u;
      }
      n.cancelable && n.preventDefault();
    }
  },
  _appendGhost: function() {
    if (!pe) {
      var n = this.options.fallbackOnBody ? document.body : Je, i = ze(Y, !0, Ps, !0, n), o = this.options;
      if (Ps) {
        for (Rt = n; j(Rt, "position") === "static" && j(Rt, "transform") === "none" && Rt !== document; )
          Rt = Rt.parentNode;
        Rt !== document.body && Rt !== document.documentElement ? (Rt === document && (Rt = Gn()), i.top += Rt.scrollTop, i.left += Rt.scrollLeft) : Rt = Gn(), Zu = hd(Rt);
      }
      pe = Y.cloneNode(!0), ft(pe, o.ghostClass, !1), ft(pe, o.fallbackClass, !0), ft(pe, o.dragClass, !0), j(pe, "transition", ""), j(pe, "transform", ""), j(pe, "box-sizing", "border-box"), j(pe, "margin", 0), j(pe, "top", i.top), j(pe, "left", i.left), j(pe, "width", i.width), j(pe, "height", i.height), j(pe, "opacity", "0.8"), j(pe, "position", Ps ? "absolute" : "fixed"), j(pe, "zIndex", "100000"), j(pe, "pointerEvents", "none"), le.ghost = pe, n.appendChild(pe), j(pe, "transform-origin", pd / parseInt(pe.style.width) * 100 + "% " + gd / parseInt(pe.style.height) * 100 + "%");
    }
  },
  _onDragStart: function(n, i) {
    var o = this, l = n.dataTransfer, u = o.options;
    if (Zt("dragStart", this, {
      evt: n
    }), le.eventCanceled) {
      this._onDrop();
      return;
    }
    Zt("setupClone", this), le.eventCanceled || (Qe = Vf(Y), Qe.removeAttribute("id"), Qe.draggable = !1, Qe.style["will-change"] = "", this._hideClone(), ft(Qe, this.options.chosenClass, !1), le.clone = Qe), o.cloneId = zs(function() {
      Zt("clone", o), !le.eventCanceled && (o.options.removeCloneOnHide || Je.insertBefore(Qe, Y), o._hideClone(), Kt({
        sortable: o,
        name: "clone"
      }));
    }), !i && ft(Y, u.dragClass, !0), i ? (rl = !0, o._loopId = setInterval(o._emulateDragOver, 50)) : (Te(document, "mouseup", o._onDrop), Te(document, "touchend", o._onDrop), Te(document, "touchcancel", o._onDrop), l && (l.effectAllowed = "move", u.setData && u.setData.call(o, l, Y)), Ie(document, "drop", o), j(Y, "transform", "translateZ(0)")), Oi = !0, o._dragStartId = zs(o._dragStarted.bind(o, i, n)), Ie(document, "selectstart", o), uo = !0, mo && j(document.body, "user-select", "none");
  },
  // Returns true - if no further action is needed (either inserted or another condition)
  _onDragOver: function(n) {
    var i = this.el, o = n.target, l, u, a, h = this.options, p = h.group, v = le.active, _ = Ds === p, m = h.sort, A = At || v, S, C = this, I = !1;
    if (bf)
      return;
    function P(ie, mt) {
      Zt(ie, C, qn({
        evt: n,
        isOwner: _,
        axis: S ? "vertical" : "horizontal",
        revert: a,
        dragRect: l,
        targetRect: u,
        canSort: m,
        fromSortable: A,
        target: o,
        completed: q,
        onMove: function(Xe, ot) {
          return Fs(Je, i, Y, l, Xe, ze(Xe), n, ot);
        },
        changed: z
      }, mt));
    }
    function B() {
      P("dragOverAnimationCapture"), C.captureAnimationState(), C !== A && A.captureAnimationState();
    }
    function q(ie) {
      return P("dragOverCompleted", {
        insertion: ie
      }), ie && (_ ? v._hideClone() : v._showClone(C), C !== A && (ft(Y, At ? At.options.ghostClass : v.options.ghostClass, !1), ft(Y, h.ghostClass, !0)), At !== C && C !== le.active ? At = C : C === le.active && At && (At = null), A === C && (C._ignoreWhileAnimating = o), C.animateAll(function() {
        P("dragOverAnimationComplete"), C._ignoreWhileAnimating = null;
      }), C !== A && (A.animateAll(), A._ignoreWhileAnimating = null)), (o === Y && !Y.animated || o === i && !o.animated) && (Ti = null), !h.dragoverBubble && !n.rootEl && o !== document && (Y.parentNode[Mt]._isOutsideThisEl(n.target), !ie && Jr(n)), !h.dragoverBubble && n.stopPropagation && n.stopPropagation(), I = !0;
    }
    function z() {
      fn = at(Y), Ar = at(Y, h.draggable), Kt({
        sortable: C,
        name: "change",
        toEl: i,
        newIndex: fn,
        newDraggableIndex: Ar,
        originalEvent: n
      });
    }
    if (n.preventDefault !== void 0 && n.cancelable && n.preventDefault(), o = wn(o, h.draggable, i, !0), P("dragOver"), le.eventCanceled)
      return I;
    if (Y.contains(n.target) || o.animated && o.animatingX && o.animatingY || C._ignoreWhileAnimating === o)
      return q(!1);
    if (rl = !1, v && !h.disabled && (_ ? m || (a = et !== Je) : At === this || (this.lastPutMode = Ds.checkPull(this, v, Y, n)) && p.checkPut(this, v, Y, n))) {
      if (S = this._getDirection(n, o) === "vertical", l = ze(Y), P("dragOverValid"), le.eventCanceled)
        return I;
      if (a)
        return et = Je, B(), this._hideClone(), P("revert"), le.eventCanceled || (Zr ? Je.insertBefore(Y, Zr) : Je.appendChild(Y)), q(!0);
      var L = Xf(i, h.draggable);
      if (!L || fS(n, S, this) && !L.animated) {
        if (L === Y)
          return q(!1);
        if (L && i === n.target && (o = L), o && (u = ze(o)), Fs(Je, i, Y, l, o, u, n, !!o) !== !1)
          return B(), L && L.nextSibling ? i.insertBefore(Y, L.nextSibling) : i.appendChild(Y), et = i, z(), q(!0);
      } else if (L && uS(n, S, this)) {
        var te = Wi(i, 0, h, !0);
        if (te === Y)
          return q(!1);
        if (o = te, u = ze(o), Fs(Je, i, Y, l, o, u, n, !1) !== !1)
          return B(), i.insertBefore(Y, te), et = i, z(), q(!0);
      } else if (o.parentNode === i) {
        u = ze(o);
        var xe = 0, Se, Re = Y.parentNode !== i, ue = !rS(Y.animated && Y.toRect || l, o.animated && o.toRect || u, S), Z = S ? "top" : "left", he = cd(o, "top", "top") || cd(Y, "top", "top"), Ce = he ? he.scrollTop : void 0;
        Ti !== o && (Se = u[Z], xo = !1, Rs = !ue && h.invertSwap || Re), xe = aS(n, o, u, S, ue ? 1 : h.swapThreshold, h.invertedSwapThreshold == null ? h.swapThreshold : h.invertedSwapThreshold, Rs, Ti === o);
        var We;
        if (xe !== 0) {
          var pt = at(Y);
          do
            pt -= xe, We = et.children[pt];
          while (We && (j(We, "display") === "none" || We === pe));
        }
        if (xe === 0 || We === o)
          return q(!1);
        Ti = o, wo = xe;
        var Ot = o.nextElementSibling, me = !1;
        me = xe === 1;
        var fe = Fs(Je, i, Y, l, o, u, n, me);
        if (fe !== !1)
          return (fe === 1 || fe === -1) && (me = fe === 1), bf = !0, setTimeout(lS, 30), B(), me && !Ot ? i.appendChild(Y) : o.parentNode.insertBefore(Y, me ? Ot : o), he && Hp(he, 0, Ce - he.scrollTop), et = Y.parentNode, Se !== void 0 && !Rs && (qs = Math.abs(Se - ze(o)[Z])), z(), q(!0);
      }
      if (i.contains(Y))
        return q(!1);
    }
    return !1;
  },
  _ignoreWhileAnimating: null,
  _offMoveEvents: function() {
    Te(document, "mousemove", this._onTouchMove), Te(document, "touchmove", this._onTouchMove), Te(document, "pointermove", this._onTouchMove), Te(document, "dragover", Jr), Te(document, "mousemove", Jr), Te(document, "touchmove", Jr);
  },
  _offUpEvents: function() {
    var n = this.el.ownerDocument;
    Te(n, "mouseup", this._onDrop), Te(n, "touchend", this._onDrop), Te(n, "pointerup", this._onDrop), Te(n, "touchcancel", this._onDrop), Te(document, "selectstart", this);
  },
  _onDrop: function(n) {
    var i = this.el, o = this.options;
    if (fn = at(Y), Ar = at(Y, o.draggable), Zt("drop", this, {
      evt: n
    }), et = Y && Y.parentNode, fn = at(Y), Ar = at(Y, o.draggable), le.eventCanceled) {
      this._nulling();
      return;
    }
    Oi = !1, Rs = !1, xo = !1, clearInterval(this._loopId), clearTimeout(this._dragStartTimer), yf(this.cloneId), yf(this._dragStartId), this.nativeDraggable && (Te(document, "drop", this), Te(i, "dragstart", this._onDragStart)), this._offMoveEvents(), this._offUpEvents(), mo && j(document.body, "user-select", ""), j(Y, "transform", ""), n && (uo && (n.cancelable && n.preventDefault(), !o.dropBubble && n.stopPropagation()), pe && pe.parentNode && pe.parentNode.removeChild(pe), (Je === et || At && At.lastPutMode !== "clone") && Qe && Qe.parentNode && Qe.parentNode.removeChild(Qe), Y && (this.nativeDraggable && Te(Y, "dragend", this), Qu(Y), Y.style["will-change"] = "", uo && !Oi && ft(Y, At ? At.options.ghostClass : this.options.ghostClass, !1), ft(Y, this.options.chosenClass, !1), Kt({
      sortable: this,
      name: "unchoose",
      toEl: et,
      newIndex: null,
      newDraggableIndex: null,
      originalEvent: n
    }), Je !== et ? (fn >= 0 && (Kt({
      rootEl: et,
      name: "add",
      toEl: et,
      fromEl: Je,
      originalEvent: n
    }), Kt({
      sortable: this,
      name: "remove",
      toEl: et,
      originalEvent: n
    }), Kt({
      rootEl: et,
      name: "sort",
      toEl: et,
      fromEl: Je,
      originalEvent: n
    }), Kt({
      sortable: this,
      name: "sort",
      toEl: et,
      originalEvent: n
    })), At && At.save()) : fn !== Ii && fn >= 0 && (Kt({
      sortable: this,
      name: "update",
      toEl: et,
      originalEvent: n
    }), Kt({
      sortable: this,
      name: "sort",
      toEl: et,
      originalEvent: n
    })), le.active && ((fn == null || fn === -1) && (fn = Ii, Ar = yo), Kt({
      sortable: this,
      name: "end",
      toEl: et,
      originalEvent: n
    }), this.save()))), this._nulling();
  },
  _nulling: function() {
    Zt("nulling", this), Je = Y = et = pe = Zr = Qe = Ys = Or = Vr = On = uo = fn = Ar = Ii = yo = Ti = wo = At = Ds = le.dragged = le.ghost = le.clone = le.active = null, ol.forEach(function(n) {
      n.checked = !0;
    }), ol.length = Ju = ku = 0;
  },
  handleEvent: function(n) {
    switch (n.type) {
      case "drop":
      case "dragend":
        this._onDrop(n);
        break;
      case "dragenter":
      case "dragover":
        Y && (this._onDragOver(n), sS(n));
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
      i = o[l], wn(i, a.draggable, this.el, !1) && n.push(i.getAttribute(a.dataIdAttr) || hS(i));
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
      wn(h, this.options.draggable, l, !1) && (o[u] = h);
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
    return wn(n, i || this.options.draggable, this.el, !1);
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
    var l = Ro.modifyOption(this, n, i);
    typeof l < "u" ? o[n] = l : o[n] = i, n === "group" && Gp(o);
  },
  /**
   * Destroy
   */
  destroy: function() {
    Zt("destroy", this);
    var n = this.el;
    n[Mt] = null, Te(n, "mousedown", this._onTapStart), Te(n, "touchstart", this._onTapStart), Te(n, "pointerdown", this._onTapStart), this.nativeDraggable && (Te(n, "dragover", this), Te(n, "dragenter", this)), Array.prototype.forEach.call(n.querySelectorAll("[draggable]"), function(i) {
      i.removeAttribute("draggable");
    }), this._onDrop(), this._disableDelayedDragEvents(), il.splice(il.indexOf(this.el), 1), this.el = n = null;
  },
  _hideClone: function() {
    if (!Or) {
      if (Zt("hideClone", this), le.eventCanceled)
        return;
      j(Qe, "display", "none"), this.options.removeCloneOnHide && Qe.parentNode && Qe.parentNode.removeChild(Qe), Or = !0;
    }
  },
  _showClone: function(n) {
    if (n.lastPutMode !== "clone") {
      this._hideClone();
      return;
    }
    if (Or) {
      if (Zt("showClone", this), le.eventCanceled)
        return;
      Y.parentNode == Je && !this.options.group.revertClone ? Je.insertBefore(Qe, Y) : Zr ? Je.insertBefore(Qe, Zr) : Je.appendChild(Qe), this.options.group.revertClone && this.animate(Y, Qe), j(Qe, "display", ""), Or = !1;
    }
  }
};
function sS(t) {
  t.dataTransfer && (t.dataTransfer.dropEffect = "move"), t.cancelable && t.preventDefault();
}
function Fs(t, n, i, o, l, u, a, h) {
  var p, v = t[Mt], _ = v.options.onMove, m;
  return window.CustomEvent && !fr && !Do ? p = new CustomEvent("move", {
    bubbles: !0,
    cancelable: !0
  }) : (p = document.createEvent("Event"), p.initEvent("move", !0, !0)), p.to = n, p.from = t, p.dragged = i, p.draggedRect = o, p.related = l || n, p.relatedRect = u || ze(n), p.willInsertAfter = h, p.originalEvent = a, t.dispatchEvent(p), _ && (m = _.call(v, p, a)), m;
}
function Qu(t) {
  t.draggable = !1;
}
function lS() {
  bf = !1;
}
function uS(t, n, i) {
  var o = ze(Wi(i.el, 0, i.options, !0)), l = Up(i.el, i.options, pe), u = 10;
  return n ? t.clientX < l.left - u || t.clientY < o.top && t.clientX < o.right : t.clientY < l.top - u || t.clientY < o.bottom && t.clientX < o.left;
}
function fS(t, n, i) {
  var o = ze(Xf(i.el, i.options.draggable)), l = Up(i.el, i.options, pe), u = 10;
  return n ? t.clientX > l.right + u || t.clientY > o.bottom && t.clientX > o.left : t.clientY > l.bottom + u || t.clientX > o.right && t.clientY > o.top;
}
function aS(t, n, i, o, l, u, a, h) {
  var p = o ? t.clientY : t.clientX, v = o ? i.height : i.width, _ = o ? i.top : i.left, m = o ? i.bottom : i.right, A = !1;
  if (!a) {
    if (h && qs < v * l) {
      if (!xo && (wo === 1 ? p > _ + v * u / 2 : p < m - v * u / 2) && (xo = !0), xo)
        A = !0;
      else if (wo === 1 ? p < _ + qs : p > m - qs)
        return -wo;
    } else if (p > _ + v * (1 - l) / 2 && p < m - v * (1 - l) / 2)
      return cS(n);
  }
  return A = A || a, A && (p < _ + v * u / 2 || p > m - v * u / 2) ? p > _ + v / 2 ? 1 : -1 : 0;
}
function cS(t) {
  return at(Y) < at(t) ? 1 : -1;
}
function hS(t) {
  for (var n = t.tagName + t.className + t.src + t.href + t.textContent, i = n.length, o = 0; i--; )
    o += n.charCodeAt(i);
  return o.toString(36);
}
function dS(t) {
  ol.length = 0;
  for (var n = t.getElementsByTagName("input"), i = n.length; i--; ) {
    var o = n[i];
    o.checked && ol.push(o);
  }
}
function zs(t) {
  return setTimeout(t, 0);
}
function yf(t) {
  return clearTimeout(t);
}
bl && Ie(document, "touchmove", function(t) {
  (le.active || Oi) && t.cancelable && t.preventDefault();
});
le.utils = {
  on: Ie,
  off: Te,
  css: j,
  find: Bp,
  is: function(n, i) {
    return !!wn(n, i, n, !1);
  },
  extend: kE,
  throttle: Wp,
  closest: wn,
  toggleClass: ft,
  clone: Vf,
  index: at,
  nextTick: zs,
  cancelNextTick: yf,
  detectDirection: Kp,
  getChild: Wi
};
le.get = function(t) {
  return t[Mt];
};
le.mount = function() {
  for (var t = arguments.length, n = new Array(t), i = 0; i < t; i++)
    n[i] = arguments[i];
  n[0].constructor === Array && (n = n[0]), n.forEach(function(o) {
    if (!o.prototype || !o.prototype.constructor)
      throw "Sortable: Mounted plugin must be a constructor function, not ".concat({}.toString.call(o));
    o.utils && (le.utils = qn(qn({}, le.utils), o.utils)), Ro.mount(o);
  });
};
le.create = function(t, n) {
  return new le(t, n);
};
le.version = XE;
var ut = [], fo, wf, xf = !1, ju, ef, sl, ao;
function pS() {
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
      this.sortable.nativeDraggable ? Te(document, "dragover", this._handleAutoScroll) : (Te(document, "pointermove", this._handleFallbackAutoScroll), Te(document, "touchmove", this._handleFallbackAutoScroll), Te(document, "mousemove", this._handleFallbackAutoScroll)), _d(), Xs(), ZE();
    },
    nulling: function() {
      sl = wf = fo = xf = ao = ju = ef = null, ut.length = 0;
    },
    _handleFallbackAutoScroll: function(i) {
      this._handleAutoScroll(i, !0);
    },
    _handleAutoScroll: function(i, o) {
      var l = this, u = (i.touches ? i.touches[0] : i).clientX, a = (i.touches ? i.touches[0] : i).clientY, h = document.elementFromPoint(u, a);
      if (sl = i, o || this.options.forceAutoScrollFallback || Do || fr || mo) {
        tf(i, this.options, h, o);
        var p = Ir(h, !0);
        xf && (!ao || u !== ju || a !== ef) && (ao && _d(), ao = setInterval(function() {
          var v = Ir(document.elementFromPoint(u, a), !0);
          v !== p && (p = v, Xs()), tf(i, l.options, v, o);
        }, 10), ju = u, ef = a);
      } else {
        if (!this.options.bubbleScroll || Ir(h, !0) === Gn()) {
          Xs();
          return;
        }
        tf(i, this.options, Ir(h, !1), !1);
      }
    }
  }, Pn(t, {
    pluginName: "scroll",
    initializeByDefault: !0
  });
}
function Xs() {
  ut.forEach(function(t) {
    clearInterval(t.pid);
  }), ut = [];
}
function _d() {
  clearInterval(ao);
}
var tf = Wp(function(t, n, i, o) {
  if (n.scroll) {
    var l = (t.touches ? t.touches[0] : t).clientX, u = (t.touches ? t.touches[0] : t).clientY, a = n.scrollSensitivity, h = n.scrollSpeed, p = Gn(), v = !1, _;
    wf !== i && (wf = i, Xs(), fo = n.scroll, _ = n.scrollFn, fo === !0 && (fo = Ir(i, !0)));
    var m = 0, A = fo;
    do {
      var S = A, C = ze(S), I = C.top, P = C.bottom, B = C.left, q = C.right, z = C.width, L = C.height, te = void 0, xe = void 0, Se = S.scrollWidth, Re = S.scrollHeight, ue = j(S), Z = S.scrollLeft, he = S.scrollTop;
      S === p ? (te = z < Se && (ue.overflowX === "auto" || ue.overflowX === "scroll" || ue.overflowX === "visible"), xe = L < Re && (ue.overflowY === "auto" || ue.overflowY === "scroll" || ue.overflowY === "visible")) : (te = z < Se && (ue.overflowX === "auto" || ue.overflowX === "scroll"), xe = L < Re && (ue.overflowY === "auto" || ue.overflowY === "scroll"));
      var Ce = te && (Math.abs(q - l) <= a && Z + z < Se) - (Math.abs(B - l) <= a && !!Z), We = xe && (Math.abs(P - u) <= a && he + L < Re) - (Math.abs(I - u) <= a && !!he);
      if (!ut[m])
        for (var pt = 0; pt <= m; pt++)
          ut[pt] || (ut[pt] = {});
      (ut[m].vx != Ce || ut[m].vy != We || ut[m].el !== S) && (ut[m].el = S, ut[m].vx = Ce, ut[m].vy = We, clearInterval(ut[m].pid), (Ce != 0 || We != 0) && (v = !0, ut[m].pid = setInterval((function() {
        o && this.layer === 0 && le.active._onTouchMove(sl);
        var Ot = ut[this.layer].vy ? ut[this.layer].vy * h : 0, me = ut[this.layer].vx ? ut[this.layer].vx * h : 0;
        typeof _ == "function" && _.call(le.dragged.parentNode[Mt], me, Ot, t, sl, ut[this.layer].el) !== "continue" || Hp(ut[this.layer].el, me, Ot);
      }).bind({
        layer: m
      }), 24))), m++;
    } while (n.bubbleScroll && A !== p && (A = Ir(A, !1)));
    xf = v;
  }
}, 30), zp = function(n) {
  var i = n.originalEvent, o = n.putSortable, l = n.dragEl, u = n.activeSortable, a = n.dispatchSortableEvent, h = n.hideGhostForTarget, p = n.unhideGhostForTarget;
  if (i) {
    var v = o || u;
    h();
    var _ = i.changedTouches && i.changedTouches.length ? i.changedTouches[0] : i, m = document.elementFromPoint(_.clientX, _.clientY);
    p(), v && !v.el.contains(m) && (a("spill"), this.onSpill({
      dragEl: l,
      putSortable: o
    }));
  }
};
function Jf() {
}
Jf.prototype = {
  startIndex: null,
  dragStart: function(n) {
    var i = n.oldDraggableIndex;
    this.startIndex = i;
  },
  onSpill: function(n) {
    var i = n.dragEl, o = n.putSortable;
    this.sortable.captureAnimationState(), o && o.captureAnimationState();
    var l = Wi(this.sortable.el, this.startIndex - (ce.length ? ce.indexOf(i) : 0), this.options);
    l ? this.sortable.el.insertBefore(i, l) : this.sortable.el.appendChild(i), this.sortable.animateAll(), o && o.animateAll();
  },
  drop: zp
};
Pn(Jf, {
  pluginName: "revertOnSpill"
});
function kf() {
}
kf.prototype = {
  onSpill: function(n) {
    var i = n.dragEl, o = n.putSortable, l = o || this.sortable;
    l.captureAnimationState(), i.parentNode && i.parentNode.removeChild(i), l.animateAll();
  },
  drop: zp
};
Pn(kf, {
  pluginName: "removeOnSpill"
});
var ce = [], ln = [], ro, In, io = !1, Qt = !1, Ai = !1, Ge, oo, Ms;
function gS() {
  function t(n) {
    for (var i in this)
      i.charAt(0) === "_" && typeof this[i] == "function" && (this[i] = this[i].bind(this));
    n.options.avoidImplicitDeselect || (n.options.supportPointer ? Ie(document, "pointerup", this._deselectMultiDrag) : (Ie(document, "mouseup", this._deselectMultiDrag), Ie(document, "touchend", this._deselectMultiDrag))), Ie(document, "keydown", this._checkKeyDown), Ie(document, "keyup", this._checkKeyUp), this.defaults = {
      selectedClass: "sortable-selected",
      multiDragKey: null,
      avoidImplicitDeselect: !1,
      setData: function(l, u) {
        var a = "";
        ce.length && In === n ? ce.forEach(function(h, p) {
          a += (p ? ", " : "") + h.textContent;
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
          ln.push(Vf(ce[u])), ln[u].sortableIndex = ce[u].sortableIndex, ln[u].draggable = !1, ln[u].style["will-change"] = "", ft(ln[u], this.options.selectedClass, !1), ce[u] === Ge && ft(ln[u], this.options.chosenClass, !1);
        o._hideClone(), l();
      }
    },
    clone: function(i) {
      var o = i.sortable, l = i.rootEl, u = i.dispatchSortableEvent, a = i.cancel;
      this.isMultiDrag && (this.options.removeCloneOnHide || ce.length && In === o && (md(!0, l), u("clone"), a()));
    },
    showClone: function(i) {
      var o = i.cloneNowShown, l = i.rootEl, u = i.cancel;
      this.isMultiDrag && (md(!1, l), ln.forEach(function(a) {
        j(a, "display", "");
      }), o(), Ms = !1, u());
    },
    hideClone: function(i) {
      var o = this;
      i.sortable;
      var l = i.cloneNowHidden, u = i.cancel;
      this.isMultiDrag && (ln.forEach(function(a) {
        j(a, "display", "none"), o.options.removeCloneOnHide && a.parentNode && a.parentNode.removeChild(a);
      }), l(), Ms = !0, u());
    },
    dragStartGlobal: function(i) {
      i.sortable, !this.isMultiDrag && In && In.multiDrag._deselectMultiDrag(), ce.forEach(function(o) {
        o.sortableIndex = at(o);
      }), ce = ce.sort(function(o, l) {
        return o.sortableIndex - l.sortableIndex;
      }), Ai = !0;
    },
    dragStarted: function(i) {
      var o = this, l = i.sortable;
      if (this.isMultiDrag) {
        if (this.options.sort && (l.captureAnimationState(), this.options.animation)) {
          ce.forEach(function(a) {
            a !== Ge && j(a, "position", "absolute");
          });
          var u = ze(Ge, !1, !0, !0);
          ce.forEach(function(a) {
            a !== Ge && dd(a, u);
          }), Qt = !0, io = !0;
        }
        l.animateAll(function() {
          Qt = !1, io = !1, o.options.animation && ce.forEach(function(a) {
            Xu(a);
          }), o.options.sort && Ns();
        });
      }
    },
    dragOver: function(i) {
      var o = i.target, l = i.completed, u = i.cancel;
      Qt && ~ce.indexOf(o) && (l(!1), u());
    },
    revert: function(i) {
      var o = i.fromSortable, l = i.rootEl, u = i.sortable, a = i.dragRect;
      ce.length > 1 && (ce.forEach(function(h) {
        u.addAnimationState({
          target: h,
          rect: Qt ? ze(h) : a
        }), Xu(h), h.fromRect = a, o.removeAnimationState(h);
      }), Qt = !1, vS(!this.options.removeCloneOnHide, l));
    },
    dragOverCompleted: function(i) {
      var o = i.sortable, l = i.isOwner, u = i.insertion, a = i.activeSortable, h = i.parentEl, p = i.putSortable, v = this.options;
      if (u) {
        if (l && a._hideClone(), io = !1, v.animation && ce.length > 1 && (Qt || !l && !a.options.sort && !p)) {
          var _ = ze(Ge, !1, !0, !0);
          ce.forEach(function(A) {
            A !== Ge && (dd(A, _), h.appendChild(A));
          }), Qt = !0;
        }
        if (!l)
          if (Qt || Ns(), ce.length > 1) {
            var m = Ms;
            a._showClone(o), a.options.animation && !Ms && m && ln.forEach(function(A) {
              a.addAnimationState({
                target: A,
                rect: oo
              }), A.fromRect = oo, A.thisAnimationDuration = null;
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
        oo = Pn({}, o);
        var a = ti(Ge, !0);
        oo.top -= a.f, oo.left -= a.e;
      }
    },
    dragOverAnimationComplete: function() {
      Qt && (Qt = !1, Ns());
    },
    drop: function(i) {
      var o = i.originalEvent, l = i.rootEl, u = i.parentEl, a = i.sortable, h = i.dispatchSortableEvent, p = i.oldIndex, v = i.putSortable, _ = v || this.sortable;
      if (o) {
        var m = this.options, A = u.children;
        if (!Ai)
          if (m.multiDragKey && !this.multiDragKeyDown && this._deselectMultiDrag(), ft(Ge, m.selectedClass, !~ce.indexOf(Ge)), ~ce.indexOf(Ge))
            ce.splice(ce.indexOf(Ge), 1), ro = null, lo({
              sortable: a,
              rootEl: l,
              name: "deselect",
              targetEl: Ge,
              originalEvent: o
            });
          else {
            if (ce.push(Ge), lo({
              sortable: a,
              rootEl: l,
              name: "select",
              targetEl: Ge,
              originalEvent: o
            }), o.shiftKey && ro && a.el.contains(ro)) {
              var S = at(ro), C = at(Ge);
              if (~S && ~C && S !== C) {
                var I, P;
                for (C > S ? (P = S, I = C) : (P = C, I = S + 1); P < I; P++)
                  ~ce.indexOf(A[P]) || (ft(A[P], m.selectedClass, !0), ce.push(A[P]), lo({
                    sortable: a,
                    rootEl: l,
                    name: "select",
                    targetEl: A[P],
                    originalEvent: o
                  }));
              }
            } else
              ro = Ge;
            In = _;
          }
        if (Ai && this.isMultiDrag) {
          if (Qt = !1, (u[Mt].options.sort || u !== l) && ce.length > 1) {
            var B = ze(Ge), q = at(Ge, ":not(." + this.options.selectedClass + ")");
            if (!io && m.animation && (Ge.thisAnimationDuration = null), _.captureAnimationState(), !io && (m.animation && (Ge.fromRect = B, ce.forEach(function(L) {
              if (L.thisAnimationDuration = null, L !== Ge) {
                var te = Qt ? ze(L) : B;
                L.fromRect = te, _.addAnimationState({
                  target: L,
                  rect: te
                });
              }
            })), Ns(), ce.forEach(function(L) {
              A[q] ? u.insertBefore(L, A[q]) : u.appendChild(L), q++;
            }), p === at(Ge))) {
              var z = !1;
              ce.forEach(function(L) {
                if (L.sortableIndex !== at(L)) {
                  z = !0;
                  return;
                }
              }), z && (h("update"), h("sort"));
            }
            ce.forEach(function(L) {
              Xu(L);
            }), _.animateAll();
          }
          In = _;
        }
        (l === u || v && v.lastPutMode !== "clone") && ln.forEach(function(L) {
          L.parentNode && L.parentNode.removeChild(L);
        });
      }
    },
    nullingGlobal: function() {
      this.isMultiDrag = Ai = !1, ln.length = 0;
    },
    destroyGlobal: function() {
      this._deselectMultiDrag(), Te(document, "pointerup", this._deselectMultiDrag), Te(document, "mouseup", this._deselectMultiDrag), Te(document, "touchend", this._deselectMultiDrag), Te(document, "keydown", this._checkKeyDown), Te(document, "keyup", this._checkKeyUp);
    },
    _deselectMultiDrag: function(i) {
      if (!(typeof Ai < "u" && Ai) && In === this.sortable && !(i && wn(i.target, this.options.draggable, this.sortable.el, !1)) && !(i && i.button !== 0))
        for (; ce.length; ) {
          var o = ce[0];
          ft(o, this.options.selectedClass, !1), ce.shift(), lo({
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
  }, Pn(t, {
    // Static methods & properties
    pluginName: "multiDrag",
    utils: {
      /**
       * Selects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be selected
       */
      select: function(i) {
        var o = i.parentNode[Mt];
        !o || !o.options.multiDrag || ~ce.indexOf(i) || (In && In !== o && (In.multiDrag._deselectMultiDrag(), In = o), ft(i, o.options.selectedClass, !0), ce.push(i));
      },
      /**
       * Deselects the provided multi-drag item
       * @param  {HTMLElement} el    The element to be deselected
       */
      deselect: function(i) {
        var o = i.parentNode[Mt], l = ce.indexOf(i);
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
        Qt && u !== Ge ? a = -1 : Qt ? a = at(u, ":not(." + i.options.selectedClass + ")") : a = at(u), l.push({
          multiDragElement: u,
          index: a
        });
      }), {
        items: KE(ce),
        clones: [].concat(ln),
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
function vS(t, n) {
  ce.forEach(function(i, o) {
    var l = n.children[i.sortableIndex + (t ? Number(o) : 0)];
    l ? n.insertBefore(i, l) : n.appendChild(i);
  });
}
function md(t, n) {
  ln.forEach(function(i, o) {
    var l = n.children[i.sortableIndex + (t ? Number(o) : 0)];
    l ? n.insertBefore(i, l) : n.appendChild(i);
  });
}
function Ns() {
  ce.forEach(function(t) {
    t !== Ge && t.parentNode && t.parentNode.removeChild(t);
  });
}
le.mount(new pS());
le.mount(kf, Jf);
le.mount(new gS());
function Ls(t) {
  return typeof t == "number" && !isNaN(t) ? t : typeof t == "string" && !isNaN(Number(t.trim())) ? parseFloat(t) : String(t);
}
let Bs;
function _S() {
  return {
    draggingEntityName: Bs,
    setDraggingEntityName: (o) => {
      Bs = o;
    },
    getDraggingEntityName: () => Bs,
    clearDraggingEntityName: () => {
      Bs = void 0;
    }
  };
}
const mS = ["data-id"], bS = ["data-id"], yS = /* @__PURE__ */ Uf({
  __name: "SortableList",
  props: {
    items: {},
    sortableListId: {},
    sortableGroupName: {}
  },
  emits: ["sort"],
  setup(t, { emit: n }) {
    const i = n, o = t, l = _t(null), { setDraggingEntityName: u, clearDraggingEntityName: a } = _S();
    let h;
    Nt(() => o.items, async () => {
      await Rr(), h && h.destroy(), p();
    }, {
      immediate: !0
    });
    function p() {
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
              taskIds: (m.length ? m : [_]).map((A) => Ls(A.dataset.id))
            }));
          }
        },
        onEnd: (v) => {
          if (a(), v.oldIndex === v.newIndex && v.from.dataset.listId === v.to.dataset.listId)
            return;
          const _ = Ls(v.to.dataset.id), m = v.items.length ? v.items : [v.item], A = m[0].previousElementSibling ? Ls(m[0].previousElementSibling.dataset.id) ?? null : null, S = m.map((C) => Ls(C.dataset.id)).filter((C) => C);
          _ && i("sort", _, A, S);
        }
      }));
    }
    return (v, _) => (Kn(), jr("div", {
      ref_key: "sortableRef",
      ref: l,
      class: "tw-flex tw-flex-col",
      "data-id": o.sortableListId
    }, [
      (Kn(!0), jr(an, null, $1(o.items, (m) => (Kn(), jr("div", {
        key: m.uuid || m.id,
        "data-id": m.id
      }, [
        K1(v.$slots, "default", { item: m })
      ], 8, bS))), 128))
    ], 8, mS));
  }
}), wS = "live", xS = 3, ES = 1e3, SS = "ping", TS = 3e4, AS = 1e3, CS = "PL-client-id", OS = async (t) => {
  var o;
  let n = null;
  const { data: i } = await t(`pocketlists.system.getWebsocketUrl?channel=${wS}`).get().json();
  return (o = i.value) != null && o.data.url && (n = DE(i.value.data.url, {
    heartbeat: {
      message: SS,
      interval: TS,
      pongTimeout: AS
    },
    autoReconnect: {
      retries: xS,
      delay: ES,
      onFailed() {
      }
    }
  })), n;
}, bd = (t) => {
  const n = Object.entries(t).filter(([i, o]) => o).reduce((i, o) => {
    const l = h1(o[1]);
    if (Array.isArray(l))
      for (const u of l)
        i.push([o[0], u.toString()]);
    else
      i.push([o[0], String(o[1])]);
    return i;
  }, []);
  return n.length ? `?${new URLSearchParams(n).toString()}` : "";
};
var Tt = [];
for (var nf = 0; nf < 256; ++nf)
  Tt.push((nf + 256).toString(16).slice(1));
function IS(t, n = 0) {
  return (Tt[t[n + 0]] + Tt[t[n + 1]] + Tt[t[n + 2]] + Tt[t[n + 3]] + "-" + Tt[t[n + 4]] + Tt[t[n + 5]] + "-" + Tt[t[n + 6]] + Tt[t[n + 7]] + "-" + Tt[t[n + 8]] + Tt[t[n + 9]] + "-" + Tt[t[n + 10]] + Tt[t[n + 11]] + Tt[t[n + 12]] + Tt[t[n + 13]] + Tt[t[n + 14]] + Tt[t[n + 15]]).toLowerCase();
}
var Ws, DS = new Uint8Array(16);
function RS() {
  if (!Ws && (Ws = typeof crypto < "u" && crypto.getRandomValues && crypto.getRandomValues.bind(crypto), !Ws))
    throw new Error("crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported");
  return Ws(DS);
}
var yd = null, wd = null, Dn = 0;
function Xp(t, n, i) {
  t = t || {};
  var o = n && i || 0, l = n || new Uint8Array(16), u = t.random || (t.rng || RS)(), a = t.msecs !== void 0 ? t.msecs : Date.now(), h = t.seq !== void 0 ? t.seq : null, p = wd, v = yd;
  return a > Dn && t.msecs === void 0 && (Dn = a, h !== null && (p = null, v = null)), h !== null && (h > 2147483647 && (h = 2147483647), p = h >>> 19 & 4095, v = h & 524287), (p === null || v === null) && (p = u[6] & 127, p = p << 8 | u[7], v = u[8] & 63, v = v << 8 | u[9], v = v << 5 | u[10] >>> 3), a + 1e4 > Dn && h === null ? ++v > 524287 && (v = 0, ++p > 4095 && (p = 0, Dn++)) : Dn = a, wd = p, yd = v, l[o++] = Dn / 1099511627776 & 255, l[o++] = Dn / 4294967296 & 255, l[o++] = Dn / 16777216 & 255, l[o++] = Dn / 65536 & 255, l[o++] = Dn / 256 & 255, l[o++] = Dn & 255, l[o++] = p >>> 4 & 15 | 112, l[o++] = p & 255, l[o++] = v >>> 13 & 63 | 128, l[o++] = v >>> 5 & 255, l[o++] = v << 3 & 255 | u[10] & 7, l[o++] = u[11], l[o++] = u[12], l[o++] = u[13], l[o++] = u[14], l[o++] = u[15], n || IS(l);
}
const Vp = EE(CS, Xp());
function PS(t) {
  const n = _t([]), i = sr("useFetch"), o = sr("options"), l = Fp(t, [() => n.value.length]), u = async () => {
    const { data: S } = await i(`pocketlists.items.get${bd({ external_app_id: o.externalAppId, external_entity_type: o.externalEntityType, external_entity_id: o.externalEntityId })}`).get().json();
    n.value = S.value.data;
  }, a = async () => {
    const S = A();
    n.value.unshift(S), await Rr(), l.focusTaskById(S.id);
  }, h = async (S, C) => {
    const I = n.value.findIndex((P) => P.id === S.id);
    if (I > -1) {
      const P = C.newName ? A({ name: C.newName }) : A();
      n.value.splice(I + (C.currentName ? 1 : 0), 0, P), await Rr(), l.focusTaskById(P.id), C.newName && p(P);
    }
  }, p = async (S, C) => {
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
          const B = n.value.findIndex((q) => q.id === S.id);
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
    I > -1 && (n.value.splice(I, 1), C != null && C.silently || await i(`pocketlists.items.delete${bd({ "id[]": S.id })}`).delete().json());
  }, _ = (S, C) => {
    typeof S.id == "string" && !C.name && v(S, { silently: !0 });
  }, m = (S) => {
    if (S.client !== Vp.value && S.entity_type === "item") {
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
    const C = Xp();
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
    onUpdate: p,
    onDelete: v,
    onBlur: _,
    handleLog: m
  };
}
const FS = { class: "tw-p-8" }, MS = /* @__PURE__ */ Uf({
  __name: "App",
  setup(t) {
    const n = sr("useFetch"), i = sr("options"), o = _t(), {
      items: l,
      fetchItems: u,
      onAdd: a,
      onInsert: h,
      onUpdate: p,
      onDelete: v,
      onBlur: _,
      handleLog: m
    } = PS(o);
    return up("listNavigation", Fp(o, [() => l.value.length])), $f(async () => {
      u();
      const A = await OS(n);
      A && Nt(A.data, (S, C) => {
        if (typeof S == "string" && S !== C)
          try {
            m(JSON.parse(S));
          } catch {
          }
      });
    }), (A, S) => (Kn(), jr("div", FS, [
      Hn("div", {
        class: "tw-mb-4",
        onClick: S[0] || (S[0] = //@ts-ignore
        (...C) => rt(a) && rt(a)(...C))
      }, " + New To-Do "),
      Hn("div", {
        ref_key: "navigatableRef",
        ref: o
      }, [
        En(yS, {
          "sortable-list-id": 666,
          "sortable-group-name": "tasks",
          items: rt(l),
          onSort: () => {
          }
        }, {
          default: ep(({ item: C }) => [
            En(WE, {
              task: C,
              sortable: !1,
              editable: !0,
              "addable-props": {
                external_app_id: rt(i).externalAppId,
                external_entity_type: rt(i).externalEntityType,
                external_entity_id: rt(i).externalEntityId
              },
              onInsert: rt(h),
              onUpdate: rt(p),
              onDelete: rt(v),
              onBlur: rt(_)
            }, null, 8, ["task", "addable-props", "onInsert", "onUpdate", "onDelete", "onBlur"])
          ]),
          _: 1
        }, 8, ["items"])
      ], 512)
    ]));
  }
}), NS = {
  apiBaseUrl: "",
  apiToken: ""
}, LS = (t = {}) => {
  const n = {
    ...NS,
    ...t
  }, i = tE(MS);
  i.provide("options", n), i.provide("useFetch", CE({
    baseUrl: n.apiBaseUrl,
    options: {
      beforeFetch({ options: o }) {
        return o.headers = {
          ...o.headers,
          Authorization: `Bearer ${n.apiToken}`,
          "X-PL-API-Client": Vp.value
        }, {
          options: o
        };
      }
    }
  })), i.mount("#app");
};
export {
  LS as init
};
