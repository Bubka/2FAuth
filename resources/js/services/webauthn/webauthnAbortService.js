/**
 * MIT License
 *
 * Copyright (c) 2020 Matthew Miller - https://github.com/MasterKale/SimpleWebAuthn
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 * A way to cancel an existing WebAuthn request, for example to cancel a
 * WebAuthn autofill authentication request for a manual authentication attempt.
 */
class WebauthnAbortService {
    controller;
  
    /**
     * Prepare an abort signal that will help support multiple auth attempts without needing to
     * reload the page
     */
    createNewAbortSignal() {
        // Abort any existing calls to navigator.credentials.create() or navigator.credentials.get()
        if (this.controller) {
            const abortError = new Error(
                'Cancelling existing WebAuthn API call for new one',
            );
            abortError.name = 'AbortError';
            this.controller.abort(abortError);
        }

        const newController = new AbortController();

        this.controller = newController;
        return newController.signal;
    }
}

export const webauthnAbortService = new WebauthnAbortService();