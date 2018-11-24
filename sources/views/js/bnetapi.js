var $app_scope = null;
    var ws = null;
    var lastRequestId = 0;
    
    var app = angular.module('ChatBotApp', []);
    app.controller('ChatBotCtrl', function($scope) {
        $app_scope = $scope;
        
        $scope.connected = false;
        $scope.users = {};
        $scope.messages = [];
    });
    
    var OnAuthResponse = function(error, msg) {
      if (error) {
        $app_scope.messages.push('Failed to auth: ' + error);
      } else {
        
        $app_scope.messages.push('Authenticated. Connecting to chat...');
        
        var connect = {
          "command": "Botapichat.ConnectRequest",
          "request_id": ++lastRequestId,
          "payload": {
          }
        };
        
        ws.send(JSON.stringify(connect));
      }
    }
    
    var OnChatConnectResponse = function(error, msg) {
      if (error) {
        $app_scope.messages.push('Failed to connect to chat: ' + error);
      } 
    }
    
    var OnChatConnect = function(error, msg) {
      $app_scope.messages.push('Connected to channel: ' + msg.channel);
    }
    
    var OnChatDisconnect = function(error, msg) {
      $app_scope.messages.push('Disconected from channel');
    }
    
    
    var OnChatSendMessageResponse = function(error, msg) {
      if (error) {
        $app_scope.messages.push('Failed to send message: ' + error);
      }
    }
    
    var OnChatSendWhisperResponse = function(error, msg) {
      if (error) {
        $app_scope.messages.push('Failed to send whisper: ' + error);
      }
    }
    
    var OnMessageEvent = function(error, msg) {
      var user = $app_scope.users[msg["user_id"]];
    
      if (msg.type == "Whisper") {
        $app_scope.messages.push('<Whisper: ' + user.toon_name + '> ' + msg["message"]);
      } else if (msg.type == "Channel") {
        $app_scope.messages.push('<' + user.toon_name + '> ' + msg["message"]);
      } else {
        $app_scope.messages.push('[' + msg.type + '] ' + msg["message"]);
      }
    }
    
    var OnWhisperEvent = function(error, msg) {
      var user = $app_scope.users[msg["user_id"]];
    
      if (user) {
        $app_scope.messages.push('Whisper from ' + user.toon_name + ': ' + msg["message"]);
      } else {
        $app_scope.messages.push('Whisper from unknown user [' + msg["user_id"] + ']: ' + msg["message"]);
      }
    }
    
    var OnUserUpdateEvent = function(error, msg) {
      $app_scope.users[msg["user_id"]] = msg;
    }
    
    var OnUserLeaveEvent = function(error, msg) {
      var user = $app_scope.users[msg["user_id"]];
    
      if (user) {
        $app_scope.messages.push('User ' + user.toon_name + ' left the channel');
      } else {
        $app_scope.messages.push('Unknown user [' + msg["user_id"] + '] left the channel');
      }
      
      delete $app_scope.users[msg["user_id"]];
    }
    
    var messageHandlers = {
      "Botapiauth.Response": OnAuthResponse,
      "Botapichat.Response": OnChatConnectResponse,
      "Botapichat.ConnectEventRequest": OnChatConnect,
      
      "Botapichat.DisconnectEventRequest": OnChatDisconnect,
      "Botapichat.SendMessageResponse": OnChatSendMessageResponse,
      "Botapichat.SendWhisperResponse": OnChatSendWhisperResponse,
      "Botapichat.MessageEventRequest": OnMessageEvent,
      "Botapichat.UserUpdateEventRequest": OnUserUpdateEvent,
      "Botapichat.UserLeaveEventRequest": OnUserLeaveEvent,
    };
    
    
    var CreateWebSocket = function(apiKey) {
      ws = new WebSocket("wss://connect-bot.classic.blizzard.com/v1/rpc/chat", "json");
    
      ws.onopen = function() {
        $app_scope.connected = true;
        $app_scope.users = {};
        $app_scope.messages = [];
        
        $app_scope.messages.push('## ChatBot Connected to server');
        $app_scope.$digest();
        
        var auth = {
          "command": "Botapiauth.AuthenticateRequest",
          "request_id": ++lastRequestId,
          "payload": {
            "api_key": apiKey,
          }
        };
        
        ws.send(JSON.stringify(auth));
      };
      
      ws.onclose = function() {
        $app_scope.connected = false;
        $app_scope.messages.push('## ChatBot Disconnected from server');
        $app_scope.$digest();
      };
    
      ws.onmessage = function(event) {
        var msg = JSON.parse(event.data);
        console.log(msg);
        
        var handler = messageHandlers[msg["command"]];
        
        if (handler) {
           handler(msg["status"], msg["payload"]);
        } else {
          $app_scope.messages.push('Recved unknown message: ' + msg["command"]);
        }
        
        $app_scope.$digest();
      }; 
    }
    
    $(document).ready(function(){
      
      $("#con-button").click(function(){
        if ($app_scope.connected) {
          ws.close();
        } else {
          CreateWebSocket($("#apikey").val());
        }
      });
      
      $("#command-button").click(function(){
        var text = $("#command-input").val();
        
        if (!text.length) {
          return;
        }
        
        $("#command-input").val("");

        $app_scope.messages.push('<Sent> ' + text);
        
        var auth = {
          "command": "Botapichat.SendMessageRequest",
          "request_id": ++lastRequestId,
          "payload": {
            "message": text,
          }
        };
        
        ws.send(JSON.stringify(auth));
      });
    });