export async function fetchWithTimeout(resource: RequestInfo, options: any = {}): Promise<Response> {
  const { timeout = 8000 } = options;
  
  const controller = new AbortController();
  const id = setTimeout(() => controller.abort(), timeout);
  const response = await fetch(resource, {
    ...options,
    signal: controller.signal  
  });

  clearTimeout(id);

  return response;
}

// taken from https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch#supplying_request_options
export async function post(resource: RequestInfo, data: any = {}) {
  const response = await fetch(resource, {
    method: "POST",
    mode: "cors", 
    cache: "no-cache",
    credentials: "same-origin", 
    headers: {
      "Content-Type": "application/json",
    },
    redirect: "follow",
    referrerPolicy: "no-referrer", 
    body: JSON.stringify(data), 
  });

  return response;
}