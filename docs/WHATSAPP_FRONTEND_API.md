# WhatsApp Frontend API

Base auth for protected Laravel WhatsApp APIs:

```http
Accept: application/json
Content-Type: application/json
Cookie: whatsapp_react_session=<httpOnly cookie set by backend>
```

Frontend transport requirements:
- use `withCredentials: true` on Axios/fetch
- do not send bearer tokens manually from the browser

## 1. Auth / Current User

### Exchange callback code

```json
{
  "name": "Auth Verify",
  "method": "POST",
  "endpoint": "/whatsapp/auth/verify",
  "auth": "No auth header required",
  "body": {
    "code": "short_lived_one_time_code_here"
  },
  "response": {
    "success": true,
    "user": {
      "id": 1,
      "name": "Hasib",
      "email": "user@example.com",
      "role": "superadmin",
      "permissions": [
        "whatsapp.access",
        "whatsapp.dashboard.view",
        "whatsapp.leads.view",
        "whatsapp.handoff.resolve"
      ],
      "exp": 1777777777
    }
  },
  "error_response": {
    "success": false,
    "error": "Code is required."
  }
}
```

### Current user

```json
{
  "name": "Auth Current User",
  "method": "GET",
  "endpoint": "/whatsapp/auth/me",
  "auth": "Cookie session",
  "query": {},
  "response": {
    "success": true,
    "user": {
      "id": 1,
      "name": "Hasib",
      "email": "user@example.com",
      "role": "superadmin",
      "permissions": [
        "whatsapp.access",
        "whatsapp.dashboard.view",
        "whatsapp.leads.view",
        "whatsapp.handoff.resolve"
      ],
      "exp": 1777777777
    }
  },
  "error_response": {
    "success": false,
    "error": "Missing authentication session."
  }
}
```

### Logout

```json
{
  "name": "Auth Logout",
  "method": "POST",
  "endpoint": "/whatsapp/auth/logout",
  "auth": "Cookie session",
  "body": {},
  "response": {
    "success": true,
    "message": "Logged out successfully.",
    "user": {
      "id": 1,
      "name": "Hasib",
      "email": "user@example.com",
      "role": "superadmin",
      "permissions": [
        "whatsapp.access",
        "whatsapp.dashboard.view",
        "whatsapp.leads.view",
        "whatsapp.handoff.resolve"
      ],
      "exp": 1777777777
    }
  },
  "error_response": {
    "success": false,
    "error": "Missing authentication session."
  }
}
```

Notes:
- Launch redirect now sends a short-lived one-time `code` to the frontend callback
- Frontend exchanges that code at `/whatsapp/auth/verify`
- Backend sets the secure `HttpOnly` cookie during the exchange
- `GET /whatsapp/auth/me` is the best endpoint for restoring auth state after page refresh
- Not currently returned: `avatar`, `timezone`

## 2. Dashboard

### Dashboard summary

