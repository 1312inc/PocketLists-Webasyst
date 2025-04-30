/**
 * WebSocket Connection Monitor
 * Tracks WebSocket connections with specific URL parameter and updates UI status
 * @version 1.0.0
 */

class WebSocketMonitor {
    constructor(config = {}) {
      // Configuration with defaults
      this.config = {
        monitorParam: 'pocketlists',
        onlineColor: 'var(--green)',
        offlineColor: 'var(--orange)',
        statusSelector: '.js-pl-ws-status',
        debug: false,
        ...config
      };
  
      this.originalWebSocket = window.WebSocket;
      this.activeConnections = new Set();
      this.isMonitoring = false;
  
      this.init();
    }
  
    init() {
      if (this.isMonitoring) return;
  
      // Store original WebSocket implementation
      window.WebSocket = this.createWrappedWebSocket();
      this.isMonitoring = true;
  
      if (this.config.debug) {
        console.debug('[WebSocketMonitor] Initialized');
      }
    }
  
    teardown() {
      window.WebSocket = this.originalWebSocket;
      this.activeConnections.clear();
      this.isMonitoring = false;
  
      if (this.config.debug) {
        console.debug('[WebSocketMonitor] Teardown complete');
      }
    }
  
    createWrappedWebSocket() {
      const monitor = this;
  
      return class WrappedWebSocket {
        constructor(url, protocols) {
          this.socket = new monitor.originalWebSocket(url, protocols);
          this.url = url;
          this.shouldMonitor = monitor.shouldMonitorConnection(url);
  
          if (this.shouldMonitor) {
            monitor.setupConnectionMonitoring(this);
            monitor.activeConnections.add(this);
          }
        }
  
        // Proxy WebSocket properties
        get readyState() { return this.socket.readyState; }
        get bufferedAmount() { return this.socket.bufferedAmount; }
        get extensions() { return this.socket.extensions; }
        get protocol() { return this.socket.protocol; }
        get binaryType() { return this.socket.binaryType; }
        set binaryType(type) { this.socket.binaryType = type; }
  
        // Proxy WebSocket methods
        send(data) { return this.socket.send(data); }
        close(code, reason) { return this.socket.close(code, reason); }
        addEventListener(type, listener, options) {
          return this.socket.addEventListener(type, listener, options);
        }
        removeEventListener(type, listener, options) {
          return this.socket.removeEventListener(type, listener, options);
        }
        dispatchEvent(event) {
          return this.socket.dispatchEvent(event);
        }
      };
    }
  
    shouldMonitorConnection(url) {
      try {
        return new URL(url).searchParams.has(this.config.monitorParam);
      } catch (e) {
        if (this.config.debug) {
          console.warn('[WebSocketMonitor] URL parsing error:', e);
        }
        return false;
      }
    }
  
    setupConnectionMonitoring(wrappedSocket) {
      const monitor = this;
  
      wrappedSocket.socket.addEventListener('open', () => {
        monitor.updateStatus(true);
        });
  
      wrappedSocket.socket.addEventListener('close', () => {
        monitor.activeConnections.delete(wrappedSocket);
        monitor.updateStatus(false);
      });
  
      wrappedSocket.socket.addEventListener('error', (event) => {
        if (monitor.config.debug) {
          console.error('[WebSocketMonitor] WebSocket error:', event);
        }
        monitor.updateStatus(false);
      });
    }
  
    updateStatus(isOnline) {
      const statusElement = document.querySelector(this.config.statusSelector);
      if (!statusElement) return;
  
      statusElement.style.background = isOnline
        ? this.config.onlineColor
        : this.config.offlineColor;
  
      // Dispatch custom event for other components
      const event = new CustomEvent('websocket-status-change', {
        detail: { isOnline }
      });
      document.dispatchEvent(event);
  
      if (this.config.debug) {
        console.debug(`[WebSocketMonitor] Status updated: ${isOnline ? 'online' : 'offline'}`);
      }
    }
  
    getActiveConnections() {
      return Array.from(this.activeConnections);
    }
  }
  
  // Export for module systems
  if (typeof module !== 'undefined' && module.exports) {
    module.exports = WebSocketMonitor;
  } else if (typeof define === 'function' && define.amd) {
    define([], () => WebSocketMonitor);
  } else {
    window.WebSocketMonitor = WebSocketMonitor;
  }