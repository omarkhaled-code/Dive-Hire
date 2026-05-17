export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig();
  const token = getCookie(event, "auth_token");
  const formData = await readFormData(event);

  try {
    const response = await fetch(`${config.public.apiBase}/developer-profile`, {
      method: "POST",
      body: formData,
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: "application/json",
        // Notice: No 'Content-Type' header here!
      },
    });

    return response;
  } catch (error: any) {
    // Pass Laravel's validation errors back to the Nuxt frontend
    throw createError({
      statusCode: error.response?.status || 500,
      statusMessage: error.message,
      data: error.response?._data,
    });
  }
});