```json
{
  "name": "Dashboard Summary",
  "method": "GET",
  "endpoint": "/whatsapp/dashboard",
  "auth": "Cookie session",
  "query": {
    "compact": 1
  },
  "response": {
    "success": true,
    "auth": {
      "uid": 1,
      "name": "Hasib",
      "email": "user@example.com",
      "role": "superadmin",
      "permissions": [
        "whatsapp.access",
        "whatsapp.dashboard.view"
      ],
      "exp": 1777777777
    },
    "bot_dashboard": {
      "success": true,
      "dashboard": {
        "summary": {
          "total_leads": 120,
          "new_leads": 25,
          "active_leads": 48,
          "cold_leads": 30,
          "converted_leads": 17,
          "queued_outbound": 8,
          "failed_outbound": 1,
          "sent_outbound": 92,
          "active_handoffs": 4,
          "open_learning_questions": 6,
          "due_followup_plans": 1,
          "unscheduled_followup_plans": 1
        },
        "working_hours": {
          "timezone": "Asia/Dhaka",
          "local_time": "2026-03-30 12:30:00",
          "is_within_working_hours": true
        }
      }
    }
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

Notes:
- `compact=1` returns the lighter summary-only dashboard
- `compact=0` also includes preview lists like `recent_leads`
- No chart or trend API yet
- `recent_leads`, `learning_questions_preview`, `due_followup_plans_preview`, and `unscheduled_followup_plans_preview` are available when `compact=0`

## 3. Lead List / Inbox

### Lead list

```json
{
  "name": "Lead List",
  "method": "GET",
  "endpoint": "/whatsapp/leads",
  "auth": "Bearer <react_token>",
  "query": {
    "page": 1,
    "limit": 25,
    "search": "pricing",
    "status": "active",
    "bot_type": "sales",
    "follow_up_required": 1,
    "tag": "interested"
  },
  "response": {
    "success": true,
    "auth": {
      "uid": 1,
      "name": "Hasib",
      "email": "user@example.com",
      "role": "superadmin",
      "permissions": [
        "whatsapp.access",
        "whatsapp.leads.view"
      ],
      "exp": 1777777777
    },
    "bot_leads": {
      "success": true,
      "leads": [
        {
          "id": 15,
          "session_id": "8801XXXXXXXXX",
          "bot_type": "sales",
          "status": "active",
          "first_seen": "2026-03-29 10:11:12",
          "last_seen": "2026-03-30 11:05:22",
          "last_user_message": "price koto",
          "last_bot_reply": "Apni chaile ami...",
          "last_interaction_type": "pricing",
          "promised_payment_at": null,
          "chat_summary": "Interested in pricing",
          "auto_reply_enabled": 1,
          "irrelevant_message_streak": 0,
          "unclear_message_streak": 0,
          "last_relevance_status": "relevant",
          "tag_profile_updated_at": "2026-03-30 11:00:00",
          "follow_up_required": 1,
          "handoff_status": "active",
          "tags": [
            "interested",
            "pricing"
          ]
        }
      ],
      "pagination": {
        "page": 1,
        "limit": 25,
        "total": 120,
        "total_pages": 5,
        "has_next": true,
        "has_prev": false
      },
      "filters": {
        "status": "active",
        "bot_type": "sales",
        "tag": "interested",
        "search": "pricing",
        "follow_up_required": 1
      }
    }
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

Supported query params:
- `page`
- `limit`
- `search`
- `status`
- `bot_type`
- `follow_up_required`
- `tag`

Not available yet:
- sort options

## 4. Lead Details

### Lead details

```json
{
  "name": "Lead Details",
  "method": "GET",
  "endpoint": "/whatsapp/leads/:sessionId",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "auth": {
      "uid": 1,
      "name": "Hasib",
      "email": "user@example.com",
      "role": "superadmin",
      "permissions": [
        "whatsapp.access",
        "whatsapp.leads.view"
      ],
      "exp": 1777777777
    },
    "lead": {
      "id": 15,
      "session_id": "8801XXXXXXXXX",
      "bot_type": "sales",
      "status": "active",
      "first_seen": "2026-03-29 10:11:12",
      "last_seen": "2026-03-30 11:05:22",
      "last_user_message": "price koto",
      "last_bot_reply": "Apni chaile ami...",
      "last_interaction_type": "pricing",
      "promised_payment_at": "2026-04-02 18:00:00",
      "chat_summary": "Interested in pricing and asked for payment timing",
      "follow_up_required": 1,
      "auto_reply_enabled": 1,
      "tags": [
        "interested",
        "pricing"
      ]
    },
    "handoff": {
      "success": true,
      "session_id": "8801XXXXXXXXX",
      "handoff": {
        "session_id": "8801XXXXXXXXX",
        "assigned_to": null,
        "reason": "Manual review needed",
        "status": "active",
        "created_at": "2026-03-30 11:10:00"
      }
    }
  },
  "error_response": {
    "success": false,
    "message": "Lead not found."
  }
}
```

Notes:
- Current Laravel response uses compact bot detail for performance
- Present now:
  - `session_id`
  - `bot_type`
  - `status`
  - `first_seen`
- `last_seen`
- `last_user_message`
- `last_bot_reply`
- `last_interaction_type`
- `promised_payment_at`
- `chat_summary`
- `follow_up_required`
- `auto_reply_enabled`
- `tags`
- handoff object

### Lead status update

```json
{
  "name": "Lead Status Update",
  "method": "POST",
  "endpoint": "/whatsapp/leads/:sessionId/status",
  "auth": "Bearer <react_token>",
  "body": {
    "status": "converted"
  },
  "response": {
    "success": true,
    "message": "Lead '8801XXXXXXXXX' status updated to 'converted'"
  },
  "error_response": {
    "success": false,
    "error": "Invalid status"
  }
}
```

Allowed status values:
- `new`
- `active`
- `cold`
- `converted`

### Promised payment update

```json
{
  "name": "Promised Payment Update",
  "method": "POST",
  "endpoint": "/whatsapp/leads/:sessionId/promised-payment",
  "auth": "Bearer <react_token>",
  "body": {
    "promised_payment_at": "2026-04-02 18:00:00"
  },
  "response": {
    "success": true,
    "session_id": "8801XXXXXXXXX",
    "promised_payment_at": "2026-04-02 18:00:00",
    "lead": {
      "session_id": "8801XXXXXXXXX"
    }
  },
  "error_response": {
    "success": false,
    "error": "Lead not found"
  }
}
```

### Auto reply update

```json
{
  "name": "Auto Reply Update",
  "method": "POST",
  "endpoint": "/whatsapp/leads/:sessionId/auto-reply",
  "auth": "Bearer <react_token>",
  "body": {
    "enabled": false
  },
  "response": {
    "success": true,
    "session_id": "8801XXXXXXXXX",
    "auto_reply_enabled": false,
    "lead": {
      "session_id": "8801XXXXXXXXX"
    }
  },
  "error_response": {
    "success": false,
    "error": "Lead not found"
  }
}
```

### Handoff update

```json
{
  "name": "Resolve Handoff",
  "method": "POST",
  "endpoint": "/whatsapp/handoffs/:sessionId/resolve",
  "auth": "Bearer <react_token>",
  "body": {},
  "response": {
    "success": true,
    "message": "Handoff resolved for session '8801XXXXXXXXX'",
    "session_id": "8801XXXXXXXXX",
    "handoff": {
      "session_id": "8801XXXXXXXXX",
      "is_handoff_active": 0,
      "reason": "Manual review needed"
    }
  },
  "error_response": {
    "success": false,
    "error": "No active handoff row found for this session"
  }
}
```

```json
{
  "name": "Assign Bot To Handoff",
  "method": "POST",
  "endpoint": "/whatsapp/handoffs/:sessionId/assign-bot",
  "auth": "Bearer <react_token>",
  "body": {},
  "response": {
    "success": true,
    "message": "Bot auto reply re-assigned for session '8801XXXXXXXXX'",
    "session_id": "8801XXXXXXXXX",
    "lead": {
      "session_id": "8801XXXXXXXXX",
      "auto_reply_enabled": 1
    },
    "handoff": {
      "session_id": "8801XXXXXXXXX",
      "is_handoff_active": 0
    }
  },
  "error_response": {
    "success": false,
    "error": "Lead not found"
  }
}
```

## 5. Chat History

### Lead chat history

```json
{
  "name": "Chat History",
  "method": "GET",
  "endpoint": "/whatsapp/leads/:sessionId/history",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "session_id": "8801XXXXXXXXX",
    "bot_type": "sales",
    "history": [
      {
        "role": "user",
        "content": "price koto"
      },
      {
        "role": "assistant",
        "content": "Apni chaile ami..."
      }
    ]
  },
  "error_response": {
    "success": false,
    "error": "Lead not found"
  }
}
```

Current history fields:
- `role`
- `content`

Not available yet:
- `id`
- `created_at`
- `message_type`
- `media_url`
- pagination
- distinct sender types like `agent`, `system`

## 6. Tags

### All tags

```json
{
  "name": "All Tags",
  "method": "GET",
  "endpoint": "/whatsapp/tags",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "tags": [
      {
        "id": 1,
        "name": "pricing",
        "description": "Interested in price",
        "created_at": "2026-03-30 10:00:00"
      }
    ]
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

### Create tag

```json
{
  "name": "Create Tag",
  "method": "POST",
  "endpoint": "/whatsapp/tags",
  "auth": "Bearer <react_token>",
  "body": {
    "name": "hot_lead",
    "description": "High intent"
  },
  "response": {
    "success": true,
    "tag": {
      "id": 2,
      "name": "hot_lead",
      "description": "High intent",
      "created_at": "2026-03-30 10:05:00"
    }
  },
  "error_response": {
    "success": false,
    "error": "Tag name is required"
  }
}
```

### Lead tags

```json
{
  "name": "Lead Tags",
  "method": "GET",
  "endpoint": "/whatsapp/leads/:sessionId/tags",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "session_id": "8801XXXXXXXXX",
    "tags": [
      "pricing",
      "interested"
    ],
    "tag_rows": [
      {
        "tag_name": "pricing",
        "source": "auto",
        "confidence": 0.93,
        "note": "Detected from history",
        "created_at": "2026-03-30 10:11:00",
        "updated_at": "2026-03-30 10:11:00"
      },
      {
        "tag_name": "interested",
        "source": "manual",
        "confidence": 1.0,
        "note": "Manual tag update",
        "created_at": "2026-03-30 10:12:00",
        "updated_at": "2026-03-30 10:12:00"
      }
    ],
    "chat_summary": "Interested in pricing"
  },
  "error_response": {
    "success": false,
    "error": "Lead not found"
  }
}
```

### Assign tag to lead

```json
{
  "name": "Assign Tag To Lead",
  "method": "POST",
  "endpoint": "/whatsapp/leads/:sessionId/tags",
  "auth": "Bearer <react_token>",
  "body": {
    "tag_name": "interested"
  },
  "response": {
    "success": true,
    "message": "Tag 'interested' assigned to lead '8801XXXXXXXXX'",
    "tags": [
      "interested",
      "pricing"
    ],
    "tag_rows": []
  },
  "error_response": {
    "success": false,
    "error": "tag_name is required"
  }
}
```

### Remove tag from lead

```json
{
  "name": "Remove Tag From Lead",
  "method": "DELETE",
  "endpoint": "/whatsapp/leads/:sessionId/tags/:tagName",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "message": "Tag 'pricing' removed from lead '8801XXXXXXXXX'",
    "tags": [
      "interested"
    ],
    "tag_rows": []
  },
  "error_response": {
    "success": false,
    "error": "Tag mapping not found"
  }
}
```

### Refresh lead tags from chat

```json
{
  "name": "Refresh Lead Tags",
  "method": "POST",
  "endpoint": "/whatsapp/leads/:sessionId/tags/refresh",
  "auth": "Bearer <react_token>",
  "body": {},
  "response": {
    "success": true,
    "session_id": "8801XXXXXXXXX",
    "chat_summary": "Interested in pricing",
    "auto_tags": [
      "pricing"
    ],
    "manual_tags": [
      "interested"
    ],
    "suppressed_auto_tags": [],
    "final_tags": [
      "pricing",
      "interested"
    ],
    "tag_rows": []
  },
  "error_response": {
    "success": false,
    "error": "Lead not found"
  }
}
```

Not available in Laravel wrapper yet:
- update tag
- delete tag
- suppressed tags
- tag feedback or history endpoint

Current tag fields available:
- master tag:
  - `id`
  - `name`
  - `description`
  - `created_at`
- lead tag relation:
  - `tag_name`
  - `source`
  - `confidence`
  - `note`
  - `created_at`
  - `updated_at`

## 7. Follow-up Plans

### Follow-up plans by lead

```json
{
  "name": "Follow-up Plans By Lead",
  "method": "GET",
  "endpoint": "/whatsapp/leads/:sessionId/followup-plans",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "plans": [
      {
        "id": 12,
        "session_id": "8801XXXXXXXXX",
        "bot_type": "sales",
        "reason": "first_message_only",
        "note": "Initial follow-up",
        "scheduled_for": "2026-03-31 10:00:00",
        "priority": "normal",
        "status": "pending",
        "last_followup_sent_at": null,
        "created_at": "2026-03-30 10:30:00",
        "updated_at": "2026-03-30 10:30:00"
      }
    ]
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

### Create follow-up plan

```json
{
  "name": "Create Follow-up Plan",
  "method": "POST",
  "endpoint": "/whatsapp/leads/:sessionId/followup-plans",
  "auth": "Bearer <react_token>",
  "body": {
    "reason": "first_message_only",
    "note": "Call later",
    "scheduled_for": "2026-03-31 10:00:00",
    "priority": "normal"
  },
  "response": {
    "success": true,
    "message": "Follow-up plan created successfully",
    "plan_id": 12
  },
  "error_response": {
    "success": false,
    "error": "Invalid follow-up reason"
  }
}
```

### Follow-up reasons

```json
{
  "name": "Follow-up Plan Reasons",
  "method": "GET",
  "endpoint": "/whatsapp/followup-plans/reasons",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "reasons": [
      "after_1_month",
      "after_1_week",
      "after_eid",
      "call_back_later",
      "demo_pending",
      "first_message_only",
      "interested_but_inactive",
      "janabo",
      "price_sensitive_followup",
      "scenario_followup",
      "stopped_replying",
      "support_unresolved",
      "tag_followup",
      "will_visit_office"
    ],
    "priorities": [
      "high",
      "low",
      "normal"
    ]
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

### All follow-up plans

```json
{
  "name": "All Follow-up Plans",
  "method": "GET",
  "endpoint": "/whatsapp/followup-plans",
  "auth": "Bearer <react_token>",
  "query": {
    "page": 1,
    "limit": 25,
    "status": "pending",
    "session_id": "8801XXXXXXXXX"
  },
  "response": {
    "success": true,
    "plans": [
      {
        "id": 12,
        "session_id": "8801XXXXXXXXX",
        "bot_type": "sales",
        "reason": "first_message_only",
        "note": "Initial follow-up",
        "scheduled_for": "2026-03-31 10:00:00",
        "priority": "normal",
        "status": "pending",
        "last_followup_sent_at": null,
        "created_at": "2026-03-30 10:30:00",
        "updated_at": "2026-03-30 10:30:00"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 120,
      "total_pages": 5,
      "has_next": true,
      "has_prev": false
    },
    "filters": {
      "status": "pending",
      "session_id": "8801XXXXXXXXX"
    }
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

Supported query params:
- `page`
- `limit`
- `status`
- `session_id`

Notes:
- Current wrapper response is paginated.
- This is the endpoint the global follow-ups page should use.

### Follow-up status update

```json
{
  "name": "Follow-up Status Update",
  "method": "POST",
  "endpoint": "/whatsapp/followup-plans/:id/status",
  "auth": "Bearer <react_token>",
  "body": {
    "status": "done"
  },
  "response": {
    "success": true,
    "message": "Follow-up plan 12 updated to 'done'"
  },
  "error_response": {
    "success": false,
    "error": "Invalid follow-up plan status"
  }
}
```

Allowed follow-up status values:
- `pending`
- `processing`
- `done`
- `cancelled`

Notes:
- Current wrapper returns a success message only, not the refreshed `plan` object.

### Follow-up mark sent

```json
{
  "name": "Follow-up Mark Sent",
  "method": "POST",
  "endpoint": "/whatsapp/followup-plans/:id/mark-sent",
  "auth": "Bearer <react_token>",
  "body": {},
  "response": {
    "success": true,
    "message": "Follow-up plan 12 marked as sent"
  },
  "error_response": {
    "success": false,
    "error": "Follow-up plan not found"
  }
}
```

Notes:
- Current wrapper returns a success message only, not the refreshed `plan` object.

### Follow-up delete or cancel

```json
{
  "name": "Follow-up Delete",
  "method": "DELETE",
  "endpoint": "/whatsapp/followup-plans/:id",
  "auth": "Bearer <react_token>",
  "query": {},
  "response": {
    "success": true,
    "message": "Follow-up plan 12 deleted successfully"
  },
  "error_response": {
    "success": false,
    "error": "Follow-up plan not found"
  }
}
```

Notes:
- Cancel is currently supported either by status update to `cancelled` or by deleting the plan.
- Delete returns a success message only.

### Follow-up reschedule

Not available yet.

There is no dedicated wrapper endpoint for rescheduling right now.

### Follow-up scheduler run

```json
{
  "name": "Follow-up Scheduler Run",
  "method": "POST",
  "endpoint": "/whatsapp/followup-plans/scheduler/run",
  "auth": "Bearer <react_token>",
  "body": {
    "dispatch_immediately": true,
    "limit": 50
  },
  "response": {
    "success": true,
    "processed_count": 3,
    "success_count": 3,
    "results": []
  },
  "error_response": {
    "success": false,
    "error": "limit must be greater than 0"
  }
}
```

Notes:
- Current wrapper path is `/whatsapp/followup-plans/scheduler/run`
- Current response fields are `processed_count`, `success_count`, and `results`

Current follow-up fields available:
- `id`
- `session_id`
- `bot_type`
- `reason`
- `note`
- `scheduled_for`
- `priority`
- `status`
- `last_followup_sent_at`
- `created_at`
- `updated_at`

## 8. Review Queues

### Open handoff chats

```json
{
  "name": "Open Handoff Chats",
  "method": "GET",
  "endpoint": "/whatsapp/review/handoffs",
  "auth": "Bearer <react_token>",
  "query": {
    "page": 1,
    "limit": 25
  },
  "response": {
    "success": true,
    "items": [
      {
        "session_id": "8801XXXXXXXXX",
        "bot_type": "sales",
        "status": "active",
        "reason": "Manual review needed",
        "severity": "high",
        "last_user_message": "price koto",
        "last_bot_reply": "Apni chaile ami...",
        "assigned_to": "agent-1",
        "created_at": "2026-03-31 11:30:00",
        "updated_at": "2026-03-31 11:35:00"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 10,
      "total_pages": 1,
      "has_next": false,
      "has_prev": false
    }
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

### Abusive chats

```json
{
  "name": "Abusive Chats",
  "method": "GET",
  "endpoint": "/whatsapp/review/abusive",
  "auth": "Bearer <react_token>",
  "query": {
    "page": 1,
    "limit": 25
  },
  "response": {
    "success": true,
    "items": [
      {
        "session_id": "8801XXXXXXXXX",
        "bot_type": "sales",
        "status": "open",
        "reason": "abusive_language",
        "severity": "high",
        "last_user_message": "....",
        "last_bot_reply": "Apni chaile ami...",
        "assigned_to": null,
        "created_at": "2026-03-31 12:10:00",
        "updated_at": "2026-03-31 12:10:00"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 1,
      "total_pages": 1,
      "has_next": false,
      "has_prev": false
    }
  }
}
```

### Manual review chats

`GET /whatsapp/review/manual`

### Unclear chats

`GET /whatsapp/review/unclear`

### Dropped chats

`GET /whatsapp/review/dropped`

All five queue endpoints return the same row shape:
- `session_id`
- `bot_type`
- `status`
- `reason`
- `severity`
- `last_user_message`
- `last_bot_reply`
- `assigned_to`
- `created_at`
- `updated_at`

UI notes:
- the React review page at `/reviews` consumes these five queue endpoints directly
- queue rows can open the lead detail route at `/leads/:sessionId`
- the current review screen uses `limit=10` for a lighter first load

### Assign to human

```json
{
  "name": "Assign To Human",
  "method": "POST",
  "endpoint": "/whatsapp/review/:sessionId/assign-human",
  "auth": "Bearer <react_token>",
  "body": {
    "assigned_to": "agent-id-or-name",
    "queue_type": "manual"
  },
  "response": {
    "success": true,
    "message": "Assigned to human successfully",
    "session_id": "8801XXXXXXXXX",
    "assigned_to": "agent-id-or-name",
    "queue_types": ["handoff", "manual"],
    "auto_reply_enabled": 0
  },
  "error_response": {
    "success": false,
    "error": "assigned_to is required"
  }
}
```

Notes:
- `queue_type` is optional
- when omitted, the backend applies the assignment to all currently active review queues for that session
- assigning to human also activates handoff and disables bot auto reply
- the success payload also includes refreshed `lead` and `handoff` objects

### Assign back to bot

Use the existing handoff endpoint:

`POST /whatsapp/handoffs/:sessionId/assign-bot`

Notes:
- this is the same endpoint used from the lead details screen
- it resolves any active handoff and re-enables bot auto reply

### Disable bot

```json
{
  "name": "Disable Bot",
  "method": "POST",
  "endpoint": "/whatsapp/review/:sessionId/disable-bot",
  "auth": "Bearer <react_token>",
  "body": {},
  "response": {
    "success": true,
    "message": "Bot disabled successfully",
    "session_id": "8801XXXXXXXXX",
    "auto_reply_enabled": 0,
    "lead": {
      "session_id": "8801XXXXXXXXX",
      "auto_reply_enabled": 0
    }
  }
}
```

### Enable bot

```json
{
  "name": "Enable Bot",
  "method": "POST",
  "endpoint": "/whatsapp/review/:sessionId/enable-bot",
  "auth": "Bearer <react_token>",
  "body": {},
  "response": {
    "success": true,
    "message": "Bot enabled successfully",
    "session_id": "8801XXXXXXXXX",
    "auto_reply_enabled": 1,
    "lead": {
      "session_id": "8801XXXXXXXXX",
      "auto_reply_enabled": 1
    }
  }
}
```

### Resolve review item

```json
{
  "name": "Resolve Review Item",
  "method": "POST",
  "endpoint": "/whatsapp/review/:sessionId/resolve",
  "auth": "Bearer <react_token>",
  "body": {
    "note": "Handled by support",
    "queue_type": "manual"
  },
  "response": {
    "success": true,
    "message": "Review item resolved",
    "session_id": "8801XXXXXXXXX",
    "queue_types": ["manual"],
    "handoff": {
      "session_id": "8801XXXXXXXXX",
      "is_handoff_active": 0
    }
  },
  "error_response": {
    "success": false,
    "error": "No active review item found for this session"
  }
}
```

Notes:
- `queue_type` is optional here too
- when omitted, the backend resolves all currently active review queues for that session

### Add internal note

```json
{
  "name": "Add Internal Note",
  "method": "POST",
  "endpoint": "/whatsapp/review/:sessionId/note",
  "auth": "Bearer <react_token>",
  "body": {
    "note": "Customer asked to reconnect tomorrow.",
    "queue_type": "manual"
  },
  "response": {
    "success": true,
    "message": "Note added successfully",
    "session_id": "8801XXXXXXXXX",
    "queue_type": "manual",
    "note": "Customer asked to reconnect tomorrow."
  },
  "error_response": {
    "success": false,
    "error": "note is required"
  }
}
```

## 9. Realtime / Live Data

Current implementation status:
- SSE is implemented through the Laravel wrapper
- JSON event polling is also available
- WebSocket is not implemented

### SSE stream

```json
{
  "name": "WhatsApp Realtime SSE",
  "method": "GET",
  "endpoint": "/whatsapp/realtime/stream",
  "auth": "Cookie session",
  "query": {
    "after_id": 0,
    "limit": 50
  },
  "response": "text/event-stream",
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

SSE auth:
- Browser sends the secure auth cookie automatically
- Frontend should open the stream with credentials enabled
- No token query param is needed anymore

Current SSE control events:
- `ready`
- `ping`

Current emitted WhatsApp events:
- `message.received`
- `message.sent`
- `lead.updated`
- `lead.tags.updated`
- `handoff.updated`
- `followup.updated`

### JSON events endpoint

```json
{
  "name": "WhatsApp Realtime Events",
  "method": "GET",
  "endpoint": "/whatsapp/realtime/events",
  "auth": "Bearer <react_token>",
  "query": {
    "after_id": 0,
    "limit": 50
  },
  "response": {
    "success": true,
    "events": [],
    "next_cursor": 0
  },
  "error_response": {
    "success": false,
    "error": "Unauthorized"
  }
}
```

### Event envelope

```json
{
  "id": 101,
  "event": "lead.updated",
  "timestamp": "2026-03-31 18:30:00",
  "session_id": "8801XXXXXXXXX",
  "data": {}
}
```

Envelope fields:
- `id`
- `event`
- `timestamp`
- `session_id` when applicable
- `data`

### Event payload examples

#### New incoming message

```json
{
  "event": "message.received",
  "timestamp": "2026-03-31 18:30:00",
  "session_id": "8801XXXXXXXXX",
  "data": {
    "role": "user",
    "content": "price koto",
    "bot_type": "sales"
  }
}
```

#### Bot replied

```json
{
  "event": "message.sent",
  "timestamp": "2026-03-31 18:30:02",
  "session_id": "8801XXXXXXXXX",
  "data": {
    "role": "assistant",
    "content": "Apni chaile ami details dite pari.",
    "bot_type": "sales"
  }
}
```

#### Lead updated

```json
{
  "event": "lead.updated",
  "timestamp": "2026-03-31 18:31:00",
  "session_id": "8801XXXXXXXXX",
  "data": {
    "status": "active",
    "auto_reply_enabled": 1,
    "follow_up_required": 1,
    "last_interaction_type": "pricing",
    "promised_payment_at": "2026-04-01 20:30:00",
    "last_seen": "2026-03-31 18:31:00"
  }
}
```

#### Lead tags updated

```json
{
  "event": "lead.tags.updated",
  "timestamp": "2026-03-31 18:31:10",
  "session_id": "8801XXXXXXXXX",
  "data": {
    "tags": [
      "pricing",
      "interested"
    ],
    "tag_rows": [
      {
        "tag_name": "pricing",
        "source": "auto",
        "confidence": 0.93,
        "note": "Detected from history",
        "created_at": "2026-03-31 18:31:10",
        "updated_at": "2026-03-31 18:31:10"
      }
    ],
    "chat_summary": "Interested in pricing"
  }
}
```

#### Handoff updated

```json
{
  "event": "handoff.updated",
  "timestamp": "2026-03-31 18:32:00",
  "session_id": "8801XXXXXXXXX",
  "data": {
    "handoff": {
      "session_id": "8801XXXXXXXXX",
      "is_handoff_active": 0,
      "reason": "Manual review needed",
      "resolved_at": "2026-03-31 18:32:00",
      "updated_at": "2026-03-31 18:32:00"
    }
  }
}
```

#### Follow-up updated

```json
{
  "event": "followup.updated",
  "timestamp": "2026-03-31 18:33:00",
  "session_id": "8801XXXXXXXXX",
  "data": {
    "plan": {
      "id": 12,
      "session_id": "8801XXXXXXXXX",
      "status": "done",
      "reason": "first_message_only",
      "scheduled_for": "2026-03-31 10:00:00",
      "priority": "normal",
      "last_followup_sent_at": "2026-03-31 18:20:00",
      "updated_at": "2026-03-31 18:33:00"
    }
  }
}
```

### Recommended frontend usage

- Leads page:
  - subscribe to `lead.updated`, `lead.tags.updated`, `handoff.updated`
- Lead details page:
  - subscribe to `message.received`, `message.sent`, `lead.updated`, `lead.tags.updated`, `handoff.updated`, `followup.updated`
- Follow-ups page:
  - subscribe to `followup.updated`

### Polling fallback

Use polling alongside realtime where needed:
- Dashboard: refresh every `60s`
- Leads list: refresh every `30s` only while visible
- Lead details history: refresh every `10-15s` only while the page is open
- Follow-ups page: refresh every `30-60s`

## 10. Learning Center

Current status:
- the Python bot supports learning-center APIs
- the Laravel wrapper now exposes the learning-center endpoints below

Available through Laravel wrapper now:
- `GET /whatsapp/learning/questions`
- `GET /whatsapp/learning/questions/:id`
- `POST /whatsapp/learning/questions/:id/resolve`

Bot-native endpoints:
- `GET /learning/questions`
- `GET /learning/questions/:id`
- `POST /learning/questions/:id/resolve`

Bot list contract:

```json
{
  "name": "Learning Questions",
  "method": "GET",
  "endpoint": "/learning/questions",
  "auth": "X-Admin-Token <bot_token>",
  "query": {
    "status": "open",
    "bot_type": "sales"
  },
  "response": {
    "success": true,
    "count": 12,
    "items": [
      {
        "id": 1,
        "session_id": "8801XXXXXXXXX",
        "bot_type": "sales",
        "user_message": "price koto",
        "bot_reply": "old reply",
        "reply_source": "fallback",
        "status": "open",
        "gap_reason": "no_training_data",
        "manual_answer": null,
        "training_content": null,
        "created_at": "2026-03-31 10:00:00",
        "updated_at": "2026-03-31 10:00:00",
        "resolved_at": null
      }
    ]
  }
}
```

Bot detail contract:

```json
{
  "name": "Learning Question Detail",
  "method": "GET",
  "endpoint": "/learning/questions/:id",
  "auth": "X-Admin-Token <bot_token>",
  "response": {
    "success": true,
    "item": {
      "id": 1,
      "session_id": "8801XXXXXXXXX",
      "bot_type": "sales",
      "user_message": "price koto",
      "bot_reply": "old reply",
      "reply_source": "fallback",
      "status": "open",
      "gap_reason": "no_training_data",
      "manual_answer": null,
      "training_content": null,
      "created_at": "2026-03-31 10:00:00",
      "updated_at": "2026-03-31 10:00:00",
      "resolved_at": null
    }
  },
  "error_response": {
    "success": false,
    "error": "Learning question not found"
  }
}
```

Bot resolve contract:

```json
{
  "name": "Resolve Learning Question",
  "method": "POST",
  "endpoint": "/learning/questions/:id/resolve",
  "auth": "X-Admin-Token <bot_token>",
  "body": {
    "manual_answer": "ম্যানুয়াল উত্তর",
    "training_content": "ঐচ্ছিক training text",
    "add_to_training": true
  },
  "response": {
    "success": true,
    "message": "Learning question 1 resolved successfully",
    "added_to_training": true
  },
  "error_response": {
    "success": false,
    "error": "manual_answer is required"
  }
}
```

## 11. Campaign UI / Outbound

Current status:
- the Python bot supports campaign and outbound APIs
- the Laravel wrapper now exposes the campaign and outbound list endpoints below

Available through Laravel wrapper now:
- `GET /whatsapp/campaigns/types`
- `GET /whatsapp/campaigns`
- `POST /whatsapp/campaigns`
- `GET /whatsapp/campaigns/:id`
- `GET /whatsapp/campaigns/:id/recipients`
- `POST /whatsapp/promotions/tag-send`
- `GET /whatsapp/outbound/types`
- `GET /whatsapp/outbound`

Bot-native endpoints:
- `GET /campaigns/types`
- `GET /campaigns`
- `POST /campaigns`
- `POST /promotions/tag-send`
- `GET /campaigns/:id`
- `POST /campaigns/:id/status`
- `POST /campaigns/:id/queue`
- `GET /campaigns/:id/recipients`
- `GET /outbound/types`
- `GET /outbound`
- `POST /outbound`
- `GET /outbound/:id`
- `POST /outbound/:id/status`
- `DELETE /outbound/:id`

Bot create-campaign contract:

```json
{
  "name": "Create Campaign",
  "method": "POST",
  "endpoint": "/campaigns",
  "auth": "X-Admin-Token <bot_token>",
  "body": {
    "name": "Eid Promo",
    "bot_type": "sales",
    "target_tag": "interested",
    "campaign_type": "text",
    "message_text": "Special offer",
    "image_url": ""
  },
  "response": {
    "success": true,
    "campaign_id": 12,
    "message": "Campaign created successfully"
  }
}
```

Bot campaigns-list contract:

```json
{
  "name": "Campaign List",
  "method": "GET",
  "endpoint": "/campaigns",
  "auth": "X-Admin-Token <bot_token>",
  "response": {
    "success": true,
    "campaigns": []
  }
}
```

Bot tag-send contract:

```json
{
  "name": "Tag Promotion Send",
  "method": "POST",
  "endpoint": "/promotions/tag-send",
  "auth": "X-Admin-Token <bot_token>",
  "body": {
    "name": "Eid Promo",
    "bot_type": "sales",
    "target_tag": "interested",
    "campaign_type": "text",
    "message_text": "Special offer",
    "image_url": "",
    "dispatch_immediately": true
  },
  "response": {
    "success": true,
    "campaign_id": 12,
    "recipient_count": 40,
    "outbound_created_count": 40,
    "dispatch_immediately": true,
    "dispatch_results": []
  }
}
```

Bot campaign-detail contract:

```json
{
  "name": "Campaign Detail",
  "method": "GET",
  "endpoint": "/campaigns/:id",
  "auth": "X-Admin-Token <bot_token>",
  "response": {
    "success": true,
    "campaign": {}
  },
  "error_response": {
    "success": false,
    "error": "Campaign not found"
  }
}
```

Bot recipients contract:

```json
{
  "name": "Campaign Recipients",
  "method": "GET",
  "endpoint": "/campaigns/:id/recipients",
  "auth": "X-Admin-Token <bot_token>",
  "response": {
    "success": true,
    "campaign": {},
    "recipients": []
  }
}
```

Bot outbound-list contract:

```json
{
  "name": "Outbound List",
  "method": "GET",
  "endpoint": "/outbound",
  "auth": "X-Admin-Token <bot_token>",
  "query": {
    "status": "queued",
    "source_type": "campaign",
    "session_id": "8801XXXXXXXXX"
  },
  "response": {
    "success": true,
    "outbound_messages": []
  }
}
```

## 12. Analytics

Current status:
- the Laravel wrapper now exposes lightweight analytics endpoints
- all analytics endpoints support optional `date_from` and `date_to` query params
- export/report download is still not implemented

Available through Laravel wrapper now:
- `GET /whatsapp/analytics/lead-source-behavior`
- `GET /whatsapp/analytics/tag-distribution`
- `GET /whatsapp/analytics/conversion-by-tag`
- `GET /whatsapp/analytics/followup-performance`
- `GET /whatsapp/analytics/reply-source-breakdown`
- `GET /whatsapp/analytics/campaign-performance`
- `GET /whatsapp/analytics/unresolved-learning-trends`

Example analytics contract:

```json
{
  "name": "Lead Source Behavior",
  "method": "GET",
  "endpoint": "/whatsapp/analytics/lead-source-behavior",
  "auth": "Bearer <react_token>",
  "query": {
    "date_from": "2026-03-01",
    "date_to": "2026-03-31"
  },
  "response": {
    "success": true,
    "analytics": {
      "date_range": {
        "date_from": "2026-03-01",
        "date_to": "2026-03-31"
      },
      "items": [
        {
          "bot_type": "sales",
          "total_leads": 120,
          "new_leads": 25,
          "active_leads": 48,
          "cold_leads": 30,
          "converted_leads": 17
        }
      ]
    }
  }
}
```

Other analytics payload shapes:
- `/whatsapp/analytics/tag-distribution`
  - `analytics.items[]`: `tag_name`, `assignment_count`, `lead_count`
- `/whatsapp/analytics/conversion-by-tag`
  - `analytics.items[]`: `tag_name`, `tagged_leads`, `converted_leads`, `conversion_rate`
- `/whatsapp/analytics/followup-performance`
  - `analytics.summary`: `total_plans`, `pending`, `processing`, `done`, `cancelled`, `sent_count`, `due_count`
  - status values: `pending`, `processing`, `done`, `cancelled`
- `/whatsapp/analytics/reply-source-breakdown`
  - `analytics.items[]`: `reply_source`, `total`
  - reply source values: `static`, `llm`, `fallback`
- `/whatsapp/analytics/campaign-performance`
  - `analytics.items[]`: `id`, `name`, `bot_type`, `target_tag`, `campaign_type`, `status`, `created_at`, `recipient_count`, `recipient_sent_count`, `outbound_count`, `outbound_sent_count`, `outbound_failed_count`
  - campaign status values: `draft`, `queued`, `sent`, `cancelled`
- `/whatsapp/analytics/unresolved-learning-trends`
  - `analytics.items[]`: `day`, `open_count`

Not available yet:
- export/report endpoint
- arbitrary grouping/sort controls

## 13. Knowledge Base / Training

Current status:
- the Laravel wrapper now exposes the underlying sales/support training stores
- the unified knowledge-base CRUD backend is now live through the Laravel wrapper
- current storage backing is live for `training`, `faq`, `static_reply`, and `content_pack`

Available through Laravel wrapper now:
- `GET /whatsapp/training/sales`
- `POST /whatsapp/training/sales`
- `DELETE /whatsapp/training/sales/:id`
- `GET /whatsapp/training/support`
- `POST /whatsapp/training/support`
- `DELETE /whatsapp/training/support/:id`

Wrapper training list contract:

```json
{
  "name": "Sales Training List",
  "method": "GET",
  "endpoint": "/whatsapp/training/sales",
  "auth": "Bearer <react_token>",
  "response": {
    "success": true,
    "count": 2,
    "items": [
      {
        "id": 4,
        "content": "When a lead asks for a demo, offer scheduling help and collect time preference.",
        "created_at": "2026-03-31 12:00:00"
      }
    ]
  }
}
```

Wrapper training create contract:

```json
{
  "name": "Create Sales Training Item",
  "method": "POST",
  "endpoint": "/whatsapp/training/sales",
  "auth": "Bearer <react_token>",
  "body": {
    "content": "New sales training content"
  },
  "response": {
    "success": true,
    "message": "Sales knowledge updated successfully"
  },
  "error_response": {
    "success": false,
    "error": "Training content is required"
  }
}
```

Wrapper training delete contract:

```json
{
  "name": "Delete Sales Training Item",
  "method": "DELETE",
  "endpoint": "/whatsapp/training/sales/:id",
  "auth": "Bearer <react_token>",
  "response": {
    "success": true,
    "message": "Sales training entry 4 deleted successfully"
  },
  "error_response": {
    "success": false,
    "error": "Sales training entry not found"
  }
}
```

Unified knowledge-base CRUD contract:

```json
{
  "name": "Knowledge Items",
  "method": "GET",
  "endpoint": "/whatsapp/knowledge/items",
  "auth": "Bearer <react_token>",
  "query": {
    "bot_type": "sales",
    "kind": "training"
  },
  "response": {
    "success": true,
    "items": [
      {
        "id": 4,
        "bot_type": "sales",
        "kind": "training",
        "status": "active",
        "title": null,
        "content": "New sales training content",
        "tags": [],
        "created_at": "2026-03-31 12:00:00",
        "updated_at": "2026-03-31 12:00:00"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 1,
      "total_pages": 1,
      "has_next": false,
      "has_prev": false
    }
  }
}
```

Live unified CRUD endpoints:
- `GET /whatsapp/knowledge/items`
- `POST /whatsapp/knowledge/items`
- `GET /whatsapp/knowledge/items/:id`
- `PATCH /whatsapp/knowledge/items/:id`
- `DELETE /whatsapp/knowledge/items/:id`

Suggested enums:
- `bot_type`: `sales`, `support`
- `kind`: `training`, `faq`, `static_reply`, `content_pack`
- `status`: `active`, `archived`

Important design note:
- current live storage supports list/detail/create/update/delete for `training`, `faq`, `static_reply`, and `content_pack`
- legacy `/whatsapp/training/sales` and `/whatsapp/training/support` routes still exist for compatibility, but `/whatsapp/knowledge/items` is now the preferred contract

Moderation enums used by the live wrapper:
- `status`: `open`, `resolved`
- `reason`: `abusive_language`, `needs_manual_review`, `unclear_voice_or_text`, `irrelevant_chat`, `handoff`
- `severity`: `low`, `medium`, `high`

## Renewal Follow-up

Renewal follow-up is a cohort-based workspace inside the WhatsApp React app.

Supported cohorts:
- `expired-clients`
- `unsubscribed-registrations`

### Expired clients cohort

```json
{
  "name": "Expired Clients Cohort",
  "method": "GET",
  "endpoint": "/whatsapp/cohorts/expired-clients",
  "auth": "Cookie session",
  "query": {
    "page": 1,
    "limit": 25,
    "search": "halim"
  },
  "response": {
    "success": true,
    "cohort": {
      "key": "expired_clients",
      "label": "Expired Clients"
    },
    "items": [
      {
        "id": 12,
        "store_id": 12,
        "user_id": 99,
        "name": "Halim",
        "display_name": "Halim",
        "phone": "017XXXXXXXX",
        "email": "halim@example.com",
        "store_name": "Halim Telecom",
        "store_url": "halimtelecom.ebitans.com",
        "plan_id": 2,
        "plan_name": "Premium",
        "status": "expired",
        "registration_date": "2026-01-10",
        "purchase_date": "2026-01-15",
        "expiry_date": "2026-03-15",
        "renew_date": "2026-02-15",
        "days_expired": 17,
        "last_sms_status": "sent",
        "last_sms_at": "2026-03-20 10:30:00",
        "last_sms_purpose": "Expired Client Follow-up"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 1,
      "total_pages": 1,
      "has_next": false,
      "has_prev": false
    }
  }
}
```

### Registered-not-subscribed cohort

```json
{
  "name": "Unsubscribed Registrations Cohort",
  "method": "GET",
  "endpoint": "/whatsapp/cohorts/unsubscribed-registrations",
  "auth": "Cookie session",
  "query": {
    "page": 1,
    "limit": 25,
    "search": "ashna"
  },
  "response": {
    "success": true,
    "cohort": {
      "key": "unsubscribed_registrations",
      "label": "Registered Not Subscribed"
    },
    "items": [
      {
        "id": 45,
        "store_id": null,
        "user_id": 45,
        "name": "Ashna",
        "display_name": "Ashna",
        "phone": "018XXXXXXXX",
        "email": "ashna@example.com",
        "store_name": null,
        "store_url": null,
        "plan_id": null,
        "plan_name": null,
        "status": "registered_not_subscribed",
        "registration_date": "2026-03-20",
        "purchase_date": null,
        "expiry_date": null,
        "renew_date": null,
        "paid_registration": false,
        "last_sms_status": null,
        "last_sms_at": null,
        "last_sms_purpose": null
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 1,
      "total_pages": 1,
      "has_next": false,
      "has_prev": false
    }
  }
}
```

### Cohort bulk actions

#### Create cohort follow-up plans

```json
{
  "name": "Create Cohort Follow-ups",
  "method": "POST",
  "endpoint": "/whatsapp/cohorts/:cohort/followups",
  "auth": "Cookie session",
  "body": {
    "ids": [12, 18, 25],
    "reason": "renewal_followup",
    "note": "Created from renewal workspace",
    "scheduled_for": "2026-04-02 11:30:00",
    "priority": "normal"
  }
}
```

#### Queue cohort WhatsApp outbound

```json
{
  "name": "Queue Cohort Outbound",
  "method": "POST",
  "endpoint": "/whatsapp/cohorts/:cohort/outbound",
  "auth": "Cookie session",
  "body": {
    "ids": [12, 18, 25],
    "bot_type": "sales",
    "message_text": "আপনার renewal pending আছে। reply দিলে আমি help করতে পারি।",
    "image_url": "",
    "scheduled_for": "2026-04-02 11:30:00"
  }
}
```

#### Send cohort SMS

```json
{
  "name": "Send Cohort SMS",
  "method": "POST",
  "endpoint": "/whatsapp/cohorts/:cohort/sms",
  "auth": "Cookie session",
  "body": {
    "ids": [12, 18, 25],
    "message_text": "আপনার subscription renewal pending আছে।",
    "purpose": "Expired Client Follow-up"
  }
}
```

### Saved renewal batches

#### List saved batches

```json
{
  "name": "List Renewal Batches",
  "method": "GET",
  "endpoint": "/whatsapp/renewal-batches",
  "auth": "Cookie session",
  "query": {
    "limit": 12,
    "status": "active",
    "cohort_key": "expired-clients",
    "search": "march"
  }
}
```

Supported filters:
- `status`: `active`, `archived`
- `cohort_key`: `expired-clients`, `unsubscribed-registrations`
- `search`: batch name or message text search

#### Save batch

```json
{
  "name": "Create Renewal Batch",
  "method": "POST",
  "endpoint": "/whatsapp/renewal-batches",
  "auth": "Cookie session",
  "body": {
    "name": "March Expired Follow-up",
    "cohort_key": "expired-clients",
    "ids": [12, 18, 25],
    "bot_type": "sales",
    "message_text": "আপনার renewal pending আছে। reply দিলে আমি help করতে পারি।",
    "image_url": "",
    "scheduled_for": "2026-04-02 11:30:00"
  }
}
```

#### Batch detail

```json
{
  "name": "Renewal Batch Detail",
  "method": "GET",
  "endpoint": "/whatsapp/renewal-batches/:id",
  "auth": "Cookie session"
}
```

Batch detail includes:
- saved recipient snapshot
- dispatch rows
- outbound IDs
- queued/sent/failed status

#### Run saved batch

```json
{
  "name": "Run Renewal Batch",
  "method": "POST",
  "endpoint": "/whatsapp/renewal-batches/:id/run",
  "auth": "Cookie session",
  "body": {}
}
```

#### Clone saved batch

```json
{
  "name": "Clone Renewal Batch",
  "method": "POST",
  "endpoint": "/whatsapp/renewal-batches/:id/clone",
  "auth": "Cookie session",
  "body": {}
}
```

#### Export batch recipients CSV

```json
{
  "name": "Export Renewal Batch Recipients",
  "method": "GET",
  "endpoint": "/whatsapp/renewal-batches/:id/export",
  "auth": "Cookie session",
  "response": "CSV file download"
}
```

#### Archive or restore batch

```json
{
  "name": "Archive Renewal Batch",
  "method": "POST",
  "endpoint": "/whatsapp/renewal-batches/:id/archive",
  "auth": "Cookie session",
  "body": {}
}
```

#### Delete batch

```json
{
  "name": "Delete Renewal Batch",
  "method": "DELETE",
  "endpoint": "/whatsapp/renewal-batches/:id",
  "auth": "Cookie session"
}
```

### Renewal batch analytics fields

Batch summary/detail responses include:
- `total_runs`
- `total_recipients`
- `total_sent_count`
- `total_failed_count`
- `last_run_at`
- `last_run_success_count`
- `last_run_failed_count`
- `status`
- `archived_at`

### Follow-up sequencing behavior

The Python bot follow-up engine now enforces:
- no duplicate pending/processing follow-up step for the same `session_id + bot_type + reason + note`
- scheduled follow-up is cancelled if the user replies after that plan was created
- scheduled follow-up is cancelled if the same follow-up message was already sent and there has been no new user reply
- scenario timing uses real production delays again, not testing-minute delays

Current `payment_promise` tone progression:
- `at_promise`: direct reminder
- `5m`: softer clarification/help offer
- `1h`: supportive follow-up
- `1d`: stronger pending reminder
- `1w`: final urgency-style reminder

## 17. Live Client Showcase

This is used when sales/support wants to share real live client website examples with a customer who does not like the default demo design.

There are two API surfaces:
- a bot-facing token-protected feed for the Python bot
- a protected React admin CRUD API for managing showcase links

### Bot-facing showcase feed

Request headers:

```http
Accept: application/json
Authorization: Bearer <EBITANS_LARAVEL_API_TOKEN>
```

Path source:
- default: `/api/whatsapp/live-client-showcase`
- configurable from `.env` using `EBITANS_LARAVEL_LIVE_CLIENT_SHOWCASE_PATH`

Example response:

```json
{
  "success": true,
  "urls": [
    "https://client-a.com",
    "https://client-b.com"
  ],
  "data": [
    {
      "id": 1,
      "title": "Fashion Client",
      "url": "https://client-a.com",
      "sort_order": 10
    },
    {
      "id": 2,
      "title": "Electronics Client",
      "url": "https://client-b.com",
      "sort_order": 20
    }
  ]
}
```

Feed rules:
- only active rows are returned
- rows are sorted by `sort_order`, then `id`
- the bot can safely read either:
  - `urls`
  - `data[].url`

Required env:

```env
EBITANS_LARAVEL_API_TOKEN=your_shared_bot_token
EBITANS_LARAVEL_LIVE_CLIENT_SHOWCASE_PATH=whatsapp/live-client-showcase
```

### Admin list showcase items

```json
{
  "name": "Live Client Showcase List",
  "method": "GET",
  "endpoint": "/whatsapp/live-client-showcases",
  "auth": "Cookie session",
  "query": {
    "page": 1,
    "limit": 25,
    "search": "fashion",
    "status": "active"
  },
  "response": {
    "success": true,
    "items": [
      {
        "id": 1,
        "title": "Fashion Client",
        "url": "https://client-a.com",
        "sort_order": 10,
        "is_active": true,
        "created_at": "2026-04-12 12:00:00",
        "updated_at": "2026-04-12 12:00:00"
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 25,
      "total": 1,
      "total_pages": 1,
      "has_next": false,
      "has_prev": false
    }
  }
}
```

Notes:
- `search` matches `title` and `url`
- `status` accepts `active` or `inactive`

### Create showcase item

```json
{
  "name": "Create Live Client Showcase",
  "method": "POST",
  "endpoint": "/whatsapp/live-client-showcases",
  "auth": "Cookie session",
  "body": {
    "title": "Fashion Client",
    "url": "https://client-a.com",
    "sort_order": 10,
    "is_active": true
  }
}
```

### Show one showcase item

```json
{
  "name": "Show Live Client Showcase",
  "method": "GET",
  "endpoint": "/whatsapp/live-client-showcases/:id",
  "auth": "Cookie session"
}
```

### Update showcase item

```json
{
  "name": "Update Live Client Showcase",
  "method": "PATCH",
  "endpoint": "/whatsapp/live-client-showcases/:id",
  "auth": "Cookie session",
  "body": {
    "title": "Updated Fashion Client",
    "sort_order": 15,
    "is_active": false
  }
}
```

### Delete showcase item

```json
{
  "name": "Delete Live Client Showcase",
  "method": "DELETE",
  "endpoint": "/whatsapp/live-client-showcases/:id",
  "auth": "Cookie session"
}
```

Frontend implementation notes:
- keep the live client list in DB, not in `.env`
- fields needed in the React admin:
  - `url`
  - optional `title`
  - `sort_order`
  - `is_active`
- after admin updates DB rows, the Python bot can pick up the latest links on its next refresh / restart
