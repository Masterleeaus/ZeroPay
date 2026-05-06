import client from './client'

export const pushApi = {
  subscribe: (subscription: PushSubscriptionJSON) =>
    client.post('/push/subscribe', { subscription }),
  unsubscribe: () => client.post('/push/unsubscribe'),
}
