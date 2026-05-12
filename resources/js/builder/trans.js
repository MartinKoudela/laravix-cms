let _trans = {};

export const setTrans = (obj) => { _trans = obj; };
export const t = (key, fallback) => _trans[key] ?? (fallback !== undefined ? fallback : key);