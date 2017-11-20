<?php

namespace Omadonex\Support\Services;

class ResponseUtils
{
    const STATUS_SUCCESS = 'success';
    const STATUS_INFO = 'info';
    const STATUS_WARNING = 'warning';
    const STATUS_ERROR = 'error';

    const TYPE_REDIRECT = 'redirect';
    const TYPE_CONTENT = 'content';
    const TYPE_RESULT = 'result';

    private static function addFlashData($statusMessage = null, $status = null)
    {
        if ($statusMessage) {
            session()->flash('status', $status ?: self::STATUS_SUCCESS);
            session()->flash('status_message', $statusMessage);
        }
    }

    public static function actionRedirect($routeName, $statusMessage = null, $status = null)
    {
        self::addFlashData($statusMessage, $status);

        return redirect()->route($routeName);
    }

    public static function actionBack($statusMessage = null, $status = null)
    {
        self::addFlashData($statusMessage, $status);

        return redirect()->back();
    }

    public static function jsonRedirect($url, $status = null)
    {
        return response()->json([
            'status' => $status ?: self::STATUS_SUCCESS,
            'type' => self::TYPE_REDIRECT,
            'value' => $url,
        ]);
    }

    public static function jsonContent($content, $status = null)
    {
        return response()->json([
            'status' => $status ?: self::STATUS_SUCCESS,
            'type' => self::TYPE_CONTENT,
            'value' => $content,
        ]);
    }

    public static function jsonSuccess()
    {
        return response()->json([
            'status' => self::STATUS_SUCCESS,
            'type' => self::TYPE_RESULT,
            'value' => null,
        ]);
    }
}