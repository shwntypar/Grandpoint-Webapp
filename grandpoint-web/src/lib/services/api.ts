/* import EncryptionService from './encryption'; */
const baseUrl = "http://localhost/Grandpoint-Webapp/grandpoint-web/api";

async function fetchWithAuth(endpoint: string, options: RequestInit = {}) {
  const token = localStorage.getItem("token");

  const headers: HeadersInit = {
    ...(token && { Authorization: `Bearer ${token}` }),
    ...options.headers,
  };

  if (!(options.body instanceof FormData)) {
    (headers as Record<string, string>)['Content-Type'] = 'application/json';
  }

  const response = await fetch(`${baseUrl}/${endpoint}`, {
    ...options,
    headers,
  });

  if (!response.ok) {
    throw new Error('Request failed');
  }

  // For DELETE requests, we might not get JSON back
  if (options.method === 'DELETE') {
    return { success: response.ok };
  }

  return response.json();
}

export const api = {
  get: async (endpoint: string) => fetchWithAuth(endpoint),
  post: async (endpoint: string, data: any) =>
    fetchWithAuth(endpoint, {
      method: "POST",
      body: data instanceof FormData ? data : JSON.stringify(data),
    }),
  delete: async (endpoint: string) =>
    fetchWithAuth(endpoint, {
      method: "DELETE"
    })
};