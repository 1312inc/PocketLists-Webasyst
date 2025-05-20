
  /**
   * WebSocket Connection Monitor
   * Tracks WebSocket connections with specific URL parameter and updates UI status
   * @version 1.0.0
   */
  export default class WebSocketMonitor {
    constructor(options = {}) {
      this.WS_MONITOR_PARAM = options.monitorParam || 'pocketlists';
      this.STATUS_ONLINE_COLOR = options.onlineColor || 'var(--green)';
      this.STATUS_OFFLINE_COLOR = options.offlineColor || 'var(--orange)';
      this.STATUS_SELECTOR = options.statusSelector || '.js-pl-ws-status';
      
      this.originalWebSocket = window.WebSocket;
      this.init();
    }

    init() {
      this.monitorWebSockets();
    }

    monitorWebSockets() {
      const self = this;
      
      window.WebSocket = function(url, protocols) {
        const socket = new self.originalWebSocket(url, protocols);

        if (new URL(url).searchParams.has(self.WS_MONITOR_PARAM)) {
          socket.addEventListener('open', () => self.updateStatus(true));
          socket.addEventListener('close', () => self.updateStatus(false));
          socket.addEventListener('error', () => self.updateStatus(false));
        }

        return socket;
      };
    }

    updateStatus(isOnline) {
      const statusElement = document.querySelector(this.STATUS_SELECTOR);
      if (!statusElement) return;
      
      statusElement.style.background = isOnline 
        ? this.STATUS_ONLINE_COLOR 
        : this.STATUS_OFFLINE_COLOR;
      
      statusElement.classList.toggle('ws-online', isOnline);
      statusElement.classList.toggle('ws-offline', !isOnline);
    }

    destroy() {
      window.WebSocket = this.originalWebSocket;
    }
  }
